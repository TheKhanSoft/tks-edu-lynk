<?php

namespace App\Models\Institute;

use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'code',
        'hod_id',
        'institute_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'status_label',
        'formatted_created_at',
    ];

    // Attributes
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('F j, Y');
    }

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function hod()
    {
        return $this->belongsTo(Institute_Staff::class, 'hod_id'); // Assuming the staff model is named `Staff`
    }

    public function classSections()
    {
        return $this->hasMany(ClassSection::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByInstitute(Builder $query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeSearch(Builder $query, ?string $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        });
    }

    public function scopeCreatedBetween(Builder $query, ?string $startDate, ?string $endDate)
    {
        return $query->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        });
    }

    public function scopeRecentlyCreated(Builder $query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeRecentlyDeleted(Builder $query, int $days = 30)
    {
        return $query->onlyTrashed()
            ->where('deleted_at', '>=', now()->subDays($days));
    }

    public function scopeOrderByName(Builder $query, string $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }
}