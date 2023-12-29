<?php

if (!defined("APP_VER")) {
	exit("No direct script access allowed");
}

?>

<div class="row">
	<div class="twelve columns">
		<div class="breadcrumb">
			<h2>Pasien List</h2>
			<p><a href="/pasien/form" class="button">Pasien Entri</a></p>
		</div>
		<?php if (count($errors) > 0) : ?>
			<?php foreach ($errors as $key => $error) : ?>
				<?php if (is_string($key)) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<p style="color: red;"><?= $error ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (count($pasiens) > 0) : ?>
			<div class="table-container">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nama</th>
							<th>Jenkel</th>
							<th>Lahir</th>
							<th>Alamat</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pasiens as $pasien) : ?>
							<tr>
								<td><?= $pasien["id"] ?></td>
								<td><?= $pasien["nama"] ?></td>
								<td><?= jenkel_display_format($pasien["jenkel"]) ?></td>
								<td><?= tanggal_display_format($pasien["lahir"]) ?></td>
								<td><?= $pasien["alamat"] ?></td>
								<td><a href="/pasien/edit/<?= $pasien["id"] ?>">Edit</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>