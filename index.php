<?php

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

session_start();

$routes = [];

function route($method, $path, $cb) {
	global $routes;
	$routes[] = [
		"method" => $method,
		"path" => $path,
		"cb" => $cb
	];
}

function get($path, $cb) {
	route("GET", $path, $cb);
}

function post($path, $cb) {
	route("POST", $path, $cb);
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
				return call_user_func_array($route["cb"], $params);
			} else {
				return call_user_func($route["cb"]);
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
	if (isset($view)) {
		$file = __DIR__ . DIRECTORY_SEPARATOR . $view . ".php";
		if (file_exists($file)) {
			include $file;
		}
	}
}

function is_login() {
	if (isset($_SESSION["username"])) {
		return true;
	}
	return false;
}

function redirect_to($path) {
	header("Location: " . $path);
	exit;
}

get("/", function () {
	if (is_login()) {
		redirect_to("/dashboard");
	} else {
		redirect_to("/user/login");
	}
	// test
	global $mysqli;
	$errors = [];
	if (mysqli_autocommit($mysqli, false)) {
		$ret1 = mysqli_query($mysqli, sprintf(
			"insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, "ajat"),
			mysqli_real_escape_string($mysqli, password_hash("test", PASSWORD_DEFAULT))
		));
		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_error($mysqli);
		}
		$ret2 = mysqli_query($mysqli, sprintf(
			"insert into user (username, password) values ('%s', '%s')",
			mysqli_real_escape_string($mysqli, "admin"),
			mysqli_real_escape_string($mysqli, password_hash("test", PASSWORD_DEFAULT))
		));
		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_error($mysqli);
		}
		if ($ret1 && $ret2) {
			mysqli_commit($mysqli);
			echo "success commit";
		} else {
			mysqli_rollback($mysqli);
			echo "failed rollback";
		}
	}
	$users = [];
	// sprintf ( string:s, int:d, float:f )
	// result ( on failed:false, success select:mysqli_object, success:true
	$result = mysqli_query($mysqli, sprintf(
		"select * from user where username like '%s'",
		mysqli_real_escape_string($mysqli, "%")
	));
	if (mysqli_errno($mysqli)) {
		$errors[] = mysqli_error($mysqli);
	}
	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				// printf ( string:s, int:d, float:f )
				// printf('%s %s <br>', $row['username'], $row['password']);
				$users[] = [
					"username" => $row["username"],
					"password" => $row["password"]
				];
			}
		}
	}
	exit;
});

get("/user/login", function () {
	if (is_login()) {
		redirect_to("/dashboard");
	}
	$inputs = [];
	$errors = [];
	if (isset($_SESSION["inputs"])) {
		$inputs = $_SESSION["inputs"];
		unset($_SESSION["inputs"]);
	}
	if (isset($_SESSION["errors"])) {
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

post("/user/login/auth", function () {
	if (is_login()) {
		redirect_to("/dashboard");
	}
	global $mysqli;
	$inputs = [];
	$errors = [];
	if (isset($_POST["username"])) {
		$username = htmlentities(strip_tags(trim($_POST["username"])));
		if (strlen($username) > 0) {
			$inputs["username"] = $username;
		} else {
			$errors["username"] = "Username tidak boleh kosong";
		}
	} else {
		$errors["username"] = "Username undefine";
	}
	if (isset($_POST["password"])) {
		$password = htmlentities(strip_tags(trim($_POST["password"])));
		if (strlen($password) > 0) {
			$inputs["password"] = $password;
		} else {
			$errors["password"] = "Password tidak boleh kosong";
		}
	} else {
		$errors["password"] = "Password undefine";
	}
	if (count($errors) == 0) {
		$res = mysqli_query($mysqli, sprintf(
			"select * from user where username = '%s'",
			mysqli_real_escape_string($mysqli, $inputs["username"])
		));
		if (mysqli_errno($mysqli)) {
			$errors[] = mysqli_error($mysqli);
		}
		if ($res) {
			if (mysqli_num_rows($res) == 1) {
				$user = mysqli_fetch_assoc($res);
				$password_hash = $user["password"];
				if (password_verify($inputs["password"], $password_hash)) {
					$_SESSION["username"] = $inputs["username"];
					header("Location: /dashboard");
					exit;
				} else {
					$errors["password"] = "Password salah";
				}
			} else {
				$errors["username"] = "Username " . $inputs["username"] . " tidak terdaftar";
			}
		}
	}
	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;
	header("Location: /user/login", true, 303);
	exit;
});

get("/user/logout", function () {
	if (isset($_SESSION["username"])) {
		unset($_SESSION["username"]);
	}
	$_SESSION = [];
	session_destroy();
	header("Location: /user/login");
	exit;
});

get("/dashboard", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}
	return [
		"view" => "dashboard",
		"title" => "Dashboard",
		"menu" => "dashboard"
	];
});

get("/pasien", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}
	$inputs = [];
	$errors = [];
	if (isset($_SESSION["inputs"])) {
		$inputs = $_SESSION["inputs"];
		unset($_SESSION["inputs"]);
	}
	if (isset($_SESSION["errors"])) {
		$errors = $_SESSION["errors"];
		unset($_SESSION["errors"]);
	}
	return [
		"view" => "pasien_form",
		"title" => "Tambah Pasien",
		"menu" => "pasien",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

post("/pasien/save", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}
	global $mysqli;
	$inputs = [];
	$errors = [];
	$is_new = false;
	if (isset($_POST["id"])) {
		$id = htmlentities(strip_tags(trim($_POST["id"])));
		if (strlen($id) > 0) {
			$inputs["id"] = $id;
		} else { // PS2023120001
			$is_new = true;
			$prefix = "PS" . date("Ym");
			$res = mysqli_query($mysqli, sprintf(
				"select ifnull(max(substring(id, 9, 4)), 0) as seq_no from pasien where substring(id, 1, 8) = '%s'",
				mysqli_real_escape_string($mysqli, $prefix)
			));
			if (mysqli_errno($mysqli)) {
				$errors[] = mysqli_error($mysqli);
			}
			if ($res) {
				if (mysqli_num_rows($res) > 0) {
					$row = mysqli_fetch_assoc($res);
					$seq_no = (int) $row["seq_no"];
					$seq_no++;
					$seq_no_str = sprintf("%04d", $seq_no);
					$inputs["id"] = $prefix . $seq_no_str;
				}
			}
		}
	} else {
		$errors["id"] = "ID undefine";
	}
	if (isset($_POST["nama"])) {
		$nama = htmlentities(strip_tags(trim($_POST["nama"])));
		if (strlen($nama) > 0) {
			$inputs["nama"] = $nama;
		} else {
			$errors["nama"] = "Nama tidak boleh kosong";
		}
	} else {
		$errors["nama"] = "Nama undefine";
	}
	if (isset($_POST["jenkel"])) {
		$jenkel = htmlentities(strip_tags(trim($_POST["jenkel"])));
		if (strlen($jenkel) > 0) {
			$inputs["jenkel"] = $jenkel;
		} else {
			$errors["jenkel"] = "Jenkel harus dipilih";
		}
	} else {
		$errors["jenkel"] = "Jenkel undefine";
	}
	if (isset($_POST["lahir"])) {
		$lahir = htmlentities(strip_tags(trim($_POST["lahir"])));
		if (strlen($lahir) > 0) {
			$inputs["lahir"] = date("Y-m-d", strtotime($lahir));
		} else {
			$errors["lahir"] = "Tanggal lahir harus diinput";
		}
	} else {
		$errors["lahir"] = "Tanggal lahir tidak dikenal";
	}
	if (isset($_POST["alamat"])) {
		$alamat = htmlentities(strip_tags(trim($_POST["alamat"])));
		if (strlen($alamat) > 0) {
			$inputs["alamat"] = $alamat;
		} else {
			$errors["alamat"] = "Alamat harus diisi";
		}
	} else {
		$errors["alamat"] = "Alamat undefined";
	}
	if (count($errors) == 0) {
		// proses save
	} else {
		$inputs["id"] = "";
	}
	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;
	header("Location: /pasien", true, 303);
	exit;
});

render(dispatch());
