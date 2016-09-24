<?php
class coming_soonControllerScs extends controllerScs {
	public function getPermissions() {
		return array(
			SCS_USERLEVELS => array(
				SCS_ADMIN => array()
			),
		);
	}
}

