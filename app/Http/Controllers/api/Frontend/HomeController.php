<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Feedback;

class HomeController extends Controller
{
    public function home()
    {
        $maxRating = Feedback::max('rating');

        $latest_comment = Feedback::with('user:id,name,photo')
            ->where('rating', $maxRating)
            ->select('id','user_id','rating')
            ->latest('id')
            ->first();

        $happy_clients = Booking::distinct('user_id')->count();

        $latest_images=Booking::with('user:id,photo')->latest('id')->select('id','user_id')->take(4)->get();

        $data = [
            'latest_comment' => $latest_comment,
            'happy_clients'  => $happy_clients,
            'latest_images'  => $latest_images,
        ];
        return response()->json([
            'status'  => true,
            'message' => 'Data retreived successfully',
            'data'    => $data,
        ]);
    }

    public function feedback(){
        $highlighted_feedbacks=Feedback::with('user:id,name,photo')->where('is_highlight',1)->latest('id')->take(3)->get();
        return response()->json([
            'status'  => true,
            'message' => 'Feedback retreived successfully(Web)',
            'data'    => $highlighted_feedbacks,
        ]);
    }
}
