<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    protected $fillable = ['name', 'address', 'phone'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function laundryItems(): HasMany
    {
        return $this->hasMany(LaundryItem::class);
    }
}