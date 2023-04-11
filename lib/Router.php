<?php

class Router {

	private static array $routes;

	private static function add(string $method, string $path, callable $callback): void {
		self::$routes[] = [
			'method' => $method,
			'path' => $path,
			'callback' => $callback
		];
	}

	public static function get(string $path, callable $callback): void {
		self::add('GET', $path, $callback);
	}

	public static function post(string $path, callable $callback): void {
		self::add('POST', $path, $callback);
	}

	public static function dispatch(): void {
		$method = $_SERVER['REQUEST_METHOD'];
		$path   = $_SERVER['PATH_INFO'] ?? '/';
		foreach (self::$routes as $route) {
			$pattern = '#^' . $route['path'] . '$#';
			if (preg_match($pattern, $path, $variables) && $route['method'] == $method) {
				array_shift($variables);
				if (count($variables) > 0)
					call_user_func_array($route['callback'], $variables);
				else
					call_user_func($route['callback']);
				return;
			}
		}
		http_response_code(404);
	}
}
