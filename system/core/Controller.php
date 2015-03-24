<?php

require_once (BASEPATH . 'core/Loader.php');
require_once (BASEPATH . 'core/StaticResource.php');

class HGController {
	
	private static $INSTANCE;

	// handlerContext : {id, rules, preHook, postHook, handler } 
	private $handlerContext = array ();
	// handlerContext end;

	public $pathContext = array ();
	private $headers;

	public $loader;

	private $staticResource;

	private function __construct () {
		self::$INSTANCE = &$this;
		$this -> pathContext = array_slice (split ('/', preg_replace ('/\?.*?$/','', urldecode (trim ($_SERVER ['REQUEST_URI'], '/')))),1);
		$this -> headers = getallheaders ();
		$this -> loader = Loader::getInstance ();
		$this -> staticResource = StaticResource::getInstance ();
	}

	public static function &getInstance () {
		if (!self::$INSTANCE) {
			self::$INSTANCE = new HGController (); 
		}
		return self::$INSTANCE;
	}

	public function showContext () {
		print_r ($this -> handlerContext);
	}

	public function &getPathContext () {	
		return $this -> pathContext;
	}

	// $HG -> setRequestHandler ({ id => '_id', rules => {'/pregex/', '/pregex/'}, handler });
	public function get (&$handlerInfo) {
		return $this -> registerHandler ('GET', $handlerInfo);
	}

	public function post (&$handlerInfo) {
		return $this -> registerHandler ('POST', $handlerInfo);
	}

	private function registerHandler ($method, &$handlerInfo) {
		if ($handlerInfo ['id'] && 
			is_array ($handlerInfo ['rules']) &&
			$handlerInfo ['handler']) 
		{
			$handlerInfo ['method'] = $method;
			$this -> handlerContext [$handlerInfo ['id']] =& $handlerInfo;
			return true;
		} else {
			return false;
		}
	}

	public function route () {
		$pathContext =& $this -> pathContext;
		$pathLength = count ($pathContext);
		$method = $_SERVER ['REQUEST_METHOD'];

		$accept = false;
		//$found = false;

		foreach ($this -> handlerContext as $handlerInfo) {
			$rules = $handlerInfo ['rules'];
			if ($pathLength == count ($rules) && $method == $handlerInfo ['method']) {
				$i = 0;
				foreach ($rules as $rule) {
					$accept = preg_match ($rule, $pathContext [$i]);
					$i++;
					if (!$accept) break; 
				}
				if ($accept) {
					//$found = true;
					$next = true;
					
					if (!($next = $this -> callHook ($handlerInfo ['preHook']))) return;
				
					// call handler;
					$handler = $handlerInfo ['handler'];
					$handler ();

					// call postHook;
					$this -> callHook ($handlerInfo ['postHook']);
					return;
				}
			}
		}
		// try static resource;
		if ($this -> staticResource -> route ()) { return; }
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		exit ("404 Page Not Found.");
	
	}

	private function callHook (&$hook) {
		$i = 0;
		$next = true;
		// call preHook;
		$hookLength = count ($hook);
		while ($next && $i < $hookLength) {
			$next = $hook [$i] ();
			$i++;
		}
		return $next;
	}

	public function view ($filename) {
		include (APPPATH . 'views/' . $filename . 'php');
	}

	public function getAccept () {
		$accept = split (',', $this -> headers ['Accept']);
		return $accept [0];
	}

	public function setContentType ($contentType) {
		header ("Content-Type: $contentType");
	}

	public function setFilterHandler ($handler) {

		// TODO : filter context needed

	} 
}

?>