<?php

// app/Http/Controllers/InstituteController.php
namespace App\Http\Controllers;

use App\Models\Institute;
use App\Services\InstituteService;
use App\Http\Requests\InstituteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function __construct(protected InstituteService $instituteService){}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'city', 'active']);
        $institutes = $this->instituteService->getAll($filters, $request->input('per_page', 15));
        
        return response()->json([
            'data' => $institutes->items(),
            'meta' => [
                'current_page' => $institutes->currentPage(),
                'last_page' => $institutes->lastPage(),
                'per_page' => $institutes->perPage(),
                'total' => $institutes->total()
            ]
        ]);
    }

    public function store(InstituteRequest $request)
    {
        $institute = $this->instituteService->create($request->validated());
        
        return response()->json([
            'message' => 'Institute created successfully!',
            'data' => $institute->load('departments')
        ], 201);
    }

    public function show(Institute $institute)
    {
        return response()->json([
            'data' => $institute->load(['departments', 'classes', 'sections'])
        ]);
    }

    public function update(InstituteRequest $request, Institute $institute)
    {
        $this->instituteService->update($institute, $request->validated());
        
        return response()->json([
            'message' => 'Institute updated successfully!',
            'data' => $institute->fresh()
        ]);
    }

    public function destroy(Institute $institute)
    {
        $this->instituteService->delete($institute);
        
        return response()->json([
            'message' => 'Institute deleted successfully!'
        ]);
    }

    public function toggleStatus(Institute $institute)
    {
        $this->instituteService->toggleStatus($institute);
        
        return response()->json([
            'message' => $institute->is_active 
                ? 'Institute has been activated!' 
                : 'Institute has been deactivated!',
            'is_active' => $institute->is_active
        ]);
    }

    public function statistics()
    {
        return response()->json([
            'data' => $this->instituteService->getStatistics()
        ]);
    }
}