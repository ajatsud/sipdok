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
			<div class="six offset-by-three columns header">
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
			<div class="six offset-by-three columns">
				<div class="box">
					<h2>Login</h2>

					<?php if (count($errors) > 0) : ?>
						<?php foreach ($errors as $key => $error) : ?>
							<?php if (is_string($key)) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<p style="color: red;"><?= $error ?></p>
						<?php endforeach; ?>
					<?php endif; ?>

					<form method="post" action="/user/login/auth">
						<label>Username</label>
						<input type="text" name="username" value="<?= $inputs["username"] ?? "" ?>" placeholder="Username" class="u-full-width">
						<?php if (isset($errors["username"])) : ?>
							<p style="color: red;"><?= $errors["username"] ?? "" ?></p>
						<?php endif; ?>
						<label>Password</label>
						<input type="password" name="password" value="<?= $inputs["password"] ?? "" ?>" placeholder="Password" class="u-full-width">
						<?php if (isset($errors["password"])) : ?>
							<p style="color: red;"><?= $errors["password"] ?? "" ?></p>
						<?php endif; ?>
						<br>
						<input type="submit" class="button-primary">
					</form>
				</div>
			</div>
		</div>
	</div>

</body>

</html>