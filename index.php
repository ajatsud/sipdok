<?php

session_start();

date_default_timezone_set('Asia/Jakarta');

include __DIR__ . DIRECTORY_SEPARATOR . 'autoloader.php';

/* Pattern
 *
 * ([0-9])
 * ([0-9]{2})
 * ([0-9]{4})
 * ([0-9]*)
 * ([0-9.]*)
 * ([0-9a-zA-Z-]*)
 * ([0-9a-zA-Z-/\\s/]*) termasuk spasi */ 


router::get('/', login_page_model::class, login_page_view::class, login_page_controller::class, 'index');
router::post('/login', login_page_model::class, login_page_view::class, login_page_controller::class, 'login');
router::get('/logout', login_page_model::class, login_page_view::class, login_page_controller::class, 'logout');
router::get('/dashboard', dashboard_page_model::class, dashboard_page_view::class, dashboard_page_controller::class, 'index');

router::get('/pasien', pasien_list_model::class, pasien_list_view::class, pasien_list_controller::class, 'index');
router::post('/pasien/search', pasien_list_model::class, pasien_list_view::class, pasien_list_controller::class, 'search');
router::post('/pasien/delete', pasien_list_model::class, pasien_list_view::class, pasien_list_controller::class, 'delete');
router::post('/pasien/popup', pasien_popup_model::class, pasien_popup_view::class, pasien_popup_controller::class, 'search');

router::get('/pasien/add', pasien_entry_model::class, pasien_entry_view::class, pasien_entry_controller::class, 'add');
router::post('/pasien/edit', pasien_entry_model::class, pasien_entry_view::class, pasien_entry_controller::class, 'edit');
router::post('/pasien/save', pasien_entry_model::class, pasien_entry_view::class, pasien_entry_controller::class, 'save');

router::get('/pendaftaran', pendaftaran_list_model::class, pendaftaran_list_view::class, pendaftaran_list_controller::class, 'index');
router::post('/pendaftaran/search', pendaftaran_list_model::class, pendaftaran_list_view::class, pendaftaran_list_controller::class, 'search');
router::post('/pendaftaran/delete', pendaftaran_list_model::class, pendaftaran_list_view::class, pendaftaran_list_controller::class, 'delete');

router::get('/pendaftaran/add', pendaftaran_entry_model::class, pendaftaran_entry_view::class, pendaftaran_entry_controller::class, 'add');
router::post('/pendaftaran/edit', pendaftaran_entry_model::class, pendaftaran_entry_view::class, pendaftaran_entry_controller::class, 'edit');
router::post('/pendaftaran/save', pendaftaran_entry_model::class, pendaftaran_entry_view::class, pendaftaran_entry_controller::class, 'save');

router::get('/antrian', antrian_list_model::class, antrian_list_view::class, antrian_list_controller::class, 'index');
router::post('/antrian/search', antrian_list_model::class, antrian_list_view::class, antrian_list_controller::class, 'search');

router::get('/periksa/add', periksa_entry_model::class, periksa_entry_view::class, periksa_entry_controller::class, 'index');
router::post('/periksa/edit', periksa_entry_model::class, periksa_entry_view::class, periksa_entry_controller::class, 'edit');
router::post('/periksa/save', periksa_entry_model::class, periksa_entry_view::class, periksa_entry_controller::class, 'save');

router::get('/periksa', periksa_list_model::class, periksa_list_view::class, periksa_list_controller::class, 'index');
router::post('/periksa/search', periksa_list_model::class, periksa_list_view::class, periksa_list_controller::class, 'search');
router::post('/periksa/delete', periksa_list_model::class, periksa_list_view::class, periksa_list_controller::class, 'delete');

router::dispatch();
