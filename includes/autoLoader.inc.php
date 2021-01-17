<?php
	spl_autoload_register("classLoader");

	// Class autoloader function
	function classLoader($class){
		if(is_file('../classes/'.$class.'.class.php')){
			// In includes files
			$dic = "../classes/";
			$extension = ".class.php";
			$path = $dic . $class . $extension;
			include_once $path;
		} else {
			// In main files
			$dic = "classes/";
			$extension = ".class.php";
			$path = $dic . $class . $extension;
			include_once $path;
		}
	}
