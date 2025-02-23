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

    public function getGroupingInfo()
    {
        return [
            'subject' => $this->topic->subject->name,
            'topic' => $this->topic->name,
            'order' => $this->topic->order,
            'level' => $this->topic->level,
        ];
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

    public function getGroupedQuestions()
    {
        $questions = Question::with('topic.subject')->get();

        $groupedQuestions = [];
        foreach ($questions as $question) {
            $subject = $question->topic->subject->name;
            $topic = $question->topic->name;
            $order = $question->topic->order;
            $level = $question->topic->level;

            $groupedQuestions[$subject][$topic][$order][$level][] = $question;
        }

        return $groupedQuestions;
    }
}

