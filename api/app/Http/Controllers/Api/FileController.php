<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UpdateFileRequest;

class FileController extends Controller
{
    public function update(UpdateFileRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $file = File::findOrFail($id);
            $file->name = $request->name;
            $file->save();
            DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => 'success'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error update file', [
                'method'  => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message'  => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();
        
        return response()->json([
            'code'    => 200,
            'message' => 'success'
        ]);
    }
}
