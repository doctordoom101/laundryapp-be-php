<?php

namespace App\Services;

use App\Models\LaundryItem;
use App\Models\Transaction;
use Carbon\Carbon;

class ReportService
{
    public function getDashboardStats($outletId = null, $dateRange = null)
    {
        $query = LaundryItem::query();
        
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }
        
        $laundryItems = $query->get();
        
        return [
            'total_items' => $laundryItems->count(),
            'total_revenue' => $laundryItems->sum('total_price'),
            'items_by_status' => $laundryItems->groupBy('process_status')->map->count(),
            'items_by_payment' => $laundryItems->groupBy('payment_status')->map->count(),
            'daily_items' => $this->getDailyItemsCount($outletId),
            'monthly_revenue' => $this->getMonthlyRevenue($outletId),
        ];
    }
    
    private function getDailyItemsCount($outletId = null)
    {
        $query = LaundryItem::whereDate('created_at', today());
        
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        return $query->count();
    }
    
    private function getMonthlyRevenue($outletId = null)
    {
        $query = LaundryItem::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year);
        
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        return $query->sum('total_price');
    }
    
    public function getTopServices($outletId = null, $limit = 5)
    {
        $query = LaundryItem::select('service_id', \DB::raw('COUNT(*) as total_orders'), \DB::raw('SUM(total_price) as total_revenue'))
                           ->with('product')
                           ->groupBy('service_id')
                           ->orderBy('total_orders', 'desc')
                           ->limit($limit);
        
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        return $query->get();
    }
}