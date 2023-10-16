<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\models\Learn\ExamResult;
use App\models\LevelTesting\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function index()
    {
        return view('pages.admin.level.index');
    }

    public function createLevel(Request $request)
    {
        try {
            DB::beginTransaction();
            $level = Level::create([
                "name" => $request->name,
            ]);
            $level->Learn()->attach($request->lessons);
            $level->Reading()->attach($request->readings);
            $level->Vocabulary()->attach($request->vocabularies);
            $level->Listen()->attach($request->listenings);
            $level->Grammar()->attach($request->grammars);
            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Thêm level Thành công!"
            ]);
        } catch (\Throwable $th) {
            Log::error("message");
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Thêm level Thất bại!"
            ]);
        }

    }

    public function getAllLevel(Request $request)
    {
        try {
            DB::beginTransaction();
            $userID = Auth::user()->id;
            $level = Level::with('Reading')
             ->with('Vocabulary')->with('Grammar')->with('Listen')->with('Learn')->get();
            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $level,
                "message" => "Lấy danh sách level Thành công!"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Lấy danh sách level Thất bại!"
            ]);
        }

    }

    public function deleteLevel(Request $request)
    {
        try {
            DB::beginTransaction();
            $level = Level::find($request->id)->delete();
            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $level,
                "message" => "Xóa level Thành công!"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Xóa level Thất bại!"
            ]);
        }

    }

    public function detailLevel(Request $request)
    {


        try {
            DB::beginTransaction();
            $level = new Level();
            $level = $level->where('id', $request->id)->with('Learn')->with('Reading')->with('Vocabulary')->with('Grammar')->with('Listen')->first();
            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $level,
                "message" => "Lấy level Thành công!"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Lấy level Thất bại!"
            ]);
        }

    }

    public function updateLevel(Request $request)
    {
        try {
            DB::beginTransaction();
            $level = Level::where('id', $request->id)->first();
            $level->update([
                "name" => $request->name,
            ]);
            $level->Learn()->sync($request->lessons);
            $level->Reading()->sync($request->readings);
            $level->Vocabulary()->sync($request->vocabularies);
            $level->Listen()->sync($request->listenings);
            $level->Grammar()->sync($request->grammars);
            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "update level Thành công!"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "update level Thất bại!"
            ]);
        }


    }

    public function checkLevelPassed()
    {
        $userID = Auth::user()->id;
        $count = DB::table('levels')->count();
        $rows = DB::table('levels')->join('exam_result', 'levels.id', '=', 'exam_result.level_id')->where('user_id', $userID)->get();
        $sum = 1;
        foreach ($rows as $row) {
            if (($row->reading_id == null || $row->is_done_read == 1)
            &&
            ($row->vocabulary_id == null || $row->is_done_vocabulary == 1)
            &&
           ( $row->listening_id == null || $row->is_done_listen == 1)
            &&
            ($row->grammar_id == null || $row->is_done_grammar == 1)) {
                $sum++;
            }
        }

        if($sum > $count){
            $sum = $count;
        }
        return response()->json([
            "data" => $sum,
            'status'=> 200
        ], 200);
    }
}
