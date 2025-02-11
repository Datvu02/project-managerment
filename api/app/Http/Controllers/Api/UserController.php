<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\ProjectUser;
use App\Models\Directory;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request) {
        DB::beginTransaction();
        try {
            $user = User::findOrFail(Auth::id());

            if ($request->has('name'))
            {
                $user->name = $request->input('name');
            }

            if($request->hasFile('avatar'))
            {
                $avatar = Storage::disk('users')->putFile('', new File($request->file('avatar')));
                $user->avatar = $avatar;
            }

            $user->save();

            DB::commit();
        
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch(Exception $e) {
            DB::rollback();
            Log::error('Error update user', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listUser(){
        return response()->json([
           'data'=>User::all()
        ]);
    }

    public function listUserByProject($id){
        $userIds = ProjectUser::where('project_id', $id)->get();
        $data = [];
        foreach ($userIds as $userId){
            $user = User::find($userId->user_id);
            if ($user){
                $data[]=$user;
            }
        }
        return response()->json([
            'data'=>$data
        ]);
    }

    public function getUserAdminByProject($id){
        $project = Project::where('id', $id)->first();
        $admin = User::where('id', $project->user_id)->first();
        // dd($project->user_id, Auth::id());
        if ($project->user_id == Auth::id()){
            return response()->json([
                'data'=> 0
            ]);
        }
        return response()->json([
            'data'=> 1
        ]);
    }
    
    public function getUserAdminByDirectory($id){
        $directory = Directory::find($id);
        $admin = User::where('user_id', $directory->user_id)->first();
        return response()->json([
            'data'=>$admin
        ]);
    }

    public function changePassword(ChangePasswordRequest $request) {
        DB::beginTransaction();
        try {
            $user = User::findOrFail(Auth::id());
            $user->password = bcrypt($request->input('password'));
            $user->save();

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);

        } catch(Exception $e) {
            DB::rollback();
            Log::error('Error change password', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function searchUserByEmail($email)
    {
        DB::beginTransaction();
        try {
            // Tìm kiếm tất cả người dùng có email khớp
            $users = User::where('email', 'like', '%' . $email . '%')->get();

            // Nếu không tìm thấy người dùng nào
            if ($users->isEmpty()) {
                return response()->json([
                    'message' => 'Không tìm thấy người dùng'
                ], Response::HTTP_NOT_FOUND);
            }

            DB::commit();
            return response()->json([
                'data' => $users
            ]);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error get user', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
}

}
