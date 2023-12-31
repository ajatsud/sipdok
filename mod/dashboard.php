<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

get("/dashboard", function () {
	if (!is_login()) {
		redirect_to("/user/login");
	}

	return [
		"view" => "dashboard_index",
		"title" => "Dashboard",
		"menu" => "dashboard"
	];
});
