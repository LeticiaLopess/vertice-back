<?php

namespace App\Http\Controllers;

use App\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    public function index()
    {
        $topics = $this->topicService->getAll();
        return response()->json(['dados' => $topics], 200);
    }

    public function create()
    {
        return response()->json(['mensagem' => 'Criar tópico'], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'subject_id' => 'required|exists:subjects,id'
        ]);
        $this->topicService->create($data);
        return response()->json(['mensagem' => 'Tópico criado com sucesso'], 201);
    }

    public function edit($id)
    {
        $topic = $this->topicService->getById($id);
        return response()->json(['dados' => $topic], 200);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'subject_id' => 'required|exists:subjects,id'
        ]);
        $this->topicService->update($id, $data);
        return response()->json(['mensagem' => 'Tópico atualizado com sucesso'], 200);
    }

    public function destroy($id)
    {
        $this->topicService->delete($id);
        return response()->json(['mensagem' => 'Tópico deletado com sucesso'], 200);
    }
}
