<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;  // Import the Service model
use Exception;
use Illuminate\Http\JsonResponse;
use App\Imports\ServiceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of all services.
     * GET /api/services
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Retrieve all services from the database
            $services = Service::all();

            // Return the list of services as a JSON response
            return response()->json($services);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to retrieve services', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created service in storage.
     * POST /api/services
     *
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validated();

            // Create a new service in the database
            $service = Service::create($validatedData);

            // Return a JSON response with the created service and a success message
            return response()->json([
                'message' => 'Service created successfully',
                'service' => $service
            ], 201);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to create service', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Import services from an Excel file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importServices(Request $request)
    {
        try {
            // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Invalid file format.',
                    'errors' => $validator->errors(),
                    'status' => 400
                ], 400);
            }

            // Import the Excel file
            Excel::import(new ServiceImport, $request->file('file'));

            return response()->json([
                'message' => 'Services imported successfully!',
                'status'  => 200
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error importing services.',
                'errors'  => [$e->getMessage()],
                'status'  => 500
            ], 500);
        }
    }

    /**
     * Display the specified service.
     * GET /api/services/{service}
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function show(Service $service): JsonResponse
    {
        try {
            // Return the service as a JSON response
            return response()->json($service);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to retrieve service', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified service in storage.
     * PUT /api/services/{service}
     *
     * @param ServiceRequest $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validated();

            // Update the service with the validated data
            $service->update($validatedData);

            // Return a JSON response with the updated service and a success message
            return response()->json([
                'message' => 'Service updated successfully',
                'service' => $service
            ]);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to update service', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified service from storage.
     * DELETE /api/services/{service}
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function destroy(Service $service): JsonResponse
    {
        try {
            // Delete the service from the database
            $service->delete();

            // Return a JSON response with a success message
            return response()->json(['message' => 'Service deleted successfully']);
        } catch (Exception $e) {
            // Return error response if something goes wrong
            return response()->json(['error' => 'Failed to delete service', 'message' => $e->getMessage()], 500);
        }
    }
}
