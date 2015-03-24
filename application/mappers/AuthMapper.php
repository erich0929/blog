<?php
	
	class AuthMapper {

		private $adminAccount;

		public function __construct () {

			$this -> adminAccount = 'erich0929@naver.com';

		}

		public function auth () {
			

			if (!$_SESSION ['userName']) {
				return array ('userName' => 'guest');
			}

			if ($_SESSION ['email'] == $this -> adminAccount) {
				return array ('userName' => 'admin');
			}

			return array ('userName' => $_SESSION ['userName']);
		}
	}

?>