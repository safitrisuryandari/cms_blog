<?php
header('Content-Type: application/json');
require 'koneksi.php';

$sql    = "SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id ASC";
$result = $koneksi->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $data]);
$koneksi->close();
