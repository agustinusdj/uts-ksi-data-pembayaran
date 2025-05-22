<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentAnalyticController extends Controller
{
    /**
     * Display a listing of the payment analytics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', PaymentAnalytic::class);
        
        $analytics = PaymentAnalytic::latest()->paginate(20);
        
        return response()->json([
            'status' => 'success',
            'data' => $analytics
        ]);
    }

    /**
     * Store a newly created payment analytic record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', PaymentAnalytic::class);
        
        $validator = Validator::make($request->all(), [
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'total_transactions' => 'required|integer|min:0',
            'total_volume' => 'required|numeric|min:0',
            'success_rate' => 'required|numeric|min:0|max:100',
            'average_processing_time' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $analytic = PaymentAnalytic::create([
            'payment_gateway_id' => $request->payment_gateway_id,
            'payment_method_id' => $request->payment_method_id,
            'date' => $request->date,
            'total_transactions' => $request->total_transactions,
            'total_volume' => $request->total_volume,
            'success_rate' => $request->success_rate,
            'average_processing_time' => $request->average_processing_time,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment analytic created successfully',
            'data' => $analytic
        ], 201);
    }

    /**
     * Display the specified payment analytic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $analytic = PaymentAnalytic::with(['paymentGateway', 'paymentMethod'])->findOrFail($id);
        
        $this->authorize('view', $analytic);
        
        return response()->json([
            'status' => 'success',
            'data' => $analytic
        ]);
    }

    /**
     * Update the specified payment analytic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $analytic = PaymentAnalytic::findOrFail($id);
        
        $this->authorize('update', $analytic);
        
        $validator = Validator::make($request->all(), [
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'total_transactions' => 'required|integer|min:0',
            'total_volume' => 'required|numeric|min:0',
            'success_rate' => 'required|numeric|min:0|max:100',
            'average_processing_time' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $analytic->update([
            'payment_gateway_id' => $request->payment_gateway_id,
            'payment_method_id' => $request->payment_method_id,
            'date' => $request->date,
            'total_transactions' => $request->total_transactions,
            'total_volume' => $request->total_volume,
            'success_rate' => $request->success_rate,
            'average_processing_time' => $request->average_processing_time,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment analytic updated successfully',
            'data' => $analytic
        ]);
    }

    /**
     * Remove the specified payment analytic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $analytic = PaymentAnalytic::findOrFail($id);
        
        $this->authorize('delete', $analytic);
        
        $analytic->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment analytic deleted successfully'
        ]);
    }
}
