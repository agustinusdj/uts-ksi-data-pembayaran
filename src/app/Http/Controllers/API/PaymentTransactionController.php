<?php

namespace App\Http\Controllers\API;

use App\Helpers\EncryptionHelper;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', PaymentTransaction::class);
        
        $transactions = PaymentTransaction::with(['paymentGateway', 'paymentMethod'])
            ->latest()
            ->paginate(20);
            
        // Process sensitive data for the response
        $transactions->getCollection()->transform(function ($transaction) {
            $transaction->customer_email = EncryptionHelper::mask($transaction->customer_email);
            return $transaction;
        });
        
        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }
    
    /**
     * Store a newly created transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', PaymentTransaction::class);
        
        $validator = Validator::make($request->all(), [
            'payment_gateway_id' => 'required|exists:payment_gateways,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:1000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Verify amount is within allowed range for the payment method
        $method = PaymentMethod::findOrFail($request->payment_method_id);
        if ($request->amount < $method->min_amount || $request->amount > $method->max_amount) {
            return response()->json([
                'status' => 'error',
                'message' => "Amount must be between {$method->min_amount} and {$method->max_amount} for this payment method"
            ], 422);
        }
        
        // Calculate fee
        $gateway = PaymentGateway::findOrFail($request->payment_gateway_id);
        $feeAmount = $request->amount * ($gateway->fee_percentage / 100);
        
        // Create transaction with encrypted sensitive data
        $transaction = PaymentTransaction::create([
            'payment_gateway_id' => $request->payment_gateway_id,
            'payment_method_id' => $request->payment_method_id,
            'transaction_code' => 'TRX-' . strtoupper(uniqid()),
            'amount' => $request->amount,
            'fee_amount' => $feeAmount,
            'status' => 'pending',
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email, // Will be encrypted via model mutator
            'description' => $request->description,
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ], 201);
    }
    
    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = PaymentTransaction::with(['paymentGateway', 'paymentMethod'])->findOrFail($id);
        
        $this->authorize('view', $transaction);
        
        // Mask sensitive data for response
        $transaction->customer_email = EncryptionHelper::mask($transaction->customer_email);
        
        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }
    
    /**
     * Update the specified transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $transaction = PaymentTransaction::findOrFail($id);
        
        $this->authorize('update', $transaction);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,success,failed,cancelled,expired',
            'processed_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $transaction->update([
            'status' => $request->status,
            'processed_at' => $request->processed_at ?? now(),
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction updated successfully',
            'data' => $transaction
        ]);
    }
    
    /**
     * Remove the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $transaction = PaymentTransaction::findOrFail($id);
        
        $this->authorize('delete', $transaction);
        
        $transaction->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction deleted successfully'
        ]);
    }
}
