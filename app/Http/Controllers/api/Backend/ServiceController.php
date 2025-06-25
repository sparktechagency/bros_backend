<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('serviceTimes:id,service_id,time')->get();
        return response()->json([
            'status'  => true,
            'message' => 'Service retreived successfully',
            'data'    => $services,
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
            'car_type' => 'required|string|max:50',
            'icon'     => 'required|mimes:png,jpg,jpeg|max:10240',
            'interior' => 'required',
            'exterior' => 'required',
            'both'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $service           = new Service();
        $service->car_type = $request->car_type;
        $service->interior = $request->interior;
        $service->exterior = $request->exterior;
        $service->both     = $request->both;
        if ($request->hasFile('icon')) {
            $image      = $request->file('icon');
            $final_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/services'), $final_name);
            $service->icon = $final_name;
        }
        $service->save();
        return response()->json([
            'status'  => true,
            'message' => 'Service added successfully',
            'data'    => $service,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $service = Service::with('serviceTimes:id,service_id,time')->findOrFail($id);
            return response()->json([
                'status'  => true,
                'message' => 'Single service retreived successfully',
                'data'    => $service,
            ]);
        } catch (Exception $e) {
            Log::error('Service show error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'service not found',
                'data'    => null,
            ]);
        }
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
            'interior' => 'sometimes|string|max:20',
            'exterior' => 'sometimes|string|max:20',
            'both'     => 'sometimes|string|max:20',
            'icon'     => 'sometimes|mimes:png,jpg,jpeg|max:10240',
            'car_type' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        try {
            $service = Service::findOrFail($id);
            if ($request->hasFile('icon')) {
                $photo_location     = public_path('uploads/services');
                $old_photo          = basename($service->icon);
                $old_photo_location = $photo_location . '/' . $old_photo;
                if (file_exists($old_photo_location)) {
                    unlink($old_photo_location);
                }

                $final_photo_name = time() . '.' . $request->icon->extension();
                $request->icon->move($photo_location, $final_photo_name);
                $service->icon = $final_photo_name;
            }
            $service->interior = $request->interior ?? $service->interior;
            $service->exterior = $request->exterior ?? $service->exterior;
            $service->both     = $request->both ?? $service->both;
            $service->car_type = $request->car_type ?? $service->car_type;
            $service->save();
            return response()->json([
                'status'  => true,
                'message' => 'Service updated successfully',
                'data'    => $service,
            ]);
        } catch (Exception $e) {
            Log::error('Service updated error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'service not found',
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
            $service            = Service::findOrFail($id);
            $photo_location     = public_path('uploads/services');
            $old_photo          = basename($service->icon);
            $old_photo_location = $photo_location . '/' . $old_photo;
            if (file_exists($old_photo_location)) {
                unlink($old_photo_location);
            }
            $service->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Service deleted successfully',
                'data'    => $service,
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
