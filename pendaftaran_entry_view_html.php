<!-- coding pass -->
<script>

function select(self)
{
	let id_pasien = self.getAttribute('data-id');
	let nama = self.getAttribute('data-nama');
	let jenkel = self.getAttribute('data-jenkel');
	let umur = self.getAttribute('data-umur');
	let alamat = self.getAttribute('data-alamat');

	document.getElementById('id_pasien').value = id_pasien;
	document.getElementById('nama').value = nama;
	document.getElementById('jenkel').value = jenkel;
	document.getElementById('umur').value = umur;
	document.getElementById('alamat').value = alamat;
	document.getElementById('pasien_popup').innerHTML = '';
}

function onKeyUp()
{
	let nama = document.getElementById('nama');
	if(nama.value.length >= 2){
		document.getElementById('pasien_popup').innerHTML = 'Loading ...';
		let ajax = new XMLHttpRequest();
		ajax.open('POST', '/pasien/popup', true);
		ajax.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				let data = JSON.parse(this.responseText);
				let html = '';
				for (let a = 0; a < data.length; a++) {
					html += "<p onclick='select(this);' data-id='" + data[a].id + "' data-nama='" + data[a].nama + "' data-jenkel='" + data[a].jenkel + "' data-umur='" + data[a].umur + "' data-alamat='" + data[a].alamat + "'><span class='nama'>" + data[a].nama + "</span> <span class='alamat'>" + data[a].alamat + "</span> <span class='umur'>" + data[a].umur + " Tahun</span></p>";
				}
				if(nama.value.length > 0){
					document.getElementById('pasien_popup').innerHTML = html;
				}else{
					document.getElementById('pasien_popup').innerHTML = '';
				}
			}
		};

		ajax.send(JSON.stringify({
			nama: nama.value
		}));
	}
}
	
</script>


<div class="container">
    <h1>Pendaftaran</h1>
    <form action="/pendaftaran/save" method="post" autocomplete="off">
		<div class="row">
			<div class="four columns">
				<label>ID</label>
				<input class="u-full-width" type="text" name="pendaftaran[id]" id="id" value="<?php echo isset($record['id']) ? $record['id'] : ''; ?>" readonly>
			</div>
			<div class="four columns">
				<label>ID Pasien</label>
				<input class="u-full-width" type="text" name="pendaftaran[id_pasien]" id="id_pasien" value="<?php echo isset($record['id_pasien']) ? $record['id_pasien'] : ''; ?>" readonly>
			</div>
		</div>
		
		<div class="row">
			<div class="four columns">
				<label>Nama</label>
				<input class="u-full-width" type="text" name="pendaftaran[nama]" id="nama" onkeyup="onKeyUp();" value="<?php echo isset($record['nama']) ? $record['nama'] : ''; ?>">
			</div>
			<div class="four columns">
				<div class="row">
					<div class="six columns">
						<label>Jenkel</label>
						<select class="u-full-width" name="pendaftaran[jenkel]" id="jenkel">
							<option value="L" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
							<option value="P" <?php echo isset($record['jenkel']) && $record['jenkel'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
						</select>
					</div>
					<div class="six columns">
						<label>Umur</label>
						<input class="u-full-width" type="number" name="pendaftaran[umur]" id="umur" step="0.1" min="0.0" value="<?php echo isset($record['umur']) ? $record['umur'] : 0.0; ?>">
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="row">
			<div class="eight columns">
				<div class="u-full-width popup" id="pasien_popup"></div>
			</div>
		</div>
		
		<div class="row">
			<div class="four columns">
				<label>Alamat</label>
                <textarea class="u-full-width" name="pendaftaran[alamat]" id="alamat"><?php echo isset($record['alamat']) ? $record['alamat'] : ''; ?></textarea>
			</div>
			<div class="four columns">
				<label>Keluhan</label>
                <textarea class="u-full-width" name="pendaftaran[keluhan]" id="keluhan"><?php echo isset($record['keluhan']) ? $record['keluhan'] : ''; ?></textarea>
			</div>
		</div>
		<input type="submit" value="Submit" class="button-primary">
		<a class="button" href="/pendaftaran">Data Pendaftaran</a>
    </form>
</div>
