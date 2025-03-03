<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\models\Exam\Exam;
use App\models\Listen\AnswerListening;
use App\models\Listen\AudioListening;
use App\models\Listen\Listening;
use App\models\Listen\QuestionListening;
use App\models\Vocabulary\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class ListenController extends Controller
{
    public function listQuestion()
    {
        return view('pages.admin.listening.question.index');

    }

    public function createQuestion()
    {
        return view('pages.admin.listening.question.create');
    }

    public function createAudioAndQuestion(Request $request)
    {
        try {
            if ($request->has('file')) {
                $file = $request->file;
                $file_name = time() . '_' . preg_replace('/\s+/', '', $file->getClientOriginalName());
                $file->move(public_path('upload/audio'), $file_name);

            }
            $audio = AudioListening::create([
                'name' => $request->name,
                'audio' => $file_name,
                'content' =>$request->content,
                'file_type' => $request->file_type
            ]);
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "audio_id" => $audio->id,
                "message" => "Uploaded file media successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function storeQuestion(Request $request)
    {
        try {
            $data = $request->all();
            foreach ($data as $key => $value) {
                $res = QuestionListening::create([
                    "id" => $value['id'],
                    "audio_listening_id" => $value['audio_id'],
                    "question" => $value['question'],
                    "level" => $value['level'],
                    "type" => $value['type']
                ]);
                if ($res->type == 1) {
                    $res->rightAnswers()->create([
                        "answer_id" => $value["answer"]
                    ]);
                }


                foreach ($data[$key]['dataAns'] as $keyAds => $item) {
                    QuestionListening::find($value['id'])->answerListening()->create([
                        "id" => $item['idAns'],
                        "answer_id" => $item['idAns'],
                        "text" => $item['text']
                    ]);
                }
            }
            return [
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ];
        } catch (\Exception $e) {
            return [
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed !"
            ];
        }


    }

    public function getAllAudio(Request $request)
    {
        $params = $request->all();
        $params['page_size'] = $params['page_size'] ?? 10;

        $dataAll = AudioListening::query()->with('questionListening');

        if(isset($request->search) && $request->search) {
            $dataAll = $dataAll->where('name', 'like', '%'. $request->search .'%');
        }
        $dataAll = $dataAll->orderBy('name', 'asc')->paginate($params['page_size'], ['*'], 'page', $params['page_number'] ?? 1);

        return response()->json([
            "status" => 200,
            "errorCode" => 0,
            "data" => $dataAll,

        ]);
    }

    public function editQuestion($id)
    {
        return view('pages.admin.listening.question.detail', compact('id'));
    }

    public function detaiQuestion($id)
    {
        try {
            $query = new AudioListening();
            $dataAll = $query->whereId($id)->with(['questionListening' => function ($question) {
                $question->with('answerListening')->with('rightAnswers');
            }])->first();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $dataAll,


            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => $e->getCode(),
                "errorCode" => $e->getCode(),
                "data" => null,
                "message" => "Failed!"
            ]);
        }
    }

    public function destroyAns(Request $request)
    {
        try {
            AnswerListening::find($request->id)->delete();
            return [
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ];
        } catch (\Exception $e) {
            return [
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ];
        }


    }

    public function updateQuestion(Request $request)
    {
        try {
            $res = QuestionListening::where('id', $request->id)->first();
            $res->update(
                [
                    "question" => $request->question,
                    "level" => $request->level,
                    "type" => $request->type
                ]
            );
            if ($res->type == 1) {
                foreach ($request->dataAns as $keyAds => $item) {
                    $ans = $res->answerListening()->find($item['id']);
                    if (isset($ans)) {
                        $ans->update([
                            "text" => $item['text']
                        ]);
                    } else {
                        $res->answerListening()->create([
                            "id" => $item['id'],
                            "answer_id" => $item['id'],
                            "text" => $item['text']
                        ]);
                    }


                }
                $res->rightAnswers()->find($request->right_answers["id"])->update([
                    "answer_id" => $request->right_answers["answer_id"]
                ]);
            } else {
                $res->answerListening()->delete();
                foreach ($request->dataAns as $keyAds => $item) {
                    $res->answerListening()->create([
                        "id" => $item['id'],
                        "answer_id" => $item['answer_id'],
                        "text" => $item['text']
                    ]);


                }
            }

            return [
                "status" => 200,
                "errorCode" => 0,
                "message" => "Updated successfully"
            ];
        } catch (\Exception $e) {
            return [
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ];
        }



    }
    public function deleteQuestion(Request $request){

        try {
            QuestionListening::find($request->id)->delete();
            return [
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ];
        } catch (\Exception $e) {
            return [
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ];
        }
    }

    public function updateAudio(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $dataQuestion = json_decode($request->question);
            
            $audio = AudioListening::whereId($id)->first();
            if ($request->has('file')) {
                $file = $request->file;
                $file_name = time() . '_' . preg_replace('/\s+/', '', $file->getClientOriginalName());
                $file->move(public_path('upload/audio'), $file_name);
                $audio->update([
                    'name' => $request->name,
                    'audio' => $file_name,
                    'content' => $request->content,
                    'file_type' => $request->file_type
                ]);
            }else {
                $audio->update([
                    'name' => $request->name,
                    'content' =>$request->content
                ]);
            }
            $questionListening = $audio->questionListening()->get()->toArray();
            $toDelete = collect($questionListening)->whereNotIn('id', collect($dataQuestion)->pluck('id'))->all();
            if (count($toDelete)) {
                foreach($toDelete as $item) {
                    QuestionListening::whereId($item['id'])->delete();
                }
            }
            foreach ($dataQuestion as $value) {
                $check = QuestionListening::whereId($value->id)->exists();
                if (!$check) {
                    $question = $audio->questionListening()->create([
                        'question' => $value->question,
                        'level' => $value->level,
                        'type' => $value->type
                    ]);
                    foreach ($value->answers as $keyAns => $valueAns) {
                        $question->answerListening()->create([
                            'id' => $valueAns->id,
                            'question_id' => $question->id,
                            'text' => $valueAns->text,
                            "answer_id" => $valueAns->id,
                        ]);
                    }
                    if ($value->right_answers) {
                        $question->rightAnswers()->create([
                            'answer_id' => $value->right_answers
                        ]);
                    }
                } else {
                    QuestionListening::whereId($value->id)->first()->update([
                        "question" => $value->question,
                        "level" => $value->level,
                        "type" => $value->type
                    ]);
                    if ($value->answers) {
                        QuestionListening::whereId($value->id)->first()->rightAnswers()->update([
                            "answer_id" => $value->right_answers
                        ]);
                    }
                    QuestionListening::whereId($value->id)->first()->answerListening()->delete();
                    foreach ($value->answers as $keyAds => $valueAns) {
                        QuestionListening::whereId($value->id)->first()->answerListening()
                            ->create([
                                'id' => $valueAns->id,
                                'question_id' => $value->id,
                                'text' => $valueAns->text,
                                "answer_id" => $valueAns->id,
                            ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Update question successfully!"
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function deleteAudio(Request $request)
    {
        try {
            $audio = AudioListening::whereId($request->id)->first();

            $audio->delete();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function topicList()
    {
        return view('pages.admin.listening.topic.index');
    }

    public function ListTopic(Request $request)
    {
        
        try {
            if ($request->is_exam) {
                $data = Listening::select('id', 'name', 'is_exam')->where('is_exam', 1)->orderBy('name', 'ASC')->paginate($request->page_size ?? 10);
            } else {
                if ($request->search) {
                    $search = strtolower($request->search);
                    $data = Listening::select('id', 'name', 'is_exam')->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                        ->orderBy('name', 'ASC')->paginate($request->page_size ?? 10);
                } else {
                    $data = Listening::select('id', 'name', 'is_exam')->orderBy('name', 'ASC')->paginate($request->page_size ?? 10);
                }
            }
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $data,
                "message" => "Successful!"
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function createTopic(Request $request)
    {
        try {
            Listening::create([
                "name" => $request->name,
                "description" => $request->description ?? " ",
                "is_exam" => $request->isExam,
            ]);
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }


    }

    public function detailTopic(Request $request, $id)
    {
        return view('pages.admin.listening.topic.detail', compact('id'));
    }

    public function detailTopicData(Request $request, $id)
    {
        try {
            $query = new Listening();
            $data = $query->where('id', $id)->with(['TopicAudioListen' => function ($item) {
                $item->with('questionListening');
            }])->first();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "data" => $data,
                "message" => "Successfull!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }

//        return view('pages.admin.vocabulary.topic.detail');
    }

    public function addAudioToTopic(Request $request)
    {
        try {
            $query = new Listening();
            $query->find($request->idTopic)->TopicAudioListen()->attach(
                [
                    'audio_listen_id' => $request->idAudio
                ]

            );
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function removeAudioFromTopic(Request $request)
    {
        try {
            $query = new Listening();
            $query->find($request->idTopic)->TopicAudioListen()->detach(
                [
                    'audio_listen_id' => $request->idAudio
                ]

            );
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }
    }

    public function editTopic(Request $request)
    {
        try {
            $vocabulary = Listening::find($request->id);
            $vocabulary->update(
                [
                    "name" => $request->name
                ]
            );
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Failed!"
            ]);
        }


    }

    public function deleteTopic(Request $request)
    {
        try {
            $exam = Exam::where('listening_id', $request->id)->first();
            if ($exam) {
                return [
                    "status" => 400,
                    "errorCode" => 400,
                    "message" => "Topic cannot be deleted as there is an active exam using it."
                ];
            }
            $vocabulary = Listening::find($request->id);
            $vocabulary->TopicAudioListen()->detach();
            $vocabulary->delete();
            return response()->json([
                "status" => 200,
                "errorCode" => 0,
                "message" => "Successful!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 400,
                "errorCode" => 400,
                "message" => "Xóa topic thất bại !"
            ]);
        }


    }
}
