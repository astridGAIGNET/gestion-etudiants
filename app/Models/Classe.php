<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'formateur_id',
        'place_id',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
