<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $subjects = $this->subjectService->getAll();
        return response()->json(['dados' => $subjects], 200);
    }

    public function create()
    {
        return response()->json(['mensagem' => 'Criar matéria'], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:subjects,name'
        ]);
        $this->subjectService->create($data);
        return response()->json(['mensagem' => 'Matéria criada com sucesso'], 201);
    }

    public function edit($id)
    {
        $subject = $this->subjectService->getById($id);
        return response()->json(['dados' => $subject], 200);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:subjects,name'
        ]);
        $this->subjectService->update($id, $data);
        return response()->json(['mensagem' => 'Matéria atualizada com sucesso'], 200);
    }

    public function destroy($id)
    {
        $this->subjectService->delete($id);
        return response()->json(['mensagem' => 'Matéria deletada com sucesso'], 200);
    }

}
