<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string']
        ]);

        // Get the JWT token from the request
        $token = $request->token;

        $secretKey = env('JWT_SECRET', 'jwt-secret-key');

        try {
            //code...
            // Decode the JWT token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Extract the email from the decoded payload
            $email = $decoded->email;

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified'], 409);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            return response()->json(['message' => 'Email verified succesfully. You can now login.'], 202);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(['message' => "An error occurred while validating your token. Please consider requesting for another. {$e->getMessage()}"], 400);
        }
    }
}
