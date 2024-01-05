<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

get("/pasien", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}

	global $mysqli;
	$errors = [];

	$res = mysqli_query($mysqli, "select * from pasien order by nama asc");
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
		"view" => "pasien_index",
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

	redirect_with("/pasien/form");
});

post("/pasien/save", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}

	global $mysqli;
	$inputs = [];
	$errors = [];
	$is_new = false;


	$id = isset($_POST["id"]) ? htmlentities(strip_tags(trim($_POST["id"]))) : "";
	if (strlen($id) > 0) {
		$inputs["id"] = $id;
	} else {
		$is_new = true;
		$inputs["id"] = get_auto_id("pasien", "PS");
	}

	$nama = isset($_POST["nama"]) ? htmlentities(strip_tags(trim($_POST["nama"]))) : "";
	if (strlen($nama) > 0) {
		$inputs["nama"] = $nama;
	} else {
		$errors["nama"] = "Nama tidak boleh kosong";
	}

	$jenkel = isset($_POST["jenkel"]) ? htmlentities(strip_tags(trim($_POST["jenkel"]))) : "";
	if (strlen($jenkel) > 0) {
		$inputs["jenkel"] = $jenkel;
	} else {
		$errors["jenkel"] = "Jenkel harus dipilih salah satu";
	}

	$lahir = isset($_POST["lahir"]) ? htmlentities(strip_tags(trim($_POST["lahir"]))) : "";
	if (strlen($lahir) > 0) {
		$inputs["lahir"] = date("Y-m-d", strtotime($lahir));
	} else {
		$errors["lahir"] = "Tanggal lahir tidak boleh kosong";
	}

	$alamat = isset($_POST["alamat"]) ? htmlentities(strip_tags(trim($_POST["alamat"]))) : "";
	if (strlen($alamat) > 0) {
		$inputs["alamat"] = $alamat;
	} else {
		$errors["alamat"] = "Alamat tidak boleh kosong";
	}

	if (count($errors) == 0) {
		if (mysqli_autocommit($mysqli, false)) {
			if ($is_new) {
				$ret = mysqli_query($mysqli, sprintf(
					"insert into pasien (
						id, nama, jenkel, lahir, alamat,
						ins_id, ins_dtm, upd_id, upd_dtm
					) values (
						'%s', '%s', '%s', '%s', '%s',
						'%s', '%s', '%s', '%s'
					)",
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
			} else {
				$ret = mysqli_query($mysqli, sprintf(
					"update pasien 
						set nama = '%s', jenkel = '%s', lahir='%s', alamat = '%s',
							upd_id = '%s', upd_dtm = '%s'
				 	where id = '%s'",
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
			}

			if ($ret) {
				mysqli_commit($mysqli);
				flash("success", "Berhasil", "Data pasien berhasil disimpan");
			} else {
				mysqli_rollback($mysqli);
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

	redirect_with("/pasien/form");
});
