<?php

if (!defined("APP_VER"))
{
	exit("No direct script access allowed");
}

post("/autocomplete/pasien", function ()
{
	global $mysqli;

	header("Content-type: application/json");

	$errors = [];

	$inputs = json_decode(file_get_contents("php://input"), true);

	if (isset($inputs["nama"]))
	{
		$nama = htmlentities(strip_tags(trim($inputs["nama"])));

		if (strlen($nama >= 2))
		{
			$inputs["nama"] = $nama;
		}
		else
		{
			$errors["nama"] = "Nama harus sama dengan 2 atau lebih karakter";
		}
	}

	$pasiens = [];

	if (count($errors) == 0)
	{
		$res = mysqli_query($mysqli, sprintf(
			"select * from pasien where nama like '%s'",
			mysqli_real_escape_string($mysqli, "%" . $inputs["nama"] . "%")
		));

		if (mysqli_errno($mysqli))
		{
			echo json_encode($pasiens);

			exit;
		}

		if ($res)
		{
			if (mysqli_num_rows($res) > 0)
			{
				while ($row = mysqli_fetch_assoc($res))
				{
					$pasiens[] = [
						"id" => $row["id"],
						"nama" => $row["nama"],
						"jenkel" => $row["jenkel"],
						"lahir" => $row["lahir"],
						"alamat" => $row["alamat"]
					];
				}
			}
		}
	}

	echo json_encode($pasiens);

	exit;
});
