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
}
