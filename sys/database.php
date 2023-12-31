<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

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
