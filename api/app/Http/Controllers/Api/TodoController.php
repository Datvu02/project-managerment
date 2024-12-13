<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\StoreTodoRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Models\Todo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::query()->paginate();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $todos,
        ]);
    }

    public function store(StoreTodoRequest $request)
    {
        DB::beginTransaction();
        try {
            $todo = new Todo();
            $todo->title = $request->input('title');
            $todo->is_complete = 0;
            $todo->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store todo', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
    }

    public function update(UpdateTodoRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $todo = Todo::findOrFail($id);
            $todo->is_complete = $request->input('is_complete');
            $todo->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update todo', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function destroy($id)
    {
        Todo::destroy($id);

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
