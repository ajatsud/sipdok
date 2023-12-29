<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

$routes = [];

function route($method, $path, $callback) {
	global $routes;
	$routes[] = [
		"method" => $method,
		"path" => $path,
		"callback" => $callback
	];
}

function get($path, $callback) {
	route("GET", $path, $callback);
}

function post($path, $callback) {
	route("POST", $path, $callback);
}

function dispatch() {
	global $routes;

	$method = strtoupper($_SERVER["REQUEST_METHOD"]);

	if (isset($_SERVER["PATH_INFO"])) {
		$path = rtrim($_SERVER["PATH_INFO"], "/");
	} else {
		$path = "/";
	}

	foreach ($routes as $route) {
		if ($route["method"] !== $method) {
			continue;
		}
		$pattern = preg_replace("/\/:([^\/]+)/", "/(?P<$1>[^/]+)", $route["path"]);
		if (preg_match("#^" . $pattern . "$#", $path, $matches)) {
			array_shift($matches);
			if (count($matches) > 0) {
				$params = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
				return call_user_func_array($route["callback"], $params);
			} else {
				return call_user_func($route["callback"]);
			}
		}
	}

	return [
		"view" => "notfound",
		"title" => "404"
	];
}

function render($data = []) {
	foreach ($data as $k => $v) {
		$$k = $v;
	}

	$header = __DIR__ . DIRECTORY_SEPARATOR . "layout_header.php";
	$layout = __DIR__ . DIRECTORY_SEPARATOR . $view . ".php";
	$footer = __DIR__ . DIRECTORY_SEPARATOR . "layout_footer.php";

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
	$file = __DIR__ . DIRECTORY_SEPARATOR . $path . ".php";
	if (file_exists($file)) {
		include $file;
	} else {
		exit("Module " . $file . " not exists!");
	}

	render(dispatch());
}
