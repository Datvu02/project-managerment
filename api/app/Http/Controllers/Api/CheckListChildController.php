<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CheckListChild;
use App\Http\Requests\CheckListChild\StoreCheckListChildRequest;
use App\Http\Requests\CheckListChild\UpdateCheckListChildRequest;
use App\Http\Requests\CheckListChild\ChangeStatusRequest;
use App\Models\CheckList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;
use App\Http\Requests\Card\UploadFileRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class CheckListChildController extends Controller
{
    public function store(StoreCheckListChildRequest $request)
    {   
        DB::beginTransaction();
        try {
            $checkListChild = new CheckListChild();
            $checkListChild->title = $request->input('title');
            $checkListChild->check_list_id = $request->input('check_list_id');
            $checkListChild->status = CheckListChild::STATUS['default'];
            $checkListChild->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error store checkListChild', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCheckListChildRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $checkListChild = CheckListChild::findOrFail($id);
            $checkListChild->title = $request->input('title');
            $checkListChild->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update checkListChild', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus(ChangeStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $checkListChild = CheckListChild::findOrFail($id);
            $checkListChild->status = $request->input('status');
            $checkListChild->save();

            if ($checkListChild->check_list_id != null)
            {
                $checkList = CheckList::findOrFail($checkListChild->check_list_id);
                $status = CheckListChild::where([
                    ['status', CheckList::STATUS['default']],
                    ['check_list_id', $checkListChild->check_list_id]
                ])->orWhere('status', null)->get();

                if (count($status) == 0)
                {
                    $checkList->status = CheckList::STATUS['done'];
                }

                if (count($status) != 0)
                {
                    $checkList->status = CheckList::STATUS['default'];
                }
                $checkList->save();
            }
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error change status check list child', [
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
        $checkListChild = CheckListChild::findOrFail($id);
        $checkListChild->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
    
    public function uploadFileCheckList(UploadFileRequest $request, $id)
    {
        Log::info('Upload file check list child');
        DB::beginTransaction();
        try {
            $data = $request->file('file');
            $path = Storage::disk('public')->putFileAs('files', $data, $data->getClientOriginalName());

            $file = new File();
            $file->path = $path;
            $file->name = $data->getClientOriginalName();
            $file->check_list_child_id = $id;
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

    public function getFiles($id)
    {
        try {
            $files = File::where('check_list_child_id', $id)->get();
            return response()->json([
                'code'    => 200,
                'message' => 'success',
                'data'    => $files
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
