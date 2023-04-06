<!-- coding pass -->
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	
	<title>Sipdok</title>
	
	<meta name="description" content="Sistem Informasi Praktik Dokter | Aplikasi Praktik Mandiri Dokter Gratis">
	<meta name="author" content="sipdok">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="/public/css/fonts.css?version=<?php echo filemtime(__DIR__ . '/public/css/fonts.css'); ?>">
	<link rel="stylesheet" href="/public/css/normalize.css?version=<?php echo filemtime(__DIR__ . '/public/css/normalize.css'); ?>">
	<link rel="stylesheet" href="/public/css/skeleton.css?version=<?php echo filemtime(__DIR__ . '/public/css/skeleton.css'); ?>">
	<link rel="stylesheet" href="/public/sweetalert/sweetalert2.custom.min.css?version=<?php echo filemtime(__DIR__ . '/public/sweetalert/sweetalert2.custom.min.css'); ?>">

	<link rel="icon" type="image/png" href="/public/images/sipdok.png">

</head>
<body>

	<main>
		<aside>
			<img src="/public/images/sipdok.png" alt="Sipdok">
			<div>
			    <span>Informasi</span>
			    <a href="/dashboard" <?php echo $current_path_active == 'dashboard' ? 'class="active"' : ''; ?>>Dashboard</a>
			    <a href="/antrian" <?php echo $current_path_active == 'antrian' ? 'class="active"' : ''; ?>>Antrian</a>
			</div>
			<div>
			    <span>Administrasi</span>
			    <a href="/pasien" <?php echo $current_path_active == 'pasien' ? 'class="active"' : ''; ?>>Pasien</a>
                <a href="/pendaftaran" <?php echo $current_path_active == 'pendaftaran' ? 'class="active"' : ''; ?>>Pendaftaran</a>
			</div>
			<div>
			    <span>Dokter</span>
			    <a href="/periksa" <?php echo $current_path_active == 'periksa' ? 'class="active"' : ''; ?>>Periksa</a>
			</div>
		</aside>
		<section>
			<br>
			<?php if(file_exists($file)){ include $file; } ?>
		</section>
	</main>

	<?php if($messages || $errors){ ?>
		<script src="/public/js/jquery.min.js"></script>
		<script src="/public/sweetalert/sweetalert2.min.js"></script>
	<?php } ?>

	<?php if($messages){ ?>
		<script>
			$(document).ready(function() {
				let Toast = Swal.mixin({
					toast: true,
					position: 'top-right',
					showConfirmButton: false,
					timer: 9000
				});

				Toast.fire({
					icon: "success",
					title: "<?php echo implode('<br>', $messages); ?>"
				});
			});
		</script>
	<?php } ?>
    
	<?php if($errors){ ?>
		<script>
			$(document).ready(function() {
				let Toast = Swal.mixin({
					toast: true,
					position: 'top-right',
					showConfirmButton: false,
					timer: 9000
				});

				Toast.fire({
					icon: "error",
					title: "<?php echo implode('<br>', $errors); ?>"
				});
			});
		</script>
	<?php } ?>
    
</body>
</html>
