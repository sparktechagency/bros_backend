<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\ManageDate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ManageDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dates = ManageDate::where('date', '>=', now())->latest('id')->paginate($request->per_page ?? 100);
        return response()->json([
            'status'  => true,
            'message' => 'Blocked Date retreived successfully.',
            'data'    => $dates,
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
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors(),
            ]);
        }
        $date = ManageDate::create(
            [
                'date' => $request->date,
            ]
        );
        return response()->json([
            'status'  => true,
            'message' => 'Date Blocked successfully',
            'data'    => $date,
        ]);
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
        try {
            $date = ManageDate::findOrFail($id);
            $date->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Date unblock successfully',
                'data'    => $date,
            ]);
        } catch (Exception $e) {
            Log::error('Date unblock error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'date not found',
                'data'    => null,
            ]);
        }
    }
}
