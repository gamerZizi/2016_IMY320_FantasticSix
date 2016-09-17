<?php
class optionsControllerScs extends controllerScs {
	public function saveGroup() {
		$res = new responseScs();
		if($this->getModel()->saveGroup(reqScs::get('post'))) {
			$res->addMessage(__('Done', SCS_LANG_CODE));
		} else
			$res->pushError ($this->getModel('options')->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			SCS_USERLEVELS => array(
				SCS_ADMIN => array('saveGroup')
			),
		);
	}
}

