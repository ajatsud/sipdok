<?php

date_default_timezone_set("Asia/Jakarta");

define("APP_VER", 1.0);

require_once __DIR__ . DIRECTORY_SEPARATOR . "database.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "router.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "functions.php";

response(request());
