<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SocialAuthApiController extends Controller
{
    /**
     * Handle social login from mobile apps.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'provider' => 'required|string|in:google,facebook',
            'provider_id' => 'required|string',
            'avatar' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find existing user or create new one
            $user = User::where('provider', $request->provider)
                ->where('provider_id', $request->provider_id)
                ->first();

            if (!$user) {
                // Check if user with same email exists
                $user = User::where('email', $request->email)->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => null, // No password for social login
                        'role' => 'user',
                        'provider' => $request->provider,
                        'provider_id' => $request->provider_id,
                        'avatar' => $request->avatar,
                    ]);
                } else {
                    // Update existing user with provider data
                    $user->update([
                        'provider' => $request->provider,
                        'provider_id' => $request->provider_id,
                        'avatar' => $request->avatar,
                    ]);
                }
            }

            // Create token for API authentication
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during social login',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
