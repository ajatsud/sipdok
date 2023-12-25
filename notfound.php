<?php

if (!isset($view))
{
	exit("No direct script access allowed");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title; ?></title>
	<link href="/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'style.css') ?>" rel="stylesheet">
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="twelve columns header">
				<div>
					<h1 class="logo">
						<span class="logo-sip">Sip</span><span class="logo-dok">dok</span>
						<img class="logo-img" src="/logo.png" alt="Sipdok">
					</h1>
					<p class="logo-des"><small>Sistem Informasi Praktik Dokter</small></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<h2>404</h2>
				<p>Halaman yang anda cari tidak ditemukan. &larr;<a href="/">Kembali</a></p>
			</div>
		</div>
	</div>

</body>

</html>