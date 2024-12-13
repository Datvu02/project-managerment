<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Http\Requests\Label\UpdateLabelRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{   
    public function index(Request $request)
    {   
        $data = $request->all();
        $query = Label::query();

        if ($request->has('q') && strlen($request->input('q')) > 0)
        {
            $query->where('name', 'LIKE', '%' . $data['q'] . '%');
        }
        $labels = $query->where('user_id',Auth::id())->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $labels,
        ]);

    }

    public function update(UpdateLabelRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $label = Label::findOrFail($id);
            $label->name = $request->input('name');
            $label->color = $request->input('color');
            $label->save();
            DB::commit(); 

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update label', [
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
        $label = Label::findOrFail($id);
        $label->delete();

        return response()->json([
            'code' => 200,
            'message' => 'success',
        ]);
    }
}
