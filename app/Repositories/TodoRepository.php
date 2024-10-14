<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository {

    public function getAllTodo()
    {
        return Todo::all();
    }


    public function getTodo($id, $user_id)
    {
       return Todo::where('id', $id)->where('user_id', $user_id)->first();
    }

    public function createTodo($data)
    {
        return Todo::create($data);
    }

    public function updateTodo($id, $data, $user_id)
    {
        $todo = Todo::find($id)->where('user_id', $user_id);
        $todo->update($data);
        return $todo;
    }

    public function deleteTodo($id)
    {
        $link = Todo::find($id);
        $link->delete();
        return $link;
    }

    public function getTodosByUserID($id, $filter)
    {
        switch ($filter) {
            default:
                return Todo::where('user_id', $id)->get();
            case 'completed':
                return Todo::where('user_id', $id)->where('is_completed', 1)->get();
            case 'uncompleted':
                return Todo::where('user_id', $id)->where('is_completed', 0)->get();
        }
    }
}