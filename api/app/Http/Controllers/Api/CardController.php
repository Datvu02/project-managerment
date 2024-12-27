<?php

namespace App\Http\Controllers\Api;
use App\Models\Card;
use App\Models\Directory;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AttachExistLabelRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Card\UploadFileRequest;
use App\Http\Requests\Card\ChangeStatusCompletedRequest;
use App\Http\Requests\Card\ChangeStatusDeadlineRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\ChangeDirectoryRequest;
use App\Http\Requests\Card\ChangeIndexRequest;
use App\Http\Requests\Card\DetachLabelRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Card\AttachNewLabelWithCardRequest;

class CardController extends Controller
{
    public function show($id)
    {
        $card = Card::findOrFail($id)->load(['labels', 'files', 'directory', 'checkLists', 'checkLists.checkListChilds']);
        $card['user']=User::find($card->user_assign_id);

        return response()->json([
            'code'    => 200,
            'message' => 'success',
            'data'    => $card,
        ]);
    }
    
    public function uploadFile(UploadFileRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->file('file');
            $path = Storage::disk('public')->putFileAs('files', $data, $data->getClientOriginalName());

            $file = new File();
            $file->path = $path;
            $file->name = $data->getClientOriginalName();
            $file->card_id = $id;
            $file->save();
            DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => 'success'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store file', [
                'method'  => __METHOD__,
                'message'  => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCardRequest $request)
    {   
        DB::beginTransaction();
        try {
            $card = new Card();
            $card->title = $request->input('title');
            $card->index = $request->input('index');
            $directory = Directory::find($request->input('directory_id'));
            $card->project_id = $directory->project_id;
            $card->directory_id = $request->input('directory_id');
            $card->user_id = Auth::id();
            $card->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store card', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            if($request->has('title')) {
                $card->title = $request->input('title');
            }
            if($request->has('description')) {
                $card->description = $request->input('description');
            }
            if($request->has('user_assign_id')){
                $card->user_assign_id = $request->input('user_assign_id');
                $user =User::find($card->user_assign_id);
                $project = Project::find(Directory::find($card->directory_id)->project_id);
                // if ($user){
                //     $details = [
                //         'title' => 'M.Work - Nhắc nhở nhiệm vụ mới',
                //         'body' => Auth::user()->name." đã giao cho bạn nhiệm vụ mới trong dự án ".$project->name
                //     ];
                //     \Mail::to($user->email)->send(new \App\Mail\SendMail($details));

                // }
            }
            $card->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update card', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatusCompleted(ChangeStatusCompletedRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            $card->status = $request->input('status');
            $card->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error change status completed', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function changeStatusDeadline(ChangeStatusDeadlineRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            $card->deadline = $request->input('deadline');
            $now = Carbon::now();
            $compareTime = Carbon::parse($card->deadline)->gt($now);

            if ($now->diffInDays($card->deadline) >=1  && $compareTime)
            {
                $card->status = Card::STATUS['default'];
            }

            if ($now->diffInDays($card->deadline) == 0 && $compareTime)
            {
                $card->status = Card::STATUS['is_almost_expired'];
            }

            if ($compareTime != true)
            {
                $card->status = Card::STATUS['expired'];
            }
            $card->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error change status deadline', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeDirectory(ChangeDirectoryRequest $request, $id)
    {   
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            $directoryFirstly = $card->directory_id;
            $indexFirstly = $card->index;
            $indexTowards = $request->input('index');
            $directoryId = $request->input('directory_id');
            $cards = Card::where('directory_id', $directoryId)
            ->where('index', $indexTowards)
            ->get();

            if (count($cards) == 0)
            {
                $card->update([
                    'directory_id' =>  $directoryId,
                    'index' => $indexTowards,
                ]);
            }

            if (count($cards) != 0)
            {
                $indexCards = Card::where('directory_id', $directoryId)
                ->where('index','>=', $indexTowards)
                ->get();

                foreach ($indexCards as $indexCard) {
                    $indexChange = $indexCard->index;
                    $indexCard->update([
                        'index' => $indexChange + 1,
                    ]);
                }
                $card->update([
                    'index' => $indexTowards,
                    'directory_id' => $directoryId,
                ]);
            }
            $dataCards = Card::where([
                ['directory_id' , $directoryFirstly],
                ['index', '>' , $indexFirstly],
            ])->get();

            foreach ($dataCards as $dataCard) {
                $indexCard = $dataCard->index;
                $dataCard->update([
                    "index" => $indexCard - 1
                ]);
            }
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error change directory', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
    }

    public function changeIndex(ChangeIndexRequest $request, $id)
    {   
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            $indexOriginal = $card->index;
            $indexInput = $request->input('index');
            $directoryId = $card->directory_id;
            
            if ($indexOriginal > $indexInput)
            {
                $cards = Card::where([
                    ['directory_id' , $directoryId],
                    ['index' , '<' , $indexOriginal],
                    ['index' , '>=' , $indexInput],
                ])->get();

                foreach ($cards as $item) {
                    $indexCard = $item->index;
                    $item->update([
                        "index" => $indexCard + 1
                    ]);
                }
                $card->update([
                    "index" => $indexInput
                ]);
            }

            if ($indexOriginal < $indexInput)
            {
                $cards = Card::where([
                    ['directory_id' , $directoryId],
                    ['index' , '>' , $indexOriginal],
                    ['index' , '<=' , $indexInput],
                ])->get();

                foreach ($cards as $item) {
                    $indexCard = $item->index;
                    $item->update([
                        "index" => $indexCard - 1
                    ]);
                }
                $card->update([
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
            
    public function attachExistLabel(AttachExistLabelRequest $request , $id) 
    {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);  
            $labelId = $request->input('label_id');
            $card->labels()->attach($labelId);
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success' 
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error attach label with card', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function detachLabel(DetachLabelRequest $request , $id) 
    {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);  
            $labelId = $request->input('label_id');
            $card->labels()->detach($labelId);
            DB::commit();
            
            return response()->json([
                'code' => 200,
                'message' => 'success' 
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error attach label with card', [
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
        $card = Card::findOrFail($id);
        $directoryId = $card->directory_id;
        $card->delete();

        $cards = Card::query()
            ->where('user_id', Auth::id())
            ->where('directory_id', $directoryId)
            ->orderBy('index', 'asc')
            ->get();

        foreach ($cards as $key => $card) {
            $card->index = $key;
            $card->save();
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }

    public function attachNewLabelWithCard(AttachNewLabelWithCardRequest $request, $id) {
        DB::beginTransaction();
        try {
            $card = Card::findOrFail($id);
            $card->labels()->create([
                'name' => $request->input('name'),
                'color' => $request->input('color'),
                'user_id' => Auth::id()
            ]);

            DB::commit();
        
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error attach new label with card', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       

    }
}
