<?php
class coming_soonScs extends moduleScs {
	public function init() {
		dispatcherScs::addFilter('optionsDefine', array($this, 'addOptions'));
		dispatcherScs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_action('init', array($this, 'checkComingSoon'), 99);
		add_action('admin_bar_menu', array($this, 'addAdminBarNotice'), 999);
		dispatcherScs::addFilter('validateBeforeSaveOpts', array($this, 'validateSaveOpts'), 10, 2);
		
	}
	public function validateSaveOpts($valid, $d) {
		
		if(isset($d['opt_values']['cs_mode'])
			&& $d['opt_values']['cs_mode'] == 'coming_soon'
			&& isset($d['opt_values']['cs_original_tpl_id'])
			&& (int)$d['opt_values']['cs_original_tpl_id'] == 0
		) {
			$valid[] = __('Please select template for your Coming Soon page before saving this mode', SCS_LANG_CODE);
		}
		return $valid;
	}
	public function addAdminTab($tabs) {
		$tabs['settings'] = array(
			'label' => __('Settings', SCS_LANG_CODE), 'callback' => array($this, 'getSettingsTabContent'), 'fa_icon' => 'fa-gear', 'sort_order' => 30, 'hidden_for_main' => true,
		);
		return $tabs;
	}
	public function getSettingsTabContent() {
		return $this->getView()->getSettingsTabContent();
	}
	public function addOptions($opts) {
		$opts['general'] = array(
			'label' => __('General', SCS_LANG_CODE),
			'sort_order' => 10,
			'hide_cat_label' => true,
			'opts' => array(
				'cs_mode' => array(
					'label' => __('Coming Soon Mode', SCS_LANG_CODE), 
					'desc' => __('Select in which mode do You want to use our plugin', SCS_LANG_CODE), 
					'def' => 'dsbl', 
					'html' => 'selectbox', //ignored for now
					'options' => array('dsbl' => __('Disabled', SCS_LANG_CODE), 'coming_soon' => __('Coming Soon Page', SCS_LANG_CODE), 'redirect' => __('Redirect to URL', SCS_LANG_CODE), '503_header' => __('503 Header Response', SCS_LANG_CODE)),
				),
			),
		);
		$opts['cs_mode_coming_soon'] = array(
			'label' => __('Template Settings', SCS_LANG_CODE),
			'sort_order' => 20,
			'hide_cat_label' => true,
			'opts' => array(
				'cs_original_tpl_id' => array(
					'label' => '',
					'desc' => '',
					'def' => '',
					'html' => 'hidden',
				),
			),
			'opts_html' => array($this, 'getTemplateOptionsHtml'),
		);
		$opts['cs_mode_redirect'] = array(
			'label' => __('Redirect Settings', SCS_LANG_CODE),
			'sort_order' => 30,
			'hide_cat_label' => true,
			'opts' => array(
				'redirecr_url' => array(
					'label' => __('Redirect URL', SCS_LANG_CODE),
					'desc' => __('Enter URL where visitors will be redirected when visit your site while it is in Maintenance mode', SCS_LANG_CODE),
					'def' => '',
					'html' => 'text',
				),
			),
		);
		$opts['site_view'] = array(
			'label' => __('Site View Options', SCS_LANG_CODE),
			'sort_order' => 40,
			'opts' => array(
				'site_view_user_roles' => array(
					'label' => __('User Roles', SCS_LANG_CODE),
					'desc' => __('By default only administrator can view site, but here you can select other user roles that can view your site while it is under construction. Note that administrator can always view site (even if de-selected in this list).', SCS_LANG_CODE),
					'def' => array('administrator'),
					'html' => 'selectlist',
					'options' => array(frameScs::_()->getModule('options'), 'getAvailableUserRolesSelect'),
					'classes' => 'chosen'
				),
				'exclude_or_show' => array(
					'label' => '',
					'desc' => '',
					'def' => '0',
					'html' => 'hidden',
					'attrs' => 'id="excludeOrShowSwitch"'
				),
				'exclude_or_show_links' => array(
					'label' => __('<span class="excludeOrShow exclude">Exclude</span> / <span class="excludeOrShow show">Show</span> only on URLs', SCS_LANG_CODE),
					'desc' => __('By default while your site is under construction - all users will see this in all links on your site. But here you can list links where you want to disable Under Contsruction mode (or vice versa - depending on Exclude/Show Only option). Just print links here, each link - from new line.', SCS_LANG_CODE),
					'def' => '',
					'html' => 'textarea',
				),
				'hide_or_show_ip' => array(
					'label' => '',
					'desc' => '',
					'def' => '',
					'html' => 'hidden',
					'attrs' => 'id="hideOrShowIP"'
				),
				'hide_or_show_ip_switch' => array(
					'label' => '',
					'desc' => '',
					'def' => '0',
					'html' => 'hidden',
					'attrs' => 'id="hideOrShowIPSwitch"'
				),
				'hide_or_show_ip_list' => array(
					'label' => __('<span class="hideForIpBtn hide">Hide</span> / <span class="hideForIpBtn show">Show</span> Only for IP', SCS_LANG_CODE),
					'desc' => __('For those IPs comming soon will not be displayed (or vice versa - depending on Hide/Show Only option).', SCS_LANG_CODE),
					'def' => '',
					'html' => 'button',
					'options' => array(
						'value' => __('Show IPs list', SCS_LANG_CODE),
						'attrs' => 'id="hideOrShowIPButton" class="button"'
					)
				)
			),
		);
		return $opts;
	}
	public function getTemplateOptionsHtml() {
		return $this->getView()->getTemplateOptionsHtml();
	}
	public function checkComingSoon() {
		$octoMod = frameScs::_()->getModule('octo');
		// Special coming soon conditions are going here
		$isAdmin = frameScs::_()->getModule('user')->isAdmin();
		$octoEditId = ((int) reqScs::getVar('tpl_edit', 'get'));
		$octoPreview = ((int) reqScs::getVar('tpl_preview', 'get'));

		// Editor mode
		if($octoEditId && $isAdmin) {
			// We have here original ID - do not allow to edit it, get copy for this octo
			$copiedOcto = $octoMod->getModel()->getOctoForOriginal( $octoEditId );
			if($copiedOcto) {
				$oid = $copiedOcto['id'];
			} else {
				$oid = $octoMod->getModel()->copy($octoEditId, array(
					'label' => get_bloginfo('name'),
					'params' => array(
						'maint_start' => date(SCS_DATE_FORMAT),
						'maint_end' => date(SCS_DATE_FORMAT, strtotime('+2 days'))
					),
				));
			}
			if($oid) {
				$octoMod->getView()->renderForPost($oid, array('isEditMode' => $isAdmin));
			} else {
				echo '<b>'. implode('<br />', $octoMod->getModel()->getErrors()). '</b>';
			}
			exit();
		}
		// Preview mode
		if($isAdmin && $octoPreview) {
			$copiedOcto = $octoMod->getModel()->getOctoForOriginal( frameScs::_()->getModule('options')->get('cs_original_tpl_id') );
			if($copiedOcto) {
				$oid = $copiedOcto['id'];
			}
			$octoMod->getView()->renderForPost($oid, array('isEditMode' => false, 'isPreviewMode' => true));
			exit();
		}
		// Usual site visitor
		if(!$isAdmin 
			&& !is_admin() 
			&& !frameScs::_()->getModule('pages')->isLogin() 
			&& !$this->_curUserCanViewSite() 
			&& !$this->_curUrlCanShowSite()
		) {
			switch(frameScs::_()->getModule('options')->get('cs_mode')) {
				case 'coming_soon':	// For admin - show site as usual
					if(!$isAdmin) {
						if(! frameScs::_()->getModule('options')->isEmpty('hide_or_show_ip')) {
							$userIP = utilsScs::getIP();

							$textIP = frameScs::_()->getModule('options')->get('hide_or_show_ip');

							$list = array_map('trim', explode(',', $textIP));

							if (is_array($list) && count($list)) {
								if ((frameScs::_()->getModule('options')->get('hide_or_show_ip_switch') == 0 
									&& in_array($userIP, $list))
									||
									(frameScs::_()->getModule('options')->get('hide_or_show_ip_switch') == 1
									&& !in_array($userIP, $list))) {
									return;
								}
							}
						}

						$originalOctoId = (int) frameScs::_()->getModule('options')->get('cs_original_tpl_id');
						$copiedOcto = $octoMod->getModel()->getOctoForOriginal( $originalOctoId );
						if($copiedOcto) {
							$oid = $copiedOcto['id'];
						} else {
							$oid = $octoMod->getModel()->copy($originalOctoId, array(
								'label' => get_bloginfo('name'),
								'params' => array(
									'maint_start' => date(SCS_DATE_FORMAT),
									'maint_end' => date(SCS_DATE_FORMAT, strtotime('+2 days'))
								),
							));
						}

						$octoObject = $octoMod->getModel()->getFullById($oid);

						if ($octoObject && isset($octoObject['params']['maint_end_disable_site']) && $octoObject['params']['maint_end_disable_site'] && isset($octoObject['params']['maint_end']) && $octoObject['params']['maint_end']) {
							$endDate = strtotime($octoObject['params']['maint_end']);
							$currendData = current_time('timestamp');

							if ($endDate <= $currendData) {
								if ($octoObject['active']) 
									$octoMod->getModel()->switchActive(
										array(
											'id' => $octoObject['id'],
											'active' => '0'
										)
									);

								return;
							}
						}


						$octoMod->getView()->renderForPost($oid, array('isEditMode' => false));
						exit();
					}
					break;
				case 'redirect':
					$redirectUrl = trim(frameScs::_()->getModule('options')->get('redirecr_url'));
					if(strpos($redirectUrl, 'http://') !== 0 && strpos($redirectUrl, 'https://') !== 0) 
						$redirectUrl = 'http://'. $redirectUrl;
					redirectScs($redirectUrl);
					break;
				case '503_header':
					header('HTTP/1.1 503 Service Temporarily Unavailable');
					header('Status: 503 Service Temporarily Unavailable');
					header('Retry-After: 300');
					exit();
					break;
			}
		}
	}
	private function _curUserCanViewSite() {
		$roles = frameScs::_()->getModule('options')->get('site_view_user_roles');
		if(empty($roles))
			return true;
		return frameScs::_()->getModule('user')->currentUserHaveRole( $roles );
	}
	private function _curUrlCanShowSite() {
		$linksTxt = trim(frameScs::_()->getModule('options')->get('exclude_or_show_links'));
		$isExclude = ((int) frameScs::_()->getModule('options')->get('exclude_or_show')) == 0;
		if(empty($linksTxt))
			return false;

		$links = array_map('trim', explode("\n", $linksTxt));
		if(!empty($links)) {
			$currUrl = uriScs::getFullUrl();
			
			if (!$isExclude)
				return !in_array($currUrl, $links);

			return in_array($currUrl, $links);
		}
		return false;
	}
	public function addAdminBarNotice($wp_admin_bar) {
		$mode = frameScs::_()->getModule('options')->get('cs_mode');
		$wp_admin_bar->add_menu( array(
			'id'        => 'comingsoon-supsystic',
			'parent'    => 'top-secondary',
			'title'     => __('Coming Soon Mode is Enabled', SCS_LANG_CODE),
			'href'      => frameScs::_()->getModule('adminmenu')->getMainLink(),
			'meta'      => array(
				'title'     => __('Coming Soon Mode is Enabled', SCS_LANG_CODE),
				'class'		=> ($mode == 'dsbl' || empty($mode) ? 'scsHidden' : '')
			),
		));
	}
}

