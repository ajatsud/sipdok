<?php

if (!defined("APP_VER"))
{
	exit("No direct script access allowed");
}

get("/pendaftaran", function ()
{
	if (!is_login())
	{
		redirect_to("/user/login");
	}

	global $mysqli;

	$errors = [];

	$res = mysqli_query(
		$mysqli,
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
			order by a.id desc
		"
	);

	if (mysqli_errno($mysqli))
	{
		$errors[] = mysqli_error($mysqli);
	}

	$pendaftarans = [];

	if ($res)
	{
		if (mysqli_num_rows($res) > 0)
		{
			while ($row = mysqli_fetch_assoc($res))
			{
				$pendaftarans[] = [
					"id" => $row["id"],
					"pasien_id" => $row["pasien_id"],
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
		"view" => "pendaftaran_index",
		"title" => "Pendaftaran List",
		"menu" => "pendaftaran",
		"errors" => $errors,
		"pendaftarans" => $pendaftarans
	];
});

get("/pendaftaran/form", function ()
{
	if (!is_login())
	{
		redirect_to("/user/login");
	}

	$inputs = [];
	$errors = [];

	if (isset($_SESSION["inputs"]))
	{
		$inputs = $_SESSION["inputs"];
		unset($_SESSION["inputs"]);
	}

	if (isset($_SESSION["errors"]))
	{
		$errors = $_SESSION["errors"];
		unset($_SESSION["errors"]);
	}

	return [
		"view" => "pendaftaran_form",
		"title" => "Pendaftaran Form",
		"menu" => "pendaftaran",
		"inputs" => $inputs,
		"errors" => $errors
	];
});

get("/pendaftaran/edit/:id", function ($id)
{
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

	if (mysqli_errno($mysqli))
	{
		$errors[] = mysqli_error($mysqli);
	}

	if ($res)
	{
		if (mysqli_num_rows($res) == 1)
		{
			if ($row = mysqli_fetch_assoc($res))
			{
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

	redirect_with("/pendaftaran/form");
});


post("/pendaftaran/save", function ()
{
	if (!is_login())
	{
		redirect_to("/user/login");
	}

	global $mysqli;

	$inputs = [];
	$errors = [];

	$is_new_pendaftaran = false;
	$is_new_pasien = false;

	if (isset($_POST["id"]))
	{
		$id = htmlentities(strip_tags(trim($_POST["id"])));

		if (strlen($id) > 0)
		{
			$inputs["id"] = $id;
		}
		else
		{
			$is_new_pendaftaran = true;
			$prefix = "RG" . date("Ym");

			$res = mysqli_query($mysqli, sprintf(
				"select ifnull(max(substring(id, 9, 4)), 0) as seq_no from pendaftaran where substring(id, 1, 8) = '%s'",
				mysqli_real_escape_string($mysqli, $prefix)
			));

			if (mysqli_errno($mysqli))
			{
				$errors[] = mysqli_error($mysqli);
			}

			if ($res)
			{
				if (mysqli_num_rows($res) == 1)
				{
					$row = mysqli_fetch_assoc($res);
					$seq_no = (int) $row["seq_no"];
					$seq_no++;
					$seq_no_str = sprintf("%04d", $seq_no);
					$inputs["id"] = $prefix . $seq_no_str; // RG2023120001
				}
			}
		}
	}
	else
	{
		$errors["id"] = "ID Undefine";
	}

	if (isset($_POST["pasien_id"]))
	{
		$pasien_id = htmlentities(strip_tags(trim($_POST["pasien_id"])));

		if (strlen($pasien_id) > 0)
		{
			$inputs["pasien_id"] = $pasien_id;
		}
		else
		{
			$is_new_pasien = true;
			$prefix = "PS" . date("Ym");

			$res = mysqli_query($mysqli, sprintf(
				"select ifnull(max(substring(id, 9, 4)), 0) as seq_no from pasien where substring(id, 1, 8) = '%s'",
				mysqli_real_escape_string($mysqli, $prefix)
			));

			if (mysqli_errno($mysqli))
			{
				$errors[] = mysqli_error($mysqli);
			}

			if ($res)
			{
				if (mysqli_num_rows($res) == 1)
				{
					$row = mysqli_fetch_assoc($res);
					$seq_no = (int) $row["seq_no"];
					$seq_no++;
					$seq_no_str = sprintf("%04d", $seq_no);
					$inputs["pasien_id"] = $prefix . $seq_no_str; // PS2023120001
				}
			}
		}
	}
	else
	{
		$errors["pasien_id"] = "Pasien ID Undefine";
	}

	if (isset($_POST["nama"]))
	{
		$nama = htmlentities(strip_tags(trim($_POST["nama"])));

		if (strlen($nama) > 0)
		{
			$inputs["nama"] = $nama;
		}
		else
		{
			$errors["nama"] = "Nama tidak boleh kosong";
		}
	}
	else
	{
		$errors["nama"] = "Nama undefine";
	}

	if (isset($_POST["jenkel"]))
	{
		$jenkel = htmlentities(strip_tags(trim($_POST["jenkel"])));

		if (strlen($jenkel) > 0)
		{
			$inputs["jenkel"] = $jenkel;
		}
		else
		{
			$errors["jenkel"] = "Jenkel tidak boleh kosong";
		}
	}
	else
	{
		$errors["jenkel"] = "Jenkel harus dipilih";
	}

	if (isset($_POST["lahir"]))
	{
		$lahir = htmlentities(strip_tags(trim($_POST["lahir"])));

		if (strlen($lahir) > 0)
		{
			$inputs["lahir"] = date("Y-m-d", strtotime($lahir));
		}
		else
		{
			$errors["lahir"] = "Tanggal lahir tidak boleh kosong";
		}
	}
	else
	{
		$errors["lahir"] = "Tanggal lahir undefine";
	}

	if (isset($_POST["alamat"]))
	{
		$alamat = htmlentities(strip_tags(trim($_POST["alamat"])));

		if (strlen($alamat) > 0)
		{
			$inputs["alamat"] = $alamat;
		}
		else
		{
			$errors["alamat"] = "Alamat tidak boleh kosong";
		}
	}
	else
	{
		$errors["alamat"] = "Alamat undefined";
	}

	if (isset($_POST["keluhan"]))
	{
		$keluhan = htmlentities(strip_tags(trim($_POST["keluhan"])));

		if (strlen($keluhan) > 0)
		{
			$inputs["keluhan"] = $keluhan;
		}
		else
		{
			$errors["keluhan"] = "Keluhan tidak boleh kosong";
		}
	}
	else
	{
		$errors["keluhan"] = "Keluhan undefined";
	}

	if (count($errors) == 0)
	{
		if (mysqli_autocommit($mysqli, false))
		{
			if ($is_new_pasien)
			{
				$ret_pasien = mysqli_query($mysqli, sprintf(
					"insert into pasien (
						id,
						nama,
						jenkel,
						lahir,
						alamat,
						ins_id,
						ins_dtm,
						upd_id,
						upd_dtm
					) values (
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)",
					mysqli_real_escape_string($mysqli, $inputs["pasien_id"]),
					mysqli_real_escape_string($mysqli, $inputs["nama"]),
					mysqli_real_escape_string($mysqli, $inputs["jenkel"]),
					mysqli_real_escape_string($mysqli, $inputs["lahir"]),
					mysqli_real_escape_string($mysqli, $inputs["alamat"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s"))
				));

				if (mysqli_errno($mysqli))
				{
					$errors[] = mysqli_error($mysqli);
				}
			}
			else
			{
				$ret_pasien = mysqli_query($mysqli, sprintf(
					"update pasien 
						set nama = '%s',
						jenkel = '%s',
						lahir = '%s',
						alamat = '%s',
						upd_id = '%s',
						upd_dtm = '%s'
				 	where id = '%s'",
					mysqli_real_escape_string($mysqli, $inputs["nama"]),
					mysqli_real_escape_string($mysqli, $inputs["jenkel"]),
					mysqli_real_escape_string($mysqli, $inputs["lahir"]),
					mysqli_real_escape_string($mysqli, $inputs["alamat"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $inputs["pasien_id"])
				));

				if (mysqli_errno($mysqli))
				{
					$errors[] = mysqli_error($mysqli);
				}
			}
			if ($is_new_pendaftaran)
			{
				$ret_pendaftaran = mysqli_query($mysqli, sprintf(
					"insert into pendaftaran (
						id, 
						pasien_id,
						keluhan,
						ins_id,
						ins_dtm,
						upd_id,
						upd_dtm
					) values (
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					)",
					mysqli_real_escape_string($mysqli, $inputs["id"]),
					mysqli_real_escape_string($mysqli, $inputs["pasien_id"]),
					mysqli_real_escape_string($mysqli, $inputs["keluhan"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s"))
				));

				if (mysqli_errno($mysqli))
				{
					$errors[] = mysqli_error($mysqli);
				}
			}
			else
			{
				$ret_pendaftaran = mysqli_query($mysqli, sprintf(
					"update pendaftaran
						set pasien_id = '%s',
						keluhan = '%s',
						upd_id = '%s',
						upd_dtm = '%s'
					where id = '%s'
					",
					mysqli_real_escape_string($mysqli, $inputs["pasien_id"]),
					mysqli_real_escape_string($mysqli, $inputs["keluhan"]),
					mysqli_real_escape_string($mysqli, $_SESSION["username"]),
					mysqli_real_escape_string($mysqli, date("Y-m-d H:i:s")),
					mysqli_real_escape_string($mysqli, $inputs["id"])
				));
				if (mysqli_errno($mysqli))
				{
					$errors[] = mysqli_error($mysqli);
				}
			}

			if ($ret_pasien && $ret_pendaftaran)
			{
				mysqli_commit($mysqli);

				flash("success", "Berhasil", "Data pendaftaran berhasil disimpan");
			}
			else
			{
				mysqli_rollback($mysqli);
			}
		}
	}
	else
	{
		if ($is_new_pendaftaran)
		{
			if (isset($inputs["id"]))
			{
				unset($inputs["id"]);
			}
		}

		if ($is_new_pasien)
		{
			if (isset($inputs["pasien_id"]))
			{
				unset($inputs["pasien_id"]);
			}
		}
	}

	$_SESSION["inputs"] = $inputs;
	$_SESSION["errors"] = $errors;

	redirect_with("/pendaftaran/form");
});
