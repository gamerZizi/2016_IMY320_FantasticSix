<?php
class supsystic_promoViewScs extends viewScs {
    public function displayAdminFooter() {
        parent::display('adminFooter');
    }
	public function showWelcomePage() {
		$this->assign('askOptions', array(
			1 => array('label' => 'Google'),
			2 => array('label' => 'Worscsess.org'),
			3 => array('label' => 'Refer a friend'),
			4 => array('label' => 'Find on the web'),
			5 => array('label' => 'Other way...'),
		));
		$this->assign('originalPage', uriScs::getFullUrl());
		parent::display('welcomePage');
	}
	public function showAdditionalmainAdminShowOnOptions($popup) {
		$this->assign('promoLink', $this->getModule()->getMainLink(). '?utm_source=plugin&utm_medium=onexit&utm_campaign=popup');
		parent::display('additionalmainAdminShowOnOptions');
	}
	public function getOverviewTabContent() {
		frameScs::_()->getModule('templates')->loadJqueryUi();
		frameScs::_()->getModule('templates')->loadSlimscroll();
		frameScs::_()->addScript('admin.overview', $this->getModule()->getModPath(). 'js/admin.overview.js');
		frameScs::_()->addStyle('admin.overview', $this->getModule()->getModPath(). 'css/admin.overview.css');
		$this->assign('mainLink', $this->getModule()->getMainLink());
		$this->assign('faqList', $this->getFaqList());
		$this->assign('serverSettings', $this->getServerSettings());
		$this->assign('news', $this->getNewsContent());
		$this->assign('contactFields', $this->getModule()->getContactFormFields());
		return parent::getContent('overviewTabContent');
	}
	public function getFaqList() {
		return array();
	}
	public function getNewsContent() {
		return '';	// For now only
	}
	public function getServerSettings() {
		return array(
			'Operating System' => array('value' => PHP_OS),
            'PHP Version' => array('value' => PHP_VERSION),
            'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
            'MySQL' => array('value' => function_exists('mysql_get_server_info') ? @mysql_get_server_info() : __('Undefined', SCS_LANG_CODE)),
            'PHP Safe Mode' => array('value' => ini_get('safe_mode') ? __('Yes', SCS_LANG_CODE) : __('No', SCS_LANG_CODE), 'error' => ini_get('safe_mode')),
            'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? __('Yes', SCS_LANG_CODE) : __('No', SCS_LANG_CODE)),
            'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
            'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
            'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
            'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
            'PHP EXIF Support' => array('value' => extension_loaded('exif') ? __('Yes', SCS_LANG_CODE) : __('No', SCS_LANG_CODE)),
            'PHP EXIF Version' => array('value' => phpversion('exif')),
            'PHP XML Support' => array('value' => extension_loaded('libxml') ? __('Yes', SCS_LANG_CODE) : __('No', SCS_LANG_CODE), 'error' => !extension_loaded('libxml')),
            'PHP CURL Support' => array('value' => extension_loaded('curl') ? __('Yes', SCS_LANG_CODE) : __('No', SCS_LANG_CODE), 'error' => !extension_loaded('curl')),
		);
	}
}
