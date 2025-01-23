<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")
    ->controller(AuthController::class)
    ->group(function () {
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
