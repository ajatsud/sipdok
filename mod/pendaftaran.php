<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

get("/pendaftaran/form", function () {
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
		"view" => "pendaftaran_form",
		"title" => "Pendaftaran",
		"menu" => "pendaftaran",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

post("/pendaftaran/save", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}
	global $mysqli;
	$inputs = [];
	$errors = [];
	$is_new_pendaftaran = false;
	$is_new_pasien = false;
	if (isset($_POST["id"])) {
		$id = htmlentities(strip_tags(trim($_POST["id"])));
		if (strlen($id) > 0) {
			$inputs["id"] = $id;
		} else {
			$is_new_pendaftaran = true;
			$prefix = "RG" . date("Ym");
			$res = mysqli_query($mysqli, sprintf(
				"select ifnull(max(substring(id, 9, 4)), 0) as seq_no from pendaftaran where substring(id, 1, 8) = '%s'",
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
					$inputs["id"] = $prefix . $seq_no_str; // RG2023120001
				}
			}
		}
	} else {
		$errors["id"] = "ID Undefine";
	}
	if (isset($_POST["pasien_id"])) {
		$pasien_id = htmlentities(strip_tags(trim($_POST["pasien_id"])));
		if (strlen($pasien_id) > 0) {
			$inputs["pasien_id"] = $pasien_id;
		} else {
			$is_new_pasien = true;
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
					$inputs["pasien_id"] = $prefix . $seq_no_str; // PS2023120001
				}
			}
		}
	} else {
		$errors["pasien_id"] = "Pasien ID Undefine";
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
	if (isset($_POST["keluhan"])) {
		$keluhan = htmlentities(strip_tags(trim($_POST["keluhan"])));
		if (strlen($keluhan) > 0) {
			$inputs["keluhan"] = $keluhan;
		} else {
			$errors["keluhan"] = "Keluhan tidak boleh kosong";
		}
	} else {
		$errors["keluhan"] = "Keluhan undefined";
	}
	if (count($errors) == 0) {
		if (mysqli_autocommit($mysqli, false)) {
			// tabel pasien
			// id, nama, jenkel, lahir, alamat, ins_id, ins_dtm, upd_id, upd_dtm
			// tabel pendaftaran
			// id, pasien_id, keluhan, ins_id, ins_dtm, upd_id, upd_dtm
		}
	} else {
		if ($is_new_pendaftaran) {
			if (isset($inputs["id"])) {
				unset($inputs["id"]);
			}
		}
		if ($is_new_pasien) {
			if (isset($inputs["pasien_id"])) {
				unset($inputs["pasien_id"]);
			}
		}
	}
	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;
	redirect_with("/pendaftaran/form");
});
