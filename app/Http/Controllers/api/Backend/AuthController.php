<?php
namespace App\Http\Controllers\api\Backend;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Notifications\NewUserCreateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'car_brand' => 'sometimes|string|max:50',
            'car_model' => 'sometimes|string|max:50',
            'password'  => 'required|string|min:4|same:c_password',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $otp            = rand(100000, 999999);
        $otp_expires_at = now()->addMinutes(10);
        $user           = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'car_brand'      => $request->car_brand,
            'car_model'      => $request->car_model,
            'password'       => Hash::make($request->password),
            'otp'            => $otp,
            'otp_expires_at' => $otp_expires_at,
        ]);

        Mail::to($request->email)->send(new OtpMail($otp));
        // notification send
        $admin                = User::where('id', 1)->first();
        $existingNotification = $admin->unreadNotifications()
            ->where('type', NewUserCreateNotification::class)
            ->first();
        if ($existingNotification) {
            $data = $existingNotification->data;

            $data['count'] += 1;

            if ($data['count'] > 9) {
                $data['title'] = "9+ new users registered.";
            } else {
                $data['title'] = "{$data['count']} new users registered.";
            }

            $existingNotification->update([
                'data' => $data,
            ]);
        } else {
            $count   = 1;
            $message = "{$count} new user registered.";

            $admin->notify(new NewUserCreateNotification($count, $message));
        }

        return response()->json([
            'status'  => true,
            'message' => 'An OTP sent to your registered email.',
            'data'    => $user,
        ], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->email_verified_at) {
                $token = $user->createToken('Bros')->plainTextToken;
                return response()->json([
                    'status'  => true,
                    'message' => 'Login successful',
                    'data'    => [
                        'access_token' => $token,
                        'user'         => $user,
                    ],
                ]);
            } else {
                $otp            = rand(100000, 999999);
                $otp_expires_at = now()->addMinutes(10);
                $user->update([
                    'otp'            => $otp,
                    'otp_expires_at' => $otp_expires_at,
                ]);
                Mail::to($request->email)->send(new OtpMail($otp));
                return response()->json([
                    'status'  => false,
                    'message' => 'Your email address is not verified. An OTP has been sent to your email. Please verify to continue.',
                ]);
            }

        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials',
            ]);
        }
    }
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'google_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            $socialId = ($request->has('google_id') && $existingUser->google_id === $request->google_id);
            if ($socialId) {
                $token = $existingUser->createToken('Bros')->plainTextToken;
                $data  = [
                    'access_token' => $token,
                    'user'         => $existingUser,
                ];
                return response()->json([
                    'status'  => true,
                    'message' => 'User login successfully.',
                    'data'    => $data,
                ], 200);
            } elseif (is_null($existingUser->google_id)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'User already exists. Sign in manually.',
                ], 200);
            } else {
                $existingUser->update([
                    'google_id' => $request->google_id ?? $existingUser->google_id,
                ]);
                $token = $existingUser->createToken('Bros')->plainTextToken;
                $data  = [
                    'access_token' => $token,
                    'user'         => $existingUser,
                ];
                return response()->json([
                    'status'  => true,
                    'message' => 'User login successfully.',
                    'data'    => $data,
                ], 200);
            }
        }

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make(Str::random(4)),
            'role'              => 'USER',
            'google_id'         => $request->google_id ?? null,
            'email_verified_at' => now(),
        ]);
        if ($user) {
            // notification send
            $admin                = User::where('id', 1)->first();
            $existingNotification = $admin->unreadNotifications()
                ->where('type', NewUserCreateNotification::class)
                ->first();
            if ($existingNotification) {
                $data = $existingNotification->data;

                $data['count'] += 1;

                if ($data['count'] > 9) {
                    $data['title'] = "9+ new users registered.";
                } else {
                    $data['title'] = "{$data['count']} new users registered.";
                }

                $existingNotification->update([
                    'data' => $data,
                ]);
            } else {
                $count   = 1;
                $message = "{$count} new user registered.";

                $admin->notify(new NewUserCreateNotification($count, $message));
            }
        }
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $final_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/users'), $final_name);
            $user->update([
                'photo' => $final_name,
            ]);
        }
        $token = $user->createToken('Bros')->plainTextToken;
        $data  = [
            'access_token' => $token,
            'user'         => $user,
        ];
        return response()->json([
            'status'  => true,
            'message' => 'User login successfully.',
            'data'    => $data], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $user                 = User::where('email', $request->email)->first();
        $otp                  = rand(100000, 999999);
        $otp_expires_at       = now()->addMinutes(10);
        $user->otp            = $otp;
        $user->otp_expires_at = $otp_expires_at;
        $user->save();

        Mail::to($request->email)->send(new OtpMail($otp));
        return response()->json([
            'status'  => true,
            'message' => 'An OTP sent to your registered email.',
            'data'    => $user,
        ], 200);
    }
    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $user = User::where('otp', $request->otp)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid OTP',
            ], 404);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'status'  => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 410);
        }

        if (! $user->email_verified_at) {
            $user->update([
                'email_verified_at' => now(),
            ]);
        }

        $user->update([
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        $token = $user->createToken('Bros')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Email verified and login successful.',
            'data'    => [
                'access_token' => $token,
                'user'         => $user,
            ],
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email|exists:users,email',
            'password'   => 'required|string|min:4|same:c_password',
            'c_password' => 'required|string|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $user           = User::whereEmail($request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'status'  => true,
            'message' => 'Password reset successfully.',
            'data'    => $user,
        ], 200);
    }

    public function profile()
    {
        $user = User::with('carPhotos')->where('id', Auth::id())->first();
        return response()->json([
            'status'  => true,
            'message' => 'Profile retreived successfully.',
            'data'    => $user,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:4|same:c_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Current password does not match.',
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully.',
            'data'    => $user,
        ], 200);
    }

    public function changeProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'phone'     => 'sometimes|string|max:20',
            'photo'     => 'sometimes|mimes:png,jpg,jpeg',
            'car_brand' => 'sometimes|string|max:255',
            'car_model' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $user            = Auth::user();
        $user->name      = $request->name;
        $user->phone     = $request->phone;
        $user->car_brand = $request->car_brand;
        $user->car_model = $request->car_model;
        if ($request->hasFile('photo')) {
            $photo_location = public_path('uploads/users');
            $old_photo      = basename($user->photo);
            if ($old_photo != 'default.png') {
                $old_photo_location = $photo_location . '/' . $old_photo;
                if (file_exists($old_photo_location)) {
                    unlink($old_photo_location);
                }
            }

            $final_photo_name = time() . '.' . $request->photo->extension();
            $request->photo->move($photo_location, $final_photo_name);
            $user->photo = $final_photo_name;
        }
        $user->save();
        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully.',
            'data'    => $user,
        ], 200);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'data'    => true,
            'message' => 'Logged out successfully',
        ]);
    }

}
