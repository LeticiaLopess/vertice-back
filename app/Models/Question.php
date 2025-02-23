<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question',
        'answer_a',
        'answer_b',
        'answer_c',
        'answer_d',
        'answer_e',
        'correct_answer',
        'topic_id',
        'status',
        'difficulty'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function getCorrectAnswerText()
    {
        $answerField = 'answer_' . $this->correct_answer;
        return $this->{$answerField};
    }

    public function isCorrectAnswer(string $answer)
    {
        return $this->correct_answer === $answer;
    }
}

