<?php
header('Content-Type: application/json');
require 'koneksi.php';

$judul       = trim($_POST['judul'] ?? '');
$id_penulis  = (int)($_POST['id_penulis'] ?? 0);
$id_kategori = (int)($_POST['id_kategori'] ?? 0);
$isi         = trim($_POST['isi'] ?? '');

if (!$judul || !$id_penulis || !$id_kategori || !$isi) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
    exit;
}

$gambar = 'default.png';

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

    $ext    = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar = uniqid('artikel_') . '.' . $ext;
    $dest   = __DIR__ . '/uploads_artikel/' . $gambar;

    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $dest)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan gambar.']);
        exit;
    }
}

date_default_timezone_set('Asia/Jakarta');
$hari   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan  = [
    1=>'Januari', 2=>'Februari', 3=>'Maret',
    4=>'April',   5=>'Mei',      6=>'Juni',
    7=>'Juli',    8=>'Agustus',  9=>'September',
    10=>'Oktober',11=>'November',12=>'Desember'
];
$now         = new DateTime();
$nama_hari   = $hari[$now->format('w')];
$tanggal     = $now->format('j');
$nama_bulan  = $bulan[(int)$now->format('n')];
$tahun       = $now->format('Y');
$jam         = $now->format('H:i');
$hari_tanggal = "$nama_hari, $tanggal $nama_bulan $tahun | $jam";

$stmt = $koneksi->prepare(
    "INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('iissss', $id_penulis, $id_kategori, $judul, $isi, $gambar, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil ditambahkan.']);
} else {
    if (isset($dest) && file_exists($dest)) unlink($dest);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan artikel: ' . $stmt->error]);
}

$stmt->close();
$koneksi->close();