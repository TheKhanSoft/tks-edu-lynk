<?php
namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\SubjectRequest;
use App\Models\Institute\Subject;
use App\Services\Institute\SubjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(private SubjectService $subjectService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['institute_id', 'department_id', 'is_active', 'search']);
        $subjects = $this->subjectService->list($filters);
        return response()->json(['success' => true, 'data' => $subjects]);
    }

    public function store(SubjectRequest $request)
    {
        $subject = $this->subjectService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully!',
            'data' => $subject->load('institute', 'department')
        ], 201);
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $this->subjectService->update($subject, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully!',
            'data' => $subject->fresh()->load('institute', 'department')
        ]);
    }

    public function destroy(Subject $subject)
    {
        $this->subjectService->delete($subject);
        return response()->json(['success' => true, 'message' => 'Subject deleted successfully!']);
    }

    public function restore(int $id)
    {
        $this->subjectService->restore($id);
        return response()->json(['success' => true, 'message' => 'Subject restored successfully!']);
    }

    public function forceDelete(int $id)
    {
        $this->subjectService->forceDelete($id);
        return response()->json(['success' => true, 'message' => 'Subject permanently deleted!']);
    }

    public function toggleStatus(Subject $subject)
    {
        $status = $this->subjectService->toggleStatus($subject);
        return response()->json([
            'success' => true,
            'message' => 'Subject status toggled successfully!',
            'data' => ['is_active' => $status]
        ]);
    }

    public function statistics()
    {
        $stats = $this->subjectService->getStatistics();
        return response()->json(['success' => true, 'data' => $stats]);
    }
}