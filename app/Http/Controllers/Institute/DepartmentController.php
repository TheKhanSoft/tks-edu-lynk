<?php
namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\DepartmentRequest;
use App\Models\Institute\Department;
use App\Services\Institute\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private DepartmentService $departmentService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'is_active', 'search']);
        $departments = $this->departmentService->list($filters);
        return response()->json(['success' => true, 'data' => $departments]);
    }

    public function store(DepartmentRequest $request)
    {
        $department = $this->departmentService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Department created successfully!',
            'data' => $department->load('departments')
        ], 201);
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->departmentService->update($department, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully!',
            'data' => $department->fresh()->load('departments')
        ]);
    }

    public function destroy(Department $department)
    {
        $this->departmentService->delete($department);
        return response()->json(['success' => true, 'message' => 'Department deleted successfully!']);
    }

    public function restore(int $id)
    {
        $this->departmentService->restore($id);
        return response()->json(['success' => true, 'message' => 'Department restored successfully!']);
    }

    public function forceDelete(int $id)
    {
        $this->departmentService->forceDelete($id);
        return response()->json(['success' => true, 'message' => 'Department permanently deleted!']);
    }

    public function recentlyDeleted()
    {
        $deletedDepartments = $this->departmentService->getRecentlyDeleted();
        return response()->json(['success' => true, 'data' => $deletedDepartments]);
    }

     public function recentlyCreated()
    {
        $recentlyCreated = $this->departmentService->getRecentlyCreated();
        return response()->json(['success' => true, 'data' => $recentlyCreated]);
    }
}