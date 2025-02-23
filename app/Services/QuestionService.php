<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionService
{
    public function getAll()
    {
        return Question::all();
    }

    public function getById($id)
    {
        $question = Question::find($id);

        if (!$question) {
            throw new ModelNotFoundException('Questão não encontrada');
        }

        return $question;
    }

    public function create(array $data)
    {
        return Question::create($data);
    }

    public function update($id, array $data)
    {
        $question = Question::find($id);

        if (!$question) {
            throw new ModelNotFoundException('Questão não encontrada');
        }

        $question->update($data);
        return $question;
    }

    public function delete($id)
    {
        $question = Question::find($id);

        if (!$question) {
            throw new ModelNotFoundException('Questão não encontrada');
        }

        return $question->delete();
    }

    public function getByStatus($status)
    {
        return Question::where('status', $status)->get();
    }

    public function getByDifficulty($difficulty)
    {
        return Question::where('difficulty', $difficulty)->get();
    }
}
