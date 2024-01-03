<?php

if (!defined("VER")) {
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
		"view" => "page_notfound",
		"title" => "404"
	];
}
