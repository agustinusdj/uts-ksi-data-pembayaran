<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    /**
     * Display a listing of the payment gateways.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', PaymentGateway::class);
        
        $gateways = PaymentGateway::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $gateways
        ]);
    }

    /**
     * Store a newly created payment gateway.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', PaymentGateway::class);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_gateways',
            'description' => 'nullable|string|max:500',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'config_json' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $gateway = PaymentGateway::create([
            'name' => $request->name,
            'description' => $request->description,
            'fee_percentage' => $request->fee_percentage,
            'is_active' => $request->is_active ?? true,
            'config_json' => $request->config_json,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment gateway created successfully',
            'data' => $gateway
        ], 201);
    }

    /**
     * Display the specified payment gateway.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        
        $this->authorize('view', $gateway);
        
        return response()->json([
            'status' => 'success',
            'data' => $gateway
        ]);
    }

    /**
     * Update the specified payment gateway.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        
        $this->authorize('update', $gateway);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_gateways,name,' . $id,
            'description' => 'nullable|string|max:500',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'config_json' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $gateway->update([
            'name' => $request->name,
            'description' => $request->description,
            'fee_percentage' => $request->fee_percentage,
            'is_active' => $request->is_active ?? $gateway->is_active,
            'config_json' => $request->config_json ?? $gateway->config_json,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment gateway updated successfully',
            'data' => $gateway
        ]);
    }

    /**
     * Remove the specified payment gateway.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        
        $this->authorize('delete', $gateway);
        
        $gateway->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment gateway deleted successfully'
        ]);
    }
}
