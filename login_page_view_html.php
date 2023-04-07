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

    <div class="container">
        <div class="row">
            <div class="six offset-by-three columns" style="background-color: transparent; margin-top: 30px;">
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
                    <div><img src="/public/images/sipdok.png" alt="Sipdok" style="width: 100px; height: 100px;"></div>
                    <div><h1>Sipdok</h1></div>
                </div>
                <form method="post" action="/login" autocomplete="off">
                    <label>Username</label>
                    <input class="u-full-width" type="text" name="login[username]" id="username">
                    <label>Password</label>
                    <input class="u-full-width" type="password" name="login[password]" id="password">
                    <label>&nbsp;</label>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>

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
