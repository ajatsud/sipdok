<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasien List</title>
</head>

<body>
    <h1>Pasien List</h1>
    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Nama</td>
                <td>Jenkel</td>
                <td>Umur</td>
                <td>Alamat</td>
                <td>Insert Date</td>
                <td>Update Date</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pasiens as $pasien) : ?>
                <tr>
                    <td><?php echo $pasien['id']; ?></td>
                    <td><?php echo $pasien['nama']; ?></td>
                    <td><?php echo $pasien['jenkel']; ?></td>
                    <td><?php echo $pasien['umur']; ?></td>
                    <td><?php echo $pasien['alamat']; ?></td>
                    <td><?php echo $pasien['insert_dt']; ?></td>
                    <td><?php echo $pasien['update_dt']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>