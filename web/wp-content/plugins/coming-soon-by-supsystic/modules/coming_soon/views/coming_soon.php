<?php
class coming_soonViewScs extends viewScs {
	public function getTemplateOptionsHtml() {
		$this->assign('presetTemplates', dispatcherScs::applyFilters('showTplsList', frameScs::_()->getModule('octo')->getModel()->getPresetTemplates()));
		return parent::getContent('csTemplateOpts');
	}
	public function getSettingsTabContent() {
		frameScs::_()->addScript('admin.coming_soon', $this->getModule()->getModPath(). 'js/admin.coming_soon.js');
		frameScs::_()->addStyle('admin.coming_soon', $this->getModule()->getModPath(). 'css/admin.coming_soon.css');
		frameScs::_()->getModule('templates')->loadJqueryUi();
		
		$options = frameScs::_()->getModule('options')->getAll();
		$this->assign('options', $options);
		return parent::getContent('csSettingsTabContent');
	}
}
