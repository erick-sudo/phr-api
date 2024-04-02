<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 409);
        }

        return response()->json(['message' => 'Verification link sent'], 200);
    }

    /**
     * Resend verification url
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $jwt = $this->generateUsersJwtToken($user);

        $this->sendMail($user->email, "Account Verification", $jwt['url']);

        return response()->json(['message' => 'Verification link sent'], 200);
    }
}
