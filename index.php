<?php
// test tambahan di update

date_default_timezone_set("Asia/Jakarta");

mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = mysqli_connect("localhost", "root", "", "sipdok", 3306);

if (mysqli_connect_errno()) {
	exit(mysqli_connect_error());
}

mysqli_set_charset($mysqli, "utf8mb4");

if (mysqli_errno($mysqli)) {
	exit(mysqli_error($mysqli));
}

// todo ganti semua kutip tunggal dengan ganda, kecuali didalam query

$routes = [];

function route($method, $path, $cb) {
    global $routes;
    $routes[] = [
        'method' => $method,
        'path' => $path,
        'cb' => $cb
    ];
}

function get($path, $cb) {
    route('GET', $path, $cb);
}

function post($path, $cb) {
    route('POST', $path, $cb);
}

function dispatch() {
    global $routes;
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    if (isset($_SERVER['PATH_INFO'])) {
        $path = rtrim($_SERVER['PATH_INFO'], '/');
    } else {
        $path = '/';
    }
    foreach ($routes as $route) {
        if ($route['method'] !== $method) {
            continue;
        }
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $route['path']);
        if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
            array_shift($matches);
            if (count($matches) > 0) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['cb'], $params);
            } else {
                return call_user_func($route['cb']);
            }
        }
    }
}

function render($data = []) {
    foreach ($data as $k => $v) {
        $$k = $v;
    }
    if (isset($view)) {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $view . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }
}

get('/', function() {

	global $mysqli;


	$errors = [];

	// test insert
	if (mysqli_autocommit($mysqli, false)) {

		//$data = htmlentities(strip_tags(trim('datax')));
	
		$ret1 = mysqli_query($mysqli, sprintf("insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, 'admin3'),
			mysqli_real_escape_string($mysqli, password_hash('test', PASSWORD_DEFAULT))
		));
	
		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_error($mysqli);
		}

		$ret2 = mysqli_query($mysqli, sprintf("insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, 'admin2'),
			mysqli_real_escape_string($mysqli, password_hash('test', PASSWORD_DEFAULT))
		));

		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_error($mysqli);
		}

		if ($ret1 && $ret2) {
			mysqli_commit($mysqli);
		} else {
			mysqli_rollback($mysqli);
		}
	}


	$users = [];

	// sprintf ( string:s, int:d, float:f )
	// result ( on failed:false, success select:mysqli_object, success:true
	$result = mysqli_query($mysqli, sprintf("select * from user where username like '%s'",
		mysqli_real_escape_string($mysqli, '%')
	));

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				// printf ( string:s, int:d, float:f )
				//printf('%s %s <br>', $row['username'], $row['password']);
				$users[] = [
					'username' => $row['username'],
					'password' => $row['password']
				];
			}
		}
	} else {
		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_errors($mysqli);
		}
	}

	return [
		'view' => 'index_view',
		'title' => 'Sipdok',
		'errors' => $errors,
		'users' => $users
	];
});

render(dispatch());
