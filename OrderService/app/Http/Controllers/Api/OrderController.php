<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    use ApiResponse;

    /**
     * PROVIDER: POST /api/orders
     * Membuat order baru (Single Product) dan memicu pengurangan stok
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1', 
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid input: " . $validator->errors()->first(), 400);
        }

        $userId = $request->user_id;
        $productId = $request->product_id;
        $qty = $request->quantity;

        // 2. CONSUMER: Validasi User ke UserService (Port 8001)
        try {
            $userUrl = config('services.user_service.base_url');
            $userResponse = Http::timeout(5)->get("{$userUrl}/api/users/{$userId}");

            if (!$userResponse->successful()) {
                return $this->errorResponse("User not found", 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse("UserService unreachable", 502);
        }

        // 3. CONSUMER: Validasi Product & Stok ke ProductService (Port 8002)
        try {
            $productUrl = config('services.product_service.base_url');
            $productResponse = Http::timeout(5)->get("{$productUrl}/api/products/{$productId}");

            if (!$productResponse->successful()) {
                return $this->errorResponse("Product not found", 404);
            }

            $productData = $productResponse->json()['data'];
            
            // Cek kecukupan stok sebelum lanjut
            if ($productData['stock'] < $qty) {
                return $this->errorResponse("Stok tidak mencukupi", 400);
            }

            $price = $productData['price'];
        } catch (Exception $e) {
            return $this->errorResponse("ProductService unreachable", 502);
        }

        // 4. Simpan ke Database & Kurangi Stok
        try {
            $totalPrice = $price * $qty;

            // Simpan data order
            $order = Order::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $qty,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Perintah ke ProductService untuk mengurangi stok secara real-time
            Http::timeout(5)->patch("{$productUrl}/api/products/{$productId}/reduce", [
                'quantity' => $qty
            ]);

            return $this->successResponse("Order created successfully", $order, 201);

        } catch (Exception $e) {
            // Memberikan pesan error asli jika terjadi kegagalan di sisi database atau koneksi patch
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process order',
                'error_asli' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PROVIDER: GET /api/orders
     * Mengambil daftar order, bisa difilter berdasarkan user_id
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->query('user_id');
        $query = Order::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $orders = $query->get();
        return $this->successResponse("Orders retrieved successfully", $orders);
    }

    /**
     * PROVIDER: GET /api/orders/{id}
     */
    public function show($id): JsonResponse
    {
        $order = Order::find($id);
        
        if (!$order) {
            return $this->errorResponse("Order not found", 404);
        }
        
        return $this->successResponse("Order retrieved successfully", $order);
    }
}