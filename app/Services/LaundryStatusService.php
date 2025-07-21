<?php

namespace App\Services;

use App\Models\LaundryItem;

class LaundryStatusService
{
    public function updateStatus(LaundryItem $laundryItem, string $status): bool
    {
        $validStatuses = ['antri', 'proses', 'selesai'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        $laundryItem->update(['process_status' => $status]);
        
        // Send notification to customer (implement as needed)
        $this->sendStatusNotification($laundryItem, $status);
        
        return true;
    }
    
    private function sendStatusNotification(LaundryItem $laundryItem, string $status)
    {
        // Implement notification logic here
        // Could be SMS, WhatsApp, email, etc.
    }
    
    public function getStatusHistory(LaundryItem $laundryItem): array
    {
        // This could be implemented with a status_history table
        // For now, return basic info
        return [
            'current_status' => $laundryItem->process_status,
            'created_at' => $laundryItem->created_at,
            'updated_at' => $laundryItem->updated_at,
        ];
    }
}