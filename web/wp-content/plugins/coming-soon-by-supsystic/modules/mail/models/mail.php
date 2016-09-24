<?php
class mailModelScs extends modelScs {
	public function testEmail($email) {
		$email = trim($email);
		if(!empty($email)) {
			if($this->getModule()->send($email, 
				__('Test email functionslity', SCS_LANG_CODE), 
				sprintf(__('This is test email for testing email functionality on your site, %s.', SCS_LANG_CODE), SCS_SITE_URL))
			) {
				return true;
			} else {
				$this->pushError( $this->getModule()->getMailErrors() );
			}
		} else
			$this->pushError (__('Empty email address', SCS_LANG_CODE), 'test_email');
		return false;
	}
}