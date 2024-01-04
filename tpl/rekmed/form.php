<?php

if (!defined("VER")) {
  exit("No direct script access allowed");
}

?>

<div class="row">
  <div class="twelve columns">
    <div class="breadcrumb">
      <h2>Rekam Medis Entri</h2>
      <p><a href="/rekmed" class="button">Rekam Medis List</a></p>
    </div>
  </div>
</div>
<div class="box">
  <form method="post" action="/rekmed/save">
    <div class="row">
      <div class="twelve columns">
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
    <div class="row">
      <div class="twelve columns">
        <h3>Informasi Pasien</h3>
        <hr>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <div class="row">
          <div class="six columns">
            <label>Pendaftaran ID</label>
            <input type="text" name="pendaftaran_id" value="<?= $inputs["pendaftaran_id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
          </div>
          <div class="six columns">
            <label>Pasien ID</label>
            <input type="text" name="pasien_id" value="<?= $inputs["pasien_id"] ?? "" ?>" placeholder="Otomatis" class="u-full-width" readonly>
          </div>
        </div>
      </div>
      <div class="six columns">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $inputs["nama"] ?? "" ?>" placeholder="Nama" class="u-full-width">
        <?php if (isset($errors["nama"])) : ?>
          <p style="color: red;"><?= $errors["nama"] ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Lahir</label>
        <input type="date" name="lahir" value="<?= (isset($inputs["lahir"])) ? date("Y-m-d", strtotime($inputs["lahir"])) : date("Y-m-d") ?>" class="u-full-width">
      </div>
      <div class="six columns">
        <label>Jenkel</label>
        <div style="display: flex; flex-direction: row; gap: 20px; justify-content: flex-start;">
          <div style="display: flex; flex-direction: row;">
            <div>Laki-Laki</div>
            <div><input type="radio" name="jenkel" value="l" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "l") ? "checked" : "" ?> class="u-full-width"></div>
          </div>
          <div style="display: flex; flex-direction: row;">
            <div>Perempuan</div>
            <div><input type="radio" name="jenkel" value="p" <?= (isset($inputs["jenkel"]) && $inputs["jenkel"] == "p") ? "checked" : "" ?> class="u-full-width"></div>
          </div>
        </div>
        <?php if (isset($errors["jenkel"])) : ?>
          <p style="color: red;"><?= $errors["jenkel"] ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Alamat</label>
        <textarea name="alamat" class="u-full-width"><?= $inputs["alamat"] ?? "" ?></textarea>
        <?php if (isset($errors["alamat"])) : ?>
          <p style="color: red;"><?= $errors["alamat"] ?></p>
        <?php endif; ?>
      </div>
      <div class="six columns">
        <label>Keluhan</label>
        <textarea name="keluhan" class="u-full-width"><?= $inputs["keluhan"] ?? "" ?></textarea>
        <?php if (isset($errors["keluhan"])) : ?>
          <p style="color: red;"><?= $errors["keluhan"] ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="twelve columns">
        <h3>Pemeriksaan</h3>
        <hr>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Rekam Medis ID</label>
        <input type="text" name="id" value="<?= $inputs["id"] ?? "" ?>" placeholder="Otomatis" readonly>
      </div>
      <div class="six columns">

      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Anamnesa</label>
        <textarea name="anamnesa" class="u-full-width"><?= $inputs["anamnesa"] ?? "" ?></textarea>
        <?php if (isset($errors["anamnesa"])) : ?>
          <p style="color: red;"><?= $errors["anamnesa"] ?></p>
        <?php endif; ?>
      </div>
      <div class="six columns">
        <label>Pemeriksaan</label>
        <textarea name="pemeriksaan" class="u-full-width"><?= $inputs["pemeriksaan"] ?? "" ?></textarea>
        <?php if (isset($errors["pemeriksaan"])) : ?>
          <p style="color: red;"><?= $errors["pemeriksaan"] ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Diagnosa</label>
        <textarea name="diagnosa" class="u-full-width"><?= $inputs["diagnosa"] ?? "" ?></textarea>
        <?php if (isset($errors["diagnosa"])) : ?>
          <p style="color: red;"><?= $errors["diagnosa"] ?></p>
        <?php endif; ?>
      </div>
      <div class="six columns">
        <label>Terapi</label>
        <textarea name="terapi" class="u-full-width"><?= $inputs["terapi"] ?? "" ?></textarea>
        <?php if (isset($errors["terapi"])) : ?>
          <p style="color: red;"><?= $errors["terapi"] ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <div class="six columns">
        <label>Biaya</label>
        <input type="number" name="biaya" value="<?= $inputs["biaya"] ?? 0 ?>" class="u-full-width">
      </div>
      <div class="six columns">
        <label>&nbsp;</label>
        <input type="submit" class="button-primary">
      </div>
    </div>
  </form>
</div>
<div class="row">
  <div class="twelve columns">
    <div class="breadcrumb">
      <h2>Histori Rekam Medis</h2>
      <p>100 Histori</p>
    </div>
  </div>
</div>
<div class="box">
  <p>...</p>
</div>