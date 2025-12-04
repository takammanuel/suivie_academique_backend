<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $table = 'salle'; // ← important : nom exact de la table

    protected $primaryKey = 'code_salle';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_salle',
        'contenance',
        'status',
    ];
}
