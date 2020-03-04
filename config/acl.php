<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Deskripsi:
 * File acl.php adalah file konfigurasi milik MY_Controller
 * Untuk mengaktifkan acl, pada tiap controller yang menextends MY_Controller 
 * ditambahkan $this->acl() pada function __construct() atau bisa juga di letakkan pada di dalam 
 * function tertentu sesuai kebutuhan
 * Cara kerjanya sederhana:
 * 1. Akan melakukan pengecekan apakah session, level, module dan uri yang berisi data sesuai dengan yang didefinisikan
 * 2. Jika tidak valid maka halaman akan dipindah menuju ke halaman telah ditentukan pada file konfigurasi ini
 * 
 * ACL Mode:
 * 1 = Hanya ngecek session saja
 * 2 = Ngecek session dan level
 * 3 = Ngecek session name dan uri
 * 4 = Ngecek session name, level dan uri
 * 5 = Ngecek session name dan module
 * 6 = Ngecek session name, module dan uri
 * 
 * Level dan Module didefinisikan pada controller lewat variabel $this->acl_level dan $this->acl_module
 * Mode 3 dan seterusnya membutuhkan tabel acl, field yang direkomendasikan
 * username      : varchar() 
 * level         : varchar()
 * modules       : text
 * uri_blacklist : text 
 * 
 */

$config['acl_mode'] = 6; // bisa diset di config dan di controller lewat variabel $acl_mode
$config['acl_session_user'] = 'user'; //nama sesi yang menyimpan username aktif setelah berhasil login
$config['acl_session_level'] = 'level'; //nama sesi yang menyimpan level aktif setelah berhasil login, hanya untuk mode 2 atau 4 saja
$config['acl_redirect'] = 'login'; // jika tidak valid maka redirect ke ...

// Bagian ini hanya perlu diisi jika menggunakan table acl pada mode 2,3 atau 4
$config['acl_table_name'] = 'user'; // nama table access control
$config['acl_user_field_name'] = 'username'; // nama field pada acl yang berisi username untuk di
$config['acl_level_field_name'] = 'level'; // nama field pada acl yang berisi level
$config['acl_field_modules_name'] = 'modules'; // nama field pada acl yang berisi module yang boleh diakses. Untuk hak akses seluruh halaman bisa diisi dengan all
$config['acl_uri_field_name'] = 'uri_blacklist'; // nama field pada acl yang berisi uri yang tidak boleh diakses.

// digunakan untuk mode 2 dan seterusnya
$config['acl_denied_url'] = 'denied';

