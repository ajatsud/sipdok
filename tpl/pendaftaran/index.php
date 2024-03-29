<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

?>

<div class="row">
	<div class="twelve columns">
		<div class="breadcrumb">
			<h2>Pendaftaran List</h2>
			<p><a href="/pendaftaran/form" class="button">Pendaftaran Entri</a></p>
		</div>
		<?php if (count($errors) > 0) : ?>
			<?php foreach ($errors as $key => $error) : ?>
				<?php if (is_string($key)) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<p style="color: red;"><?= $error ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (count($pendaftarans) > 0) : ?>
			<div class="table-container">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>No</th>
							<th>ID</th>
							<th>Nama</th>
							<th>Lahir</th>
							<th>Alamat</th>
							<th>Keluhan</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php $row_no = 1; ?>
						<?php foreach ($pendaftarans as $pendaftaran) : ?>
							<tr>
								<td><?= $row_no ?></td>
								<td><?= $pendaftaran["id"] ?></td>
								<td><?= $pendaftaran["nama"] ?> <small>(<?= $pendaftaran["jenkel"] ?>)</small></td>
								<td><?= display_year_lahir($pendaftaran["lahir"]) ?></td>
								<td><?= $pendaftaran["alamat"] ?></td>
								<td><?= $pendaftaran["keluhan"] ?></td>
								<td class="td-button-container">
									<a class="button button-success" href="/rekmed/pendaftaran/<?= $pendaftaran["id"] ?>">Periksa</a>
									<a class="button button-warning" href="/pendaftaran/edit/<?= $pendaftaran["id"] ?>">Edit</a>
									<a class="button button-danger" href="/pendaftaran/edit/<?= $pendaftaran["id"] ?>">Hapus</a>
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