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

function jenkel_display_format($jenkel) {
	if ($jenkel == "l") {
		return "Laki-Laki";
	}
	return "Perempuan";
}

function tanggal_display_format($date) { // "2023-12-30"
	return date("d F Y", strtotime($date));
}

get("/", function () {
	if (is_login()) {
		redirect_to("/dashboard");
	} else {
		redirect_to("/user/login");
	}
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

	global $mysqli;

	$errors = [];

	$res = mysqli_query($mysqli, "select * from pasien order by id desc");
	if (mysqli_errno($mysqli)) {
		$errors[] = mysqli_error($mysqli);
	}

	$pasiens = [];

	if ($res) {
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$pasiens[] = [
					"id" => $row["id"],
					"nama" => $row["nama"],
					"jenkel" => $row["jenkel"],
					"lahir" => $row["lahir"],
					"alamat" => $row["alamat"]
				];
			}
		}
	}

	return [
		"view" => "pasien",
		"title" => "Pasien List",
		"menu" => "pasien",
		"errors" => $errors,
		"pasiens" => $pasiens
	];
});

get("/pasien/form", function () {
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
		"title" => "Pasien Form",
		"menu" => "pasien",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

get("/pasien/edit/:id", function ($id) {
	global $mysqli;

	$inputs = [];
	$errors = [];

	$id = htmlentities(strip_tags(trim($id)));

	$res = mysqli_query($mysqli, sprintf(
		"select * from pasien where id = '%s'",
		mysqli_real_escape_string($mysqli, $id)
	));

	if (mysqli_errno($mysqli)) {
		$errors[] = mysqli_error($mysqli);
	}

	if ($res) {
		if (mysqli_num_rows($res) == 1) {
			if ($row = mysqli_fetch_assoc($res)) {
				$inputs["id"] = $row["id"];
				$inputs["nama"] = $row["nama"];
				$inputs["jenkel"] = $row["jenkel"];
				$inputs["lahir"] = $row["lahir"];
				$inputs["alamat"] = $row["alamat"];
			}
		}
	}

	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;

	header("Location: /pasien/form", true, 303);
	exit;
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
		} else {
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
				if (mysqli_num_rows($res) == 1) {
					$row = mysqli_fetch_assoc($res);
					$seq_no = (int) $row["seq_no"];
					$seq_no++;
					$seq_no_str = sprintf("%04d", $seq_no);
					$inputs["id"] = $prefix . $seq_no_str; // PS2023120001
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
			$errors["jenkel"] = "Jenkel tidak boleh kosong";
		}
	} else {
		$errors["jenkel"] = "Jenkel harus dipilih";
	}

	if (isset($_POST["lahir"])) {
		$lahir = htmlentities(strip_tags(trim($_POST["lahir"])));
		if (strlen($lahir) > 0) {
			$inputs["lahir"] = date("Y-m-d", strtotime($lahir));
		} else {
			$errors["lahir"] = "Tanggal lahir tidak boleh kosong";
		}
	} else {
		$errors["lahir"] = "Tanggal lahir undefine";
	}

	if (isset($_POST["alamat"])) {
		$alamat = htmlentities(strip_tags(trim($_POST["alamat"])));
		if (strlen($alamat) > 0) {
			$inputs["alamat"] = $alamat;
		} else {
			$errors["alamat"] = "Alamat tidak boleh kosong";
		}
	} else {
		$errors["alamat"] = "Alamat undefined";
	}

	if (count($errors) == 0) {
		if ($is_new) {
			if (mysqli_autocommit($mysqli, false)) {
				$ret = mysqli_query($mysqli, sprintf(
					"insert into pasien (id, namax, jenkel, lahir, alamat, ins_id, ins_dtm, upd_id, upd_dtm) 
					values ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
					mysqli_real_escape_string($mysqli, $inputs["id"]),
					mysqli_real_escape_string($mysqli, $inputs["nama"]),
					mysqli_real_escape_string($mysqli, $inputs["jenkel"]),
					mysqli_real_escape_string($mysqli, $inputs["lahir"]),
					mysqli_real_escape_string($mysqli, $inputs["alamat"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s"))
				));

				if (mysqli_errno($mysqli)) {
					$errors[] = mysqli_error($mysqli);
				}

				if ($ret) {
					mysqli_commit($mysqli);
					redirect_to("/pasien");
				} else {
					mysqli_rollback($mysqli);
				}
			}
		} else {
			if (mysqli_autocommit($mysqli, false)) {
				$ret = mysqli_query($mysqli, sprintf(
					"update pasien set nama = '%s', jenkel = '%s', lahir='%s', alamat = '%s', upd_id = '%s', upd_dtm = '%s' where id = '%s'",
					mysqli_real_escape_string($mysqli, $inputs["nama"]),
					mysqli_real_escape_string($mysqli, $inputs["jenkel"]),
					mysqli_real_escape_string($mysqli, $inputs["lahir"]),
					mysqli_real_escape_string($mysqli, $inputs["alamat"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $inputs["id"])
				));

				if (mysqli_errno($mysqli)) {
					$errors[] = mysqli_error($mysqli);
				}

				if ($ret) {
					mysqli_commit($mysqli);
					redirect_to("/pasien");
				} else {
					mysqli_rollback($mysqli);
				}
			}
		}
	} else {
		if ($is_new) {
			if (isset($inputs["id"])) {
				unset($inputs["id"]);
			}
		}
	}

	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;

	header("Location: /pasien/form", true, 303);
	exit;
});

get("/pendaftaran", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}

	$inputs = [];
	$errors = [];

	return [
		"view" => "pendaftaran",
		"title" => "Pendaftaran",
		"menu" => "pendaftaran",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

post("/popup/pasien/pendaftaran", function () {
	global $mysqli;

	header("Content-type: application/json");

	$errors = [];
	$inputs = json_decode(file_get_contents("php://input"), true);

	if (isset($inputs["nama"])) {
		$nama = htmlentities(strip_tags(trim($inputs["nama"])));
		if (strlen($nama >= 2)) {
			$inputs["nama"] = $nama;
		} else {
			$errors["nama"] = "Nama harus sama dengan 2 atau lebih karakter";
		}
	}

	$pasiens = [];

	if (count($errors) == 0) {
		$res = mysqli_query($mysqli, sprintf(
			"select * from pasien where nama like '%s'",
			mysqli_real_escape_string($mysqli, "%" . $inputs["nama"] . "%")
		));

		if (mysqli_errno($mysqli)) {
			echo json_encode($pasiens);
			exit;
		}

		if ($res) {
			if (mysqli_num_rows($res)) {
				while ($row = mysqli_fetch_assoc($res)) {
					$pasiens[] = [
						"id" => $row["id"],
						"nama" => $row["nama"],
						"jenkel" => $row["jenkel"],
						"lahir" => $row["lahir"],
						"alamat" => $row["alamat"]
					];
				}
			}
		}
	}

	echo json_encode($pasiens);
	exit;
});

render(dispatch());
