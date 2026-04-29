<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EAI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root { --primary-color: #4f46e5; --secondary-color: #818cf8; --bg-color: #f3f4f6; --card-bg: #ffffff; --text-main: #1f2937; }
        body { background-color: var(--bg-color); font-family: 'Poppins', sans-serif; color: var(--text-main); }
        .navbar-custom { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 15px 0; }
        .navbar-brand { font-weight: 700; letter-spacing: 0.5px; }
        .card-custom { background: var(--card-bg); border-radius: 24px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .table th { background-color: transparent; font-weight: 600; color: #6b7280; border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; }
        .table td { vertical-align: middle; padding: 15px 8px; border-bottom: 1px solid #f3f4f6; }
        .nav-pills .nav-link { color: #4b5563; font-weight: 600; border-radius: 14px; padding: 12px 20px; transition: all 0.3s ease; }
        .nav-pills .nav-link:hover:not(.active) { background-color: #e0e7ff; color: var(--primary-color); }
        .nav-pills .nav-link.active { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); box-shadow: 0 8px 15px rgba(79, 70, 229, 0.25); }
        .btn-modern { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; border-radius: 12px; color: white; font-weight: 600; padding: 10px 20px; transition: all 0.3s ease; }
        .btn-modern:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); color: white;}
        .btn-action-edit { background-color: #e0e7ff; color: var(--primary-color); border: none; border-radius: 10px; transition: 0.3s; padding: 6px 12px; }
        .btn-action-edit:hover { background-color: var(--primary-color); color: white; }
        .btn-action-delete { background-color: #fee2e2; color: #ef4444; border: none; border-radius: 10px; transition: 0.3s; padding: 6px 12px; }
        .btn-action-delete:hover { background-color: #ef4444; color: white; }
        .section-title { color: var(--primary-color); font-weight: 700; }
        .modal-content { border-radius: 20px; border: none; }
        .modal-header-custom { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-top-left-radius: 20px; border-top-right-radius: 20px; }
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #e5e7eb; }
        .form-control:focus { border-color: var(--secondary-color); box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.2); }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom mb-5">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="navbar-brand h1 mb-0 fs-4"><i class="bi bi-speedometer2 me-2"></i> Admin Panel</span>
        <a href="/" class="btn btn-light btn-sm fw-bold" style="border-radius: 10px; color: var(--primary-color);"><i class="bi bi-shop me-1"></i> Ke Halaman Toko</a>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card card-custom p-3 sticky-top" style="top: 20px;">
                <ul class="nav nav-pills flex-column" id="adminTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active w-100 text-start mb-2" data-bs-toggle="pill" data-bs-target="#produk-tab" type="button"><i class="bi bi-box-seam me-2"></i> Kelola Produk</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link w-100 text-start" data-bs-toggle="pill" data-bs-target="#user-tab" type="button"><i class="bi bi-people me-2"></i> Kelola Pelanggan</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="produk-tab">
                    <div class="card card-custom p-4 px-5">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <h4 class="section-title mb-0">Database Produk <span class="badge bg-light text-secondary fs-6 border">Port 8002</span></h4>
                            <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Menu
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover border-white">
                                <thead><tr><th>ID</th><th>Nama Menu</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
                                <tbody id="table-produk"><tr><td colspan="5" class="text-center text-muted py-4">Memuat data...</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="user-tab">
                    <div class="card card-custom p-4 px-5">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <h4 class="section-title mb-0">Database Pelanggan <span class="badge bg-light text-secondary fs-6 border">Port 8001</span></h4>
                            <button class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                                <i class="bi bi-person-plus me-1"></i> Tambah Pelanggan
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover border-white">
                                <thead><tr><th>ID</th><th>Nama Pelanggan</th><th>Email</th><th>No. HP</th><th>Aksi</th></tr></thead>
                                <tbody id="table-user"><tr><td colspan="5" class="text-center text-muted py-4">Memuat data...</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahProduk" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header modal-header-custom border-0"><h5 class="modal-title fw-bold">✨ Tambah Menu Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form id="form-tambah-produk">
          <div class="modal-body p-4">
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Nama Menu</label><input type="text" id="p_name" class="form-control" required></div>
              <div class="row g-3">
                  <div class="col-md-6 mb-2"><label class="form-label text-muted fw-semibold">Harga (Rp)</label><input type="number" id="p_price" class="form-control" required></div>
                  <div class="col-md-6 mb-2"><label class="form-label text-muted fw-semibold">Stok Awal</label><input type="number" id="p_stock" class="form-control" required></div>
              </div>
              <input type="hidden" id="p_user_id" value="1"> 
          </div>
          <div class="modal-footer border-0 pb-4 px-4"><button type="submit" class="btn btn-modern w-100" id="btn-save-p">Simpan Menu</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditProduk" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header modal-header-custom border-0"><h5 class="modal-title fw-bold">✏️ Edit Menu</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form id="form-edit-produk">
          <div class="modal-body p-4">
              <input type="hidden" id="edit_p_id">
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Nama Menu</label><input type="text" id="edit_p_name" class="form-control" required></div>
              <div class="row g-3">
                  <div class="col-md-6 mb-2"><label class="form-label text-muted fw-semibold">Harga (Rp)</label><input type="number" id="edit_p_price" class="form-control" required></div>
                  <div class="col-md-6 mb-2"><label class="form-label text-muted fw-semibold">Stok</label><input type="number" id="edit_p_stock" class="form-control" required></div>
              </div>
          </div>
          <div class="modal-footer border-0 pb-4 px-4"><button type="submit" class="btn btn-modern w-100" id="btn-update-p">Update Data Menu</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header modal-header-custom border-0"><h5 class="modal-title fw-bold">👤 Tambah Pelanggan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form id="form-tambah-user">
          <div class="modal-body p-4">
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Nama Lengkap</label><input type="text" id="u_name" class="form-control" required></div>
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Email Aktif</label><input type="email" id="u_email" class="form-control" required></div>
              <div class="mb-2"><label class="form-label text-muted fw-semibold">Nomor HP</label><input type="text" id="u_phone" class="form-control" required></div>
          </div>
          <div class="modal-footer border-0 pb-4 px-4"><button type="submit" class="btn btn-modern w-100" id="btn-save-u">Simpan Data Pelanggan</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header modal-header-custom border-0"><h5 class="modal-title fw-bold">✏️ Edit Pelanggan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <form id="form-edit-user">
          <div class="modal-body p-4">
              <input type="hidden" id="edit_u_id">
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Nama Lengkap</label><input type="text" id="edit_u_name" class="form-control" required></div>
              <div class="mb-4"><label class="form-label text-muted fw-semibold">Email Aktif</label><input type="email" id="edit_u_email" class="form-control" required></div>
              <div class="mb-2"><label class="form-label text-muted fw-semibold">Nomor HP</label><input type="text" id="edit_u_phone" class="form-control" required></div>
          </div>
          <div class="modal-footer border-0 pb-4 px-4"><button type="submit" class="btn btn-modern w-100" id="btn-update-u">Update Data Pelanggan</button></div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const API_PRODUK = 'http://localhost:8002/api/products';
    const API_USER = 'http://localhost:8001/api/users';

    document.addEventListener('DOMContentLoaded', () => { loadDataProduk(); loadDataUser(); });

    // --- READ ---
    async function loadDataProduk() {
        try {
            const res = await fetch(API_PRODUK); const data = await res.json(); let html = '';
            data.data.forEach(p => {
                html += `<tr><td class="text-muted fw-semibold">#${p.id}</td><td class="fw-bold text-dark">${p.name}</td><td class="text-primary fw-semibold">Rp ${parseInt(p.price).toLocaleString('id-ID')}</td><td><span class="badge" style="background:#e0e7ff; color:var(--primary-color); padding:8px 12px; border-radius:8px;">Stok: ${p.stock}</span></td><td><button class="btn-action-edit me-1" onclick="siapkanEditProduk(${p.id}, '${p.name}', ${p.price}, ${p.stock})"><i class="bi bi-pencil-fill"></i></button><button class="btn-action-delete" onclick="hapusProduk(${p.id})"><i class="bi bi-trash-fill"></i></button></td></tr>`;
            });
            document.getElementById('table-produk').innerHTML = html;
        } catch (e) {}
    }

    async function loadDataUser() {
        try {
            const res = await fetch(API_USER); const data = await res.json(); let html = '';
            data.data.forEach(u => {
                // TOMBOL EDIT & DELETE SEKARANG AKTIF DI SINI
                html += `<tr><td class="text-muted fw-semibold">#${u.id}</td><td class="fw-bold text-dark">${u.name}</td><td class="text-muted">${u.email}</td><td class="text-muted">${u.phone || '-'}</td><td><button class="btn-action-edit me-1" onclick="siapkanEditUser(${u.id}, '${u.name}', '${u.email}', '${u.phone}')"><i class="bi bi-pencil-fill"></i></button><button class="btn-action-delete" onclick="hapusUser(${u.id})"><i class="bi bi-trash-fill"></i></button></td></tr>`;
            });
            document.getElementById('table-user').innerHTML = html;
        } catch (e) {}
    }

    // --- CREATE PRODUK ---
    document.getElementById('form-tambah-produk').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { name: document.getElementById('p_name').value, price: document.getElementById('p_price').value, stock: document.getElementById('p_stock').value, user_id: document.getElementById('p_user_id').value };
        await fetch(API_PRODUK, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        bootstrap.Modal.getInstance(document.getElementById('modalTambahProduk')).hide(); Swal.fire('Berhasil!', '', 'success'); loadDataProduk(); 
    });

    // --- EDIT & DELETE PRODUK ---
    function siapkanEditProduk(id, name, price, stock) {
        document.getElementById('edit_p_id').value = id; document.getElementById('edit_p_name').value = name; document.getElementById('edit_p_price').value = price; document.getElementById('edit_p_stock').value = stock;
        new bootstrap.Modal(document.getElementById('modalEditProduk')).show();
    }
    document.getElementById('form-edit-produk').addEventListener('submit', async (e) => {
        e.preventDefault(); const id = document.getElementById('edit_p_id').value;
        const payload = { name: document.getElementById('edit_p_name').value, price: document.getElementById('edit_p_price').value, stock: document.getElementById('edit_p_stock').value };
        await fetch(`${API_PRODUK}/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        bootstrap.Modal.getInstance(document.getElementById('modalEditProduk')).hide(); Swal.fire('Diupdate!', '', 'success'); loadDataProduk(); 
    });
    async function hapusProduk(id) {
        if(confirm('Yakin hapus menu ini?')) { await fetch(`${API_PRODUK}/${id}`, { method: 'DELETE' }); Swal.fire('Terhapus!', '', 'success'); loadDataProduk(); }
    }

    // --- CREATE USER ---
    document.getElementById('form-tambah-user').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { name: document.getElementById('u_name').value, email: document.getElementById('u_email').value, phone: document.getElementById('u_phone').value };
        await fetch(API_USER, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        bootstrap.Modal.getInstance(document.getElementById('modalTambahUser')).hide(); Swal.fire('Berhasil!', '', 'success'); loadDataUser(); 
    });

    // --- EDIT & DELETE USER (FITUR BARU) ---
    function siapkanEditUser(id, name, email, phone) {
        document.getElementById('edit_u_id').value = id; document.getElementById('edit_u_name').value = name; document.getElementById('edit_u_email').value = email; document.getElementById('edit_u_phone').value = (phone !== 'undefined' && phone !== 'null') ? phone : '';
        new bootstrap.Modal(document.getElementById('modalEditUser')).show();
    }
    document.getElementById('form-edit-user').addEventListener('submit', async (e) => {
        e.preventDefault(); const id = document.getElementById('edit_u_id').value;
        const payload = { name: document.getElementById('edit_u_name').value, email: document.getElementById('edit_u_email').value, phone: document.getElementById('edit_u_phone').value };
        await fetch(`${API_USER}/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        bootstrap.Modal.getInstance(document.getElementById('modalEditUser')).hide(); Swal.fire('Diupdate!', 'Data pelanggan berhasil diubah.', 'success'); loadDataUser(); 
    });
    async function hapusUser(id) {
        if(confirm('Yakin hapus pelanggan ini?')) { await fetch(`${API_USER}/${id}`, { method: 'DELETE' }); Swal.fire('Terhapus!', 'Data pelanggan hilang.', 'success'); loadDataUser(); }
    }
</script>

</body>
</html>