<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    exit;
}

// Cek apakah penulis masih memiliki artikel
$cek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM artikel WHERE id_penulis = ?");
$cek->bind_param('i', $id);
$cek->execute();
$jml = $cek->get_result()->fetch_assoc()['jml'];
$cek->close();

if ($jml > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Penulis tidak dapat dihapus karena masih memiliki artikel.']);
    exit;
}

// Ambil foto untuk dihapus
$stmt = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    exit;
}

$del = $koneksi->prepare("DELETE FROM penulis WHERE id = ?");
$del->bind_param('i', $id);

if ($del->execute()) {
    if ($row['foto'] !== 'default.png' && file_exists(__DIR__ . '/uploads_penulis/' . $row['foto'])) {
        unlink(__DIR__ . '/uploads_penulis/' . $row['foto']);
    }
    echo json_encode(['status' => 'success', 'message' => 'Penulis berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']);
}

$del->close();
$koneksi->close();
