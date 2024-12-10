<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code']; // Ajusta los campos según tu tabla

    /**
     * Relación con municipios.
     */
    public function municipios()
    {
        return $this->hasMany(Municipality::class);
    }
}
