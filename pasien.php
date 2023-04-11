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
    $pasiens = $pasien->findAll();
    Render::view('pasien/list', [
        'pasiens' => $pasiens
    ]);
});

Router::dispatch();
