<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TopicService
{
    public function create(array $data)
    {
        $subject = Subject::find($data['subject_id']);

        if (!$subject) {
            throw new ModelNotFoundException('Matéria não encontrada');
        }

        return Topic::create($data);
    }

    public function update($id, array $data)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            throw new ModelNotFoundException('Tópico não encontrado');
        }

        $topic->update($data);
        return $topic;
    }

    public function delete($id)
    {
        $topic = Topic::find($id);

        if (!$topic) {
            throw new ModelNotFoundException('Tópico não encontrado');
        }

        return $topic->delete();
    }

    public function getAll()
    {
        return Topic::all();
    }

    public function getById($id)
    {
        $topic = Topic::find($id);
        if (!$topic) {
            throw new ModelNotFoundException('Tópico não encontrado');
        }

        return $topic;
    }

    public function getTopicsBySubject($subjectId)
    {
        $subject = Subject::find($subjectId);

        if (!$subject) {
            throw new ModelNotFoundException('Matéria não encontrada');
        }

        return $subject->topics()->whereNull('parent_id')->get();
    }

    public function getChildrenTopics($topicId)
    {
        $topic = Topic::find($topicId);

        if (!$topic) {
            throw new ModelNotFoundException('Tópico não encontrado');
        }

        return $topic->children()->orderBy('order')->get();
    }
}
