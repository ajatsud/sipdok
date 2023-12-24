<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . 'style.css') ?>" rel="stylesheet">
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="four offset-by-four columns">
				<h3>Login</h3>
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
					<input type="submit">
				</form>
			</div>
		</div>
	</div>

</body>

</html>