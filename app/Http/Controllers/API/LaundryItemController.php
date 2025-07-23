<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaundryItemRequest;
use App\Http\Resources\LaundryItemResource;
use App\Models\LaundryItem;
use App\Models\Product;
use Illuminate\Http\Request;

class LaundryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = LaundryItem::with(['product', 'outlet', 'user']);
        
        // Filter by outlet for non-admin users
        if (!auth()->user()->hasRole('admin')) {
            $query->where('outlet_id', auth()->user()->branch_id);
        }
        
        $laundryItems = $query->paginate(10);
        return LaundryItemResource::collection($laundryItems);
    }

    public function store(StoreLaundryItemRequest $request)
    {
        $product = Product::findOrFail($request->service_id);
        $code = $this->generateLaundryCode();
        
        $laundryItem = LaundryItem::create([
            'code' => $code,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_id' => $request->service_id,
            'outlet_id' => auth()->user()->branch_id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'unit_price' => $product->price,
            'total_price' => $product->price * $request->quantity,
            'notes' => $request->notes,
        ]);
        
        return response()->json([
            'message' => 'Laundry item created successfully',
            'laundry_item' => new LaundryItemResource($laundryItem->load(['product', 'outlet', 'user']))
        ], 201);
    }

    public function show(LaundryItem $laundryItem)
    {
        return new LaundryItemResource($laundryItem->load(['product', 'outlet', 'user']));
    }

    public function checkStatus($code)
    {
        $laundryItem = LaundryItem::where('code', $code)->first();
        
        if (!$laundryItem) {
            return response()->json([
                'message' => 'Laundry item not found'
            ], 404);
        }
        
        return response()->json([
            'code' => $laundryItem->code,
            'customer_name' => $laundryItem->customer_name,
            'service' => $laundryItem->product->name,
            'notes' => $laundryItem->notes,
            'process_status' => $laundryItem->process_status,
            'payment_status' => $laundryItem->payment_status,
            'created_at' => $laundryItem->created_at,
        ]);
    }

    public function updateStatus(Request $request, LaundryItem $laundryItem)
    {
        $request->validate([
            'process_status' => 'required|in:antri,proses,selesai',
        ]);
        
        $laundryItem->update([
            'process_status' => $request->process_status
        ]);
        
        return response()->json([
            'message' => 'Status updated successfully',
            'laundry_item' => new LaundryItemResource($laundryItem->load(['product', 'outlet', 'user']))
        ]);
    }

    private function generateLaundryCode()
    {
        $date = now()->format('ymd');
        $lastItem = LaundryItem::whereDate('created_at', today())->latest()->first();
        $number = $lastItem ? (int)substr($lastItem->code, -4) + 1 : 1;
        
        return 'LND' . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}