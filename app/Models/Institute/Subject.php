<?php

namespace App\Models\Institute;

// use App\Models\Institute;
use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'institute_id',
        'department_id',
        'type',
        'description',
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
        'short_description',
    ];

    // Eager Loading Methods
    public function withInstituteAndDepartment()
    {
        return $this->with(['institute', 'department']);
    }

    public function withActiveClassSubjects()
    {
        return $this->with(['classSubjects' => function ($query) {
            $query->active();
        }]);
    }

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    //Custom Attributes
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
            });
        });
    }

    public function scopeCreatedBetween($query, $startDate, $endDate)
    {
        return $query->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        });
    }

    public function scopeRecentlyCreated($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeRecentlyDeleted($query, $days = 30)
    {
        return $query->onlyTrashed()
            ->where('deleted_at', '>=', now()->subDays($days));
    }

    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }
}