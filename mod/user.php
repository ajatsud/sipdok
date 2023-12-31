<?php

if (!defined("APP_VER"))
{
	exit("No direct script access allowed");
}

get("/user/login", function ()
{
	if (is_login())
	{
		redirect_to("/dashboard");
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
		"view" => "user_login_form",
		"title" => "Login",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

post("/user/login/auth", function ()
{
	if (is_login())
	{
		redirect_to("/dashboard");
	}

	global $mysqli;

	$inputs = [];
	$errors = [];


	$username = isset($_POST["username"]) ? htmlentities(strip_tags(trim($_POST["username"]))) : "";

	if (strlen($username) > 0)
	{
		$inputs["username"] = $username;
	}
	else
	{
		$errors["username"] = "Username tidak boleh kosong";
	}


	$password = isset($_POST["password"]) ? htmlentities(strip_tags(trim($_POST["password"]))) : "";

	if (strlen($password) > 0)
	{
		$inputs["password"] = $password;
	}
	else
	{
		$errors["password"] = "Password tidak boleh kosong";
	}


	if (count($errors) == 0)
	{
		$res = mysqli_query($mysqli, sprintf(
			"select * from user where username = '%s'",
			mysqli_real_escape_string($mysqli, $inputs["username"])
		));

		if (mysqli_errno($mysqli))
		{
			$errors[] = mysqli_error($mysqli);
		}

		if ($res)
		{
			if (mysqli_num_rows($res) == 1)
			{
				$user = mysqli_fetch_assoc($res);
				$password_hash = $user["password"];

				if (password_verify($inputs["password"], $password_hash))
				{
					$_SESSION["username"] = $inputs["username"];

					flash("success", "Login Berhasil", "Selamat datang " . $inputs["username"]);

					redirect_to("/dashboard");
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

	redirect_with("/user/login");
});

get("/user/logout", function ()
{
	if (isset($_SESSION["username"]))
	{
		unset($_SESSION["username"]);
	}

	$_SESSION = [];

	session_destroy();

	redirect_to("/user/login");
});
