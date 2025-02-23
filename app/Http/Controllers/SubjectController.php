<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        try {
            $subjects = $this->subjectService->getAll();
            return response()->json([
                'message' => 'Matérias recuperadas com sucesso',
                'data' => $subjects
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar matérias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return response()->json([
            'message' => 'Página de criação de matéria'
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:subjects,name'
            ]);

            $this->subjectService->create($data);
            return response()->json([
                'message' => 'Matéria criada com sucesso'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao criar matéria no banco de dados',
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
            $subject = $this->subjectService->getById($id);
            return response()->json([
                'message' => 'Matéria recuperada com sucesso',
                'data' => $subject
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Matéria não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Falha ao recuperar matéria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:subjects,name,' . $id
            ]);

            $this->subjectService->update($id, $data);
            return response()->json([
                'message' => 'Matéria atualizada com sucesso'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'error' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Matéria não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao atualizar matéria no banco de dados',
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
            $this->subjectService->delete($id);
            return response()->json([
                'message' => 'Matéria deletada com sucesso'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Matéria não encontrada',
                'error' => $e->getMessage()
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Falha ao deletar matéria no banco de dados',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno no servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
