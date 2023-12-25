<?php

if (!isset($view)) {
    exit("No direct script access allowed");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'style.css') ?>" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="twelve columns header">
                <div>
                    <h1 class="logo">
                        <span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
                    </h1>
                    <p class="logo-des"><small>Sistem Informasi Praktik Dokter</small></p>
                </div>
                <div class="nav">
                    <a href="/" <?= ($menu == "dashboard") ? 'class="active"' : ""  ?>>Dashboard</a>
                    <a href="/pasien" <?= ($menu == "pasien") ? 'class="active"' : ""  ?>>Pasien</a>
                    <a href="/pendaftaran" <?= ($menu == "pendaftaran") ? 'class="active"' : ""  ?>>Pendaftaran</a>
                    <a href="/antrian" <?= ($menu == "antrian") ? 'class="active"' : ""  ?>>Antrian</a>
                    <a href="/pemeriksaan" <?= ($menu == "pemeriksaan") ? 'class="active"' : ""  ?>>Pemeriksaan</a>
                    <a href="/kasir" <?= ($menu == "kasir") ? 'class="active"' : ""  ?>>Kasir</a>
                    <a href="/laporan" <?= ($menu == "laporan") ? 'class="active"' : ""  ?>>Laporan</a>
                    <a href="/user/logout" <?= ($menu == "user") ? 'class="active"' : ""  ?>>Logout</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                <h2>Dashboard</h2>
                <p>Selamat datang <strong><?= $_SESSION["username"] ?></strong></p>
            </div>
        </div>
    </div>

</body>

</html>