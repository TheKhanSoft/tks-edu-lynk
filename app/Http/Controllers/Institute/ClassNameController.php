<?php

namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\ClassNameRequest;
use App\Models\Institute\ClassName;
use App\Services\Institute\ClassNameService;
use Illuminate\Http\Request;

class ClassNameController extends Controller
{
    public function __construct(private ClassNameService $classNameService){}

    public function index(Request $request)
    {
        $filters = $request->only([
            'institute_id',
            'name',
            'active',
            'search',
            'start_date',
            'end_date'
        ]);
        
        $classes = $this->classNameService->list($filters);

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    public function store(ClassNameRequest $request)
    {
        $className = $this->classNameService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Class created successfully!',
            'data' => $className->load('institute')
        ], 201);
    }

    public function update(ClassNameRequest $request, ClassName $className)
    {
        $this->classNameService->update($className, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Class updated successfully!',
            'data' => $className->fresh()->load('institute')
        ]);
    }

    public function destroy(ClassName $className)
    {
        $this->classNameService->delete($className);

        return response()->json([
            'success' => true,
            'message' => 'Class deleted successfully!'
        ]);
    }

    public function restore(int $id)
    {
        $this->classNameService->restore($id);

        return response()->json([
            'success' => true,
            'message' => 'Class restored successfully!'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->classNameService->forceDelete($id);

        return response()->json([
            'success' => true,
            'message' => 'Class permanently deleted!'
        ]);
    }

    public function recentlyDeleted()
    {
        $deletedClasses = $this->classNameService->getRecentlyDeleted();

        return response()->json([
            'success' => true,
            'data' => $deletedClasses
        ]);
    }
}