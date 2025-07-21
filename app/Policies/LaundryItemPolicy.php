<?php

namespace App\Policies;

use App\Models\LaundryItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaundryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['admin', 'petugas']);
    }

    public function view(User $user, LaundryItem $laundryItem): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        
        return $user->branch_id === $laundryItem->outlet_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('petugas');
    }

    public function update(User $user, LaundryItem $laundryItem): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        
        return $user->hasRole('petugas') && $user->branch_id === $laundryItem->outlet_id;
    }

    public function delete(User $user, LaundryItem $laundryItem): bool
    {
        return $user->hasRole('admin');
    }
}