<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Only return active payment methods to regular users
        $methods = PaymentMethod::where('is_active', true)->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $methods
        ]);
    }

    /**
     * Store a newly created payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', PaymentMethod::class);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_methods',
            'type' => 'required|string|in:virtual_account,e_wallet,credit_card,qris,bank_transfer',
            'icon' => 'nullable|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $method = PaymentMethod::create([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment method created successfully',
            'data' => $method
        ], 201);
    }

    /**
     * Display the specified payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $method = PaymentMethod::findOrFail($id);
        
        // For non-active methods, check user has permission
        if (!$method->is_active) {
            $this->authorize('view', $method);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $method
        ]);
    }

    /**
     * Update the specified payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        
        $this->authorize('update', $method);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $id,
            'type' => 'required|string|in:virtual_account,e_wallet,credit_card,qris,bank_transfer',
            'icon' => 'nullable|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gt:min_amount',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $method->update([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon ?? $method->icon,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'is_active' => $request->is_active ?? $method->is_active,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment method updated successfully',
            'data' => $method
        ]);
    }

    /**
     * Remove the specified payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);
        
        $this->authorize('delete', $method);
        
        $method->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment method deleted successfully'
        ]);
    }
}
