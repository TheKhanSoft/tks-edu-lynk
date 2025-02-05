<?php
namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\ClassSectionRequest;
use App\Models\Institute\ClassSection;
use App\Services\Institute\ClassSectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassSectionController extends Controller
{
    public function __construct(private ClassSectionService $classSectionService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['institute_id', 'is_active', 'search']);
        $classSections = $this->classSectionService->list($filters);
        return response()->json(['success' => true, 'data' => $classSections]);
    }

    public function store(ClassSectionRequest $request)
    {
        $classSection = $this->classSectionService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Class Section created successfully!',
            'data' => $classSection->load('institute')
        ], 201);
    }

    public function update(ClassSectionRequest $request, ClassSection $classSection)
    {
        $this->classSectionService->update($classSection, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Class Section updated successfully!',
            'data' => $classSection->fresh()->load('institute')
        ]);
    }

    public function destroy(ClassSection $classSection)
    {
        $this->classSectionService->delete($classSection);
        return response()->json(['success' => true, 'message' => 'Class Section deleted successfully!']);
    }

    public function restore(int $id)
    {
        $this->classSectionService->restore($id);
        return response()->json(['success' => true, 'message' => 'Class Section restored successfully!']);
    }

    public function forceDelete(int $id)
    {
        $this->classSectionService->forceDelete($id);
        return response()->json(['success' => true, 'message' => 'Class Section permanently deleted!']);
    }

    public function recentlyDeleted()
    {
        $recentlyDeleted = $this->classSectionService->getRecentlyDeleted(30);
        return response()->json(['success' => true, 'data' => $recentlyDeleted]);
    }

    public function recentlyCreated()
    {
        $recentlyCreated = $this->classSectionService->getRecentlyCreated(7);
        return response()->json(['success' => true, 'data' => $recentlyCreated]);
    }
}