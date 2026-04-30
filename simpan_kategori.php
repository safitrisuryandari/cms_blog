<?php
header('Content-Type: application/json');
require 'koneksi.php';

$nama_kategori = trim($_POST['nama_kategori'] ?? '');
$keterangan    = trim($_POST['keterangan'] ?? '');

if (!$nama_kategori) {
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori wajib diisi.']);
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param('ss', $nama_kategori, $keterangan);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil ditambahkan.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();
