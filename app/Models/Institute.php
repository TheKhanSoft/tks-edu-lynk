<?php

namespace App\Models;


use App\Models\Institute\ClassName;
use App\Models\Institute\ClassSection;
use App\Models\Institute\Department;
use App\Models\Institute\Section;
use App\Models\Institute\Subject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institute extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'email',
        'contact_no',
        'extra_contacts',
        'is_active'
    ];

    protected $casts = [
        'extra_contacts' => 'array',
        'is_active' => 'boolean'
    ];
    protected $appends = [
        'status_label',
        'formatted_created_at',
        'formatted_extra_contacts',
    ];

    // Default eager loading
    protected $with = ['departments'];

    // Eager Loading Methods
    public function withStudentsAndTeachers()
    {
        return $this->with(['students', 'teachers']);
    }
    
    public function withDepartmentsAndClassSections()
    {
        return $this->with(['departments', 'classSections']);
    }

    public function withActiveDepartments()
    {
        return $this->with(['departments' => function ($query) {
            $query->active();
        }]);
    }

    // Relationships
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function classes()
    {
       return $this->hasMany(ClassName::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function classSections()
    {
        return $this->hasMany(ClassSection::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
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

    public function scopeOfType(Builder $query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInCity(Builder $query, string $city)
    {
        return $query->where('city', $city);
    }

    public function scopeSearch(Builder $query, ?string $search)
    {
        return $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%")
                    ->orWhere('state', 'LIKE', "%{$search}%")
                    ->orWhere('postal_code', 'LIKE', "%{$search}%")
                    ->orWhere('country', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('contact_no', 'LIKE', "%{$search}%");
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

    // Custom Attributes
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('F j, Y');
    }

    public function getFormattedExtraContactsAttribute(): string
    {
        return $this->extra_contacts 
            ? json_encode($this->extra_contacts, JSON_PRETTY_PRINT) 
            : 'No extra contacts available';
    }

    public function getFullAddressAttribute()
    {
        return sprintf(
            '%s, %s, %s, %s',
            $this->address,
            $this->city,
            $this->state,
            $this->country
        );
    }

    public function getContactInfoAttribute()
    {
        return array_filter([
            'primary' => $this->contact_no,
            'email' => $this->email,
            'additional' => $this->extra_contacts
        ]);
    }
}