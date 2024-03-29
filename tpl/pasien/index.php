<?php

if (!defined("VER")) {
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
							<th>No</th>
							<th>ID</th>
							<th>Nama</th>
							<th>Lahir</th>
							<th>Alamat</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $row_no = 1; ?>
						<?php foreach ($pasiens as $pasien) : ?>
							<tr>
								<td><?= $row_no ?></td>
								<td><?= $pasien["id"] ?></td>
								<td><?= $pasien["nama"] ?> (<small><?= $pasien["jenkel"] ?></small>)</td>
								<td><?= display_year_lahir($pasien["lahir"]) ?></td>
								<td><?= $pasien["alamat"] ?></td>
								<td class="td-button-container">
									<a class="button button-warning" href="/pasien/edit/<?= $pasien["id"] ?>">Edit</a>
									<a class="button button-danger" href="/pasien/edit/<?= $pasien["id"] ?>">Hapus</a>
								</td>
							</tr>
							<?php $row_no++; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>