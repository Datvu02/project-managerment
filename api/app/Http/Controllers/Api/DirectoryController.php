<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Directory\ChangeIndexRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Directory;
use App\Http\Requests\Directory\StoreDirectoryRequest;
use App\Http\Requests\Directory\UpdateDirectoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Directory::query();
        $directories = $query->where('project_id', $request->input('project_id'))
                                ->with(['cards' => function($query) {
                                                        $query->orderBy('index', 'asc')->with('labels');
                                                        }])->orderBy('index', 'asc')->get();
        foreach ($directories as $directory){
            foreach ($directory->cards as $card){
                $card['user'] = User::find($card->user_assign_id);
            }
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $directories,
        ]);
    }

    public function store(StoreDirectoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $directory = new Directory();
            $directory->title = $request->input('title');
            $directory->user_id = Auth::id();
            $directory->index = $request->input('index');
            $directory->project_id = $request->input('project_id');
            $directory->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => $directory
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store directory', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
    }

    public function update(UpdateDirectoryRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $directory = Directory::findOrFail($id);
            $directory->title = $request->input('title');
            $directory->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update directory', [
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
        $directory = Directory::findOrFail($id);
        $directory->delete();

        $directories = Directory::query()
            ->where('user_id', Auth::id())
            ->orderBy('index', 'asc')
            ->get();

        foreach ($directories as $key => $directory) {
            $directory->index = $key;
            $directory->save();
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    public function changeIndex(ChangeIndexRequest $request, $id)
    {   
        DB::beginTransaction();
        try {
            $directory = Directory::findOrFail($id);
            
            $indexOriginal= $directory->index;
           
            $indexInput = $request->input('index');

            if ($indexOriginal > $indexInput)
            {
                $datas = Directory::where([
                    ['user_id', Auth::id()],
                    ['index', '<', $indexOriginal],
                    ['index', '>=' , $indexInput],
                ])->get();
                
                foreach($datas as $data){
                    $indexDirectory = $data->index;
                    $data->update([
                        "index" => $indexDirectory + 1
                    ]);
                }
                $directory->update([
                    "index" => $indexInput
                ]);
            }

            if ($indexOriginal < $indexInput)
            {
                $datas = Directory::where([
                    ['user_id', Auth::id()],
                    ['index','>', $indexOriginal],
                    ['index', '<=' , $indexInput],
                ])->get();

                foreach($datas as $data){
                    $indexDirectory = $data->index;
                    $data->update([
                        "index" => $indexDirectory - 1
                    ]);
                }
                $directory->update([
                    "index" => $indexInput
                ]);
            }
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error change index', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
