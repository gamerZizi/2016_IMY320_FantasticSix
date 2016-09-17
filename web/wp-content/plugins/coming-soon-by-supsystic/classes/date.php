<?php
class dateScs {
	static public function _($time = NULL) {
		if(is_null($time)) {
			$time = time();
		}
		return date(SCS_DATE_FORMAT_HIS, $time);
	}
}