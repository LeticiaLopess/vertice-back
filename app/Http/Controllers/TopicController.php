<?php

namespace App\Http\Controllers;

use App\Services\TopicService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class TopicController extends Controller
{
    protected $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    public function index()
    {
        try {
            $topics = $this->topicService->getAll();
            return response()->json([
                'message' => 'Tópicos recuperados com sucesso',
                'data' => $topics
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar tópicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return response()->json([
            'message' => 'Página de criação de tópico'
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'subject_id' => 'required|exists:subjects,id'
            ]);

            $this->topicService->create($data);
            return response()->json([
                'message' => 'Tópico criado com sucesso'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao criar tópico no banco de dados',
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
            $topic = $this->topicService->getById($id);
            return response()->json([
                'message' => 'Tópico recuperado com sucesso',
                'data' => $topic
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tópico não encontrado',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar tópico',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'subject_id' => 'required|exists:subjects,id'
            ]);

            $this->topicService->update($id, $data);
            return response()->json([
                'message' => 'Tópico atualizado com sucesso'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tópico não encontrado',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao atualizar tópico no banco de dados',
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
            $this->topicService->delete($id);
            return response()->json([
                'message' => 'Tópico deletado com sucesso'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tópico não encontrado',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao deletar tópico no banco de dados',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTopicsBySubject($subjectId)
    {
        try {
            $topics = $this->topicService->getTopicsBySubject($subjectId);
            return response()->json([
                'message' => 'Tópicos recuperados com sucesso',
                'data' => $topics
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Disciplina não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar tópicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getChildrenTopics($topicId)
    {
        try {
            $children = $this->topicService->getChildrenTopics($topicId);
            return response()->json([
                'message' => 'Subtópicos recuperados com sucesso',
                'data' => $children
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tópico não encontrado',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar subtópicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
