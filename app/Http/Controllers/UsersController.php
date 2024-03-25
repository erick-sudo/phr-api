<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    /**
     * Show User details
     */
    public function show($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('show', $user);
        
        return response()->json($user);
    }

    /**
     * User profile
     */
    public function profile(Request $request) {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'You are unauthorized'], 403);
        }
        
        return response()->json($user->profile());
    }

    /**
     * Show User roles
     */
    public function showUserRoles($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('show', $user);

        $roles = $user->roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
        })->toArray();
        return response()->json($roles);
    }
}
