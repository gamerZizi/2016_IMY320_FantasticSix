<?php
class mailViewScs extends viewScs {
	public function getTabContent() {
		frameScs::_()->getModule('templates')->loadJqueryUi();
		frameScs::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
		
		$this->assign('options', frameScs::_()->getModule('options')->getCatOscs( $this->getCode() ));
		$this->assign('testEmail', frameScs::_()->getModule('options')->get('notify_email'));
		return parent::getContent('mailAdmin');
	}
}
