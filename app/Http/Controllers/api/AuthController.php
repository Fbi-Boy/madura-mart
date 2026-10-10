<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * POST /api/login
     * Body: email, password
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Cek user ada & password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // 4. Cek user aktif (project lu punya kolom is_active)
        if (isset($user->is_active) && !$user->is_active) {
            return response()->json([
                'message' => 'Akun tidak aktif.',
            ], 403);
        }

        // 5. Bikin token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 6. Return
        return response()->json([
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role ?? null,
            ],
        ], 200);
    }

    /**
     * POST /api/logout  (protected by auth:sanctum)
     */
    public function logout(Request $request): JsonResponse
    {
        // Hapus token yang lagi dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ], 200);
    }
}
