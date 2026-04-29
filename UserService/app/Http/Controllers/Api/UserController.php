<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * PROVIDER: GET /api/users
     * Mengambil semua daftar user
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();
            return $this->successResponse("Users retrieved successfully", $users);
        } catch (Exception $e) {
            return $this->errorResponse("Internal Server Error", 500);
        }
    }

    /**
     * PROVIDER: GET /api/users/{id}
     */
    public function show($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid User ID", 400);
        }

        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse("User not found", 404);
        }

        return $this->successResponse("User found", $user);
    }

    /**
     * PROVIDER: POST /api/users
     * Menambahkan user/pelanggan baru ke database
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi password diganti jadi phone
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15' // Maksimal 15 digit angka
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Gagal menambahkan user: " . $validator->errors()->first(), 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone // Disimpan sebagai phone, tanpa bcrypt
            ]);

            return $this->successResponse("User berhasil ditambahkan!", $user, 201);
        } catch (Exception $e) {
            // Ditambahkan getMessage() biar kalau error ketahuan penyebabnya
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error_asli' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PROVIDER: PUT /api/users/{id}
     * Mengupdate data user/pelanggan
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse("Pelanggan tidak ditemukan", 404);
        }

        try {
            $user->update($request->all());
            return $this->successResponse("Pelanggan berhasil diupdate!", $user);
        } catch (Exception $e) {
            return $this->errorResponse("Gagal mengupdate pelanggan", 500);
        }
    }

    /**
     * PROVIDER: DELETE /api/users/{id}
     * Menghapus data user/pelanggan
     */
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse("Pelanggan tidak ditemukan", 404);
        }

        try {
            $user->delete();
            return $this->successResponse("Pelanggan berhasil dihapus!", null);
        } catch (Exception $e) {
            return $this->errorResponse("Gagal menghapus pelanggan", 500);
        }
    }
    /**
     * CONSUMER: GET /api/users/{id}/orders
     */
    public function showWithOrders($id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Invalid User ID", 400);
        }

        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse("User not found", 404);
        }

        try {
            $baseUrl = config('services.order_service.base_url');
            $response = Http::timeout(5)->get("{$baseUrl}/api/orders", [
                'user_id' => $id
            ]);

            if ($response->successful()) {
                $orderData = $response->json()['data'] ?? [];

                $result = $user->toArray();
                $result['orders'] = $orderData;

                return $this->successResponse("User and orders retrieved successfully", $result);
            } else {
                return $this->errorResponse("Failed to fetch data from OrderService", 502);
            }
        } catch (Exception $e) {
            return $this->errorResponse("OrderService unreachable", 502);
        }
    }
}
