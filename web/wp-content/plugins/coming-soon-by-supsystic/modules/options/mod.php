<?php
class optionsScs extends moduleScs {
	private $_tabs = array();
	private $_options = array();
	private $_optionsToCategoires = array();	// For faster search
	
	public function init() {
		dispatcherScs::addAction('afterModulesInit', array($this, 'initAllOptValues'));
		//dispatcherScs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
	}
	public function initAllOptValues() {
		// Just to make sure - that we loaded all default options values
		$this->getAll();
	}
    /**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
    public function get($code) {
        return $this->getModel()->get($code);
    }
	/**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
	public function isEmpty($code) {
		return $this->getModel()->isEmpty($code);
	}
	public function getAllowedPublicOptions() {
		// empty for now
		return array();
	}
	public function getAdminPage() {
		if(installerScs::isUsed()) {
			return $this->getView()->getAdminPage();
		} else {
			return frameScs::_()->getModule('supsystic_promo')->showWelcomePage();
		}
	}
	public function addAdminTab($tabs) {
		$tabs['settings'] = array(
			'label' => __('Settings', SCS_LANG_CODE), 'callback' => array($this, 'getSettingsTabContent'), 'fa_icon' => 'fa-gear', 'sort_order' => 30,
		);
		return $tabs;
	}
	public function getSettingsTabContent() {
		return $this->getView()->getSettingsTabContent();
	}
	public function getTabs() {
		if(empty($this->_tabs)) {
			$this->_tabs = dispatcherScs::applyFilters('mainAdminTabs', array(
				//'main_page' => array('label' => __('Main Page', SCS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'wp_icon' => 'dashicons-admin-home', 'sort_order' => 0), 
			));
			foreach($this->_tabs as $tabKey => $tab) {
				if(!isset($this->_tabs[ $tabKey ]['url'])) {
					$this->_tabs[ $tabKey ]['url'] = $this->getTabUrl( $tabKey );
				}
			}
			uasort($this->_tabs, array($this, 'sortTabsClb'));
		}
		return $this->_tabs;
	}
	public function sortTabsClb($a, $b) {
		if(isset($a['sort_order']) && isset($b['sort_order'])) {
			if($a['sort_order'] > $b['sort_order'])
				return 1;
			if($a['sort_order'] < $b['sort_order'])
				return -1;
		}
		return 0;
	}
	public function getTab($tabKey) {
		$this->getTabs();
		return isset($this->_tabs[ $tabKey ]) ? $this->_tabs[ $tabKey ] : false;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getActiveTab() {
		$reqTab = reqScs::getVar('tab');
		return empty($reqTab) ? 'settings' : $reqTab;
	}
	public function getTabUrl($tab = '') {
		static $mainUrl;
		if(empty($mainUrl)) {
			$mainUrl = frameScs::_()->getModule('adminmenu')->getMainLink();
		}
		return empty($tab) ? $mainUrl : $mainUrl. '&tab='. $tab;
	}
	public function getRolesList() {
		if(!function_exists('get_editable_roles')) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );
		}
		return get_editable_roles();
	}
	public function getAvailableUserRolesSelect() {
		$rolesList = $this->getRolesList();
		$rolesListForSelect = array();
		foreach($rolesList as $rKey => $rData) {
			$rolesListForSelect[ $rKey ] = $rData['name'];
		}
		return $rolesListForSelect;
	}
	public function getAll() {
		if(empty($this->_options)) {
			$this->_options = dispatcherScs::applyFilters('optionsDefine', array(
				'system' => array(
					'label' => __('System', SCS_LANG_CODE),
					'sort_order' => 90,
					'opts' => array(
						'send_stats' => array('label' => __('Send usage statistics', SCS_LANG_CODE), 'desc' => __('Send information about what plugin options you prefer to use, this will help us make our solution better for You.', SCS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
						'add_love_link' => array('label' => __('Enable promo link', SCS_LANG_CODE), 'desc' => __('We are trying to make our plugin better for you, and you can help us with this. Just check this option - and small promotion link will be added in the bottom of your Coming Soon page. This is easy for you - but very helpful for us!', SCS_LANG_CODE), 'def' => '0', 'html' => 'checkboxHiddenVal'),
					),
				),
			));
			$isPro = frameScs::_()->getModule('supsystic_promo')->isPro();
			if(!$isPro) {
				$mainLink = frameScs::_()->getModule('supsystic_promo')->getMainLink();
			}
			uasort($this->_options, array($this, 'sortMainOptsClb'));
			foreach($this->_options as $catKey => $cData) {
				foreach($cData['opts'] as $optKey => $opt) {
					$this->_optionsToCategoires[ $optKey ] = $catKey;
					if(isset($opt['pro']) && !$isPro) {
						$this->_options[ $catKey ]['opts'][ $optKey ]['pro'] = $mainLink. '?utm_source=plugin&utm_medium='. $optKey. '&utm_campaign=popup';
					}
				}
			}
			$this->getModel()->fillInValues( $this->_options );
		}
		return $this->_options;
	}
	public function sortMainOptsClb($a, $b) {
		if(isset($a['sort_order']) && isset($b['sort_order'])) {
			if($a['sort_order'] > $b['sort_order'])
				return 1;
			if($a['sort_order'] < $b['sort_order'])
				return -1;
		}
		return 0;
	}
	public function getFullCat($cat) {
		$this->getAll();
		return isset($this->_options[ $cat ]) ? $this->_options[ $cat ] : false;
	}
	public function getCatOscs($cat) {
		$opts = $this->getFullCat($cat);
		return $opts ? $opts['opts'] : false;
	}
}

