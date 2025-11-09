<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Report;
use App\Models\ReportSchool;
use App\Models\School;
use App\Models\ReportTemp;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolReportController extends Controller
{
    public function index()
    {
        $report_schools = ReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->orderBy('id','DESC')
            ->simplePaginate(10);

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'report_schools'=>$report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.reports.index',$data);
    }

    public function not_index()
    {
        $report_schools = ReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where(function($q){
                $q->where('situation','=',0)
                    ->orWhere('situation','=',1)
                    ->orWhere('situation','=',2)
                    ->orWhere('situation',null);
            })
            ->orderBy('id','DESC')
            ->simplePaginate(10); 

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'report_schools'=>$report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.reports.not_index',$data);
    }

    public function show_person_Signed()
    {
        $report_schools = ReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where('signed_user_id',auth()->user()->id)
            ->orderBy('id','DESC')
            ->simplePaginate(10);

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'report_schools'=>$report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.reports.show_person_Signed',$data);
    }

    public function search(Request $request)
    {
        $want = $request->input('want');

        $report_schools = DB::table('report_schools')
            ->leftJoin('reports', 'report_schools.report_id', '=', 'reports.id')
            ->where('report_schools.code','like',"%".auth()->user()->code."%")
            ->where(function($q) use ($want){
                $q->where('reports.name','like','%'.$want.'%')
                    ->orWhere('reports.id','=',$want)
                    ->orWhere('reports.content','like','%'.$want.'%');
            })
            ->get();            
        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'report_schools'=>$report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
            'want'=>$want,
        ];
        return view('schools.reports.search',$data);
    }

    public function print($id)
    {
        $report_schools = ReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where('id',">=",$id)
            ->orderBy('id','DESC')
            ->get();
        $sections = config('boe.sections');
        $data = [
            'report_schools'=>$report_schools,
            'sections'=>$sections,
        ];
        return view('schools.reports.print',$data);
    }

    public function no_report(ReportSchool $report_school)
    {
        /**
        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['situation'] = 4;

        $report_school->update($att);

        return redirect()->route('school_report.index');
         */

    }

    public function create(ReportSchool $report_school)
    {
        if(date('Ymd') > str_replace('-','',$report_school->report->die_date)){
            return back();
        }
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
            'report_school'=>$report_school,
        ];

        return view('schools.reports.create',$data);
    }

    public function store(Request $request)
    {

        foreach($request->input('type') as $k=>$v){
            if($v=="checkbox"){
                if(empty($request->input('answer_checkbox'.$k))){
                    return back()->withErrors(['errors'=>['每題均為必填！']]);
                }
            }elseif($v=="radio"){

            }
        }

        $report_school = ReportSchool::where('id',$request->input('report_school_id'))
        ->first();
        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['situation'] = 1;

        $report_school->update($att);

        $answer = $request->input('answer');

        foreach($request->input('type') as $k=>$v){
            if($v=="checkbox"){
                $att2['answer'] = "";
                foreach($request->input('answer_checkbox'.$k) as $k1=>$v1){
                    $att2['answer'] .= $v1.",";
                }
                $att2['answer'] = substr($att2['answer'],0,-1);
            }else{

                $att2['answer'] = $answer[$k];
            }
            $att2['report_id'] = $report_school->report_id;
            $att2['question_id'] = $k;
            $att2['report_school_id'] = $report_school->id;
            $att2['school_code'] = auth()->user()->code;
            Answer::create($att2);
        }

        //echo "<body onload='opener.location.reload();window.close();'>";
        echo "
        <script>
        // 確保頁面加載完成後執行
        window.onload = function() {
            // 檢查父頁面是否存在且可以訪問 jQuery
            if (window.parent && window.parent.$) {
                // 關閉 venobox 視窗
                if (typeof window.parent.$.venobox !== 'undefined') {
                    window.parent.$.venobox.close();  // 關閉 venobox 視窗
                }

                // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                window.parent.location.reload();                
            }
        };
        </script>";
    }

    public function show(ReportSchool $report_school)
    {
        $answers = Answer::where('report_school_id',$report_school->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $answer_data[$answer->question_id] = $answer->answer;
        }
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
            'answer_data'=>$answer_data,
            'report_school'=>$report_school,
        ];
        return view('schools.reports.show',$data);
    }

    public function edit(ReportSchool $report_school)
    {
        $answers = Answer::where('report_school_id',$report_school->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $answer_data[$answer->question_id] = $answer->answer;
        }
        $sections = config('boe.sections');
        $data = [
            'answer_data'=>$answer_data,
            'report_school'=>$report_school,
            'sections'=>$sections,
        ];
        return view('schools.reports.edit',$data);
    }

    public function update(Request $request)
    {

        foreach($request->input('type') as $k=>$v){
            if($v=="checkbox"){
                if(empty($request->input('answer_checkbox'.$k))){
                    return back()->withErrors(['errors'=>['每題均為必填！']]);
                }
            }elseif($v=="radio"){

            }
        }

        $report_school = ReportSchool::where('id',$request->input('report_school_id'))
            ->first();
        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['situation'] = 1;

        $report_school->update($att);

        Answer::where('report_school_id',$request->input('report_school_id'))
            ->delete();

        $answer = $request->input('answer');

        foreach($request->input('type') as $k=>$v){
            if($v=="checkbox"){
                $att2['answer'] = "";
                foreach($request->input('answer_checkbox'.$k) as $k1=>$v1){
                    $att2['answer'] .= $v1.",";
                }
                $att2['answer'] = substr($att2['answer'],0,-1);
            }else{

                $att2['answer'] = $answer[$k];
            }
            $att2['report_id'] = $report_school->report_id;
            $att2['question_id'] = $k;
            $att2['report_school_id'] = $report_school->id;
            $att2['school_code'] = auth()->user()->code;
            Answer::create($att2);
        }

        //echo "<body onload='opener.location.reload();window.close();'>";
        echo "
        <script>
        // 確保頁面加載完成後執行
        window.onload = function() {
            // 檢查父頁面是否存在且可以訪問 jQuery
            if (window.parent && window.parent.$) {
                // 關閉 venobox 視窗
                if (typeof window.parent.$.venobox !== 'undefined') {
                    window.parent.$.venobox.close();  // 關閉 venobox 視窗
                }

                // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                window.parent.location.reload();                
            }
        };
        </script>";
    }

    public function back(ReportSchool $report_school)
    {
        $att['situation'] = 0;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->back();
    }

    public function delay(ReportSchool $report_school)
    {
        $att['situation'] = 5;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->back();
    }

    public function cancel(ReportSchool $report_school)
    {
        $att['situation'] = 6;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->back();
    }

    public function passing(ReportSchool $report_school)
    {
        $att['situation'] = 3;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->back();
    }

    public function save_temp(Request $request)
    {
        $att = $request->all();

        $data = [];
        foreach($att['type'] as $k=>$v){
            $data[$k]['type'] = $v;

            $question = Question::find($k);
            $data[$k]['options'] = unserialize($question->options);

            if($v != "checkbox"){
                $data[$k]['answer'] =$att['answer'][$k];
            }else{
                if(isset($att['answer_checkbox'.$k])){
                    $data[$k]['answer'] = $att['answer_checkbox'.$k];
                }
            }
        }

        $att_temp['content'] = serialize($data);
        $att_temp['report_id'] = $att['report_id'];
        $att_temp['code'] = auth()->user()->code;
        $att_temp['user_id'] = auth()->user()->id;

        $check = ReportTemp::where('code',$att_temp['code'])
            ->where('report_id',$att_temp['report_id'])
            ->first();

        if(empty($check)){
            $check = ReportTemp::create($att_temp);
        }else{
            $check->update($att_temp);
        }
        $data = $check->id;

        $result = json_encode($data,true);
        echo $result;
        return ;
    }

    public function pull_temp($report_id)
    {
        $report_temp = ReportTemp::where('code',auth()->user()->code)
            ->where('report_id',$report_id)
            ->first();
        $data = unserialize($report_temp->content);

        $result = json_encode($data,true);
        echo $result;
        return ;
    }
}