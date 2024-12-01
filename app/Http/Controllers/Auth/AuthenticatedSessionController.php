<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthenticatedSessionController extends Controller
{
    /**
     * Login to the app.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function store(LoginRequest $request)
    {
        try {
            // Authenticate the user
            $request->authenticate();

            // Delete all existing tokens for the authenticated user
            $request->user()->tokens()->delete();

            // Get user details
            $user = $request->user();

            // Call the login method (ensure this exists and returns necessary data)
            $response = $user->login();

            // Return a JSON response with user data and message
            return response()->json([
                'data' => $response,
                'message' => 'Successfully logged in'
            ], 200);

        } catch (AuthenticationException $e) {
            // Catch AuthenticationException and return an unauthorized response
            return response()->json([
                'errors' => [$e->getMessage(), 'Unauthorized access'],
                'message' => 'Invalid credentials'
            ], 401);

        } catch (ValidationException $e) {
            // Catch ValidationException and return a validation error response
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Validation error'
            ], 422);

        } catch (Exception $e) {
            // Catch any general exception
            return response()->json([
                'errors' => [$e->getMessage()],
                'message' => 'Login error'
            ], 500);
        }
    }

    /**
     * Logout function.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            // Delete the current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);

        } catch (\Exception $e) {
            // Handle any exceptions that might occur during logout
            return response()->json([
                'errors' => [$e->getMessage()],
                'message' => 'Logout error'
            ], 500);
        }
    }
}
