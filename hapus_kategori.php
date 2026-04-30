<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    exit;
}

// Cek apakah kategori masih digunakan artikel
$cek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM artikel WHERE id_kategori = ?");
$cek->bind_param('i', $id);
$cek->execute();
$jml = $cek->get_result()->fetch_assoc()['jml'];
$cek->close();

if ($jml > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Kategori tidak dapat dihapus karena masih memiliki artikel.']);
    exit;
}

$del = $koneksi->prepare("DELETE FROM kategori_artikel WHERE id = ?");
$del->bind_param('i', $id);

if ($del->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']);
}

$del->close();
$koneksi->close();
