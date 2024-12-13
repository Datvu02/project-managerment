<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Directory;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        $myProject = ProjectUser::where('user_id', auth()->id())->get();
        $data = [];
        foreach ($myProject as $pr){
            $project = Project::find($pr->project_id);
            if ($project){
                $users = [];
                $listUser = ProjectUser::where('project_id',$project->id)->get();
                foreach ($listUser as $user_item){
                    $user = User::find($user_item->user_id);
                    if ($user){
                        $users[]=$user;
                    }
                }
                $project['users']=$users;
                $data[]=$project;
            }
        }
        return response()->json([
            'data'=>$data
        ]);
    }
    public function store(Request $request){
        $project = new Project();
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->user_id = auth()->id();
        $project->save();
        if ($request->has('usersId')){
            foreach ($request->input('usersId') as $id){
                $user = User::find($id);
                if ($user){
                    ProjectUser::create([
                        'project_id'=>$project->id,
                        'user_id'=>$id
                    ]);
                }
            }
        }
        $myProject = ProjectUser::where('user_id',auth()->id())->where('project_id', $project->id)->get();
        if(count($myProject) === 0){
            ProjectUser::create([
                'project_id'=>$project->id,
                'user_id'=>auth()->id()
            ]);
        }
        return response()->json([
            'message'=>'Tạo mới thành công'
        ]);
    }
    public function destroy($id){
        $project = Project::find($id);
        $project->delete();
        Directory::where('project_id', $id)->delete();
        ProjectUser::where('project_id', $id)->delete();

        return response()->json([
            'message'=>'xóa thành công'
        ]);
    }
    public function getCards($id){
        $cards = Card::where('project_id', $id)->get();
        return response()->json([
            'data'=>$cards
        ]);
    }
}
