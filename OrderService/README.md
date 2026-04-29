# OrderService - UTS Enterprise Application Integration

Modul OrderService untuk tugas UTS Integrasi Aplikasi Enterprise.

## Peran Service
- **Provider**: Menyediakan API untuk membuat order dan mengambil histori order.
- **Consumer**: Memanggil `UserService` (port 8001) dan `ProductService` (port 8002) untuk validasi.

## Setup
1. Pastikan database MySQL menyala.
2. Jalankan perintah berikut di folder `OrderService`:
   ```bash
   composer install
   php artisan migrate --seed
   ```
3. Jalankan server:
   ```bash
   php artisan serve --port=8003
   ```

## API Endpoints
- `POST /api/orders` - Membuat order baru.
- `GET /api/orders?user_id={id}` - Mengambil histori order user.
- `GET /api/orders/{id}` - Mengambil detail order.

## Testing via cURL
```bash
curl -X POST http://localhost:8003/api/orders \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"product_id":1,"qty":1}'
```
