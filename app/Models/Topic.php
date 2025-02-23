<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'subject_id', 'parent_id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($topic) {
            $lastOrder = self::where('subject_id', $topic->subject_id)
                ->where('parent_id', $topic->parent_id)
                ->max('order');

            $topic->order = $lastOrder ? $lastOrder + 1 : 1;

            if ($topic->parent_id) {
                $parentTopic = self::find($topic->parent_id);
                $topic->level = $parentTopic->level + 1;

            } else {
                $topic->level = 0;
            }
        });
    }

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
