<?php
namespace App\Services\Institute;

use App\Models\Institute\ClassSubject;

class ClassSubjectService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        return ClassSubject::query()
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
            })
            ->when(isset($filters['class_section_id']), function ($query) use ($filters) {
                $query->where('class_section_id', $filters['class_section_id']);
            })
            ->when(isset($filters['subject_id']), function ($query) use ($filters) {
                $query->where('subject_id', $filters['subject_id']);
            })
            ->when(isset($filters['teacher_id']), function ($query) use ($filters) {
                $query->where('teacher_id', $filters['teacher_id']);
            })
            ->when(isset($filters['session_id']), function ($query) use ($filters) {
                $query->where('session_id', $filters['session_id']);
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                $filters['is_active'] ? $query->active() : $query->inactive();
            })
            ->with(['institute', 'class_section', 'subject', 'teacher']) // Eager load relationships
            ->latest()
            ->paginate($perPage);
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return ClassSubject::recentlyDeleted($days)->get();
    }

    public function getRecentlyCreated(int $days = 7)
    {
        return ClassSubject::recentlyCreated($days)->get();
    }

    public function create(array $data): ClassSubject
    {
        return ClassSubject::create($data);
    }

    public function update(ClassSubject $classSubject, array $data)
    {
        return $classSubject->update($data);
    }

    public function delete(ClassSubject $classSubject)
    {
        return $classSubject->delete();
    }

    public function restore(int $classSubjectId)
    {
        return ClassSubject::withTrashed()->findOrFail($classSubjectId)->restore();
    }

    public function forceDelete(int $classSubjectId)
    {
        return ClassSubject::withTrashed()->findOrFail($classSubjectId)->forceDelete();
    }

    public function toggleStatus(ClassSubject $classSubject): bool
    {
        return $classSubject->update(['is_active' => !$classSubject->is_active]);
    }

    public function getStatistics(): array
    {
        return [
            'total' => ClassSubject::count(),
            'active' => ClassSubject::where('is_active', true)->count(),
            'by_teacher' => ClassSubject::selectRaw('teacher_id, count(*) as count')
                ->groupBy('teacher_id')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'teacher_id')
                ->toArray(),
            'by_session' => ClassSubject::selectRaw('session_id, count(*) as count')
                ->groupBy('session_id')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'session_id')
                ->toArray(),
        ];
    }
}