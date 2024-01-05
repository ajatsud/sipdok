<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

get("/dashboard", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}

	global $mysqli;

	$errors = [];



	$total_pasien = 0;

	$res = mysqli_query($mysqli, "select count(*) as total from pasien");

	if (mysqli_errno($mysqli)) {
		$errors[] = mysqli_error($mysqli);
	}

	if ($res) {
		$row = mysqli_fetch_assoc($res);
		$total_pasien = $row["total"];
	}



	$total_pendapatan = 0;

	$res = mysqli_query($mysqli, "select sum(biaya) as total from rekmed");

	if (mysqli_errno($mysqli)) {
		$errors[] = mysqli_error($mysqli);
	}

	if ($res) {
		$row = mysqli_fetch_assoc($res);
		$total_pendapatan = $row["total"];
	}


	return [
		"view" => "dashboard_index",
		"title" => "Dashboard",
		"menu" => "dashboard",
		"errors" => $errors,
		"total_pendapatan" => $total_pendapatan,
		"total_pasien" => $total_pasien
	];
});
