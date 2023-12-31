<?php

date_default_timezone_set("Asia/Jakarta");

define("APP_VER", 1.0);
define("APP_DIR", __DIR__);
define("APP_SEP", DIRECTORY_SEPARATOR);

include APP_DIR . APP_SEP . "sys" . APP_SEP . "database.php";
include APP_DIR . APP_SEP . "sys" . APP_SEP . "router.php";
include APP_DIR . APP_SEP . "sys" . APP_SEP . "bootstrap.php";
include APP_DIR . APP_SEP . "lib" . APP_SEP . "functions.php";

response(request());
