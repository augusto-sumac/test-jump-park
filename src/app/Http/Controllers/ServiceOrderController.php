<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceOrderRequest;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Validator;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
    }

    public function store(StoreServiceOrderRequest $request)
    {
        $validatedData = $request->validated();

        $serviceOrder = ServiceOrder::create($validatedData);

        return response()->json($serviceOrder, 201);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
