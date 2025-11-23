<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_module';

    protected $fillable = [
        'nom_module',
        'code_module',
        'semestre',
        'id_enseignant'
    ];

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'id_enseignant');
    }

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }
}
