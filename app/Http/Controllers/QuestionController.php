<?php

namespace App\Http\Controllers;

use App\Services\QuestionService;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class QuestionController extends Controller
{
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function index()
    {
        try {
            $questions = $this->questionService->getAll();
            return response()->json([
                'message' => 'Questões recuperadas com sucesso',
                'data' => $questions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar questões',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return response()->json([
            'message' => 'Página de criação de questão'
        ], 200);
    }

    public function store(Request $request)
    {
        try {
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
            return response()->json([
                'message' => 'Questão criada com sucesso'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao criar questão no banco de dados',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $question = $this->questionService->getById($id);

            return response()->json([
                'message' => 'Questão recuperada com sucesso',
                'data' => $question
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Questão não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar questão',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
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
            return response()->json([
                'message' => 'Questão atualizada com sucesso'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Questão não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao atualizar questão no banco de dados',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->questionService->delete($id);
            return response()->json([
                'message' => 'Questão deletada com sucesso'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Questão não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao deletar questão no banco de dados',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkAnswer($questionId, $answer)
    {
        try {
            $question = Question::findOrFail($questionId);

            if ($question->isCorrectAnswer($answer)) {
                return response()->json([
                    'message' => 'Resposta correta!',
                    'data' => [
                        'correct_answer' => $question->getCorrectAnswerText()
                    ]
                ], 200);

            } else {
                return response()->json([
                    'message' => 'Resposta incorreta!'
                ], 400);
            }

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Questão não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getGroupedQuestions()
    {
        try {
            $questions = Question::with('topic.subject')->get();

            $groupedQuestions = [];
            foreach ($questions as $question) {
                $subject = $question->topic->subject->name;
                $topic = $question->topic->name;
                $order = $question->topic->order;
                $level = $question->topic->level;

                $groupedQuestions[$subject][$topic][$order][$level][] = $question;
            }

            return response()->json([
                'message' => 'Questões agrupadas recuperadas com sucesso',
                'data' => $groupedQuestions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar questões agrupadas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
