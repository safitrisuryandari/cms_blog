<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Manajemen Blog (CMS)</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --primary:   #3b82f6;
    --primary-d: #2563eb;
    --danger:    #ef4444;
    --danger-d:  #dc2626;
    --success:   #22c55e;
    --bg:        #f1f5f9;
    --sidebar:   #1e293b;
    --sidebar-t: #94a3b8;
    --card:      #ffffff;
    --border:    #e2e8f0;
    --text:      #0f172a;
    --muted:     #64748b;
    --header:    #ffffff;
    --radius:    8px;
    --shadow:    0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
  }

  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }

  .app-header {
    background: var(--header);
    border-bottom: 1px solid var(--border);
    padding: 0 24px;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: var(--shadow);
    position: sticky; top: 0; z-index: 100;
  }
  .app-header .logo-icon { font-size: 20px; }
  .app-header .app-title { font-size: 15px; font-weight: 700; color: var(--text); }
  .app-header .app-sub   { font-size: 11px; color: var(--muted); margin-top: 1px; }

  .layout { display: flex; flex: 1; }

  .sidebar {
    width: 220px; min-height: calc(100vh - 56px);
    background: var(--sidebar);
    padding: 20px 0;
    flex-shrink: 0;
  }
  .sidebar-label {
    font-size: 10px; font-weight: 600; letter-spacing: .08em;
    color: var(--sidebar-t); padding: 0 20px 10px;
    text-transform: uppercase;
  }
  .nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; cursor: pointer;
    color: var(--sidebar-t); font-size: 13.5px; font-weight: 500;
    border-left: 3px solid transparent;
    transition: all .18s;
  }
  .nav-item:hover { background: rgba(255,255,255,.06); color: #fff; }
  .nav-item.active { background: rgba(59,130,246,.18); color: #fff; border-left-color: var(--primary); }
  .nav-item .nav-icon { font-size: 16px; width: 20px; text-align: center; }

  .content { flex: 1; padding: 28px; overflow-x: auto; }

  .section-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 20px;
  }
  .section-title { font-size: 17px; font-weight: 700; }

  .btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border: none; border-radius: var(--radius);
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: all .15s; font-family: inherit;
  }
  .btn-primary  { background: var(--primary);  color: #fff; }
  .btn-primary:hover  { background: var(--primary-d); }
  .btn-danger   { background: var(--danger);   color: #fff; }
  .btn-danger:hover   { background: var(--danger-d); }
  .btn-secondary { background: #f1f5f9; color: var(--text); border: 1px solid var(--border); }
  .btn-secondary:hover { background: #e2e8f0; }
  .btn-sm { padding: 5px 12px; font-size: 12px; }
  .btn-edit  { background: var(--primary); color: #fff; }
  .btn-edit:hover { background: var(--primary-d); }
  .btn-hapus { background: var(--danger);  color: #fff; }
  .btn-hapus:hover { background: var(--danger-d); }

  .table-wrap { background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead th {
    background: #f8fafc; text-align: left;
    padding: 12px 16px; font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--muted); border-bottom: 1px solid var(--border);
  }
  tbody tr { border-bottom: 1px solid var(--border); }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #f8fafc; }
  tbody td { padding: 12px 16px; font-size: 13.5px; vertical-align: middle; }
  .action-btns { display: flex; gap: 6px; }
  .foto-thumb { width: 44px; height: 44px; border-radius: 6px; object-fit: cover; background: #e2e8f0; }
  .badge {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: 11.5px; font-weight: 600;
    background: #dbeafe; color: #1d4ed8;
  }
  .pw-mask { color: var(--muted); font-size: 13px; letter-spacing: 2px; }
  .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; }

  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 200;
    align-items: center; justify-content: center;
    padding: 20px;
  }
  .modal-overlay.open { display: flex; }

  .modal {
    background: var(--card); border-radius: 12px;
    padding: 28px; width: 100%; max-width: 480px;
    box-shadow: 0 20px 60px rgba(0,0,0,.25);
    animation: pop .2s ease;
  }
  @keyframes pop { from { transform: scale(.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }

  .modal-title { font-size: 17px; font-weight: 700; margin-bottom: 20px; }

  .form-row { display: flex; gap: 14px; }
  .form-row .form-group { flex: 1; }
  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px; color: var(--text); }
  .form-control {
    width: 100%; padding: 9px 12px; border: 1px solid var(--border);
    border-radius: var(--radius); font-size: 13.5px; font-family: inherit;
    color: var(--text); background: #fff;
    transition: border-color .15s, box-shadow .15s;
    outline: none;
  }
  .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
  textarea.form-control { resize: vertical; min-height: 100px; }
  select.form-control { cursor: pointer; }
  .file-label {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 14px; border: 1px solid var(--border);
    border-radius: var(--radius); font-size: 13px; cursor: pointer;
    background: #f8fafc; color: var(--text); font-family: inherit;
    transition: background .15s;
  }
  .file-label:hover { background: #e2e8f0; }
  input[type="file"] { display: none; }
  .file-name { font-size: 12.5px; color: var(--muted); margin-left: 4px; }

  .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 8px; }

  .confirm-modal { text-align: center; max-width: 360px; }
  .confirm-icon { font-size: 40px; margin-bottom: 12px; }
  .confirm-title { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
  .confirm-text  { font-size: 13px; color: var(--muted); margin-bottom: 20px; }

  #toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 999;
    padding: 12px 20px; border-radius: var(--radius);
    font-size: 13.5px; font-weight: 600; color: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,.2);
    opacity: 0; transform: translateY(10px);
    transition: all .3s;
    pointer-events: none;
  }
  #toast.show { opacity: 1; transform: translateY(0); }
  #toast.success { background: var(--success); }
  #toast.error   { background: var(--danger); }

  .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>

<header class="app-header">
  <span class="logo-icon">📰</span>
  <div>
    <div class="app-title">Sistem Manajemen Blog (CMS)</div>
    <div class="app-sub">Blog Keren</div>
  </div>
</header>

<div class="layout">

  <nav class="sidebar">
    <div class="sidebar-label">Menu Utama</div>
    <div class="nav-item active" data-menu="penulis">
      <span class="nav-icon">👤</span> Kelola Penulis
    </div>
    <div class="nav-item" data-menu="artikel">
      <span class="nav-icon">📄</span> Kelola Artikel
    </div>
    <div class="nav-item" data-menu="kategori">
      <span class="nav-icon">🗂️</span> Kelola Kategori
    </div>
  </nav>

  <main class="content" id="mainContent">
  </main>
</div>

<div id="toast"></div>

<div class="modal-overlay" id="modalTambahPenulis">
  <div class="modal">
    <div class="modal-title">Tambah Penulis</div>
    <div class="form-row">
      <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" class="form-control" id="tp_nama_depan" placeholder="Ahmad">
      </div>
      <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" class="form-control" id="tp_nama_belakang" placeholder="Fauzi">
      </div>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" id="tp_username" placeholder="ahmad_f">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" id="tp_password" placeholder="••••••••••••••">
    </div>
    <div class="form-group">
      <label>Foto Profil</label><br>
      <label class="file-label" for="tp_foto">
        📁 Choose File
        <input type="file" id="tp_foto" accept="image/*" onchange="showFileName(this,'tp_foto_name')">
      </label>
      <span class="file-name" id="tp_foto_name">No file chosen</span>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalTambahPenulis')">Batal</button>
      <button class="btn btn-primary" id="btnSimpanPenulis" onclick="simpanPenulis()">Simpan Data</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalEditPenulis">
  <div class="modal">
    <div class="modal-title">Edit Penulis</div>
    <input type="hidden" id="ep_id">
    <div class="form-row">
      <div class="form-group">
        <label>Nama Depan</label>
        <input type="text" class="form-control" id="ep_nama_depan">
      </div>
      <div class="form-group">
        <label>Nama Belakang</label>
        <input type="text" class="form-control" id="ep_nama_belakang">
      </div>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" id="ep_username">
    </div>
    <div class="form-group">
      <label>Password Baru <small style="color:var(--muted)">(kosongkan jika tidak diganti)</small></label>
      <input type="password" class="form-control" id="ep_password" placeholder="••••••••••••••">
    </div>
    <div class="form-group">
      <label>Foto Profil <small style="color:var(--muted)">(kosongkan jika tidak diganti)</small></label><br>
      <label class="file-label" for="ep_foto">
        📁 Choose File
        <input type="file" id="ep_foto" accept="image/*" onchange="showFileName(this,'ep_foto_name')">
      </label>
      <span class="file-name" id="ep_foto_name">No file chosen</span>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalEditPenulis')">Batal</button>
      <button class="btn btn-primary" id="btnUpdatePenulis" onclick="updatePenulis()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalTambahArtikel">
  <div class="modal" style="max-width:520px">
    <div class="modal-title">Tambah Artikel</div>
    <div class="form-group">
      <label>Judul</label>
      <input type="text" class="form-control" id="ta_judul" placeholder="Judul artikel...">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Penulis</label>
        <select class="form-control" id="ta_penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <select class="form-control" id="ta_kategori"></select>
      </div>
    </div>
    <div class="form-group">
      <label>Isi Artikel</label>
      <textarea class="form-control" id="ta_isi" placeholder="Tulis isi artikel di sini..."></textarea>
    </div>
    <div class="form-group">
      <label>Gambar</label><br>
      <label class="file-label" for="ta_gambar">
        📁 Choose File
        <input type="file" id="ta_gambar" accept="image/*" onchange="showFileName(this,'ta_gambar_name')">
      </label>
      <span class="file-name" id="ta_gambar_name">No file chosen</span>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalTambahArtikel')">Batal</button>
      <button class="btn btn-primary" id="btnSimpanArtikel" onclick="simpanArtikel()">Simpan Data</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalEditArtikel">
  <div class="modal" style="max-width:520px">
    <div class="modal-title">Edit Artikel</div>
    <input type="hidden" id="ea_id">
    <div class="form-group">
      <label>Judul</label>
      <input type="text" class="form-control" id="ea_judul">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Penulis</label>
        <select class="form-control" id="ea_penulis"></select>
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <select class="form-control" id="ea_kategori"></select>
      </div>
    </div>
    <div class="form-group">
      <label>Isi Artikel</label>
      <textarea class="form-control" id="ea_isi"></textarea>
    </div>
    <div class="form-group">
      <label>Gambar <small style="color:var(--muted)">(kosongkan jika tidak diganti)</small></label><br>
      <label class="file-label" for="ea_gambar">
        📁 Choose File
        <input type="file" id="ea_gambar" accept="image/*" onchange="showFileName(this,'ea_gambar_name')">
      </label>
      <span class="file-name" id="ea_gambar_name">No file chosen</span>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalEditArtikel')">Batal</button>
      <button class="btn btn-primary" id="btnUpdateArtikel" onclick="updateArtikel()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalTambahKategori">
  <div class="modal">
    <div class="modal-title">Tambah Kategori</div>
    <div class="form-group">
      <label>Nama Kategori</label>
      <input type="text" class="form-control" id="tk_nama" placeholder="Nama kategori...">
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" id="tk_ket" placeholder="Deskripsi kategori..."></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalTambahKategori')">Batal</button>
      <button class="btn btn-primary" id="btnSimpanKategori" onclick="simpanKategori()">Simpan Data</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalEditKategori">
  <div class="modal">
    <div class="modal-title">Edit Kategori</div>
    <input type="hidden" id="ek_id">
    <div class="form-group">
      <label>Nama Kategori</label>
      <input type="text" class="form-control" id="ek_nama">
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" id="ek_ket"></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalEditKategori')">Batal</button>
      <button class="btn btn-primary" id="btnUpdateKategori" onclick="updateKategori()">Simpan Perubahan</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalHapus">
  <div class="modal confirm-modal">
    <div class="confirm-icon">🗑️</div>
    <div class="confirm-title">Hapus data ini?</div>
    <div class="confirm-text">Data yang dihapus tidak dapat dikembalikan.</div>
    <div style="display:flex;gap:10px;justify-content:center">
      <button class="btn btn-secondary" onclick="closeModal('modalHapus')">Batal</button>
      <button class="btn btn-danger" id="btnKonfirmasiHapus">Ya, Hapus</button>
    </div>
  </div>
</div>

<script>
let activeMenu = 'penulis';
let deleteCallback = null;

/* ── INIT ── */
document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', () => {
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    item.classList.add('active');
    activeMenu = item.dataset.menu;
    renderSection(activeMenu);
  });
});

window.addEventListener('DOMContentLoaded', () => renderSection('penulis'));

function renderSection(menu) {
  const c = document.getElementById('mainContent');
  if (menu === 'penulis')  loadPenulis(c);
  if (menu === 'artikel')  loadArtikel(c);
  if (menu === 'kategori') loadKategori(c);
}

function loadPenulis(c) {
  c.innerHTML = `
    <div class="section-header">
      <div class="section-title">Data Penulis</div>
      <button class="btn btn-primary" onclick="openModal('modalTambahPenulis'); resetFormPenulis()">+ Tambah Penulis</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Foto</th><th>Nama</th><th>Username</th><th>Password</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tbodyPenulis">
          <tr><td colspan="5" class="empty-state">Memuat data…</td></tr>
        </tbody>
      </table>
    </div>`;
  fetchPenulis();
}

function fetchPenulis() {
  fetch('ambil_penulis.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbodyPenulis');
      if (!res.data || !res.data.length) {
        tbody.innerHTML = '<tr><td colspan="5" class="empty-state">Belum ada data penulis.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(p => `
        <tr>
          <td><img class="foto-thumb" src="uploads_penulis/${esc(p.foto)}" alt="" onerror="this.src='uploads_penulis/default.png'"></td>
          <td>${esc(p.nama_depan)} ${esc(p.nama_belakang)}</td>
          <td>${esc(p.user_name)}</td>
          <td class="pw-mask">${p.password.substring(0,18)}…</td>
          <td>
            <div class="action-btns">
              <button class="btn btn-sm btn-edit"  onclick="editPenulis(${p.id})">Edit</button>
              <button class="btn btn-sm btn-hapus" onclick="konfirmasiHapus(()=>hapusPenulis(${p.id}))">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    });
}

function resetFormPenulis() {
  ['tp_nama_depan','tp_nama_belakang','tp_username','tp_password'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('tp_foto').value = '';
  document.getElementById('tp_foto_name').textContent = 'No file chosen';
}

function simpanPenulis() {
  const btn = document.getElementById('btnSimpanPenulis');
  const fd = new FormData();
  fd.append('nama_depan',    document.getElementById('tp_nama_depan').value.trim());
  fd.append('nama_belakang', document.getElementById('tp_nama_belakang').value.trim());
  fd.append('user_name',     document.getElementById('tp_username').value.trim());
  fd.append('password',      document.getElementById('tp_password').value.trim());
  const foto = document.getElementById('tp_foto').files[0];
  if (foto) fd.append('foto', foto);

  setBtnLoading(btn, true);
  fetch('simpan_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalTambahPenulis'); fetchPenulis(); }
    });
}

function editPenulis(id) {
  fetch('ambil_satu_penulis.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'success') return showToast(res.message, 'error');
      const p = res.data;
      document.getElementById('ep_id').value          = p.id;
      document.getElementById('ep_nama_depan').value  = p.nama_depan;
      document.getElementById('ep_nama_belakang').value = p.nama_belakang;
      document.getElementById('ep_username').value    = p.user_name;
      document.getElementById('ep_password').value    = '';
      document.getElementById('ep_foto').value        = '';
      document.getElementById('ep_foto_name').textContent = 'No file chosen';
      openModal('modalEditPenulis');
    });
}

function updatePenulis() {
  const btn = document.getElementById('btnUpdatePenulis');
  const fd = new FormData();
  fd.append('id',            document.getElementById('ep_id').value);
  fd.append('nama_depan',    document.getElementById('ep_nama_depan').value.trim());
  fd.append('nama_belakang', document.getElementById('ep_nama_belakang').value.trim());
  fd.append('user_name',     document.getElementById('ep_username').value.trim());
  fd.append('password',      document.getElementById('ep_password').value.trim());
  const foto = document.getElementById('ep_foto').files[0];
  if (foto) fd.append('foto', foto);

  setBtnLoading(btn, true);
  fetch('update_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalEditPenulis'); fetchPenulis(); }
    });
}

function hapusPenulis(id) {
  const fd = new FormData(); fd.append('id', id);
  fetch('hapus_penulis.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => { showToast(res.message, res.status); if (res.status === 'success') fetchPenulis(); });
}

function loadArtikel(c) {
  c.innerHTML = `
    <div class="section-header">
      <div class="section-title">Data Artikel</div>
      <button class="btn btn-primary" onclick="bukaModalTambahArtikel()">+ Tambah Artikel</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tbodyArtikel">
          <tr><td colspan="6" class="empty-state">Memuat data…</td></tr>
        </tbody>
      </table>
    </div>`;
  fetchArtikel();
}

function fetchArtikel() {
  fetch('ambil_artikel.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbodyArtikel');
      if (!res.data || !res.data.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="empty-state">Belum ada data artikel.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(a => `
        <tr>
          <td><img class="foto-thumb" src="uploads_artikel/${esc(a.gambar)}" alt="" onerror="this.style.background='#e2e8f0'"></td>
          <td>${esc(a.judul)}</td>
          <td><span class="badge">${esc(a.nama_kategori)}</span></td>
          <td>${esc(a.nama_penulis)}</td>
          <td style="font-size:12px;color:var(--muted)">${esc(a.hari_tanggal)}</td>
          <td>
            <div class="action-btns">
              <button class="btn btn-sm btn-edit"  onclick="editArtikel(${a.id})">Edit</button>
              <button class="btn btn-sm btn-hapus" onclick="konfirmasiHapus(()=>hapusArtikel(${a.id}))">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    });
}

async function bukaModalTambahArtikel() {
  await populateDropdowns('ta_penulis', 'ta_kategori');
  document.getElementById('ta_judul').value = '';
  document.getElementById('ta_isi').value   = '';
  document.getElementById('ta_gambar').value = '';
  document.getElementById('ta_gambar_name').textContent = 'No file chosen';
  openModal('modalTambahArtikel');
}

function simpanArtikel() {
  const btn = document.getElementById('btnSimpanArtikel');
  const fd = new FormData();
  fd.append('judul',       document.getElementById('ta_judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ta_penulis').value);
  fd.append('id_kategori', document.getElementById('ta_kategori').value);
  fd.append('isi',         document.getElementById('ta_isi').value.trim());
  const g = document.getElementById('ta_gambar').files[0];
  if (g) fd.append('gambar', g);

  setBtnLoading(btn, true);
  fetch('simpan_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalTambahArtikel'); fetchArtikel(); }
    });
}

async function editArtikel(id) {
  const res = await fetch('ambil_satu_artikel.php?id=' + id).then(r => r.json());
  if (res.status !== 'success') return showToast(res.message, 'error');
  const a = res.data;
  await populateDropdowns('ea_penulis', 'ea_kategori', a.id_penulis, a.id_kategori);
  document.getElementById('ea_id').value    = a.id;
  document.getElementById('ea_judul').value = a.judul;
  document.getElementById('ea_isi').value   = a.isi;
  document.getElementById('ea_gambar').value = '';
  document.getElementById('ea_gambar_name').textContent = 'No file chosen';
  openModal('modalEditArtikel');
}

function updateArtikel() {
  const btn = document.getElementById('btnUpdateArtikel');
  const fd = new FormData();
  fd.append('id',          document.getElementById('ea_id').value);
  fd.append('judul',       document.getElementById('ea_judul').value.trim());
  fd.append('id_penulis',  document.getElementById('ea_penulis').value);
  fd.append('id_kategori', document.getElementById('ea_kategori').value);
  fd.append('isi',         document.getElementById('ea_isi').value.trim());
  const g = document.getElementById('ea_gambar').files[0];
  if (g) fd.append('gambar', g);

  setBtnLoading(btn, true);
  fetch('update_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalEditArtikel'); fetchArtikel(); }
    });
}

function hapusArtikel(id) {
  const fd = new FormData(); fd.append('id', id);
  fetch('hapus_artikel.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => { showToast(res.message, res.status); if (res.status === 'success') fetchArtikel(); });
}

function loadKategori(c) {
  c.innerHTML = `
    <div class="section-header">
      <div class="section-title">Data Kategori Artikel</div>
      <button class="btn btn-primary" onclick="openModal('modalTambahKategori'); resetFormKategori()">+ Tambah Kategori</button>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Nama Kategori</th><th>Keterangan</th><th>Aksi</th></tr>
        </thead>
        <tbody id="tbodyKategori">
          <tr><td colspan="3" class="empty-state">Memuat data…</td></tr>
        </tbody>
      </table>
    </div>`;
  fetchKategori();
}

function fetchKategori() {
  fetch('ambil_kategori.php')
    .then(r => r.json())
    .then(res => {
      const tbody = document.getElementById('tbodyKategori');
      if (!res.data || !res.data.length) {
        tbody.innerHTML = '<tr><td colspan="3" class="empty-state">Belum ada data kategori.</td></tr>';
        return;
      }
      tbody.innerHTML = res.data.map(k => `
        <tr>
          <td><span class="badge">${esc(k.nama_kategori)}</span></td>
          <td style="color:var(--muted)">${esc(k.keterangan)}</td>
          <td>
            <div class="action-btns">
              <button class="btn btn-sm btn-edit"  onclick="editKategori(${k.id})">Edit</button>
              <button class="btn btn-sm btn-hapus" onclick="konfirmasiHapus(()=>hapusKategori(${k.id}))">Hapus</button>
            </div>
          </td>
        </tr>`).join('');
    });
}

function resetFormKategori() {
  document.getElementById('tk_nama').value = '';
  document.getElementById('tk_ket').value  = '';
}

function simpanKategori() {
  const btn = document.getElementById('btnSimpanKategori');
  const fd = new FormData();
  fd.append('nama_kategori', document.getElementById('tk_nama').value.trim());
  fd.append('keterangan',    document.getElementById('tk_ket').value.trim());

  setBtnLoading(btn, true);
  fetch('simpan_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalTambahKategori'); fetchKategori(); }
    });
}

function editKategori(id) {
  fetch('ambil_satu_kategori.php?id=' + id)
    .then(r => r.json())
    .then(res => {
      if (res.status !== 'success') return showToast(res.message, 'error');
      const k = res.data;
      document.getElementById('ek_id').value   = k.id;
      document.getElementById('ek_nama').value = k.nama_kategori;
      document.getElementById('ek_ket').value  = k.keterangan;
      openModal('modalEditKategori');
    });
}

function updateKategori() {
  const btn = document.getElementById('btnUpdateKategori');
  const fd = new FormData();
  fd.append('id',            document.getElementById('ek_id').value);
  fd.append('nama_kategori', document.getElementById('ek_nama').value.trim());
  fd.append('keterangan',    document.getElementById('ek_ket').value.trim());

  setBtnLoading(btn, true);
  fetch('update_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      setBtnLoading(btn, false);
      showToast(res.message, res.status);
      if (res.status === 'success') { closeModal('modalEditKategori'); fetchKategori(); }
    });
}

function hapusKategori(id) {
  const fd = new FormData(); fd.append('id', id);
  fetch('hapus_kategori.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => { showToast(res.message, res.status); if (res.status === 'success') fetchKategori(); });
}

async function populateDropdowns(selPenulisId, selKategoriId, selPenulis, selKategori) {
  const [rP, rK] = await Promise.all([
    fetch('ambil_penulis.php').then(r => r.json()),
    fetch('ambil_kategori.php').then(r => r.json())
  ]);
  const spEl = document.getElementById(selPenulisId);
  const skEl = document.getElementById(selKategoriId);

  spEl.innerHTML = (rP.data || []).map(p =>
    `<option value="${p.id}" ${p.id == selPenulis ? 'selected' : ''}>${esc(p.nama_depan)} ${esc(p.nama_belakang)}</option>`
  ).join('');

  skEl.innerHTML = (rK.data || []).map(k =>
    `<option value="${k.id}" ${k.id == selKategori ? 'selected' : ''}>${esc(k.nama_kategori)}</option>`
  ).join('');
}

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
});

function konfirmasiHapus(cb) {
  deleteCallback = cb;
  openModal('modalHapus');
}
document.getElementById('btnKonfirmasiHapus').addEventListener('click', () => {
  closeModal('modalHapus');
  if (deleteCallback) { deleteCallback(); deleteCallback = null; }
});

function showFileName(input, nameId) {
  document.getElementById(nameId).textContent = input.files[0]?.name || 'No file chosen';
}

function esc(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showToast(msg, type) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'show ' + (type === 'success' ? 'success' : 'error');
  setTimeout(() => t.className = '', 3000);
}

function setBtnLoading(btn, loading) {
  if (loading) {
    btn.dataset.orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner"></span> Menyimpan…';
    btn.disabled = true;
  } else {
    btn.innerHTML = btn.dataset.orig;
    btn.disabled = false;
  }
}
</script>
</body>
</html>
