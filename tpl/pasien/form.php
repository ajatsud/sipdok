<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

?>

<div class="row">
	<div class="twelve columns">
		<div class="breadcrumb">
			<h2>Pasien Entri</h2>
			<p><a href="/pasien" class="button">Pasien List</a></p>
		</div>
	</div>
</div>
<div class="box">
	<form method="post" action="/pasien/save">
		<div class="row">
			<div class="six-column">
				<?php if (count($errors) > 0) : ?>
					<?php foreach ($errors as $key => $error) : ?>
						<?php if (is_string($key)) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<p style="color: red;"><?= $error ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label>ID</label>
				<input type="text" name="id" value="<?= $inputs["id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
			</div>
			<div class="six columns">
				<label>Nama</label>
				<input type="text" name="nama" value="<?= $inputs["nama"] ?? "" ?>" placeholder="Nama" class="u-full-width">
				<?php if (isset($errors["nama"])) : ?>
					<p style="color: red;"><?= $errors["nama"] ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<label>Jenkel</label>
				<div style="display: flex; flex-direction: row; gap: 20px; justify-content: flex-start;">
					<div style="display: flex; flex-direction: row;">
						<div>Laki-Laki</div>
						<div><input type="radio" name="jenkel" value="l" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "l") ? "checked" : "" ?> class="u-full-width"></div>
					</div>
					<div style="display: flex; flex-direction: row;">
						<div>Perempuan</div>
						<div><input type="radio" name="jenkel" value="p" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "p") ? "checked" : "" ?> class="u-full-width"></div>
					</div>
				</div>
				<?php if (isset($errors["jenkel"])) : ?>
					<p style="color: red;"><?= $errors["jenkel"] ?></p>
				<?php endif; ?>
			</div>
			<div class="six columns">
				<label>Lahir</label>
				<input type="date" name="lahir" value="<?= (isset($inputs["lahir"])) ? date("Y-m-d", strtotime($inputs["lahir"])) : date("Y-m-d") ?>" class="u-full-width">
			</div>
		</div>
		<div class="row">
			<div class="twelve columns">
				<label>Alamat</label>
				<textarea name="alamat" class="u-full-width"><?= $inputs["alamat"] ?? "" ?></textarea>
				<?php if (isset($errors["alamat"])) : ?>
					<p style="color: red;"><?= $errors["alamat"] ?></p>
				<?php endif; ?>
			</div>
		</div>
		<input type="submit" class="button-primary">
	</form>
</div>