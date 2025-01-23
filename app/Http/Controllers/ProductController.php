<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController
{
    public function create(Request $request)
    {
        [
            "code" => $code,
            "name" => $name,
            "category" => $category,
            "price" => $price
        ] = $request->validate([
            "code" => "required",
            "name" => "required",
            "category" => "required",
            "price" => "required|numeric"
        ]);

        $product = new Product();
        $product->code = $code;
        $product->name = $name;
        $product->category = $category;
        $product->price = $price;
        $product->save();

        return response()->json($product->toArray());
    }

    public function index(Request $request)
    {
        [
            "pageSize" => $pageSize,
            "page" => $page
        ] = array_merge(
            [
                "pageSize" => 100,
                "page" => 1
            ],
            $request->validate([
                "pageSize" => "numeric|min:1",
                "page" => "numeric|min:1"
            ])
        );

        $offset = ($page - 1) * $pageSize;

        $products = Product::take($pageSize)->skip($offset)->get();
        $count = Product::count();

        return response()->json([
            "data" => $products->toArray(),
            "pagination" => [
                "page" => (int) $page,
                "pageSize" => (int) $pageSize,
                "pageCount" => ceil($count / $pageSize),
                "total" => $count
            ]
        ]);
    }

    public function show(int $id)
    {
        $product = Product::where('products.id', $id)->firstOrFail();

        return response()->json($product->toArray());
    }

    public function update(Request $request, int $id)
    {
        $product = Product::where('products.id', $id)->firstOrFail();

        [
            "code" => $code,
            "name" => $name,
            "category" => $category,
            "price" => $price
        ] = $request->validate([
            "code" => "required",
            "name" => "required",
            "category" => "required",
            "price" => "required|numeric"
        ]);

        $product->code = $code;
        $product->name = $name;
        $product->category = $category;
        $product->price = $price;
        $product->save();

        return response()->json($product->toArray());
    }

    public function destroy(int $id)
    {
        $product = Product::where('products.id', $id)->firstOrFail();
        $product->delete();

        return response()->json($product->toArray());
    }
}
