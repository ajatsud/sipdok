<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

function render($data = []) {
	foreach ($data as $k => $v) {
		$$k = $v;
	}

	$header = APP_DIR . APP_SEP . ".." . APP_SEP . "tpl" . APP_SEP . "layout_header.php";
	$layout = APP_DIR . APP_SEP . ".." . APP_SEP . "tpl" . APP_SEP . $view . ".php";
	$footer = APP_DIR . APP_SEP . ".." . APP_SEP . "tpl" . APP_SEP . "layout_footer.php";

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
	$file = APP_DIR . APP_SEP . ".." . APP_SEP . "mod" . APP_SEP . $path . ".php";
	if (file_exists($file)) {
		include $file;
	} else {
		exit("Module " . $file . " not exists!");
	}

	render(dispatch());
}
