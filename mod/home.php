<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

get("/", function () {
	if (is_login()) {
		redirect_to("/dashboard");
	} else {
		redirect_to("/user/login");
	}
});
