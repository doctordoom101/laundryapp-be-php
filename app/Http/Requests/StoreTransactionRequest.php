<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'laundry_item_id' => 'required|exists:laundry_items,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,transfer,qris,lainnya',
            'paid_at' => 'required|date',
        ];
    }
}