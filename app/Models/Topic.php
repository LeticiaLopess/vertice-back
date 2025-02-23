<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'subject_id', 'parent_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function children() // Relacionamento recursivo
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function parent() // Relacionamento recursivo
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
