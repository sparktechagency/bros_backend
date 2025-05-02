<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhotoGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $photos = PhotoGallery::latest('id')->paginate($request->per_page ?? 10);
        return response()->json([
            'status'  => true,
            'message' => 'Photo retreived successfully',
            'data'    => $photos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $photo = new PhotoGallery();
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $final_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/photo_gallery'), $final_name);
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

            $photo = PhotoGallery::findOrFail($id);
            if ($request->hasFile('photo')) {
                $photo_location     = public_path('uploads/photo_gallery');
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
                'message' => 'Photo updated successfully',
                'data'    => $photo,
            ]);
        } catch (Exception $e) {
            Log::error('Photo updated error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'data not found',
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
            $photo              = PhotoGallery::findOrFail($id);
            $photo_location     = public_path('uploads/photo_gallery');
            $old_photo          = basename($photo->photo);
            $old_photo_location = $photo_location . '/' . $old_photo;
            if (file_exists($old_photo_location)) {
                unlink($old_photo_location);
            }
            $photo->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Photo deleted successfully',
                'data'    => $photo,
            ]);
        } catch (Exception $e) {
            Log::error('Photo deleted error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'data not found',
                'data'    => null,
            ]);
        }
    }
}
