<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

//use Illuminate\Database\Eloquent\Model;



class Autor extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'autores';

    protected $fillable = ['nombre', 'fecha_nacimiento'];

    public function libro(){
        return $this->belongsToMany(Libro::class, null, 'autor_ids', 'libro_ids');
    }
}
