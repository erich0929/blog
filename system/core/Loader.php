<?php

	class Loader {

		private static $INSTANCE;

		private $context = array ();

		private function __construct () {
			require_once (BASEPATH . "core/database/DBdriver.php");
		}

		public static function &getInstance () {
			if (!self::$INSTANCE) {
				self::$INSTANCE = new Loader ();
			}
			return self::$INSTANCE;
		}

		public function controller ($controller) {
			require_once (APPPATH . "controllers/$controller.php");
			return $this;
		}

		public function view ($view) {
			require (APPPATH . "views/$view.php");
		}

		public function database ($driver, $dbConfig = false) {
			if ($this -> context ["$driver.driver"]) {
				if (!$dbConfig) {
					return $this -> context ["$driver.driver"];
				} 
			} else {
				require_once (BASEPATH . "core/database/drivers/$driver.php");
				$this -> context ["$driver.driver"] = new $driver ($dbConfig);
			}
			return $this -> context [$driver];
		}

		public function mapper ($mapper, $singleton = true) {
			if (!$this -> context ["$mapper.mapper"]) {
				require_once (APPPATH . "mappers/$mapper.php");
				$obj = new $mapper ();
				$this -> context ["$mapper.mapper"] = $obj;
			}
			if ($singleton) {
				return $this -> context ["$mapper.mapper"];
			} else {
				return new $mapper ();
			}
		}

		public function showContext () {
			print_r ($this -> context);
		}
	}

?>