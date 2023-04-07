<?php

class router
{
	private static $routes = [];
	
	static function route($method, $path, $model, $view, $controller, $function)
	{
		self::$routes[] = [
			'method' => $method,
			'path' => $path,
			'model' => $model,
            'view' => $view,
			'controller' => $controller,
			'function' => $function
		];
	}
	
	static function get($path, $model, $view, $controller, $function)
	{
		self::route('GET', $path, $model, $view, $controller, $function);
	}
	
	static function post($path, $model, $view, $controller, $function)
	{
		self::route('POST', $path, $model, $view, $controller, $function);
	}
	
	static function dispatch()
	{
		$method = $_SERVER['REQUEST_METHOD'];
        
        if(isset($_SERVER['PATH_INFO']))
            $path = $_SERVER['PATH_INFO'];
        else
            $path = '/';
            
        // user yang belum login hanya boleh lihat halama ini
        $white_list = [
            'login_page_view'    
        ];
		
		foreach(self::$routes as $route){
			
			$pattern = '#^' . $route['path'] . '$#';
			
			if(preg_match($pattern, $path, $var) && $method == $route['method']){
				
				if(!isset($_SESSION['username']) && !in_array($route['view'], $white_list)){
				    $model = new login_page_model(self::connection());
				    $controller = new login_page_controller();
				    $view = new login_page_view();
				}else{
				    $model = (new $route['model'](self::connection()));
    				$controller = (new $route['controller']);
                    $view = (new $route['view']);    
				}
				
				array_shift($var);
				array_push($var, $model);
				
				$view->output(call_user_func_array(
				    [$controller, $route['function']], $var
				));
			}
		}
	}
    
    static function connection()
    {
        try{
            $pdo = new PDO('mysql:host=localhost;dbname=sipdok;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION
            ]);
        }catch(PDOException $e){
            echo $e->getMessage();
            exit;
        }

        return $pdo;
    }
}
