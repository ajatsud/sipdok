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
				<h3>Dashboard</h3>
				<p>Selamat datang <?= $_SESSION["username"] ?></p>
				<p>&larr; <a href="/user/logout">Logout</a></p>
            </div>
        </div>
    </div>
</body>
</html>
