<?php

namespace App\Services;

use App\Repositories\TodoRepository;

class TodoService {
    private $todoRepository;

    public function __construct(TodoRepository $todoRepository) {
        $this->todoRepository = $todoRepository;
    }

    public function getTodo($id)
    {
        $userID = auth()->user()->id;
        return $this->todoRepository->getTodo($id, $userID);
    }

    public function createTodo($data)
    {
        return $this->todoRepository->createTodo([
            'title' => $data['title'],
            'user_id' => auth()->user()->id,
            'description' => $data['description'],
            'is_completed' => 0,
        ]);
    }

    public function updateTodo($id, $data)
    {
        return $this->todoRepository->updateTodo($id, $data, auth()->user()->id);
    }

    public function deleteTodo($id)
    {
        return $this->todoRepository->deleteTodo($id);
    }


    public function getTodosByUserID($filter)
    {
        return $this->todoRepository->getTodosByUserID(auth()->user()->id, $filter );
    }

    public function completeTodo($id)
    {
        return $this->todoRepository->updateTodo($id, ['is_completed' => 1], auth()->user()->id);
    }
}
