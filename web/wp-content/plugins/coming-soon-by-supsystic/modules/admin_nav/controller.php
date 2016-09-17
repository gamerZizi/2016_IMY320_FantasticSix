<?php
class admin_navControllerScs extends controllerScs {
	public function getPermissions() {
		return array(
			SCS_USERLEVELS => array(
				SCS_ADMIN => array()
			),
		);
	}
}