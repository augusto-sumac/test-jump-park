<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceOrder;
use App\Http\Requests\StoreServiceOrderRequest;
use App\Http\Requests\UpdateServiceOrderRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ServiceOrder",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="vehiclePlate", type="string"),
 *     @OA\Property(property="entryDateTime", type="string"),
 *     @OA\Property(property="exitDateTime", type="string"),
 *     @OA\Property(property="priceType", type="string"),
 *     @OA\Property(property="price", type="number"),
 *     @OA\Property(property="userId", type="integer"),
 *     @OA\Property(property="status", type="boolean")
 * )
 */

class ServiceOrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/service-orders",
     *     summary="List all service orders",
     *     description="List all service orders with pagination and optional vehicle plate filter.",
     *     tags={"Service Orders"},
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of items per page (default: 5)",
     *         required=false,
     *         @OA\Schema(type="integer", default=5)
     *     ),
     *     @OA\Parameter(
     *         name="vehiclePlateFilter",
     *         in="query",
     *         description="Filter by vehicle plate (optional)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of service orders",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ServiceOrder"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/service-orders",
     *     summary="Create a new service order",
     *     description="Create a new service order with the provided data.",
     *     tags={"Service Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceOrder")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceOrder")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(StoreServiceOrderRequest $request)
    {
        $validatedData = $request->validated();

        $serviceOrder = ServiceOrder::create($validatedData);

        return response()->json($serviceOrder, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/service-orders/{id}",
     *     summary="Update an existing service order",
     *     description="Update an existing service order with the provided data.",
     *     tags={"Service Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ServiceOrder")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service order updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceOrder")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service order not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(UpdateServiceOrderRequest $request, $id)
    {
        $serviceOrder = ServiceOrder::findOrFail($id);

        $serviceOrder->update($request->validated());

        return response()->json($serviceOrder, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/service-orders/{id}",
     *     summary="Deactivate an existing service order",
     *     description="Deactivate an existing service order by setting 'active' to false.",
     *     tags={"Service Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service order successfully deactivated"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service order not found"
     *     )
     * )
     */
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
