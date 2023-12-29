<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

get("/pendaftaran", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}
	$inputs = [];
	$errors = [];
	return [
		"view" => "pendaftaran_form",
		"title" => "Pendaftaran",
		"menu" => "pendaftaran",
		"inputs" => $inputs,
		"errors" => $errors
	];
});
