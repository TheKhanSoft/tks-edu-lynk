<?php
namespace App\Services\Institute;

use App\Models\Institute\Department;

class DepartmentService
{
    public function list(array $filters = [], int $perPage = 15)
    {
        return Department::query()
            ->when(isset($filters['institute_id']), function ($query) use ($filters) {
                $query->where('institute_id', $filters['institute_id']);
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
            ->with('institute') // Eager load institute
            ->latest()
            ->paginate($perPage);
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return Department::recentlyDeleted($days)->get();
    }
    public function getRecentlyCreated(int $days = 7)
    {
        return Department::recentlyCreated($days)->get();
    }

    public function create(array $data): Department
    {
        return Department::create($data);
    }

    public function update(Department $department, array $data)
    {
        return $department->update($data);
    }

    public function delete(Department $department)
    {
        return $department->delete();
    }

    public function restore(int $departmentId)
    {
        return Department::withTrashed()->findOrFail($departmentId)->restore();
    }

    public function forceDelete(int $departmentId)
    {
        return Department::withTrashed()->findOrFail($departmentId)->forceDelete();
    }

    public function getStatistics(): array
    {
        return [
            'total' => Department::count(),
            'active' => Department::where('is_active', true)->count(),
            'inactive' => Department::where('is_active', false)->count(),
        ];
    }
}