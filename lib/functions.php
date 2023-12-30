<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
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
	return date("d F Y", strtotime($date));
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
