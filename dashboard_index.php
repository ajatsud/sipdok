<?php

if (!defined("APP_VER")) {
    exit("No direct script access allowed");
}

?>

<div class="row">
    <div class="twelve columns">
        <div class="breadcrumb">
            <h2>Dashboard</h2>
            <p>Selamat datang <strong><?= $_SESSION["username"] ?></strong></p>
        </div>
    </div>
</div>