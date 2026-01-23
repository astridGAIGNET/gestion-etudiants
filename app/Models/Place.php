<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
