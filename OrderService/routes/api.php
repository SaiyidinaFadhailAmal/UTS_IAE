<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OrderService API Routes (UTS - IAE)
|--------------------------------------------------------------------------
*/

/**
 * 1. PROVIDER: Create Order
 * Method: POST
 * URL: /api/orders
 * Body: {user_id, product_id, qty}
 */
Route::post('/orders', [OrderController::class, 'store']);

/**
 * 2. PROVIDER: Get Orders by User
 * Method: GET
 * URL: /api/orders?user_id={id}
 */
Route::get('/orders', [OrderController::class, 'index']);

/**
 * 3. PROVIDER: Get Order by ID
 * Method: GET
 * URL: /api/orders/{id}
 */
Route::get('/orders/{id}', [OrderController::class, 'show']);
