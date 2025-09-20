<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate OTP (for demo - in production, use proper SMS service)
        $otp = rand(1000, 9999);
        $otpExpiresAt = now()->addMinutes(10);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // In production, send OTP via SMS
        // $this->sendOtpSms($user->phone_number, $otp);

        // Create personal access token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully. Please verify OTP.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'otp' => $otp // Remove this in production
            ]
        ], 201);
    }

    /**
     * Login user with email/phone and password
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // Can be email or phone number
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Determine if login is email or phone number
        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        // Attempt to find user
        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if user is verified (optional - if you require OTP verification)
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your account first',
                'needs_verification' => true
            ], 403);
        }

        // Create personal access token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Send OTP for verification
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|exists:users,phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        // Generate new OTP
        $otp = rand(1000, 9999);
        $otpExpiresAt = now()->addMinutes(10);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]);

        // In production, send OTP via SMS
        // $this->sendOtpSms($user->phone_number, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'otp' => $otp // Remove this in production
            ]
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone_number', $request->phone_number)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        // Clear OTP and mark as verified
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        // Create new token if needed
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function user(Request $request)
    {
        $user = $request->user()->load([
            'financialGoals',
            'loanEmiDetails',
            'udhariRecords'
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|string|max:15|unique:users,phone_number,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Helper method to send OTP via SMS (Implement with your SMS provider)
     */
    private function sendOtpSms($phoneNumber, $otp)
    {
        // Example with Twilio (you need to install and configure it)
        /*
        try {
            $twilio = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.auth_token')
            );

            $message = $twilio->messages->create(
                $phoneNumber,
                [
                    'from' => config('services.twilio.phone_number'),
                    'body' => "Your verification code is: $otp. Valid for 10 minutes."
                ]
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
        */

        // For now, just log the OTP
        \Log::info("OTP for $phoneNumber: $otp");
        return true;
    }
}