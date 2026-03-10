<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request):JsonResponse{
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user =User::create($validated);
        return response()->json([
            'message' => 'account created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],

        ], 201);
    }
}
