<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pemesanan Makanan Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
        }

        body { 
            background-color: var(--bg-color); 
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Card Menu Animasi & Efek Floating */
        .menu-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.15);
        }

        .price-tag {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .stock-badge {
            background-color: #e0e7ff;
            color: var(--primary-color);
            border-radius: 12px;
            padding: 5px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Form Card */
        .form-card {
            background: var(--card-bg);
            border-radius: 24px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            padding: 30px;
            animation: slideInRight 0.6s ease-out forwards;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.2);
        }

        /* Tombol Modern */
        .btn-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-modern:active {
            transform: translateY(1px);
        }

        /* Animasi Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Delay animasi beruntun untuk kartu */
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        /* Custom Alert */
        .alert-custom {
            border-radius: 14px;
            border: none;
            font-weight: 600;
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom mb-5">
    <div class="container d-flex justify-content-center">
        <span class="navbar-brand h1 mb-0 fs-3"> Order Service</span>
    </div>
</nav>

<div class="container">
    <div class="row g-5">
        <div class="col-lg-7">
            <h3 class="mb-4 fw-bold" style="color: var(--primary-color);">Menu Spesial Hari Ini</h3>
            <div class="row g-4" id="product-list">
                <div class="col-12 text-center text-muted">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p>Menyinkronkan data dari ProductService...</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="form-card sticky-top" style="top: 20px;">
                <h4 class="mb-4 fw-bold text-center">Detail Pesanan</h4>
                <form id="order-form">
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">ID Pelanggan (User ID)</label>
                        <input type="number" class="form-control" id="user_id" value="1" placeholder="Masukkan ID" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">Pilih Menu</label>
                        <select class="form-select" id="product_id" required>
                            <option value="">Tunggu sebentar...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">Jumlah Porsi</label>
                        <input type="number" class="form-control" id="quantity" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-modern w-100 mt-2" id="btn-order">🚀 Konfirmasi Pesanan</button>
                </form>

                <div id="alert-box" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const PRODUCT_API_URL = 'http://localhost:8002/api/products';
    const ORDER_API_URL = 'http://localhost:8003/api/orders';

    document.addEventListener('DOMContentLoaded', loadProducts);

    async function loadProducts() {
        try {
            const response = await fetch(PRODUCT_API_URL);
            const data = await response.json();
            
            const productList = document.getElementById('product-list');
            const productSelect = document.getElementById('product_id');
            
            productList.innerHTML = ''; 
            productSelect.innerHTML = '<option value="" disabled selected>-- Silakan Pilih Menu --</option>';

            if(data.data) {
                data.data.forEach((product, index) => {
                    // Penambahan class delay untuk efek muncul bergantian
                    let delayClass = `delay-${(index % 4) + 1}`;
                    
                    productList.innerHTML += `
                        <div class="col-md-6 mb-2">
                            <div class="card menu-card h-100 p-3 ${delayClass}" onclick="selectProduct(${product.id})">
                                <div class="card-body p-0">
                                    <h5 class="card-title fw-bold mb-2">${product.name}</h5>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="price-tag">Rp ${parseInt(product.price).toLocaleString('id-ID')}</span>
                                        <span class="stock-badge">Stok: ${product.stock}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    productSelect.innerHTML += `<option value="${product.id}">${product.name} - Rp ${parseInt(product.price).toLocaleString('id-ID')}</option>`;
                });
            }
        } catch (error) {
            console.error(error);
            document.getElementById('product-list').innerHTML = `
                <div class="alert alert-danger alert-custom w-100">
                    <i class="bi bi-exclamation-triangle"></i> Gagal terhubung ke ProductService (Port 8002).
                </div>`;
        }
    }

    // Fungsi klik kartu langsung pilih menu di dropdown
    function selectProduct(id) {
        document.getElementById('product_id').value = id;
        // Efek kedip sedikit di form
        const formCard = document.querySelector('.form-card');
        formCard.style.transform = 'scale(1.02)';
        setTimeout(() => formCard.style.transform = 'scale(1)', 200);
    }

    document.getElementById('order-form').addEventListener('submit', async function(e) {
        e.preventDefault(); 
        
        const btn = document.getElementById('btn-order');
        const alertBox = document.getElementById('alert-box');
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
        btn.disabled = true;
        alertBox.innerHTML = '';

        const payload = {
            user_id: document.getElementById('user_id').value,
            product_id: document.getElementById('product_id').value,
            quantity: document.getElementById('quantity').value
        };

        try {
            const response = await fetch(ORDER_API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if(response.ok) {
                alertBox.innerHTML = `
                    <div class="alert alert-success alert-custom text-center">
                        🎉 <strong>Sukses!</strong><br>
                        Pesanan berhasil dibuat.<br>
                        Total Tagihan: <span class="fw-bold text-success">Rp ${parseInt(result.data.total_price).toLocaleString('id-ID')}</span>
                    </div>`;
                loadProducts(); 
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger alert-custom">❌ ${result.message || 'Gagal membuat pesanan'}</div>`;
            }
        } catch (error) {
            alertBox.innerHTML = `<div class="alert alert-danger alert-custom">❌ Gagal menghubungi OrderService.</div>`;
        } finally {
            btn.innerHTML = '🚀 Konfirmasi Pesanan';
            btn.disabled = false;
        }
    });
</script>

</body>
</html>