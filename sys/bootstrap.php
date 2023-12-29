<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

function render($data = []) {
	foreach ($data as $k => $v) {
		$$k = $v;
	}
	$header = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "tpl" . DIRECTORY_SEPARATOR . "layout_header.php";
	$layout = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "tpl" . DIRECTORY_SEPARATOR . $view . ".php";
	$footer = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "tpl" . DIRECTORY_SEPARATOR . "layout_footer.php";
	if (file_exists($header) && file_exists($layout) && file_exists($footer)) {
		include $header;
		include $layout;
		include $footer;
	} else {
		exit("Failed to render, file not exist!");
	}
}

function request() {
	if (isset($_SERVER["PATH_INFO"])) {
		$path_info = rtrim($_SERVER["PATH_INFO"], "/");
		$path_array = explode("/", $path_info);
		array_shift($path_array);
		$path = array_shift($path_array);
	} else {
		$path = "home";
	}
	return $path;
}

function response($path) {
	$file = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "mod" . DIRECTORY_SEPARATOR . $path . ".php";
	if (file_exists($file)) {
		include $file;
	} else {
		exit("Module " . $file . " not exists!");
	}
	render(dispatch());
}
