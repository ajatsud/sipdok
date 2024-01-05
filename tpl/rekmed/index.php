<?php

if (!defined("VER")) {
  exit("No direct script access allowed");
}

?>

<div class="row">
  <div class="twelve columns">
    <div class="breadcrumb">
      <h2>Rekam Medis List</h2>
      <p>Disini filter / Condition</p>
    </div>
    <?php if (count($errors) > 0) : ?>
      <?php foreach ($errors as $key => $error) : ?>
        <?php if (is_string($key)) : ?>
          <?php continue; ?>
        <?php endif; ?>
        <p style="color: red;"><?= $error ?></p>
      <?php endforeach; ?>
    <?php endif; ?>
    <?php if (count($rekmeds) > 0) : ?>
      <div class="table-container">
        <table class="u-full-width">
          <thead>
            <tr>
              <th>No</th>
              <th>ID</th>
              <th>Nama</th>
              <th>Anamnesa</th>
              <th>Pemeriksaan</th>
              <th>Diagnosa</th>
              <th>Terapi</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php $row_no = 1; ?>
            <?php foreach ($rekmeds as $rekmed) : ?>
              <tr>
                <td><?= $row_no ?></td>
                <td><?= $rekmed["id"] ?></td>
                <td><?= $rekmed["nama"] ?> <small>(<?= $rekmed["jenkel"] ?>)</small></td>
                <td><?= $rekmed["anamnesa"] ?></td>
                <td><?= $rekmed["pemeriksaan"] ?></td>
                <td><?= $rekmed["diagnosa"] ?></td>
                <td><?= $rekmed["terapi"] ?></td>
                <td class="td-button-container">
                  <a class="button button-warning" href="/rekmed/edit/<?= $rekmed["id"] ?>">Edit</a>
                  <a class="button button-danger" href="/rekmed/edit/<?= $rekmed["id"] ?>">Hapus</a>
                </td>
              </tr>
              <?php $row_no++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>