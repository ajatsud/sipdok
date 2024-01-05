<?php

if (!defined("VER")) {
  exit("No direct script access allowed");
}

get("/rekmed", function () {
  if (!is_login()) {
    redirect_to("/user/login");
  }

  global $mysqli;
  $errors = [];

  $res = mysqli_query(
    $mysqli,
    "select a.id,
      a.pendaftaran_id,
      a.pasien_id,
      a.anamnesa,
      a.pemeriksaan,
      a.diagnosa,
      a.terapi,
      a.biaya,
      ( select b.keluhan 
        from pendaftaran b
        where b.id = a.pendaftaran_id
          and b.pasien_id = a.pasien_id ) keluhan,
      ( select b.nama
        from pasien b
        where b.id = a.pasien_id ) nama,
      ( select b.jenkel
        from pasien b
        where b.id = a.pasien_id ) jenkel,
      ( select b.lahir
        from pasien b
        where b.id = a.pasien_id ) lahir,
      ( select b.alamat 
        from pasien b
        where b.id = a.pasien_id ) alamat
      from rekmed a
		"
  );

  if (mysqli_errno($mysqli)) {
    $errors[] = mysqli_error($mysqli);
  }

  $rekmeds = [];

  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      while ($row = mysqli_fetch_assoc($res)) {
        $rekmeds[] = [
          "id" => $row["id"],
          "pendaftaran_id" => $row["pendaftaran_id"],
          "pasien_id" => $row["pasien_id"],
          "anamnesa" => $row["anamnesa"],
          "pemeriksaan" => $row["pemeriksaan"],
          "diagnosa" => $row["diagnosa"],
          "terapi" => $row["terapi"],
          "biaya" => $row["biaya"],
          "keluhan" => $row["keluhan"],
          "nama" => $row["nama"],
          "jenkel" => $row["jenkel"],
          "lahir" => $row["lahir"],
          "alamat" => $row["alamat"]
        ];
      }
    }
  }

  return [
    "view" => "rekmed_index",
    "title" => "Rekam Medis List",
    "menu" => "rekmed",
    "errors" => $errors,
    "rekmeds" => $rekmeds
  ];
});

get("/rekmed/form", function () {
  if (!is_login()) {
    redirect_to("/user/login");
  }

  $inputs = [];
  $errors = [];

  if (isset($_SESSION["inputs"])) {
    $inputs = $_SESSION["inputs"];
    unset($_SESSION["inputs"]);
  }

  if (isset($_SESSION["errors"])) {
    $errors = $_SESSION["errors"];
    unset($_SESSION["errors"]);
  }

  return [
    "view" => "rekmed_form",
    "title" => "Rekam Medis Form",
    "menu" => "rekmed",
    "inputs" => $inputs,
    "errors" => $errors
  ];
});

get("/rekmed/edit/:id", function ($id) {
  global $mysqli;

  $inputs = [];
  $errors = [];

  $id = htmlentities(strip_tags(trim($id)));

  $res = mysqli_query($mysqli, sprintf(
    "select a.id,
      a.pendaftaran_id,
      a.pasien_id,
      a.anamnesa,
      a.pemeriksaan,
      a.diagnosa,
      a.terapi,
      a.biaya,
      ( select b.keluhan 
        from pendaftaran b
        where b.id = a.pendaftaran_id
          and b.pasien_id = a.pasien_id ) keluhan,
      ( select b.nama
        from pasien b
        where b.id = a.pasien_id ) nama,
      ( select b.jenkel
        from pasien b
        where b.id = a.pasien_id ) jenkel,
      ( select b.lahir
        from pasien b
        where b.id = a.pasien_id ) lahir,
      ( select b.alamat 
        from pasien b
        where b.id = a.pasien_id ) alamat
      from rekmed a 
      where a.id = '%s'
		",
    mysqli_real_escape_string($mysqli, $id)
  ));

  if (mysqli_errno($mysqli)) {
    $errors[] = mysqli_error($mysqli);
  }

  if ($res) {
    if (mysqli_num_rows($res) == 1) {
      if ($row = mysqli_fetch_assoc($res)) {
        $inputs["id"] = $row["id"];
        $inputs["pendaftaran_id"] = $row["pendaftaran_id"];
        $inputs["pasien_id"] = $row["pasien_id"];
        $inputs["anamnesa"] = $row["anamnesa"];
        $inputs["pemeriksaan"] = $row["pemeriksaan"];
        $inputs["diagnosa"] = $row["diagnosa"];
        $inputs["terapi"] = $row["terapi"];
        $inputs["biaya"] = $row["biaya"];
        $inputs["keluhan"] = $row["keluhan"];
        $inputs["nama"] = $row["nama"];
        $inputs["jenkel"] = $row["jenkel"];
        $inputs["lahir"] = $row["lahir"];
        $inputs["alamat"] = $row["alamat"];
      }
    }
  }

  $_SESSION["inputs"] = $inputs;
  $_SESSION["errors"] = $errors;

  redirect_with("/rekmed/form");
});

get("/rekmed/pendaftaran/:id", function ($id) {
  global $mysqli;

  $inputs = [];
  $errors = [];

  $id = htmlentities(strip_tags(trim($id)));

  $res = mysqli_query($mysqli, sprintf(
    "select
      a.id,
      a.pasien_id,
      a.keluhan,
      ( Select b.nama
          From pasien b
         Where b.id = a.pasien_id ) nama,
      ( Select b.jenkel
          From pasien b
         Where b.id = a.pasien_id ) jenkel,
      ( Select b.lahir
          From pasien b
         Where b.id = a.pasien_id ) lahir,
      ( Select b.alamat
          From pasien b
         Where b.id = a.pasien_id ) alamat
     From pendaftaran a
    Where a.id = '%s'",
    mysqli_real_escape_string($mysqli, $id)
  ));

  if (mysqli_errno($mysqli)) {
    $errors[] = mysqli_error($mysqli);
  }

  if ($res) {
    if (mysqli_num_rows($res) == 1) {
      if ($row = mysqli_fetch_assoc($res)) {
        $inputs["pendaftaran_id"] = $row["id"];
        $inputs["pasien_id"] = $row["pasien_id"];
        $inputs["keluhan"] = $row["keluhan"];
        $inputs["nama"] = $row["nama"];
        $inputs["jenkel"] = $row["jenkel"];
        $inputs["lahir"] = $row["lahir"];
        $inputs["alamat"] = $row["alamat"];
      }
    }
  }

  $_SESSION["inputs"] = $inputs;
  $_SESSION["errors"] = $errors;

  redirect_with("/rekmed/form");
});


post("/rekmed/save", function () {
  if (!is_login()) {
    redirect_to("/user/login");
  }

  global $mysqli;

  $inputs = [];
  $errors = [];

  // rekam medis

  $is_new_rekmed = false;

  $id = isset($_POST["id"]) ? htmlentities(strip_tags(trim($_POST["id"]))) : "";
  if (strlen($id) > 0) {
    $inputs["id"] = $id;
  } else {
    $is_new_rekmed = true;
    $inputs["id"] = get_auto_id("rekmed", "RM");
  }

  $pendaftaran_id = isset($_POST["pendaftaran_id"]) ? htmlentities(strip_tags(trim($_POST["pendaftaran_id"]))) : "";
  if (strlen($pendaftaran_id) > 0) {
    $inputs["pendaftaran_id"] = $pendaftaran_id;
  } else {
    $errors["pendaftaran_id"] = "Pendaftaran ID tidak boleh kosong";
  }

  $pasien_id = isset($_POST["pasien_id"]) ? htmlentities(strip_tags(trim($_POST["pasien_id"]))) : "";
  if (strlen($pasien_id) > 0) {
    $inputs["pasien_id"] = $pasien_id;
  } else {
    $errors["pasien_id"] = "Pasien ID tidak boleh kosong";
  }

  $anamnesa = isset($_POST["anamnesa"]) ? htmlentities(strip_tags(trim($_POST["anamnesa"]))) : "";
  if (strlen($anamnesa) > 0) {
    $inputs["anamnesa"] = $anamnesa;
  } else {
    $errors["anamnesa"] = "Anamnesa tidak boleh kosong";
  }

  $pemeriksaan = isset($_POST["pemeriksaan"]) ? htmlentities(strip_tags(trim($_POST["pemeriksaan"]))) : "";
  if (strlen($pemeriksaan) > 0) {
    $inputs["pemeriksaan"] = $pemeriksaan;
  } else {
    $errors["pemeriksaan"] = "Pemeriksaan tidak boleh kosong";
  }

  $diagnosa = isset($_POST["diagnosa"]) ? htmlentities(strip_tags(trim($_POST["diagnosa"]))) : "";
  if (strlen($diagnosa) > 0) {
    $inputs["diagnosa"] = $diagnosa;
  } else {
    $errors["diagnosa"] = "Diagnosa tidak boleh kosong";
  }

  $terapi = isset($_POST["terapi"]) ? htmlentities(strip_tags(trim($_POST["terapi"]))) : "";
  if (strlen($terapi) > 0) {
    $inputs["terapi"] = $terapi;
  } else {
    $errors["terapi"] = "Terapi tidak boleh kosong";
  }

  $biaya = isset($_POST["biaya"]) ? htmlentities(strip_tags(trim($_POST["biaya"]))) : 0;
  $inputs["biaya"] = (float) $biaya;

  // pendaftaran

  $keluhan = isset($_POST["keluhan"]) ? htmlentities(strip_tags(trim($_POST["keluhan"]))) : "";
  if (strlen($keluhan) > 0) {
    $inputs["keluhan"] = $keluhan;
  } else {
    $errors["keluhan"] = "Keluhan tidak boleh kosong";
  }

  // pasien

  $nama = isset($_POST["nama"]) ? htmlentities(strip_tags(trim($_POST["nama"]))) : "";
  if (strlen($nama) > 0) {
    $inputs["nama"] = $nama;
  } else {
    $errors["nama"] = "Nama tidak boleh kosong";
  }

  $jenkel = isset($_POST["jenkel"]) ? htmlentities(strip_tags(trim($_POST["jenkel"]))) : "";
  if (strlen($jenkel) > 0) {
    $inputs["jenkel"] = $jenkel;
  } else {
    $errors["jenkel"] = "Jenkel harus dipilih salah satu";
  }

  $lahir = isset($_POST["lahir"]) ? htmlentities(strip_tags(trim($_POST["lahir"]))) : "";
  if (strlen($lahir) > 0) {
    $inputs["lahir"] = date("Y-m-d", strtotime($lahir));
  } else {
    $errors["lahir"] = "Tanggal lahir tidak boleh kosong";
  }

  $alamat = isset($_POST["alamat"]) ? htmlentities(strip_tags(trim($_POST["alamat"]))) : "";
  if (strlen($alamat) > 0) {
    $inputs["alamat"] = $alamat;
  } else {
    $errors["alamat"] = "Alamat tidak boleh kosong";
  }

  if (count($errors) == 0) {
    if (mysqli_autocommit($mysqli, false)) {
      if ($is_new_rekmed) {
        $ret_rekmed = mysqli_query($mysqli, sprintf(
          "insert into rekmed (
            id, pendaftaran_id, pasien_id,
            anamnesa, pemeriksaan, diagnosa, terapi, biaya,
            ins_id, ins_dtm, upd_id, upd_dtm 
          ) values (
            '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', %f,
            '%s', '%s', '%s', '%s'
          )
          ",
          mysqli_real_escape_string($mysqli, $inputs["id"]),
          mysqli_real_escape_string($mysqli, $inputs["pendaftaran_id"]),
          mysqli_real_escape_string($mysqli, $inputs["pasien_id"]),
          mysqli_real_escape_string($mysqli, $inputs["anamnesa"]),
          mysqli_real_escape_string($mysqli, $inputs["pemeriksaan"]),
          mysqli_real_escape_string($mysqli, $inputs["diagnosa"]),
          mysqli_real_escape_string($mysqli, $inputs["terapi"]),
          mysqli_real_escape_string($mysqli, $inputs["biaya"]),
          mysqli_real_escape_string($mysqli, $_SESSION["username"]),
          mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
          mysqli_real_escape_string($mysqli, $_SESSION["username"]),
          mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s"))
        ));

        if (mysqli_errno($mysqli)) {
          $errors[] = mysqli_error($mysqli);
        }
      } else {
        $ret_rekmed = mysqli_query($mysqli, sprintf(
          "update rekmed set anamnesa = '%s',
            pemeriksaan = '%s',
            diagnosa = '%s',
            terapi = '%s',
            biaya = %f,
            upd_id = '%s',
            upd_dtm = '%s'
          where id = '%s'
            and pendaftaran_id = '%s'
            and pasien_id = '%s'
          ",
          mysqli_real_escape_string($mysqli, $inputs["anamnesa"]),
          mysqli_real_escape_string($mysqli, $inputs["pemeriksaan"]),
          mysqli_real_escape_string($mysqli, $inputs["diagnosa"]),
          mysqli_real_escape_string($mysqli, $inputs["terapi"]),
          mysqli_real_escape_string($mysqli, $inputs["biaya"]),
          mysqli_real_escape_string($mysqli, $_SESSION["username"]),
          mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
          mysqli_real_escape_string($mysqli, $inputs["id"]),
          mysqli_real_escape_string($mysqli, $inputs["pendaftaran_id"]),
          mysqli_real_escape_string($mysqli, $inputs["pasien_id"])
        ));

        if (mysqli_errno($mysqli)) {
          $errors[] = mysqli_error($mysqli);
        }
      }

      $ret_pendaftaran = mysqli_query($mysqli, sprintf(
        "update pendaftaran set keluhan = '%s',
          upd_id = '%s',
          upd_dtm = '%s'
          where id = '%s'
            and pasien_id = '%s'
        ",
        mysqli_real_escape_string($mysqli, $inputs["keluhan"]),
        mysqli_real_escape_string($mysqli, $_SESSION["username"]),
        mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
        mysqli_real_escape_string($mysqli, $inputs["pendaftaran_id"]),
        mysqli_real_escape_string($mysqli, $inputs["pasien_id"])
      ));

      if (mysqli_errno($mysqli)) {
        $errors[] = mysqli_error($mysqli);
      }

      $ret_pasien = mysqli_query($mysqli, sprintf(
        "update pasien 
						set nama = '%s', jenkel = '%s', lahir='%s', alamat = '%s',
							upd_id = '%s', upd_dtm = '%s'
				 	where id = '%s'",
        mysqli_real_escape_string($mysqli, $inputs["nama"]),
        mysqli_real_escape_string($mysqli, $inputs["jenkel"]),
        mysqli_real_escape_string($mysqli, $inputs["lahir"]),
        mysqli_real_escape_string($mysqli, $inputs["alamat"]),
        mysqli_real_escape_string($mysqli, $_SESSION["username"]),
        mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
        mysqli_real_escape_string($mysqli, $inputs["pasien_id"])
      ));

      if ($ret_rekmed && $ret_pendaftaran && $ret_pasien) {
        mysqli_commit($mysqli);
        flash("success", "Berhasil", "Data Rekam medis berhasil disimpan");
      } else {
        mysqli_rollback($mysqli);
      }
    }
  } else {
    if ($is_new_rekmed) {
      if (isset($inputs["id"])) {
        unset($inputs["id"]);
      }
    }
  }

  $_SESSION["inputs"] = $inputs;
  $_SESSION["errors"] = $errors;

  redirect_with("/rekmed/form");
});
