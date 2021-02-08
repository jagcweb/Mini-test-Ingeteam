<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    //Modelo para la tabla tasks
    protected $table = 'tasks';


    //Relación con el usuario Many to One. Many Task per User.
    public function user(){
        return $this->belongsTo(User::class);
    }
}
