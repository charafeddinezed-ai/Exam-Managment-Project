<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'date_examen',
        'heure_debut',
        'heure_fin',
        'type_examen',
        'etat_validation',
        'id_module',
        'id_salle',
        'id_groupe',
        'id_responsable'
    ];

    protected $casts = [
        'date_examen' => 'date',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'id_groupe');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }

    public function surveillances()
    {
        return $this->hasMany(Surveillance::class, 'id_examen');
    }
}
