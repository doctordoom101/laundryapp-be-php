<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'outlet'])->paginate(10);
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        
        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user->load(['role', 'outlet']))
        ], 201);
    }

    public function show(User $user)
    {
        return new UserResource($user->load(['role', 'outlet']));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $user->update($request->validated());
        
        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user->load(['role', 'outlet']))
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}