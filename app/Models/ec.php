<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EC extends Model
{  protected $table = 'ec';
    protected $primaryKey = 'code_ec';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code_ec',
        'label_ec',
        'description_ec',
        'code_ue',
        'nb_heures_ec',
        'nb_credit_ec'
    ];



}
