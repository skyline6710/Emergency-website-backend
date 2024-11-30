<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'new_password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            // Update the user's password
            $request->user()->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            // Return a success response
            return response()->json([
                'message' => 'Password changed successfully',
                'status'  => 200,
            ], 200);

        } catch (Exception $e) {
            // Return an error response with the exception message
            return response()->json([
                'message' => 'Error changing password',
                'errors'  => [$e->getMessage()],
                'status'  => 500,
            ], 500);
        }
    }
}
