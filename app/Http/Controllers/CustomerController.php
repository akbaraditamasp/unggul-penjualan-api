<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController
{
    public function create(Request $request)
    {
        [
            "code" => $code,
            "name" => $name,
            "address" => $address,
            "gender" => $gender
        ] = $request->validate([
            "code" => "required",
            "name" => "required",
            "address" => "required",
            "gender" => [
                "required",
                Rule::in(["male", "female"])
            ]
        ]);

        $customer = new Customer();
        $customer->code = $code;
        $customer->name = $name;
        $customer->address = $address;
        $customer->gender = $gender;
        $customer->save();

        return response()->json($customer->toArray());
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

        $customers = Customer::take($pageSize)->skip($offset)->get();
        $count = Customer::count();

        return response()->json([
            "data" => $customers->toArray(),
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
        $customer = Customer::where('customers.id', $id)->firstOrFail();

        return response()->json($customer->toArray());
    }

    public function update(Request $request, int $id)
    {
        $customer = Customer::where('customers.id', $id)->firstOrFail();

        [
            "code" => $code,
            "name" => $name,
            "address" => $address,
            "gender" => $gender
        ] = $request->validate([
            "code" => "required",
            "name" => "required",
            "address" => "required",
            "gender" => [
                "required",
                Rule::in(["male", "female"])
            ]
        ]);

        $customer->code = $code;
        $customer->name = $name;
        $customer->address = $address;
        $customer->gender = $gender;
        $customer->save();

        return response()->json($customer->toArray());
    }

    public function destroy(int $id)
    {
        $customer = Customer::where('customers.id', $id)->firstOrFail();
        $customer->delete();

        return response()->json($customer->toArray());
    }
}
