<?php
namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\ClassSubjectRequest;
use App\Models\Institute\ClassSubject;
use App\Services\Institute\ClassSubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    public function __construct(private ClassSubjectService $classSubjectService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['institute_id', 'department_id', 'is_active', 'search']);
        $classSubjects = $this->classSubjectService->list($filters);
        return response()->json(['success' => true, 'data' => $classSubjects]);
    }

    public function store(ClassSubjectRequest $request)
    {
        $classSubject = $this->classSubjectService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Class Subject created successfully!',
            'data' => $classSubject->load('institute', 'department')
        ], 201);
    }

    public function update(ClassSubjectRequest $request, ClassSubject $classSubject)
    {
        $this->classSubjectService->update($classSubject, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Class Subject updated successfully!',
            'data' => $classSubject->fresh()->load('institute', 'department')
        ]);
    }

    public function destroy(ClassSubject $classSubject)
    {
        $this->classSubjectService->delete($classSubject);
        return response()->json(['success' => true, 'message' => 'Class Subject deleted successfully!']);
    }

    public function restore(int $id)
    {
        $this->classSubjectService->restore($id);
        return response()->json(['success' => true, 'message' => 'Class Subject restored successfully!']);
    }

    public function forceDelete(int $id)
    {
        $this->classSubjectService->forceDelete($id);
        return response()->json(['success' => true, 'message' => 'Class Subject permanently deleted!']);
    }

    public function toggleStatus(ClassSubject $classSubject)
    {
        $status = $this->classSubjectService->toggleStatus($classSubject);
        return response()->json([
            'success' => true,
            'message' => 'Class Subject status toggled successfully!',
            'data' => ['is_active' => $status]
        ]);
    }

    public function statistics()
    {
        $stats = $this->classSubjectService->getStatistics();
        return response()->json(['success' => true, 'data' => $stats]);
    }
}