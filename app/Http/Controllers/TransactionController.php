<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController
{
    public function create(Request $request)
    {
        [
            "receipt" => $receipt,
            "customer_id" => $customerId,
            "details" => $details
        ] = $request->validate([
            "receipt" => "required",
            "customer_id" => "required|numeric",
            "details" => "required|array",
            "details.*.product_id" => "required|numeric",
            "details.*.quantity" => "required|numeric"
        ]);

        $customer = Customer::where('customers.id', $customerId)->firstOrFail();

        $productIds = collect($details)->map(function ($item) {
            return $item["product_id"];
        })->toArray();
        $products = Product::whereIn('products.id', $productIds)->get();

        return DB::transaction(function () use ($products, $customer, $receipt, $details) {
            $transactionDetails = [];
            $total = 0;

            foreach ($details as $detail) {
                [
                    "product_id" => $productId,
                    "quantity" => $quantity
                ] = $detail;

                $product = $products->first(function ($value) use ($productId) {
                    return $value->id === (int) $productId;
                });
                if (!$product) continue;

                $subtotal = $quantity * $product->price;

                $transactionDetail = new TransactionDetail();
                $transactionDetail->product_id = $product->id;
                $transactionDetail->price = $product->price;
                $transactionDetail->quantity = $quantity;
                $transactionDetail->subtotal = $subtotal;
                $total = $total + $subtotal;

                $transactionDetails[] = $transactionDetail;
            }

            $transaction = new Transaction();
            $transaction->receipt = $receipt;
            $transaction->total = $total;

            $customer->transactions()->save($transaction);
            $transaction->details()->saveMany($transactionDetails);

            return response()->json(
                [
                    ...$transaction->toArray(),
                    "details" => collect($transactionDetails)->map(function ($item) {
                        return $item->toArray();
                    })
                ]
            );
        });
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

        $transactions = Transaction::with(["details.product", "customer"])
            ->take($pageSize)
            ->skip($offset)
            ->get();
        $count = Transaction::count();

        return response()->json([
            "data" => $transactions->toArray(),
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
        $transaction = Transaction::with(["details.product", "customer"])
            ->where('transactions.id', $id)
            ->firstOrFail();

        return response()->json($transaction->toArray());
    }

    public function update(Request $request, int $id)
    {
        $transaction = Transaction::with(["details.product", "customer"])
            ->where('transactions.id', $id)
            ->firstOrFail();

        [
            "receipt" => $receipt,
            "customer_id" => $customerId,
            "details" => $details
        ] = $request->validate([
            "receipt" => "required",
            "customer_id" => "required|numeric",
            "details" => "required|array",
            "details.*.id" => "numeric",
            "details.*.product_id" => "required|numeric",
            "details.*.quantity" => "required|numeric"
        ]);

        $customer = Customer::where('customers.id', $customerId)->firstOrFail();

        $detailCollection = collect($details);
        $productIds = $detailCollection->map(function ($item) {
            return $item["product_id"];
        })->toArray();
        $products = Product::whereIn('products.id', $productIds)->get();

        $modifiedIds = $detailCollection->filter(function ($item) {
            return isset($item["id"]);
        })->map(function ($item) {
            return (int) $item["id"];
        })->toArray();

        $deletedIds = $transaction->details->filter(function ($item) use ($modifiedIds) {
            return !in_array($item->id, $modifiedIds);
        })
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();

        return DB::transaction(function () use (
            $details,
            $products,
            $transaction,
            $deletedIds,
            $customer,
            $receipt,
        ) {
            $transactionDetails = [];
            $total = 0;

            foreach ($details as $detail) {
                [
                    "id" => $id,
                    "product_id" => $productId,
                    "quantity" => $quantity
                ] = array_merge(["id" => NULL], $detail);

                $product = $products->first(function ($value) use ($productId) {
                    return $value->id === (int) $productId;
                });
                if (!$product) continue;

                $subtotal = $quantity * $product->price;

                $transactionDetail = [
                    "transaction_id" => $transaction->id,
                    "product_id" => $product->id,
                    "price" => $product->price,
                    "quantity" => $quantity,
                    "subtotal" => $subtotal
                ];

                if ($id) {
                    $findModified = $transaction->details->first(function ($item) use ($id) {
                        return $item->id === $id;
                    });

                    if ($findModified) {
                        $transactionDetail["id"] = $findModified->id;
                    }
                }

                $total = $total + $subtotal;

                $transactionDetails[] = $transactionDetail;
            }

            TransactionDetail::upsert(
                $transactionDetails,
                uniqueBy: ["id"],
                update: ['product_id', 'price', 'quantity', 'subtotal']
            );

            TransactionDetail::whereIn('transaction_details.id', $deletedIds)->delete();

            $transaction->customer_id = $customer->id;
            $transaction->total = $total;
            $transaction->receipt = $receipt;
            $transaction->save();

            return response()->json($transaction->refresh()->toArray());
        });
    }

    public function destroy(int $id)
    {
        $transaction = Transaction::with(["details.product", "customer"])
            ->where('transactions.id', $id)
            ->firstOrFail();
        $transaction->delete();

        return response()->json($transaction->toArray());
    }
}
