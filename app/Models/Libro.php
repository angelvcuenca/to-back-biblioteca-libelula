<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;

use MongoDB\Laravel\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected  $connection = 'mongodb';
    protected  $collection = 'libros';
    protected  $fillable = ['titulo', 'anio_publicacion', 'descripcion'];

    public function autor()
    {
        return $this->belongsToMany(Autor::class, null, 'libro_ids', 'autor_ids');
    }
}
