<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_blog';

$koneksi = new mysqli($host, $user, $pass, $db);
$koneksi->set_charset('utf8mb4');

if ($koneksi->connect_error) {
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $koneksi->connect_error]));
}
