<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personnel extends Model
{
    use HasFactory;
    protected $table='personnel';
    protected $primarykey='code_personnel';
   protected $fillable = [
    'code_personnel',
    'nom_personnel',
    'sex_personnel',
    'phone_personnel',
    'login_personnel',
    'password_personnel',
    'type_personnel'
];

}
