<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'code', 'professor_name'];

    public function lessons() {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
