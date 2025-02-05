<?php
namespace App\Services\Institute;

use App\Models\Institute\Section;

class SectionService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        return Section::query()
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                $filters['is_active'] ? $query->active() : $query->inactive();
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('description', 'like', "%{$filters['search']}%");
                });
            })
            ->with('institute')
            ->latest()
            ->paginate($perPage);
    }

     public function getRecentlyDeleted(int $with_in_days = 30)
    {
        return Section::recentlyDeleted($with_in_days)->get();
    }
    public function getRecentlyCreated(int $with_in_days = 7)
    {
        return Section::recentlyCreated($with_in_days)->get();
    }

    public function create(array $data)
    {
        return Section::create($data);
    }

    public function update(Section $section, array $data)
    {
        return $section->update($data);
    }

    public function delete(Section $section)
    {
        return $section->delete();
    }

    public function restore(int $sectionId)
    {
        return Section::withTrashed()->findOrFail($sectionId)->restore();
    }

    public function forceDelete(int $sectionId)
    {
        return Section::withTrashed()->findOrFail($sectionId)->forceDelete();
    }

    public function getStatistics()
    {
        return [
            'total' => Section::count(),
            'active' => Section::where('is_active', true)->count(),
            'inactive' => Section::where('is_active', false)->count(),
        ];
    }
}