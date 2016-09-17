<?php
class mailControllerScs extends controllerScs {
	public function testEmail() {
		$res = new responseScs();
		$email = reqScs::getVar('test_email', 'post');
		if($this->getModel()->testEmail($email)) {
			$res->addMessage(__('Now check your email inbox / spam folders for test mail.'));
		} else 
			$res->pushError ($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function saveMailTestRes() {
		$res = new responseScs();
		$result = (int) reqScs::getVar('result', 'post');
		frameScs::_()->getModule('options')->getModel()->save('mail_function_work', $result);
		$res->ajaxExec();
	}
	public function saveOptions() {
		$res = new responseScs();
		$oscsModel = frameScs::_()->getModule('options')->getModel();
		$submitData = reqScs::get('post');
		if($oscsModel->saveGroup($submitData)) {
			$res->addMessage(__('Done', SCS_LANG_CODE));
		} else
			$res->pushError ($oscsModel->getErrors());
		$res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			SCS_USERLEVELS => array(
				SCS_ADMIN => array('testEmail', 'saveMailTestRes', 'saveOptions')
			),
		);
	}
}
