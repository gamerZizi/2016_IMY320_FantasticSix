<?php
class subscribeControllerScs extends controllerScs {
	public function subscribe() {
		$res = new responseScs();
		$data = reqScs::get('post');
		$id = isset($data['id']) ? (int) $data['id'] : 0;
		$nonce = $_REQUEST['_wpnonce'];
		if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'subscribe-'. $id)) {
			die('Some error with your request.........');
		}
		if($this->getModel()->subscribe(reqScs::get('post'), true)) {
			$dest = $this->getModel()->getDest();
			$destData = $this->getModule()->getDestByKey( $dest );
			$lastBlock = $this->getModel()->getLastBlock();
			$withoutConfirm = (isset($lastBlock['params']['sub_ignore_confirm']) && $lastBlock['params']['sub_ignore_confirm']['val'])
				|| (isset($lastBlock['params']['sub_dsbl_dbl_opt_id']) && $lastBlock['params']['sub_dsbl_dbl_opt_id']['val']);
			if(isset($lastBlock['params']['sub_dest']['val']) 
				&& $lastBlock['params']['sub_dest']['val'] == 'mailpoet' 
				&& class_exists('WYSIJA')
				&& ($wisijaConfigModel = WYSIJA::get('config', 'model'))
			) {
				$withoutConfirm = !(bool) $wisijaConfigModel->getValue('confirm_dbleoptin');
			}
			if($destData && isset($destData['require_confirm']) && $destData['require_confirm'] && !$withoutConfirm)
				$res->addMessage(isset($lastBlock['params']['sub_txt_confirm_sent']['val']) 
						? $lastBlock['params']['sub_txt_confirm_sent']['val'] : 
						__('Confirmation link was sent to your email address. Check your email!', SCS_LANG_CODE));
			else
				$res->addMessage(isset($lastBlock['params']['sub_txt_success']) && !empty($lastBlock['params']['sub_txt_success']['val'])
						? $lastBlock['params']['sub_txt_success']['val']
						: __('Thank you for subscribe!', SCS_LANG_CODE));
			$redirectUrl = isset($lastBlock['params']['sub_redirect_url']) && !empty($lastBlock['params']['sub_redirect_url']['val'])
					? $lastBlock['params']['sub_redirect_url']['val']
					: false;
			if(!empty($redirectUrl)) {
				$redirectUrl = trim($redirectUrl);
				if(strpos($redirectUrl, 'http') !== 0) {
					$redirectUrl = 'http://'. $redirectUrl;
				}
				$res->addData('redirect', $redirectUrl);
			}
		} else
			$res->pushError ($this->getModel()->getErrors());
		if(!$res->isAjax()) {
			if(!$res->error()) {
				// Add statistics here when we will have it
				/*$popupActions = reqScs::getVar('oct_actions_'. $id, 'cookie');
				if(empty($popupActions)) {
					$popupActions = array();
				}
				$popupActions['subscribe'] = date('m-d-Y H:i:s');
				reqScs::setVar('oct_actions_'. $id, $popupActions, 'cookie', array('expire' => 7 * 24 * 3600));
				frameScs::_()->getModule('statistics')->getModel()->add(array(
					'id' => $id,
					'type' => 'subscribe',
				));*/
			}
			$res->mainRedirect(isset($redirectUrl) && $redirectUrl ? $redirectUrl : '');
		}
		return $res->ajaxExec();
	}
	public function confirm() {
		
		$res = new responseScs();
		if(!$this->getModel()->confirm(reqScs::get('get'))) {
			$res->pushError ($this->getModel()->getErrors());
		}
		$lastBlock = $this->getModel()->getLastBlock();
		$this->getView()->displaySuccessPage($lastBlock, $res);
		exit();
		// Just simple redirect for now
		//$siteUrl = get_bloginfo('wpurl');
		//redirectScs($siteUrl);
	}
	public function getMailchimpLists() {
		$res = new responseScs();
		if(($lists = $this->getModel()->getMailchimpLists(reqScs::get('post'))) !== false) {
			$res->addData('lists', $lists);
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			SCS_USERLEVELS => array(
				SCS_ADMIN => array('getMailchimpLists')
			),
		);
	}
}

