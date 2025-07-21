<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::paginate(10);
        return response()->json($outlets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $outlet = Outlet::create($request->all());
        
        return response()->json([
            'message' => 'Outlet created successfully',
            'outlet' => $outlet
        ], 201);
    }

    public function show(Outlet $outlet)
    {
        return response()->json($outlet);
    }

    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $outlet->update($request->all());
        
        return response()->json([
            'message' => 'Outlet updated successfully',
            'outlet' => $outlet
        ]);
    }

    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        
        return response()->json([
            'message' => 'Outlet deleted successfully'
        ]);
    }
}