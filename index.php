<?php

date_default_timezone_set("Asia/Jakarta");

mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = mysqli_connect("localhost", "root", "", "sipdok", 3306);

if (mysqli_connect_errno())
{
	exit(mysqli_connect_error());
}

mysqli_set_charset($mysqli, "utf8mb4");

if (mysqli_errno($mysqli))
{
	exit(mysqli_error($mysqli));
}

session_start();

$routes = [];

function route($method, $path, $cb)
{
	global $routes;

	$routes[] = [
		"method" => $method,
		"path" => $path,
		"cb" => $cb
	];
}

function get($path, $cb)
{
	route("GET", $path, $cb);
}

function post($path, $cb)
{
	route("POST", $path, $cb);
}

function dispatch()
{
	global $routes;

	$method = strtoupper($_SERVER["REQUEST_METHOD"]);

	if (isset($_SERVER["PATH_INFO"]))
	{
		$path = rtrim($_SERVER["PATH_INFO"], "/");
	}
	else
	{
		$path = "/";
	}

	foreach ($routes as $route)
	{
		if ($route["method"] !== $method)
		{
			continue;
		}

		$pattern = preg_replace("/\/:([^\/]+)/", "/(?P<$1>[^/]+)", $route["path"]);

		if (preg_match("#^" . $pattern . "$#", $path, $matches))
		{
			array_shift($matches);

			if (count($matches) > 0)
			{
				$params = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
				return call_user_func_array($route["cb"], $params);
			}
			else
			{
				return call_user_func($route["cb"]);
			}
		}
	}
}

function render($data = [])
{
	foreach ($data as $k => $v)
	{
		$$k = $v;
	}

	if (isset($view))
	{
		$file = __DIR__ . DIRECTORY_SEPARATOR . $view . ".php";

		if (file_exists($file))
		{
			include $file;
		}
	}
}

get("/", function ()
{
	global $mysqli;

	if (!isset($_SESSION["USERNAME"]))
	{
		header("Location: /user/login");
		exit;
	}

	$errors = [];

	if (mysqli_autocommit($mysqli, false))
	{
		$ret1 = mysqli_query($mysqli, sprintf(
			"insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, "admin3"),
			mysqli_real_escape_string($mysqli, password_hash("test", PASSWORD_DEFAULT))
		));

		if (mysqli_errno($mysqli))
		{
			$errors[] = mysqli_error($mysqli);
		}

		$ret2 = mysqli_query($mysqli, sprintf(
			"insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, "admin2"),
			mysqli_real_escape_string($mysqli, password_hash("test", PASSWORD_DEFAULT))
		));

		if (mysqli_errno($mysqli))
		{
			$errors[] = mysqli_error($mysqli);
		}

		if ($ret1 && $ret2)
		{
			mysqli_commit($mysqli);
		}
		else
		{
			mysqli_rollback($mysqli);
		}
	}

	$users = [];

	// sprintf ( string:s, int:d, float:f )
	// result ( on failed:false, success select:mysqli_object, success:true
	$result = mysqli_query($mysqli, sprintf(
		"select * from user where username like '%s'",
		mysqli_real_escape_string($mysqli, "%")
	));

	if ($result)
	{
		if (mysqli_num_rows($result) > 0)
		{
			while ($row = mysqli_fetch_assoc($result))
			{
				// printf ( string:s, int:d, float:f )
				//printf('%s %s <br>', $row['username'], $row['password']);
				$users[] = [
					"username" => $row["username"],
					"password" => $row["password"]
				];
			}
		}
	}
	else
	{
		if (mysqli_errno($mysqli))
		{
			$errors[] = mysqli_error($mysqli);
		}
	}

	return [
		"view" => "index_view",
		"title" => "Sipdok",
		"errors" => $errors,
		"users" => $users
	];
});

get("/user/login", function ()
{
	if (isset($_SESSION["username"]))
	{
		header("Location: /dashboard");
		exit;
	}

	$inputs = [];
	$errors = [];

	if (isset($_SESSION["inputs"]))
	{
		$inputs = $_SESSION["inputs"];
		unset($_SESSION["inputs"]);
	}

	if (isset($_SESSION["errors"]))
	{
		$errors = $_SESSION["errors"];
		unset($_SESSION["errors"]);
	}

	return [
		"view" => "login",
		"title" => "Login",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

post("/user/login/auth", function ()
{
	if (isset($_SESSION["username"]))
	{
		header("Location: /dashboard");
		exit;
	}

	global $mysqli;

	$inputs = [];
	$errors = [];

	if (isset($_POST["username"]))
	{
		$username = htmlentities(strip_tags(trim($_POST["username"])));

		if (strlen($username) > 0)
		{
			$inputs["username"] = $username;
		}
		else
		{
			$errors["username"] = "Username tidak boleh kosong";
		}
	}
	else
	{
		$errors["username"] = "Username undefined";
	}


	if (isset($_POST["password"]))
	{
		$password = htmlentities(strip_tags(trim($_POST["password"])));

		if (strlen($password) > 0)
		{
			$inputs["password"] = $password;
		}
		else
		{
			$errors["password"] = "Password tidak boleh kosong";
		}
	}
	else
	{
		$errors["password"] = "Password undefined";
	}


	if (count($errors) == 0)
	{
		$result = mysqli_query($mysqli, sprintf(
			"select * from user where username = '%s'",
			mysqli_real_escape_string($mysqli, $inputs["username"])
		));

		if (mysqli_errno($mysqli))
		{
			$errors[] = mysqli_error($mysqli);
		}

		if ($result)
		{
			if (mysqli_num_rows($result) == 1)
			{
				$user = mysqli_fetch_assoc($result);
				$password_hash = $user["password"];

				if (password_verify($inputs["password"], $password_hash))
				{
					$_SESSION["username"] = $inputs["username"];
					header("Location: /dashboard");
					exit;
				}
				else
				{
					$errors["password"] = "Password salah";
				}
			}
			else
			{
				$errors["username"] = "Username " . $inputs["username"] . " tidak terdaftar";
			}
		}
	}

	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;

	header("Location: /user/login", true, 303);
	exit;
});

get("/user/logout", function ()
{
	if (isset($_SESSION["username"]))
	{
		unset($_SESSION["username"]);
	}

	$_SESSION = [];

	session_destroy();

	header("Location: /user/login");
	exit;
});

get("/dashboard", function ()
{
	if (!isset($_SESSION["username"]))
	{
		header("Location: /user/login");
		exit;
	}

	return [
		"view" => "dashboard",
		"title" => "Dashboard"
	];
});

render(dispatch());
