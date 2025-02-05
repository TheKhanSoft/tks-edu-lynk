<?php

namespace App\Services\Institute;

use App\Models\Institute\ClassName;

class ClassNameService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        return ClassName::query()
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
            })
            ->when(isset($filters['class_name']), function ($query) use ($filters) {
                $query->byClassName($filters['class_name']);
            })
            ->when(isset($filters['active']), function ($query) use ($filters) {
                $filters['active'] ? $query->active() : $query->inactive();
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->search($filters['search']);
            })
            ->when(
                isset($filters['start_date']) && isset($filters['end_date']),
                function ($query) use ($filters) {
                    $query->createdBetween(
                        $filters['start_date'],
                        $filters['end_date']
                    );
                }
            )
            ->with('institute')
            ->latest()
            ->paginate($perPage);
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return ClassName::recentlyDeleted($days)->get();
    }

    public function getRecentlyCreated(int $days = 7)
    {
        return ClassName::recentlyCreated($days)->get();
    }

    public function create(array $data): ClassName
    {
        return ClassName::create($data);
    }

    public function update(ClassName $className, array $data)
    {
        return $className->update($data);
    }

    public function delete(ClassName $className)
    {
        return $className->delete();
    }

    public function restore(int $classNameId)
    {
        return ClassName::withTrashed()->findOrFail($classNameId)->restore();
    }

    public function forceDelete(int $classNameId)
    {
        return ClassName::withTrashed()->findOrFail($classNameId)->forceDelete();
    }

    public function getStatistics(): array
    {
        return [
            'total' => ClassName::count(),
            'active' => ClassName::active()->count(),
            'inactive' => ClassName::inactive()->count(),
        ];
    }
}