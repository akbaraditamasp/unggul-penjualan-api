<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")
    ->controller(AuthController::class)
    ->group(function () {
        Route::get("/", 'checkToken')->middleware('auth:sanctum');
        Route::post("/", 'login');
        Route::delete("/", 'logout')->middleware('auth:sanctum');
    });

Route::prefix("product")
    ->controller(ProductController::class)
    ->middleware("auth:sanctum")
    ->group(function () {
        Route::delete("/{id}", "destroy");
        Route::put("/{id}", "update");
        Route::get("/{id}", "show");
        Route::post("/", "create");
        Route::get("/", "index");
    });

Route::prefix("customer")
    ->controller(CustomerController::class)
    ->middleware("auth:sanctum")
    ->group(function () {
        Route::delete("/{id}", "destroy");
        Route::put("/{id}", "update");
        Route::get("/{id}", "show");
        Route::post("/", "create");
        Route::get("/", "index");
    });

Route::prefix("transaction")
    ->controller(TransactionController::class)
    ->middleware("auth:sanctum")
    ->group(function () {
        Route::delete("/{id}", "destroy");
        Route::put("/{id}", "update");
        Route::get("/{id}", "show");
        Route::post("/", "create");
        Route::get("/", "index");
    });

Route::middleware("auth:sanctum")->get("/stats", function () {
    $customerCount = Customer::count();
    $productCount = Product::count();
    $transactionCount = Transaction::count();

    return response()->json([
        "customer" => (int) $customerCount,
        "product" => (int) $productCount,
        "transaction" => (int) $transactionCount,
    ]);
});
