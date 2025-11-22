<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_groupe';

    protected $fillable = [
        'nom_groupe',
        'niveau',
        'specialite'
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_groupe');
    }
}
