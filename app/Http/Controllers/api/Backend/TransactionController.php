<?php

namespace App\Http\Controllers\api\Backend;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search   = $request->search;
        $bookings = Booking::with('user:id,name,email,photo')->latest('id');
        if ($request->filter) {
            $bookings = $bookings->where('service_id',$request->filter);
        }
        if ($request->search) {
            $bookings = $bookings->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
        $bookings = $bookings->paginate($request->per_page ?? 10);
        return response()->json([
            'status'  => true,
            'message' => 'Booking information retreived successfully',
            'data'    => $bookings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
