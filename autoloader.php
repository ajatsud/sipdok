<?php

// coding pass

class autoloader
{
	static function register()
	{
	    return spl_autoload_register(function ($class){
			$file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
			if(file_exists($file)){
				include $file;
				return true;
			}
			return false;
		});
	}
}

autoloader::register();
