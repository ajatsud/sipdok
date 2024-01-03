<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

function render($data = []) {
	foreach ($data as $k => $v) {
		$$k = $v;
	}

	$dir = "x";
	$file = "x";

	if (isset($view)) {
		$view_arr = explode("_", $view);
		if (count($view_arr) == 2) {
			$dir = array_shift($view_arr);
			$file = array_shift($view_arr);
		}
	}

	$header = DIR . SEP . "tpl" . SEP . "layout" . SEP . "header.php";
	$layout = DIR . SEP . "tpl" . SEP . $dir . SEP . $file . ".php";
	$footer = DIR . SEP . "tpl" . SEP . "layout" . SEP . "footer.php";

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
	$file = DIR . SEP . "mod" . SEP . $path . ".php";
	if (file_exists($file)) {
		include $file;
	} else {
		redirect_to("/");
	}

	render(dispatch());
}
