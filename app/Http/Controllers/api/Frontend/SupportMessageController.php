<?php
namespace App\Http\Controllers\api\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\SupportMessageMail;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportMessageController extends Controller
{
    public function supportMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $support_message = SupportMessage::create([
            'full_name' => $request->full_name,
            'subject'   => $request->subject,
            'message'   => $request->message,
        ]);
        $admin = User::where('id', 1)->first();
        Mail::to($admin->email)->send(new SupportMessageMail($support_message));
        return response()->json([
            'status'  => true,
            'message' => 'Support message sent successfully.',
            'data'    => $support_message,
        ]);
    }
}
