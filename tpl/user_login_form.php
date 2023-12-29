<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

?>

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
				<br><input type="submit" class="button-primary">
			</form>
		</div>
	</div>
</div>