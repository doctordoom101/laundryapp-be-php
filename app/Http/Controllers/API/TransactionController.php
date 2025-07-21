<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\LaundryItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        $laundryItem = LaundryItem::findOrFail($request->laundry_item_id);
        
        $transaction = Transaction::create([
            'laundry_item_id' => $request->laundry_item_id,
            'amount' => $request->amount,
            'unit_price' => $laundryItem->unit_price,
            'quantity' => $laundryItem->quantity,
            'method' => $request->method,
            'paid_at' => $request->paid_at,
        ]);
        
        // Update payment status
        $totalPaid = $laundryItem->transactions()->sum('amount');
        $paymentStatus = 'belum_bayar';
        
        if ($totalPaid >= $laundryItem->total_price) {
            $paymentStatus = 'lunas';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'dp';
        }
        
        $laundryItem->update(['payment_status' => $paymentStatus]);
        
        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction
        ], 201);
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['laundryItem.product', 'laundryItem.outlet']);
        
        // Filter by outlet for non-admin users
        if (!auth()->user()->hasRole('admin')) {
            $query->whereHas('laundryItem', function ($q) {
                $q->where('outlet_id', auth()->user()->branch_id);
            });
        }
        
        $transactions = $query->paginate(10);
        return response()->json($transactions);
    }
}