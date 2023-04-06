<!-- coding pass -->
<div class="container">
	<h1>Pasien</h1>
	<form action="/pasien/search" method="post" autocomplete="off">
		<div class="row">
			<div class="three columns">
				<label>Keyword</label>
				<input class="u-full-width" type="text" name="pasien[keyword]" placeholder="Nama atau alamat" value="<?php echo isset($keyword) ? $keyword : ''; ?>">
			</div>
			<div class="three columns">
				<label>Jenkel</label>
				<select class="u-full-width" name="pasien[jenkel]">
					<option value="%" <?php echo isset($jenkel) && $jenkel == '' || $jenkel == '%' ? 'selected' : ''; ?>>Semua</option>
					<option value="L" <?php echo isset($jenkel) && $jenkel == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
					<option value="P" <?php echo isset($jenkel) && $jenkel == 'P' ? 'selected' : ''; ?>>Perempuan</option>
				</select>
			</div>
			<div class="three columns">
				<label>Periode</label>
				<select name="pasien[period]" class="u-full-width">
					<option value="<?php echo date('d'); ?>" <?php echo ($period == date('d')) ? 'selected' : ''; ?>>Hari ini</option>
					<option value="<?php echo date('md'); ?>" <?php echo ($period == date('md')) ? 'selected' : ''; ?>>Bulan ini</option>
					<option value="<?php echo date('ymd'); ?>" <?php echo ($period == date('ymd')) ? 'selected' : ''; ?>>Tahun ini</option>
					<option value="%" <?php echo ($period == '%') ? 'selected' : ''; ?>>Semua</option>
				</select>
			</div>
			<div class="three columns">
				<label>&nbsp;</label>
				<input type="submit" value="Submit" class="button-primary">
			</div>
		</div>
	</form>
	
	<p><a class="button" href="/pasien/add">Tambah</a></p>
	
	<div style="overflow-x: auto;">
		<table class="u-full-width">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Jenkel</th>
					<th>Umur</th>
					<th>Alamat</th>
					<th>#</th>
					<th>#</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; ?>
				<?php foreach($records as $record){ ?>
				<tr>
					<td><?php echo ++$i; ?></td>
					<td><?php echo $record['nama']; ?></td>
					<td>
					    
					    <?php
					    
					    if($record['jenkel'] == 'L'){
					        echo 'Laki-Laki';
					    }else{
					        echo 'Perempuan';
					    }
					    
					    ?>
						
					</td>
					<td style="text-align: right;"><?php echo $record['umur']; ?></td>
					<td><?php echo $record['alamat']; ?></td>
					<td>
						<form action="/pasien/edit" method="post">
							<input type="hidden" name="pasien[id]" value="<?php echo $record['id']; ?>">
							<input type="submit" value="Edit" class="button-warning">
						</form>
					</td>
					<td>
						<form action="/pasien/delete" method="post" onsubmit="return confirm('Apakah anda yakin akan menghapus ?');">
							<input type="hidden" name="pasien[id]" value="<?php echo $record['id']; ?>">
							<input type="submit" value="Hapus" class="button-danger">
						</form>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
