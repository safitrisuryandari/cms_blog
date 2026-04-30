<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama_kategori, keterangan FROM kategori_artikel WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if ($data) {
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
}

$stmt->close();
$koneksi->close();
