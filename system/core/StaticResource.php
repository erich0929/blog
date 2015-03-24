<?php

	class StaticResource {

		private static $INSTANCE;

		private $mimeTypes = array ();

		private function __construct () {
			$this -> init ();	
		}

		public function getInstance () {
			if (!self::$INSTANCE) self::$INSTANCE = new StaticResource ();
			return self::$INSTANCE;
		}

		private function init () {
			$HG =& getInstance ();
			$this -> mimeTypes ['html'] = function ($filename) use ($HG) {
					$HG -> setContentType ('text/html');
					self::writeText ($filename);
			};
			$this -> mimeTypes ["css"] = function ($filename) use ($HG) {
					$HG -> setContentType ('text/css');
					self::writeText ($filename);
			};
			
			$this -> mimeTypes ["js"] = function ($filename) use ($HG) {
					$HG -> setContentType ('text/javascript');
					self::writeText ($filename);
			};
			
			$this -> mimeTypes ["png"] = function ($filename) use ($HG) {
					$HG -> setContentType  ('image/png');
				//echo $filename;

					echo $im = file_get_contents($filename);

					//header('Content-Type: image/png');
				
				//	echo 'b';
				//	echo $im;
					//imagedestroy($image);
			};

			$this -> mimeTypes ["jpeg"] = function ($filename) use ($HG) {
					$HG -> setContentType  ('image/jpeg');
					self::writeStream ($filename);
			}; 

			$this -> mimeTypes ["ttf"] = function ($filename) use ($HG) {
					$HG -> setContentType  ('font/opentype');
					self::writeStream ($filename);
			};
			$this -> mimeTypes ["woff"] = function ($filename) use ($HG) {
					$HG -> setContentType  ('application/font-woff');
					self::writeStream ($filename);
			}; 

			$this -> mimeTypes ["svg"] = function ($filename) use ($HG) {
					$HG -> setContentType  ('image/svg+xml');
					self::writeStream ($filename);
			}; 

			
		}

		public function route () {
			$HG =& getInstance ();
			$pathContext =& $HG -> getPathContext ();
			$pathLength = count ($pathContext);

			if (preg_match ('/\.(html|js|css|png|jpeg|woff|ttf|svg)$/', $pathContext [$pathLength - 1], $matches)) {
				$ext = $matches [1];

				$mimeType = $this -> mimeTypes [$ext];
	
				
				if (!$mimeType) return false;
				
				$filename = APPPATH . 'public/' . join ('/', $pathContext);
				if (!file_exists($filename)) return false;

				// run callback.
				$mimeType ($filename);
				return true;
			}	
		}

		private static function writeText ($filename) {
			require ($filename);
			return true;
		}

		private static function writeStream ($filename) {
			readfile ($filename);
			return true;
		}
	}

?>