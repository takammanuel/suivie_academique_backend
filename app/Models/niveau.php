<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class niveau extends Model
{
    use HasFactory;
     protected $table='niveau';
     protected $primarykey ='code_niveau';
     protected $fillable =[
        'code_niveau',
        'label_niveau',
        'description_niveau',
        'code_filiere'

     ];
}
