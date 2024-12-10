<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'departamento_id']; // Ajusta los campos según tu tabla

    /**
     * Relación con el departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Department::class);
    }
}
