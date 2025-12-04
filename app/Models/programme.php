<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $table = 'programme'; // nom exact de la table

    protected $primaryKey = 'code_programme'; // si tu utilises une clé personnalisée
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_programme',      // ← à inclure si utilisé comme clé
        'code_ec',
        'code_salle',
        'heure_fin',
        'heure_debut',
        'code_personel',
        'nombre_dheure',
        'statut',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    // Relations
    public function ec()
    {
        return $this->belongsTo(EC::class, 'code_ec', 'code_ec');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'code_salle', 'code_salle');
    }
}
