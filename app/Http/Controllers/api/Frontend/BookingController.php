<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewAppoinmentNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role == 'ADMIN') {
            $search   = $request->search;
            $bookings = Booking::with('user:id,name,email,photo,car_brand,car_model', 'user.carPhotos:id,user_id,photo')->latest('id');
            if ($request->filter) {
                $bookings = $bookings->where('service_id', $request->filter);
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
        } else {
            $bookings = Booking::where('user_id', Auth::user()->id)->latest('id')->paginate($request->per_page ?? 10);
            return response()->json([
                'status'  => true,
                'message' => 'Booking information retreived successfully',
                'data'    => $bookings,
            ]);

        }
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
        $user  = Auth::user();
        $rules = [
            'service_id'               => 'required|numeric|exists:services,id',
            'service_name'             => 'required|string|max:100',
            'service_type'             => 'required|string|max:100',
            'booking_date'             => 'required|date',
            'booking_time'             => 'required',
            'stripe_payment_intent_id' => 'required',
            'price'                    => 'required',
            'booking_note'             => 'sometimes|string|max:6000',
            'full_name'                => 'required|string|max:100',
            'phone'                    => 'required|string|max:20',
            'email'                    => 'required|email|max:200',
        ];
        if ($user->car_brand == null || $user->car_model == null) {
            $rules['car_brand'] = 'required|string|max:100';
            $rules['car_model'] = 'required|string|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors(),
            ]);
        }
        if ($user->car_brand == null || $user->car_model == null) {
            $user->car_brand = $request->car_brand;
            $user->car_model = $request->car_model;
            $user->save();
        }

        $booking = Booking::create([
            'user_id'                  => $user->id,
            'stripe_payment_intent_id' => $request->stripe_payment_intent_id,
            'service_id'               => $request->service_id,
            'service_name'             => $request->service_name,
            'service_type'             => $request->service_type,
            'booking_date'             => $request->booking_date,
            'booking_time'             => $request->booking_time,
            'price'                    => $request->price,
            'booking_note'             => $request->booking_note ?? null,
            'full_name'                => $request->full_name,
            'phone'                    => $request->phone ?? null,
            'email'                    => $request->email ?? null,
        ]);
        $appointment_id = $booking->id;
        $admin          = User::where('id', 1)->first();
        $admin->notify(new NewAppoinmentNotification($appointment_id));
        return response()->json([
            'status'  => true,
            'message' => 'Booking information saved successfully',
            'data'    => $booking,
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
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Booking deleted successfully',
                'data'    => $booking,
            ]);
        } catch (Exception $e) {
            Log::error('Booking delete error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'booking not found',
                'data'    => null,
            ]);
        }
    }

    public function bookingStatus($id)
    {
        try {
            $booking         = Booking::findOrFail($id);
            $booking->status = 'Completed';
            $booking->save();
            return response()->json([
                'status'  => true,
                'message' => 'Booking status change successfully',
                'data'    => $booking,
            ]);
        } catch (Exception $e) {
            Log::error('Booking status update error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'booking not found',
                'data'    => null,
            ]);
        }
    }
}
