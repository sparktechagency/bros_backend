<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Notifications\NewFeedbackNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $feedbacks = Feedback::with('user:id,name,photo')->latest('id')->paginate($request->per_page ?? 10);
        return response()->json([
            'status'  => true,
            'message' => 'Feedback retreived successfully',
            'data'    => $feedbacks,
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
            'service_id' => 'required|numeric|exists:services,id',
            'rating'     => 'required|numeric|max:5|min:1',
            'comment'    => 'required|string|max:4000',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $feedback = Feedback::create([
            'user_id'    => Auth::user()->id,
            'service_id' => $request->service_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        $feedback_id = $feedback->id;
        $admin       = User::where('id', 1)->first();
        $admin->notify(new NewFeedbackNotification($feedback_id));
        return response()->json([
            'status'  => true,
            'message' => 'Feedback saved successfully',
            'data'    => $feedback,
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
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();
            return response()->json([
                'status'  => true,
                'message' => 'Feedback deleted successfully',
                'data'    => $feedback,
            ]);
        } catch (Exception $e) {
            Log::error('Feedback delete error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'feedback not found',
                'data'    => null,
            ]);
        }
    }

    public function feedbackHighlight($id)
    {
        try {
            $feedback               = Feedback::findOrFail($id);
            $feedback->is_highlight = $feedback->is_highlight == 1 ? 0 : 1;
            $feedback->save();
            return response()->json([
                'status'  => true,
                'message' => 'Feedback status change successfully',
                'data'    => $feedback,
            ]);
        } catch (Exception $e) {
            Log::error('Feedback status change error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'feedback not found',
                'data'    => null,
            ]);
        }

    }
}
