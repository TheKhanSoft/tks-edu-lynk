<?php
namespace App\Services\Institute;

use App\Models\Institute\ClassSection;

class ClassSectionService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        return ClassSection::query()
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
            })
            ->when(isset($filters['department_id']), function ($query) use ($filters) {
                $query->where('department_id', $filters['department_id']);
            })
            ->when(isset($filters['class_id']), function ($query) use ($filters) {
                $query->where('class_id', $filters['class_id']);
            })
            ->when(isset($filters['section_id']), function ($query) use ($filters) {
                $query->where('section_id', $filters['section_id']);
            })
            ->when(isset($filters['session_id']), function ($query) use ($filters) {
                $query->where('session_id', $filters['session_id']);
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                $filters['is_active'] ? $query->active() : $query->inactive();
            })
            ->with(['institute', 'department', 'class', 'section']) 
            ->latest()
            ->paginate($perPage);
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return ClassSection::recentlyDeleted($days)->get();
    }

    public function getRecentlyCreated(int $days = 7)
    {
        return ClassSection::recentlyCreated($days)->get();
    }

    public function create(array $data): ClassSection
    {
        return ClassSection::create($data);
    }

    public function update(ClassSection $classSection, array $data)
    {
        return $classSection->update($data);
    }

    public function delete(ClassSection $classSection)
    {
        return $classSection->delete();
    }

    public function restore(int $classSectionId)
    {
        return ClassSection::withTrashed()->findOrFail($classSectionId)->restore();
    }

    public function forceDelete(int $classSectionId)
    {
        return ClassSection::withTrashed()->findOrFail($classSectionId)->forceDelete();
    }

    public function getStatistics(): array
    {
        return [
            'total' => ClassSection::count(),
            'active' => ClassSection::where('is_active', true)->count(),
            'inactive' => ClassSection::where('is_active', false)->count(),
        ];
    }
}