<?php

// coding pass

class view
{
	static function render($file, $data = [])
	{
		if(isset($_SERVER['PATH_INFO']))
		    $current_path = $_SERVER['PATH_INFO'];
		else
		    $current_path = '/';
		
		$current_path_arr = explode('/', $current_path);
		$current_path_active = $current_path_arr[1];
		
		foreach($data as $k => $v)
			$$k = $v;
		
		$file = __DIR__ . DIRECTORY_SEPARATOR . $file . '_html.php';
		$lyot = __DIR__ . DIRECTORY_SEPARATOR . 'layout.php';
		
		if(file_exists($lyot))
		    include $lyot;
	}
}
