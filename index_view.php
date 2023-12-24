<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="/style.css?version=<?= filemtime(__DIR__ . DIRECTORY_SEPARATOR . "style.css") ?>" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
				<h1>Halo</h1>
				<p>Selamat datang di aplikasi Sipdok</p>
				<?php foreach ($errors as $error): ?>
					<p style="color: red;"><?= $error ?></p>	
				<?php endforeach; ?>
				<?php if (count($users) > 0): ?>
					<ul>
						<?php foreach ($users as $user): ?>
							<li><?= $user["username"] . ' : ' . $user["password"] ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
