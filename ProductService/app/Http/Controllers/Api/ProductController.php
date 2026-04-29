<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse; // Pastikan folder Traits sudah benar (App\Traits atau App\Http\Traits)
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // INI WAJIB ADA BIAR STOK BISA BERKURANG
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * PROVIDER: GET /api/products
     * Mengambil semua daftar produk/makanan
     */
    public function index(): JsonResponse
    {
        try {
            $products = Product::all();
            return $this->successResponse("Products retrieved successfully", $products);
        } catch (Exception $e) {
            return $this->errorResponse("Internal Server Error", 500);
        }
    }


    /**
     * PROVIDER: POST /api/products
     * Menambahkan produk baru ke database
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:1' // ID pemilik/penjual
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Gagal menambahkan produk: " . $validator->errors()->first(), 400);
        }

        try {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
                'user_id' => $request->user_id
            ]);

            return $this->successResponse("Produk berhasil ditambahkan!", $product, 201);
        } catch (Exception $e) {
            return $this->errorResponse("Internal Server Error", 500);
        }
    }
    /**
     * PROVIDER: GET /api/products/{id}
     */
    public function show($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid Product ID", 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse("Product not found", 404);
        }

        return $this->successResponse("Product retrieved successfully", $product);
    }

    /**
     * PROVIDER: PUT /api/products/{id}
     * Mengupdate data produk
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->errorResponse("Produk tidak ditemukan", 404);
        }

        try {
            // Update data yang dikirim saja
            $product->update($request->all());
            return $this->successResponse("Produk berhasil diupdate!", $product);
        } catch (Exception $e) {
            return $this->errorResponse("Gagal mengupdate produk", 500);
        }
    }

    /**
     * PROVIDER: DELETE /api/products/{id}
     * Menghapus data produk
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->errorResponse("Produk tidak ditemukan", 404);
        }

        try {
            $product->delete();
            return $this->successResponse("Produk berhasil dihapus!", null);
        } catch (Exception $e) {
            return $this->errorResponse("Gagal menghapus produk", 500);
        }
    }
    /**
     * PROVIDER: PATCH /api/products/{id}/reduce
     * Fungsi untuk mengurangi stok secara otomatis
     */
    public function reduceStock(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse("Product not found", 404);
        }

        // Proses pengurangan stok
        $qty = $request->quantity;
        $product->stock = $product->stock - $qty;
        $product->save();

        return $this->successResponse("Stok berhasil dikurangi", [
            'product_name' => $product->name,
            'remaining_stock' => $product->stock
        ]);
    }

    /**
     * CONSUMER: GET /api/products/{id}/owner
     * Memanggil UserService untuk mendapatkan data pemilik produk
     */
    public function showWithOwner($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid Product ID", 400);
        }

        $product = Product::find($id);
        if (!$product) {
            return $this->errorResponse("Product not found", 404);
        }

        try {
            $baseUrl = config('services.user_service.base_url');
            $response = Http::timeout(5)->get("{$baseUrl}/api/users/{$product->user_id}");

            if ($response->successful()) {
                $userData = $response->json()['data'] ?? null;
                $result = $product->toArray();
                $result['owner'] = $userData;

                return $this->successResponse("Product and owner details retrieved successfully", $result);
            } else {
                return $this->errorResponse("Failed to fetch owner data from UserService", 502);
            }
        } catch (Exception $e) {
            return $this->errorResponse("UserService unreachable", 502);
        }
    }
}
