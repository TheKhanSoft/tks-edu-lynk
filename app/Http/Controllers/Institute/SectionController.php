<?php
namespace App\Http\Controllers\Institute;

use App\Http\Requests\Institute\SectionRequest;
use App\Models\Institute\Section;
use App\Services\Institute\SectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct(private SectionService $sectionService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['institute_id', 'is_active', 'search']);
        $sections = $this->sectionService->list($filters);
        return response()->json(['success' => true, 'data' => $sections]);
    }

    public function store(SectionRequest $request)
    {
        $section = $this->sectionService->create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Section created successfully!',
            'data' => $section->load('institute')
        ], 201);
    }

    public function update(SectionRequest $request, Section $section)
    {
        $this->sectionService->update($section, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully!',
            'data' => $section->fresh()->load('institute')
        ]);
    }

    public function destroy(Section $section)
    {
        $this->sectionService->delete($section);
        return response()->json(['success' => true, 'message' => 'Section deleted successfully!']);
    }

    public function restore(int $id)
    {
        $this->sectionService->restore($id);
        return response()->json(['success' => true, 'message' => 'Section restored successfully!']);
    }

    public function forceDelete(int $id)
    {
        $this->sectionService->forceDelete($id);
        return response()->json(['success' => true, 'message' => 'Section permanently deleted!']);
    }

    public function recentlyDeleted()
    {
        $recentlyDeleted = $this->sectionService->getRecentlyDeleted(30);
        return response()->json(['success' => true, 'data' => $recentlyDeleted]);
    }

    public function recentlyCreated()
    {
        $recentlyCreated = $this->sectionService->getRecentlyCreated(7);
        return response()->json(['success' => true, 'data' => $recentlyCreated]);
    }
}