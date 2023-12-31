<?php

session_start();

date_default_timezone_set("Asia/Jakarta");

define("VER", 1.0);
define("DIR", __DIR__);
define("SEP", DIRECTORY_SEPARATOR);

include DIR . SEP . "sys" . SEP . "database.php";
include DIR . SEP . "sys" . SEP . "router.php";
include DIR . SEP . "sys" . SEP . "bootstrap.php";
include DIR . SEP . "lib" . SEP . "functions.php";

response(request());
