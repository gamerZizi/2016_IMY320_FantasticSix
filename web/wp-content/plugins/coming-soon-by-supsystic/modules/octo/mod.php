<?php
class octoScs extends moduleScs {
	private $_assetsUrl = '';
	private $_oldAssetsUrl = 'https://supsystic.com/_assets/coming_soon/';
	
	
	public function init() {
		// template_redirect action should be here for normal octo
		//add_action('template_redirect', array($this, 'checkOctoShow'));
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getEditLink($id) {
		return uriScs::_(array('tpl_edit' => $id));
	}
	public function getAssetsUrl() {
		if(empty($this->_assetsUrl)) {
			$this->_assetsUrl = frameScs::_()->getModule('templates')->getCdnUrl(). '_assets/coming_soon/';
		}
		return $this->_assetsUrl;
	}
	public function getOldAssetsUrl() {
		return $this->_oldAssetsUrl;
	}
}

