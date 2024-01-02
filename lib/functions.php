<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

function d($var) {
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

function dd($var) {
	d($var);
	exit;
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

function redirect_with($path) {
	header("Location: " . $path, true, 303);
	exit;
}

function jenkel_display_format($jenkel) {
	if ($jenkel == "l") {
		return "Laki-Laki";
	}
	return "Perempuan";
}

function tanggal_display_format($date) { // "2023-12-30"
	return date("d M Y", strtotime($date));
}

function tanggal_display_format_year($date) {
	return date("Y", strtotime($date));
}

function display_year_lahir($date) {
	return date("Y", strtotime($date));
}

function flash($icon = "", $title = "", $message = "") {
	if ($icon !== "" && $title !== "" && $message !== "") {
		if (isset($_SESSION["flash"])) {
			unset($_SESSION["flash"]);
		}
		$_SESSION["flash"] = [
			"icon" => $icon,
			"title" => $title,
			"message" => $message
		];
	} else {
		if (!isset($_SESSION["flash"])) {
			return [];
		}
		$flash = $_SESSION["flash"];
		unset($_SESSION["flash"]);
		return $flash;
	}
}

function get_auto_id($table_name, $prefix_2_char) {
	global $mysqli;

	$prefix = $prefix_2_char . date("Ym");
	$res = mysqli_query($mysqli, sprintf(
		"select ifnull(max(substring(id, 9, 4)), 0) as seq_no from %s where substring(id, 1, 8) = '%s'",
		mysqli_real_escape_string($mysqli, $table_name),
		mysqli_real_escape_string($mysqli, $prefix)
	));

	if (mysqli_errno($mysqli)) {
		flash("error", "Failed to generate ID", mysqli_error($mysqli));
		return "";
	}

	if ($res) {
		if (mysqli_num_rows($res) == 1) {
			$row = mysqli_fetch_assoc($res);
			$seq_no = (int) $row["seq_no"];
			$seq_no++;
			$seq_no_str = sprintf("%04d", $seq_no);
			$prefix = $prefix . $seq_no_str; // XX2023120001
			return $prefix;
		}
	}

	return "";
}
