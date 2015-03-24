<?php 

class Handler {
	private $handler = array ();

	private static $autoIncrement = 0;
	public function __construct () {
		self::$autoIncrement ++;
		$this -> handler ['id'] = self::$autoIncrement;
	}

	public function id ($id) {
		$this -> handler ['id'] = $id;
		return $this;
	}

	public function rule ($preg) {
	
		$rules =& $this -> handler ['rules'];
		if (!is_array ($rules)) $rules = array ();
		array_push ($rules, $preg);
		return $this;
	}

	public function rules ($pregArr) {
		$this -> handler ['rules'] =& $pregArr;
		return $this;
	}

	public function handler ($callback) {
		$this -> handler ['handler'] = $callback;
		return $this;
	}

	public function preHook ($callback) {
		$this -> registerHook ('preHook', $callback);
		return $this;
	}

	public function postHook ($callback) {
		$this -> registerHook ('postHook', $callback);
		return $this;
	}

	public function registerHook ($where, $callback) {
		$hook =& $this -> handler [$where];
		if (!is_array ($hook)) $hook = array ();
		array_push ($hook, $callback);
	}

	public function build () {
		$handler = $this -> handler;
		if ($handler ['id'] &&
			is_array ($handler ['rules']) &&
			$handler ['handler']
		) 
		{
			if ($handler ['preHook'] ) {
				if (!is_array ($handler ['preHook'])) return false; 
			}
			if ($handler ['postHook'] ) {
				if (!is_array ($handler ['postHook'])) return false;
			}

			return $this -> handler;
		}
		return false;
	}

}

class DBConfig {

	private $dbConfig;

	public function __construct () {
		$this -> dbConfig = array ();
		$this -> dbConfig ['drivername'] = 'utf8';
		$this -> dbConfig ['dbcollation'] = 'utf8_general_ci';
	}

	public function username ($username) {
		$this -> dbConfig ['username'] = $username;
		return $this;
	}

	public function password ($password) {
		$this -> dbConfig ['password'] = $password;
		return $this;
	}

	public function hostname ($hostname) {
		$this -> dbConfig ['hostname'] = $hostname;
		return $this;
	}

	public function database ($database) {
		$this -> dbConfig ['database'] = $database;
		return $this;
	}

	public function driver ($driver) {
		$this -> dbConfig ['drivername'] = $driver;
		return $this;
	}

	public function charset ($charset) {
		$this -> dbConfig ['charset'] = $charset;
		return $this;
	}

	public function dbcollation ($dbcollation) {
		$this -> dbConfig ['dbcollation'] = $dbcollation;
		return $this;
	}

	public function build () {
		return $this -> dbConfig;
	}

}

?>