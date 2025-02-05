<?php
namespace App\Services\Institute;

use App\Models\Institute\Subject;

class SubjectService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        $subject_query = Subject::query();
        return $subject_query
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
            })
            ->when(isset($filters['department_id']), function ($query) use ($filters) {
                $query->where('department_id', $filters['department_id']);
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                $filters['is_active'] ? $query->active() : $query->inactive();
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('code', 'like', "%{$filters['search']}%");
                });
            })
            ->with(['institute', 'department']) // Eager load relationships
            ->latest()
            ->paginate($perPage);
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return Subject::recentlyDeleted($days)->get();
    }

    public function getRecentlyCreated(int $days = 7)
    {
        return Subject::recentlyCreated($days)->get();
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data)
    {
        return $subject->update($data);
    }

    public function delete(Subject $subject)
    {
        return $subject->delete();
    }

    public function restore(int $subjectId)
    {
        return Subject::withTrashed()->findOrFail($subjectId)->restore();
    }

    public function forceDelete(int $subjectId)
    {
        return Subject::withTrashed()->findOrFail($subjectId)->forceDelete();
    }

    public function toggleStatus(Subject $subject)
    {
        return $subject->update(['is_active' => !$subject->is_active]);
    }

    public function getStatistics()
    {
        return [
            'total' => Subject::count(),
            'active' => Subject::where('is_active', true)->count(),
            'by_type' => Subject::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_department' => Subject::selectRaw('department_id, count(*) as count')
                ->groupBy('department_id')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'department_id')
                ->toArray(),
        ];
    }
}