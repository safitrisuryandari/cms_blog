<?php
header('Content-Type: application/json');
require 'koneksi.php';

$id            = (int)($_POST['id'] ?? 0);
$nama_depan    = trim($_POST['nama_depan'] ?? '');
$nama_belakang = trim($_POST['nama_belakang'] ?? '');
$user_name     = trim($_POST['user_name'] ?? '');
$password      = trim($_POST['password'] ?? '');

if (!$id || !$nama_depan || !$nama_belakang || !$user_name) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit;
}

$stmt = $koneksi->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$lama = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$lama) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    exit;
}

$foto = $lama['foto'];

if (!empty($_FILES['foto']['name'])) {
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($_FILES['foto']['tmp_name']);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($mime, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan.']);
        exit;
    }
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'Ukuran file maksimal 2 MB.']);
        exit;
    }

    $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_baru = uniqid('penulis_') . '.' . $ext;
    $dest     = __DIR__ . '/uploads_penulis/' . $foto_baru;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan foto.']);
        exit;
    }

    if ($foto !== 'default.png' && file_exists(__DIR__ . '/uploads_penulis/' . $foto)) {
        unlink(__DIR__ . '/uploads_penulis/' . $foto);
    }
    $foto = $foto_baru;
}

if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('sssssi', $nama_depan, $nama_belakang, $user_name, $hash, $foto, $id);
} else {
    $stmt = $koneksi->prepare(
        "UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?"
    );
    $stmt->bind_param('ssssi', $nama_depan, $nama_belakang, $user_name, $foto, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Data penulis berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();
