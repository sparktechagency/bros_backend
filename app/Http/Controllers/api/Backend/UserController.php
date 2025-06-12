<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $users    = User::where('role', 'USER')->latest('id');
        if ($request->search) {
            $users->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('car_brand', 'LIKE', '%' . $request->search . '%');
            });
        }
        $users = $users->paginate($per_page);
        return response()->json([
            'status'  => true,
            'message' => 'Users information retreived successfully',
            'data'    => $users,
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
        $user = User::with('carPhotos')->withCount('bookings')->where('id', $id)->first();
        return response()->json([
            'status'  => true,
            'message' => 'User information retreived successfully',
            'data'    => $user,
        ]);
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
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status'  => true,
                'message' => 'User delete successfully',
                'data'    => $user,
            ]);
        } catch (Exception $e) {
            $user = User::findOrFail($id);
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
                'data'    => null,
            ]);
        }
    }
}
