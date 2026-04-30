<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id          = (int)($_POST['id'] ?? 0);
$judul       = trim($_POST['judul'] ?? '');
$id_penulis  = (int)($_POST['id_penulis'] ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$isi         = trim($_POST['isi'] ?? '');

if (!$id || !$judul || !$id_penulis || !$id_kategori || !$isi) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$lama = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$lama) {
    echo json_encode(['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
    exit;
}

$gambar = $lama['gambar'];

if (!empty($_FILES['gambar']['name'])) {
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($_FILES['gambar']['tmp_name']);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan.']);
        exit;
    }
    if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ext       = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar_baru = uniqid('artikel_') . '.' . $ext;
    $dest      = __DIR__ . '/uploads_artikel/' . $gambar_baru;

    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $dest)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan gambar.']);
        exit;
    }

    if (file_exists(__DIR__ . '/uploads_artikel/' . $gambar)) {
        unlink(__DIR__ . '/uploads_artikel/' . $gambar);
    }
    $gambar = $gambar_baru;
}

$stmt = $koneksi->prepare(
    "UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?"
);
$stmt->bind_param('iisssi', $id_penulis, $id_kategori, $judul, $isi, $gambar, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui artikel: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();
