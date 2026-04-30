<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    exit;
}

$del = $koneksi->prepare("DELETE FROM artikel WHERE id = ?");
$del->bind_param('i', $id);

if ($del->execute()) {
    $path = __DIR__ . '/uploads_artikel/' . $row['gambar'];
    if (file_exists($path)) unlink($path);
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil dihapus.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus artikel.']);
}

$del->close();
$koneksi->close();
