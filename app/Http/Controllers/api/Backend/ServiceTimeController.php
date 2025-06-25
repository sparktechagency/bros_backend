<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\ServiceTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'service_id' => 'required|exists:services,id',
            'time'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $service_time             = new ServiceTime();
        $service_time->service_id = $request->service_id;
        $service_time->time       = $request->time;
        $service_time->save();
        return response()->json([
            'status'  => true,
            'message' => 'Service time added successfully',
            'data'    => $service_time,
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
        $validator = Validator::make($request->all(), [
            'time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        try {
            $service_time       = ServiceTime::findOrFail($id);
            $service_time->time = $request->time;
            $service_time->save();
            return response()->json([
                'status'  => true,
                'message' => 'Service time updated successfully',
                'data'    => $service_time,
            ]);
        } catch (Exception $e) {
            Log::error('Service time updated error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'service time not found',
                'data'    => null,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service_time = ServiceTime::findOrFail($id);
            $service_time->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Service time deleted successfully',
                'data'    => $service_time,
            ]);
        } catch (Exception $e) {
            Log::error('service deleted error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'data not found',
                'data'    => null,
            ]);
        }
    }

}
