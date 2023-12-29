<?php

date_default_timezone_set("Asia/Jakarta");

define("APP_VER", 1.0);

include __DIR__ . DIRECTORY_SEPARATOR . "sys" . DIRECTORY_SEPARATOR . "database.php";
include __DIR__ . DIRECTORY_SEPARATOR . "sys" . DIRECTORY_SEPARATOR . "router.php";
include __DIR__ . DIRECTORY_SEPARATOR . "sys" . DIRECTORY_SEPARATOR . "bootstrap.php";
include __DIR__ . DIRECTORY_SEPARATOR . "sys" . DIRECTORY_SEPARATOR . "functions.php";

response(request());
