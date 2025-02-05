<?php

namespace App\Services;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InstituteService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        return Institute::query()
            ->when(isset($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->when(isset($filters['is_active']), function ($query) use ($filters) {
                $filters['is_active'] ? $query->active() : $query->inactive();
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('address', 'like', "%{$filters['search']}%")
                      ->orWhere('city', 'like', "%{$filters['search']}%")
                      ->orWhere('state', 'like', "%{$filters['search']}%");
                });
            })
             ->with('departments') // Eager load departments
            ->latest()
            ->paginate($perPage)
        ;


        // $query = Institute::query();
        // // Apply filters
        // if (isset($filters['type'])) {
        //     $query->byType($filters['type']);
        // }

        // if (isset($filters['city'])) {
        //     $query->inCity($filters['city']);
        // }

        // if (isset($filters['active'])) {
        //     $query->active();
        // }

        // return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Institute
    {
        return Institute::findOrFail($id);
    }

    public function create(array $data): Institute
    {
        return Institute::create($data);
    }

    public function update(Institute $institute, array $data): bool
    {
        return $institute->update($data);
    }

    public function delete(Institute $institute): bool
    {
        return $institute->delete();
    }

    public function getRecentlyDeleted(int $days = 30)
    {
        return Institute::recentlyDeleted($days)->get();
    }
    public function getRecentlyCreated(int $days = 7)
    {
        return Institute::recentlyCreated($days)->get();
    }

    public function restore(int $instituteId)
    {
        return Institute::withTrashed()->findOrFail($instituteId)->restore();
    }

    public function forceDelete(int $instituteId)
    {
        return Institute::withTrashed()->findOrFail($instituteId)->forceDelete();
    }

    public function toggleStatus(Institute $institute): bool
    {
        return $institute->update(['is_active' => !$institute->is_active]);
    }

    public function getStatistics(): array
    {
        return [
            'total' => Institute::count(),
            'active' => Institute::active()->count(),
            'by_type' => Institute::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_city' => Institute::selectRaw('city, count(*) as count')
                ->groupBy('city')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'city')
                ->toArray()
        ];
    }
}
