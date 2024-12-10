<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class User_register extends Authenticatable implements JWTSubject
{
    use HasFactory;

    // Opcionalmente, define la tabla si no sigue la convención de pluralización

    protected $guarded = ['id'];

    protected $table = 'user_registers';
    
    protected $allowIncluded = [
        'Role',
        'Apprentice',
        'Message',
        'Notification'
    ];

    protected $allowFilter = [
        'id',
        'identification',
        'name',
        'last_name',
        'telephone',
        'email',
        'address',
        'department',
        'municipality',
        'password',
        'id_role'
    ];

    protected $allowSort = [
        'id',
        'identification',
        'name',
        'last_name',
        'telephone',
        'email',
        'address',
        'department',
        'municipality',
        'password',
        'id_role'
    ];

    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relación con la tabla roles
    public function Role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function Apprentice(): HasMany
    {
        return $this->hasMany(Apprentice::class);
    }
    public function Message(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    public function Notification(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Encriptar la contraseña automáticamente al crear o actualizar
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function scopeIncluded(Builder $query): void
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query): void
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }

    public function scopeSort(Builder $query): void
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $sortField) {
            $direction = 'asc';

            if (substr($sortField, 0, 1) === '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }

    public function scopeGetOrPaginate(Builder $query): array|Collection|LengthAwarePaginator
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));

            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}
