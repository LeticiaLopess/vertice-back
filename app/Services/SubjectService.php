<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubjectService
{
    public function getAll()
    {
        return Subject::all();
    }

    public function getById($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            throw new ModelNotFoundException('Matéria não encontrada');
        }

        return $subject;
    }

    public function create(array $data)
    {
        return Subject::create($data);
    }

    public function update($id, array $data)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            throw new ModelNotFoundException('Matéria não encontrada');
        }

        $subject->update($data);
        return $subject;
    }

    public function delete($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            throw new ModelNotFoundException('Matéria não encontrada');
        }

        return $subject->delete();
    }
}
