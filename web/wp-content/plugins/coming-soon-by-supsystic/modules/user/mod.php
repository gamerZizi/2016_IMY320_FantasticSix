<?php
class userScs extends moduleScs {
	protected $_data = array();
    protected $_curentID = 0;
	protected $_dataLoaded = false;
	
    public function loadUserData() {
        return $this->getCurrent();
    }
    public function isAdmin() {
		if(!function_exists('wp_get_current_user')) {
			frameScs::_()->loadPlugins();
		}
        return current_user_can( frameScs::_()->getModule('adminmenu')->getMainCap() );
    }
	public function getCurrentUserPosition() {
		if($this->isAdmin())
			return SCS_ADMIN;
		else if($this->getCurrentID())
			return SCS_LOGGED;
		else 
			return SCS_GUEST;
	}
    public function getCurrent() {
		return wp_get_current_user();
    }
	
    public function getCurrentID() {
		$this->_loadUserData();
		return $this->_curentID;
    }
	protected function _loadUserData() {
		if(!$this->_dataLoaded) {
			if(!function_exists('wp_get_current_user')) frameScs::_()->loadPlugins();
				$user = wp_get_current_user();
			$this->_data = $user->data;
			$this->_curentID = $user->ID;
			$this->_dataLoaded = true;
		}
	}
	public function getAdminsList() {
		$admins = dbScs::get('SELECT * FROM #__users 
			INNER JOIN #__usermeta ON #__users.ID = #__usermeta.user_id
			WHERE #__usermeta.meta_key = "#__capabilities" AND #__usermeta.meta_value LIKE "%administrator%"');
		return $admins;
	}
	public function currentUserHaveRole($role) {
		$user = $this->getCurrent();
		if($user && $user->ID && isset($user->roles) && !empty($user->roles)) {
			if(is_array($role)) {	// Multiple roles check at one time
				$rolesIntersect = array_intersect($role, $user->roles);
				return !empty($rolesIntersect);
			} else {
				return in_array($role, $user->roles);
			}
		}
		return false;
	}
}

