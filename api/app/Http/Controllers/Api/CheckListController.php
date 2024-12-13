<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CheckList;
use App\Http\Requests\CheckList\StoreCheckListRequest;
use App\Http\Requests\CheckList\UpdateCheckListRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;

class CheckListController extends Controller
{
    public function store(StoreCheckListRequest $request)
    {
        DB::beginTransaction();
        try {
            $checkList = new CheckList();
            $checkList->title = $request->input('title');
            $checkList->status = CheckList::STATUS['default'];
            $checkList->card_id = $request->input('card_id');
            $checkList->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch(Exception $e) {
            DB::rollback();
            Log::error('Error store checkList', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCheckListRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $checkList = CheckList::findOrFail($id);
            $checkList->title = $request->input('title');
            $checkList->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update checkList', [
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
        $checkList = CheckList::findOrFail($id);
        $checkList->delete();
        
        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
