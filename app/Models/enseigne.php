<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseigne extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'enseigne';

    // Colonnes autorisées pour l'insertion/mise à jour
    protected $fillable = [
        'code_ec',
        'code_personnel',
    ];

    // Relations
    public function ec()
    {
        return $this->belongsTo(Ec::class, 'code_ec', 'code_ec');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'code_personnel', 'code_personnel');
    }
}
