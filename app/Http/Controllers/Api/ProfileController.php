<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse{
        $user = $request->user();
        return response()->json([
            'message' => 'profile fetched',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->format('Y-m-d H:i'),
            ],
        ], 200);
    }


    public function update(Request $request): JsonResponse{
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['sometimes','required','string','max:255',],$user->id
        ]);

        $user->update($validated);
        return response()->json([
            'message' => 'updated successfully',
            'user' =>[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }


    public function updatePassword(Request $request): JsonResponse{
        $request->validate([
            'current_password' => ['required','string'],
            'new_password' => ['required', 'confirmed', 'Password::min(8)'],
        ]);

        $user = $request->user();

        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'message' => 'incorrect password',
            ],422);
        }

        $user->update(['password' => $request->new_password]);

        return response()->json([
            'message' => 'password updated seccessfully',
        ],200);
    }


    public function destroy(Request $request): JsonResponse{
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ],200);
    }
}
