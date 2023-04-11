<?php

include __DIR__ . '/lib/Router.php';
include __DIR__ . '/lib/Database.php';
include __DIR__ . '/lib/DatabaseTable.php';
include __DIR__ . '/lib/Render.php';

/* ([0-9])
 * ([0-9]{2})
 * ([0-9]{4})
 * ([0-9]*)
 * ([0-9.]*)
 * ([0-9a-zA-Z-]*)
 * ([0-9a-zA-Z-/\\s/]*) termasuk spasi
 */

Router::get('/', function (): void {
	$pasien = new DatabaseTable(Database::connection(), 'pasien', ['id']);

	/*
	$pasien->insert([
		'id' => 'xxx',
		'nama' => 'Anu',
		'jenkel' => 'L',
		'umur' => 30,
		'alamat' => 'bandung',
		'insert_dt' => date('Y-m-d'),
		'update_dt' => date('Y-m-d')
	]);
	
	$pasien->update([
		'id' => 'xxx',
		'nama' => 'Anu update',
		'jenkel' => 'L',
		'umur' => 30,
		'alamat' => 'bandung',
		'insert_dt' => date('Y-m-d'),
		'update_dt' => date('Y-m-d')
	]);
	
	$pasien->delete([
		'id' => 'xxxxx'
	]);
	
	if ($data = $pasien->search(['id' => 'PS2304070002']))
		var_dump($data);
	else
		echo 'no data';
	*/

	Render::view('index/home', [
		'test' => 'testing testing'
	]);
});

Router::get('/([0-9a-zA-Z-]*)', function (string $test): void {
	Render::view('index/home', [
		'test' => $test
	]);
});

Router::dispatch();
