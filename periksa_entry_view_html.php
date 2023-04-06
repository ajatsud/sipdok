<!-- coding pass -->
<div class="container">
	<h1>Periksa</h1>
	<form method="post" action="/periksa/save">
		<div class="row">
			<div class="four columns">
				<div class="row">
					<div class="six columns">
						<label>ID</label>
						<input class="u-full-width" type="text" name="periksa[id]" id="id" value="<?php echo isset($record['id']) ? $record['id'] : ''; ?>" readonly>
					</div>
					<div class="six columns">
						<label>ID Pendaftaran</label>
						<input class="u-full-width" type="text" name="periksa[id_pendaftaran]" id="id_pendaftaran" value="<?php echo isset($record['id_pendaftaran']) ? $record['id_pendaftaran'] : ''; ?>" readonly>
					</div>
				</div>
				<label>Nama</label>
				<input class="u-full-width" type="text" name="periksa[nama]" id="nama" value="<?php echo isset($record['nama']) ? $record['nama'] : ''; ?>" readonly>
				<div class="row">
					<div class="six columns">
						<label>Jenkel</label>
						<select class="u-full-width" name="periksa[jenkel]" id="jenkel">
							<option value="L" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
							<option value="P" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
						</select>
					</div>
					<div class="six columns">
						<label>Umur</label>
						<input class="u-full-width" type="number" name="periksa[umur]" id="umur" value="<?php echo isset($record['umur']) ? $record['umur'] : '' ?>" step="0.1" min="0.0" readonly>
					</div>
				</div>
				<label>Alamat</label>
				<textarea readonly class="u-full-width" name="periksa[alamat]" id="alamat"><?php echo isset($record['alamat']) ? $record['alamat'] : ''; ?></textarea>
			</div>
			<div class="eight columns">
				<div class="row">
					<div class="six columns">
						<label>Anamnesa</label>
						<textarea class="u-full-width" name="periksa[anamnesa]" id="anamnesa"><?php if(isset($record['anamnesa']) && !empty($record['anamnesa'])){ echo $record['anamnesa']; }else{ if(isset($record['keluhan']) && !empty($record['keluhan'])){ echo $record['keluhan']; }else{ echo ''; } } ?></textarea>
					</div>
					<div class="six columns">
						<label>Pemeriksaan</label>
                		<textarea class="u-full-width" name="periksa[pemeriksaan]" id="pemeriksaan"><?php echo isset($record['pemeriksaan']) ? $record['pemeriksaan'] : ''; ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label>Diagnosa</label>
                		<textarea class="u-full-width" name="periksa[diagnosa]" id="diagnosa"><?php echo isset($record['diagnosa']) ? $record['diagnosa'] : ''; ?></textarea>		
					</div>
					<div class="six columns">
						<label>Terapi</label>
                		<textarea class="u-full-width" name="periksa[terapi]" id="terapi"><?php echo isset($record['terapi']) ? $record['terapi'] : ''; ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="six columns">
						<label>Biaya</label>
						<input class="u-full-width" type="number" name="periksa[biaya]" id="biaya" step="0.1" min="0.0" value="<?php echo isset($record['biaya']) ? $record['biaya'] : '0' ?>">
					</div>
					<div class="six columns">
						<label>&nbsp;</label>
						<input type="submit" value="<?php echo !isset($record['id_pendaftaran']) || empty($record['id_pendaftaran']) ? 'Antrian Berikutnya' : 'Submit' ?>" class="button-primary">
						<a class="button" href="/periksa/add">Reset</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
