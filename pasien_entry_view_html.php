<!-- coding pass -->
<div class="container">
	<h1>Pasien</h1>
	<form action="/pasien/save" method="post">
		<div class="row">
			<div class="three columns">
				<label>ID</label>
				<input class="u-full-width" type="text" name="pasien[id]" value="<?php echo isset($record['id']) ? $record['id'] : ''; ?>" readonly>
				<label>Nama</label>
				<input class="u-full-width" type="text" name="pasien[nama]" value="<?php echo isset($record['nama']) ? $record['nama'] : ''; ?>">
			</div>
			<div class="three columns">
				<label>Jenkel</label>
				<select class="u-full-width" name="pasien[jenkel]">
					<option value="L" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
					<option value="P" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
				</select>
				<label>Umur</label>
				<input class="u-full-width" type="number" name="pasien[umur]" step="0.1" min="0.0" value="<?php echo isset($record['umur']) ? $record['umur'] : ''; ?>">
			</div>
		</div>		
		<div class="row">
			<div class="six columns">
				<label>Alamat</label>
				<textarea class="u-full-width" name="pasien[alamat]"><?php echo isset($record['alamat']) ? $record['alamat'] : ''; ?></textarea>
			</div>
		</div>
		<input type="submit" value="Submit" class="button-primary">
		<a class="button" href="/pasien">Data Pasien</a>
	</form>
</div>
