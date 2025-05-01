<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CarImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CarImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // photo
    // user_id
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
            'photo' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $photo          = new CarImage();
        $photo->user_id = Auth::user()->id;
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $final_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/user_car_photo'), $final_name);
            $photo->photo = $final_name;
        }
        $photo->save();
        return response()->json([
            'status'  => true,
            'message' => 'Photo added successfully',
            'data'    => $photo,
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
            'photo' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        try {

            $photo = CarImage::findOrFail($id);
            if ($request->hasFile('photo')) {
                $photo_location     = public_path('uploads/user_car_photo');
                $old_photo          = basename($photo->photo);
                $old_photo_location = $photo_location . '/' . $old_photo;
                if (file_exists($old_photo_location)) {
                    unlink($old_photo_location);
                }

                $final_photo_name = time() . '.' . $request->photo->extension();
                $request->photo->move($photo_location, $final_photo_name);
                $photo->photo = $final_photo_name;
            }
            $photo->save();
            return response()->json([
                'status'  => true,
                'message' => 'Car photo updated successfully',
                'data'    => $photo,
            ]);
        } catch (Exception $e) {
            Log::error('Car photo update error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'date not found',
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
            $photo              = CarImage::findOrFail($id);
            $photo_location     = public_path('uploads/user_car_photo');
            $old_photo          = basename($photo->photo);
            $old_photo_location = $photo_location . '/' . $old_photo;
            if (file_exists($old_photo_location)) {
                unlink($old_photo_location);
            }

            $photo->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Car photo deleted successfully',
                'data'    => $photo,
            ]);
        } catch (Exception $e) {
            Log::error('Car photo delete error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'data not found',
                'data'    => null,
            ]);
        }
    }
}
