<?php
class subscribeModelScs extends modelScs {
	private $_dest = '';
	private $_lastBlock = null;	// Some small internal caching
	public function __construct() {
		$this->_setTbl('subscribers');
	}
	public function unsubscribe($user_id) {
		$user = new WP_User($user_id);
		$this->delete(array(
			'email' => $user->data->user_email
		));
	}
	public function subscribe($d = array(), $validateIp = false) {
		$id = isset($d['id']) ? $d['id'] : 0;
		if($id) {
			$block = frameScs::_()->getModule('octo')->getModel('octo_blocks')->getById($id);
			if($block && $block['cat_code'] == 'subscribes') {
				$dest = $block['params']['sub_dest']['val'];
				$subMethod = 'subscribe_'. $dest;
				if(method_exists($this, $subMethod)) {
					$this->_dest = $dest;
					$this->_lastBlock = $block;
					$d = dbScs::prepareHtmlIn($d);
					if($this->validateFields($d, $block)) {
						return $this->$subMethod($d, $block, $validateIp);
					}
				} else
					$this->pushError (__('Something goes wrong', SCS_LANG_CODE));
			} else
				$this->pushError (__('Empty or invalid ID', SCS_LANG_CODE));
		} else
			$this->pushError (__('Empty or invalid ID', SCS_LANG_CODE));
		return false;
	}
	public function validateFields($d, $block) {
		if(isset($block['params']['fields']) && !empty($block['params']['fields']['val'])) {
			$errors = array();
			foreach($block['params']['fields']['val'] as $f) {
				$k = $f['name'];
				if(isset($f['required']) && $f['required']) {
					$value = isset($d[ $k ]) ? trim($d[ $k ]) : false;
					if(empty($value)) {
						$errors[ $k ] = sprintf($f['html'] == 'selectbox'
							? __('Please select %s', SCS_LANG_CODE)
							: __('Please enter %s', SCS_LANG_CODE)
						, $f['label']);
					}
				}
			}
			if(!empty($errors)) {
				$this->pushError($errors);
				return false;
			}
		}
		return true;
	}
	public function getDest() {
		return $this->_dest;
	}
	public function getLastBlock() {
		return $this->_lastBlock;
	}
	private function _checkOftenAccess($d = array()) {
		//if((int) frameScs::_()->getModule('options')->get('disable_subscribe_ip_antispam'))
			return true;
		//return true;
		$onlyCheck = isset($d['only_check']) ? $d['only_check'] : false;
		$onlyAdd = isset($d['only_add']) ? $d['only_add'] : false;
		$ip = utilsScs::getIP();
		if(empty($ip)) {
			$this->pushError(__('Can\'t detect your IP, please don\'t spam', SCS_LANG_CODE));
			return false;
		}
		$accessByIp = get_option(SCS_CODE. '_access_py_ip');
		if(empty($accessByIp)) {
			$accessByIp = array();
		}
		$time = time();
		$break = false;
		if($onlyAdd) {
			$accessByIp[ $ip ] = $time;
			update_option(SCS_CODE. '_access_py_ip', $accessByIp);
			return true;
		}
		// Clear old values
		if(!empty($accessByIp)) {
			foreach($accessByIp as $k => $v) {
				if($time - (int) $v >= 3600)
					unset($accessByIp[ $k ]);
			}
		}
		if(isset($accessByIp[ $ip ])) {
			if($time - (int) $accessByIp[ $ip ] <= 30 * 60) {
				$break = true;
			} else
				$accessByIp[ $ip ] = $time;
		} else {
			$accessByIp[ $ip ] = $time;
		}
		if(!$onlyCheck)
			update_option(SCS_CODE. '_access_py_ip', $accessByIp);
		if($break) {
			$this->pushError(__('You just subscribed from this IP', SCS_LANG_CODE));
			return false;
		}
		return true;
	}
	private function _getInvalidEmailMsg($block) {
		return isset($block['params']['sub_txt_invalid_email']) && !empty($block['params']['sub_txt_invalid_email']['val'])
			? $block['params']['sub_txt_invalid_email']['val']
			: __('Empty or invalid email', SCS_LANG_CODE);
	}
	private function _emailExists($email) {
		return email_exists($email) || $this->setWhere(array('email' => $email))->getFromTbl(array('return' => 'row'));
	}
	/**
	 * WordPress subscribe functionality
	 */
	public function subscribe_wordpress($d, $block, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				if(!$this->_emailExists($email)) {
					if(!$validateIp || $validateIp && $this->_checkOftenAccess()) {
						$username = isset($d['name']) ? $d['name'] : '';
						$username = $this->_getUsernameFromEmail($email, $username);
						if(isset($block['params']['sub_ignore_confirm']) && $block['params']['sub_ignore_confirm']['val']) {
							return $this->createWpSubscriber($block, $email, $username, $d);
						} else {
							$confirmHash = md5($email. NONCE_KEY);
							if($this->insert(array(
								'username' => $username,
								'email' => $email,
								'hash' => $confirmHash,
								'block_id' => $block['id'],
								'all_data' => utilsScs::serialize( $d ),
							))) {
								$this->sendWpUserConfirm($username, $email, $confirmHash, $block, $d);
								return true;
							}
						}
					}
				} else
					$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
			} else
				$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
		} else
			$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
		return false;
	}
	public function createWpSubscriber($block, $email, $username, $d) {
		$password = wp_generate_password();
		$userId = wp_create_user($username, $password, $email);
		if($userId && !is_wp_error($userId)) {
			if(!function_exists('wp_new_user_notification')) {
				frameScs::_()->loadPlugins();
			}
			// If there was selected some special role - check it here
			$this->_lastBlock = $block;
			if(isset($block['params']['sub_wp_create_user_role'])
				&& !empty($block['params']['sub_wp_create_user_role']['val'])
				&& $block['params']['sub_wp_create_user_role']['val'] != 'subscriber'
			) {
				$user = new WP_User($userId);
				$user->set_role( $block['params']['sub_wp_create_user_role']['val'] );
			}
			/*if(isset($block['params']['sub_fields'])
				&& !empty($block['params']['sub_fields'])
			) {
				foreach($block['params']['sub_fields'] as $k => $f) {
					if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
					if(isset($d[ $k ])) {
						wp_update_user(array('ID' => $userId, $k => $d[ $k ]));
					}
				}
			}*/
			$this->_sendNewUserNotification($block, $userId, $password, $d);
			return true;
		} else {
			$this->pushError (is_wp_error($userId) ? $userId->get_error_message() : __('Can\'t subscribe for now. Please try again latter.', SCS_LANG_CODE));
		}
		return false;
	}
	private function _sendNewUserNotification($block, $userId, $password, $data) {
		$emailSubject = isset($block['params']['sub_txt_subscriber_mail_subject']) ? $block['params']['sub_txt_subscriber_mail_subject']['val'] : false;
		$emailContent = isset($block['params']['sub_txt_subscriber_mail_message']) ? $block['params']['sub_txt_subscriber_mail_message']['val'] : false;
		if($emailSubject && $emailContent) {
			$user = get_userdata( $userId );
			$blogName = wp_specialchars_decode(get_bloginfo('name'));
			$adminEmail = isset($block['params']['sub_txt_subscriber_mail_from'])
				? $block['params']['sub_txt_subscriber_mail_from']['val']
				: get_bloginfo('admin_email');
			$replaceVariables = array(
				'sitename' => $blogName,
				'siteurl' => get_bloginfo('wpurl'),
				'user_login' => $user->user_login,
				'user_email' => $user->user_email,
				'password' => $password,
				'login_url' => wp_login_url(),
			);
			$fieldData = array();

			foreach ($block['params']['fields']['val'] as $field) {
				if (!isset($data[$field['name']]) || $field['name'] == 'email' || $field['name'] == 'name') continue;

				$fieldData[$field['name']] = $data[$field['name']];
			}

			foreach($replaceVariables as $k => $v) {
				$emailSubject = str_replace('['. $k. ']', $v, $emailSubject);
				$emailContent = str_replace('['. $k. ']', $v, $emailContent);
			}

			frameScs::_()->getModule('mail')->send($user->user_email,
				$emailSubject,
				$emailContent,
				$blogName,
				$adminEmail,
				$blogName,
				$adminEmail);
			// Email to admin about new user registration - as simple as we can do - ust copied original wp code
			$message  = sprintf(__('New user registration on your site %s:'), $blogName) . '<br />';
			$message .= sprintf(__('Username: %s'), $user->user_login) . '<br />';
			$message .= sprintf(__('E-mail: %s'), $user->user_email) . '<br />';

			foreach ($fieldData as $key => $value)
				$message .= $key . ': ' . $value . '<br />';

			frameScs::_()->getModule('mail')->send($adminEmail,
				sprintf(__('[%s] New User Registration'), $blogName),
				$message,
				$blogName,
				get_bloginfo('admin_email'),
				$blogName,
				get_bloginfo('admin_email'));
		} else {	// Just use standard wp method
			wp_new_user_notification($userId);
		}
	}
	public function sendWpUserConfirm($username, $email, $confirmHash, $block, $data) {
		$blogName = wp_specialchars_decode(get_bloginfo('name'));
		$replaceVariables = array(
			'sitename' => $blogName,
			'siteurl' => get_bloginfo('wpurl'),
			'confirm_link' => uriScs::mod('subscribe', 'confirm', array('email' => $email, 'hash' => $confirmHash)),
		);
		$fieldData = array();

		foreach ($block['params']['fields']['val'] as $field) {
			if (!isset($data[$field['name']])) continue;

			$fieldData[$field['name']] = $data[$field['name']];
		}

		$adminEmail = isset($block['params']['sub_txt_confirm_mail_from'])
			? $block['params']['sub_txt_confirm_mail_from']['val']
			: get_bloginfo('admin_email');
		$confirmSubject = isset($block['params']['sub_txt_confirm_mail_subject']) && !empty($block['params']['sub_txt_confirm_mail_subject'])
				? $block['params']['sub_txt_confirm_mail_subject']['val']
				: __('Confirm subscription on [sitename]', SCS_LANG_CODE);
		$confirmContent = isset($block['params']['sub_txt_confirm_mail_message']) && !empty($block['params']['sub_txt_confirm_mail_message'])
				? $block['params']['sub_txt_confirm_mail_message']['val']
				: __('You subscribed on site <a href="[siteurl]">[sitename]</a>. Follow <a href="[confirm_link]">this link</a> to complete your subscription. If you did not subscribe here - just ignore this message.', SCS_LANG_CODE);
		foreach($replaceVariables as $k => $v) {
			$confirmSubject = str_replace('['. $k. ']', $v, $confirmSubject);
			$confirmContent = str_replace('['. $k. ']', $v, $confirmContent);
		}
		foreach($fieldData as $k => $v) {
			$confirmSubject = str_replace('['. $k. ']', $v, $confirmSubject);
			$confirmContent = str_replace('['. $k. ']', $v, $confirmContent);
		}

		frameScs::_()->getModule('mail')->send($email,
			$confirmSubject,
			$confirmContent,
			$blogName,
			$adminEmail,
			$blogName,
			$adminEmail);
	}
	public function confirm($d = array()) {
		$d['email'] = isset($d['email']) ? trim($d['email']) : '';
		$d['hash'] = isset($d['hash']) ? trim($d['hash']) : '';
		$block = array();
		if(!empty($d['email']) && !empty($d['hash'])) {
			$subscriber = $this->setWhere(array(
				'email' => $d['email'],
				'hash' => $d['hash'],
				'activated' => 0))->getFromTbl(array('return' => 'row'));
			if(!empty($subscriber)) {
				if(isset($subscriber['block_id']) && !empty($subscriber['block_id'])) {
					$block = frameScs::_()->getModule('octo')->getModel('octo_blocks')->getById($subscriber['block_id']);
					$this->_lastBlock = $block;
				}
				$subscriber['all_data'] = isset($subscriber['all_data']) ? utilsScs::unserialize($subscriber['all_data']) : array();
				$res = $this->createWpSubscriber($block, $subscriber['email'], $subscriber['username'], $subscriber['all_data']);
				if($res) {
					$this->update(array('activated' => 1), array('id' => $subscriber['id']));
				}
				return $res;
			}
		}
		// One and same error for all other cases
		$this->pushError(__('Send me some info, pls', SCS_LANG_CODE));
		return false;
	}
	private function _getUsernameFromEmail($email, $username = '') {
		if(!empty($username)) {
			if(username_exists($username)) {
				return $this->_getUsernameFromEmail($email, $username. mt_rand(1, 9999));
			}
			return $username;
		} else {
			$nameHost = explode('@', $email);
			if(username_exists($nameHost[0])) {
				return $this->_getUsernameFromEmail($nameHost[0]. mt_rand(1, 9999). '@'. $nameHost[1], $name);
			}
			return $nameHost[0];
		}
	}
	/**
	 * MailChimp functions
	 */
	private function _getMailchimpInst($key) {
		static $instances = array();
		if(!isset($instances[ $key ])) {
			if(!class_exists('mailChimpClientScs'))
				require_once($this->getModule()->getModDir(). 'classes'. DS. 'mailChimpClient.php');
			$instances[ $key ] = new mailChimpClientScs( $key );
		}
		return $instances[ $key ];
	}
	public function isMailchimpSupported() {
		if(!function_exists('curl_init')) {
			$this->pushError(__('MailChimp require CURL to be setup on your server. Please contact your hosting provider and ask them to setup CURL libruary for you.', SCS_LANG_CODE));
			return false;
		}
		return true;
	}
	public function getMailchimpLists($d = array()) {
		if(!$this->isMailchimpSupported())
			return false;
		$key = isset($d['key']) ? trim($d['key']) : '';
		if(!empty($key)) {
			$client = $this->_getMailchimpInst( $key );
			$apiRes = $client->call('lists/list');
			if($apiRes && is_array($apiRes) && isset($apiRes['data']) && !empty($apiRes['data'])) {
				$listsDta = array();
				foreach($apiRes['data'] as $list) {
					$listsDta[ $list['id'] ] = $list['name'];
				}
				return $listsDta;
			} else {
				if(isset($apiRes['errors']) && !empty($apiRes['errors'])) {
					$this->pushError($apiRes['errors']);
				} elseif($client->haveError()) {
					$this->pushError($client->getLastError());
				} else {
					$this->pushError(__('There was some problem while trying to get your lists. Make sure that your API key is correct.', SCS_LANG_CODE));
				}
			}
		} else
			$this->pushError(__('Empty API key', SCS_LANG_CODE));
		return false;
	}
	public function subscribe_mailchimp($d, $block, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				if(!$this->isMailchimpSupported())
					return false;
				$lists = isset($block['params']['sub_mailchimp_lists']) ? $block['params']['sub_mailchimp_lists']['val'] : array();
				$apiKey = isset($block['params']['sub_mailchimp_api_key']) ? $block['params']['sub_mailchimp_api_key']['val'] : array();
				if(!empty($lists)) {
					if(!empty($apiKey)) {
						if(!$validateIp || $validateIp && $this->_checkOftenAccess(array('only_check' => true))) {
							$name = '';
							if(isset($block['params']['enb_sub_name']) && $block['params']['enb_sub_name']['val']) {
								$name = trim($d['name']);
							}
							$client = $this->_getMailchimpInst( $apiKey );
							$member = array(
								'email' => $email,
							);
							$dataToSend = array('email' => $member);
							if(!empty($name)) {
								$firstLastNames = array_map('trim', explode(' ', $name));
								$dataToSend['merge_vars'] = array(
									'FNAME' => $firstLastNames[ 0 ],
								);
								if(isset($firstLastNames[ 1 ]) && !empty($firstLastNames[ 1 ])) {
									$dataToSend['merge_vars']['LNAME'] = $firstLastNames[ 1 ];
								}
							}
							if(isset($block['params']['sub_fields'])
								&& !empty($block['params']['sub_fields']['val'])
							) {
								foreach($block['params']['sub_fields'] as $k => $f) {
									if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
									if(isset($d[ $k ])) {
										if(!isset($dataToSend['merge_vars']))
											$dataToSend['merge_vars'] = array();
										$dataToSend['merge_vars'][$k] = $d[ $k ];
									}
								}
							}
							// Disable double opt-in
							if((isset($block['params']['sub_dsbl_dbl_opt_id']) && $block['params']['sub_dsbl_dbl_opt_id']['val'])
								|| (isset($block['params']['sub_ignore_confirm']) && $block['params']['sub_ignore_confirm']['val'])
							) {
								$dataToSend['double_optin'] = false;
							}
							foreach($lists as $listId) {
								$dataToSend['id'] = $listId;
								$res = $client->call('lists/subscribe', $dataToSend);
								if(!$res) {
									$this->pushError (__('Something going wrong while trying to send data to MailChimp. Please contact site owner.', SCS_LANG_CODE));
									return false;
								} elseif(isset($res['status']) && $res['status'] == 'error') {
									$this->pushError ( $res['error'] );
									return false;
								}
							}
							if($validateIp) {
								$this->_checkOftenAccess(array('only_add' => true));
							}
							return true;
						}
					} else
						$this->pushError (__('No API key entered in admin area - contact site owner to resolve this issue.', SCS_LANG_CODE));
				} else
					$this->pushError (__('No lists to add selected in admin area - contact site owner to resolve this issue.', SCS_LANG_CODE));
			} else
				$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
		} else
			$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
		return false;
	}
	public function subscribe_mailpoet($d, $block, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email) && is_email($email)) {
			if(!$validateIp || $validateIp && $this->_checkOftenAccess(array('only_check' => true))) {
				if(class_exists('WYSIJA')) {
					$name = '';
					if(isset($block['params']['enb_sub_name']) && $block['params']['enb_sub_name']['val']) {
						$name = trim($d['name']);
					}
					$userData = array('email' => $email);
					if(!empty($name)) {
						$firstLastNames = array_map('trim', explode(' ', $name));
						$userData['firstname'] = $firstLastNames[ 0 ];
						if(isset($firstLastNames[ 1 ]) && !empty($firstLastNames[ 1 ])) {
							$userData['lastname'] = $firstLastNames[ 1 ];
						}
					}
					$userFields = array();
					if(isset($block['params']['sub_fields'])
						&& !empty($block['params']['sub_fields']['val'])
					) {
						foreach($block['params']['sub_fields'] as $k => $f) {
							if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
							if(isset($d[ $k ])) {
								$userFields[$k] = $d[ $k ];
							}
						}
					}
					$dataSubscriber = array(
						'user' => $userData,
						'user_list' => array('list_ids' => array( $block['params']['sub_mailpoet_list']['val'] )),
					);
					if(!empty($userFields)) {
						$dataSubscriber['user_field'] = $userFields;
					}
					$helperUser = WYSIJA::get('user', 'helper');
					if($helperUser->addSubscriber($dataSubscriber)) {
						if($validateIp) {
							$this->_checkOftenAccess(array('only_add' => true));
						}
						return true;
					} else {
						$messages = $helperUser->getMsgs();
						$this->pushError( (!empty($messages) && isset($messages['error']) && !empty($messages['error']) ? $messages['error'] : __('Some error occured during subscription process', SCS_LANG_CODE)));
					}
				} else
					$this->pushError (__('Can\'t find MailPoet on this server', SCS_LANG_CODE));
			}
		} else
			$this->pushError ($this->_getInvalidEmailMsg($block), 'email');
		return false;
	}
}
