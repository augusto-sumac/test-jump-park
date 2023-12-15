<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceOrder;
use App\Http\Requests\StoreServiceOrderRequest;
use App\Http\Requests\UpdateServiceOrderRequest;
use Validator;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $vehiclePlateFilter = $request->input('vehiclePlateFilter', '');

        //Query with Query Builder
        /*$serviceOrders = DB::table('service_orders')
            ->select('service_orders.*', 'users.name as userName')
            ->leftJoin('users', 'service_orders.userId', '=', 'users.id')
            ->where('service_orders.status', true)
            ->when($vehiclePlateFilter, function ($query) use ($vehiclePlateFilter) {
                return $query->where('service_orders.vehiclePlate', 'like', '%' . $vehiclePlateFilter . '%');
            })
            ->paginate($perPage);*/

        //Query with Full Eloquent
        $serviceOrders = ServiceOrder::with('user')
        ->where('status', true)
        ->when($vehiclePlateFilter, function ($query) use ($vehiclePlateFilter) {
            return $query->where('vehiclePlate', 'like', '%' . $vehiclePlateFilter . '%');
        })
        ->paginate($perPage);

        return response()->json($serviceOrders);
    }

    public function store(StoreServiceOrderRequest $request)
    {
        $validatedData = $request->validated();

        $serviceOrder = ServiceOrder::create($validatedData);

        return response()->json($serviceOrder, 201);
    }

    public function update(UpdateServiceOrderRequest $request, $id)
    {
        $serviceOrder = ServiceOrder::findOrFail($id);

        $serviceOrder->update($request->validated());

        return response()->json($serviceOrder, 200);
    }

    public function destroy($id)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 500);
            }

            $serviceOrder = ServiceOrder::findOrFail($id);

            // Desativa o registro, em vez de excluí-lo
            $serviceOrder->update(['active' => false]);

            return response()->json(['message' => 'Service order successfully deactivated'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to deactivate service order'], 500);
        }
    }

}
