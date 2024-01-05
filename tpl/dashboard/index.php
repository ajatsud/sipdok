<?php

if (!defined("VER")) {
    exit("No direct script access allowed");
}

?>

<div class="row">
    <div class="twelve columns">
        <div class="breadcrumb">
            <h2>Dashboard</h2>
            <p>Selamat datang <strong><?= $_SESSION["username"] ?></strong></p>
        </div>
        <?php if (count($errors) > 0) : ?>
            <?php foreach ($errors as $key => $error) : ?>
                <?php if (is_string($key)) : ?>
                    <?php continue; ?>
                <?php endif; ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div class="box">
    <div class="row">
        <div class="twelve columns">
            <h3>Ringkasan</h3>
            <p>Total pasien : <?= number_format($total_pasien) ?></p>
            <p>Total pendapatan : Rp. <?= number_format($total_pendapatan) ?></p>
        </div>
    </div>
</div>