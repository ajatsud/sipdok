<?php

if (!defined("VER")) {
  exit("No direct script access allowed");
}

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

get("/rekmed/periksa/:id", function ($id) {
  global $mysqli;

  $inputs = [];
  $errors = [];

  $id = htmlentities(strip_tags(trim($id)));

  $res = mysqli_query($mysqli, sprintf(
    "select a.id,
				a.pasien_id,
				a.keluhan,
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
		   from pendaftaran a
		  where a.id = '%s'",
    mysqli_real_escape_string($mysqli, $id)
  ));

  if (mysqli_errno($mysqli)) {
    $errors[] = mysqli_error($mysqli);
  }

  if ($res) {
    if (mysqli_num_rows($res) == 1) {
      if ($row = mysqli_fetch_assoc($res)) {
        $inputs["id"] = $row["id"];
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
