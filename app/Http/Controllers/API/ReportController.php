<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LaundryItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'required|in:daily,monthly,yearly',
            'date' => 'required|date',
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        $date = Carbon::parse($request->date);
        $query = LaundryItem::with(['product', 'outlet', 'transactions']);

        // Filter by outlet if provided
        if ($request->outlet_id) {
            $query->where('outlet_id', $request->outlet_id);
        }

        // Filter by date range based on type
        switch ($request->type) {
            case 'daily':
                $query->whereDate('created_at', $date);
                break;
            case 'monthly':
                $query->whereMonth('created_at', $date->month)
                      ->whereYear('created_at', $date->year);
                break;
            case 'yearly':
                $query->whereYear('created_at', $date->year);
                break;
        }

        $laundryItems = $query->get();
        
        // Calculate statistics
        $totalItems = $laundryItems->count();
        $totalRevenue = $laundryItems->sum('total_price');
        $totalPaid = $laundryItems->sum(function ($item) {
            return $item->transactions->sum('amount');
        });
        
        $statusCounts = $laundryItems->groupBy('process_status')->map->count();
        $paymentCounts = $laundryItems->groupBy('payment_status')->map->count();

        return response()->json([
            'period' => $request->type,
            'date' => $date->format('Y-m-d'),
            'statistics' => [
                'total_items' => $totalItems,
                'total_revenue' => $totalRevenue,
                'total_paid' => $totalPaid,
                'outstanding' => $totalRevenue - $totalPaid,
            ],
            'status_breakdown' => $statusCounts,
            'payment_breakdown' => $paymentCounts,
            'items' => $laundryItems->map(function ($item) {
                return [
                    'code' => $item->code,
                    'customer_name' => $item->customer_name,
                    'service' => $item->product->name,
                    'total_price' => $item->total_price,
                    'paid_amount' => $item->transactions->sum('amount'),
                    'process_status' => $item->process_status,
                    'payment_status' => $item->payment_status,
                    'created_at' => $item->created_at,
                ];
            }),
        ]);
    }
}