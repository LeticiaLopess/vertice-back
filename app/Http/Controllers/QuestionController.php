<?php

namespace App\Http\Controllers;

use App\Services\QuestionService;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
        $questions = $this->questionService->getAll();
        return response()->json(['dados' => $questions], 200);
    }

    public function create()
    {
        return response()->json(['mensagem' => 'Criar questão'], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'answer_a' => 'required|string',
            'answer_b' => 'required|string',
            'answer_c' => 'required|string',
            'answer_d' => 'required|string',
            'answer_e' => 'required|string',
            'correct_answer' => 'required|string',
            'status' => 'required|in:active,disqualified,annulled',
            'difficulty' => 'required|in:easy,medium,hard'
        ]);
        $this->questionService->create($data);
        return response()->json(['mensagem' => 'Questão criada com sucesso'], 201);
    }

    public function edit($id)
    {
        $question = $this->questionService->getById($id);
        return response()->json(['dados' => $question], 200);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'topic_id' => 'required|exists:topics,id',
            'answer_a' => 'required|string',
            'answer_b' => 'required|string',
            'answer_c' => 'required|string',
            'answer_d' => 'required|string',
            'answer_e' => 'required|string',
            'correct_answer' => 'required|string',
            'status' => 'required|in:active,disqualified,annulled',
            'difficulty' => 'required|in:easy,medium,hard'
        ]);
        $this->questionService->update($id, $data);
        return response()->json(['mensagem' => 'Questão atualizada com sucesso'], 200);
    }

    public function destroy($id)
    {
        $this->questionService->delete($id);
        return response()->json(['mensagem' => 'Questão deletada com sucesso'], 200);
    }

    public function checkAnswer($questionId, $answer)
    {
        $question = Question::findOrFail($questionId);

        if ($question->isCorrectAnswer($answer)) {
            return response()->json([
                'status' => 200,
                'message' => 'Resposta correta!',
                'correct_answer' => $question->getCorrectAnswerText(),
            ], 200);

        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Resposta incorreta!',
            ], 400);
        }
    }

}
