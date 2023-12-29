<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

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
					"insert into pasien (id, nama, jenkel, lahir, alamat, ins_id, ins_dtm, upd_id, upd_dtm) 
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
	redirect_with("/pasien/form");
});