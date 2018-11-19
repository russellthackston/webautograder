<?php

class Security {
	
	//public static function checkAccess($requireAdmin = FALSE) {
	public static function checkAccess($requireAdmin) {

		if (!isset($_COOKIE['wag_sessionid'])) {
			return FALSE;
		}

		$db = new DB();
		$session = $db->getSessionBySessionID($_COOKIE['wag_sessionid']);

		if ($session == NULL) {
			return FALSE;
		}

		if ($requireAdmin) {
			
			$userid = $session->userid;
			$user = $db->getUserByID($userid);
			
			if (!$user->isinstructor) {
				return FALSE;
			}

		}
		
		return TRUE;
	}
	
}


?>