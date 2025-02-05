<?php

namespace App\Models\Institute;

use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'institute_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = [
        'status_label',
        'formatted_created_at',
        'short_description'
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

    public function getShortDescriptionAttribute()
    {
        return $this->description 
            ? str()->limit($this->description, 100) 
            : 'No description available';
    }

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
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
                    ->orWhere('description', 'LIKE', "%{$search}%");
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