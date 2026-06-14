<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Report;
use App\Models\ReportSchool;
use App\Models\School;
use App\Models\UserPower;
use Rap2hpoutre\FastExcel\FastExcel;
use Purifier;
use Illuminate\Support\Str;

class EduReportController extends Controller
{
    public function index()
    {        
        $reports = Report::where('user_id',auth()->user()->id)
            ->where('situation','<>',3)
            ->where('situation','<>',4)
            ->orderBy('id','DESC')
            ->paginate(20);        
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [            
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('edus.reports.index',$data);
    }

    public function passing()
    {        
        $reports = Report::where('user_id',auth()->user()->id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(20);
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [            
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('edus.reports.passing',$data);
    }

    public function create()
    {
        $sections = config('boe.sections');
        $data = [
            'select_school'=>'',
            'sections'=>$sections,
        ];
        return view('edus.reports.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'die_date' => 'required',
            'die_date' => 'required|date_format:Y-m-d',
            'files.*'=>'nullable|max:10240',
            'title.*'=>'required',
            'sel_school'=>'required',
        ]);

        $att['user_id'] = auth()->user()->id;
        $att['name'] = $request->input('name');
        $att['die_date'] = $request->input('die_date');
        $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph'=>false));
        if($request->input('form_action')=="送出審核不再修改"){
            $att['situation'] = "1";
        }elseif($request->input('form_action')=="暫存"){
            $att['situation'] = "-1";
        }
        $att['section_id'] = auth()->user()->section_id;

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if(!empty($request->input('sel_school'))){
            $school_set=checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_'.$key] = $value;
            }
        }

        //檢查檔案
        $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
        $report = Report::create($att);

        //公務電話
        $user_att['telephone'] = $request->input('telephone');
        auth()->user()->update($user_att);

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    //'size' => $file->getClientSize(),
                ];
                if ( $info['extension'] && !in_array($info['extension'],$allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                                      
                $file->storeAs('public/report_files/'.$report->id, $safeName);
            }
        }


        foreach($request->input('title') as $k=>$v){
            $att2['title'] = Purifier::clean($v, array('AutoFormat.AutoParagraph'=>false));
            $type = $request->input('type');
            $att2['type'] = $type[$k];
            if($att2['type']=="radio" or $att2['type'] == "checkbox"){
                $options = serialize(Purifier::clean($request->input('option'.$k), array('AutoFormat.AutoParagraph'=>false)));
            }elseif($att2['type']=="text" or $att2['type']=="num"){
                $options = null;
            }
            $att2['options'] = $options;

            $att2['report_id'] = $report->id;
            $att2['show'] = 1;
            Question::create($att2);
        }


        return redirect()->route('edu_report.index');
    }

    public function date_late(Report $report)
    {

        $data = [
            'report'=>$report,
        ];
        return view('edus.reports.date_late',$data);
    }

    public function save_date_late(Request $request,Report $report)
    {
        if($report->user_id == auth()->user()->id) {
            $report->update($request->all());
        }
        $att1['situation'] = null;
        $att1['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',5)->update($att1);

        $att2['situation'] = 1;
        $att2['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',0)->update($att2);

        $att3['situation'] = 1;
        $att3['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',2)->update($att3);

        $att4['situation'] = 1;
        $att4['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',4)->update($att4);

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

    public function resend(Report $report)
    {
        if($report->user_id == auth()->user()->id){
            $att['situation'] = 1;
            $report->update($att);
        }

        return redirect()->route('edu_report.index');
    }

    public function destroy(Request $request,Report $report)
    {
        if($report->situation==3 or $report->situation==4){
            abort(404,'都審核或廢除了，還想偷改？');
        }
        if($report->user_id == auth()->user()->id){
            Question::where('report_id',$report->id)->delete();
            Answer::where('report_id',$report->id)->delete();
            ReportSchool::where('report_id',$report->id)->delete();
            $report->delete();            
        }
        return redirect()->route('edu_report.index');
    }

    /**
    public function question_destroy(Question $question)
    {
        if($question->report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)){
            $question->delete();
        }
        return redirect()->route('edu_report.show',$question->report->id);
    }


    public function school_destroy(Report $report,$school_id)
    {
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)){
            $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
            $select_school .= ",";
            $select_school = str_replace($school_id.",","",$select_school);

            $att['school_set_0'] = null;
            $att['school_set_1'] = null;
            $att['school_set_2'] = null;
            $att['school_set_3'] = null;
            $att['school_set_4'] = null;

            $report->update($att);

            $school_set=checkbox_val(explode(',',$select_school));
            foreach ($school_set as $key => $value) {
                $att2['school_set_'.$key] = $value;
            }

            $report->update($att2);

        }
        return redirect()->route('edu_report.show',$report->id);
    }
     *
     * */

    public function show(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $a = ['A','B','C','D','E','F','G','H','I','J'];
            $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();
            if(!$user_power){
                abort(404,'你想做什麼壞事？');
            }            
        }
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $school_select = School::orderBy('code_no')->get();

        $files = get_files(storage_path('app/public/report_files/' . $report->id));

        $sections = config('boe.sections');

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'school_select'=>$school_select,
            'old_schools'=>$old_schools,
            'files'=>$files,
            'sections'=>$sections,
        ];

        return view('edus.reports.show',$data);
    }

    public function print(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $a = ['A','B','C','D','E','F','G','H','I','J'];
            $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();
            if(!$user_power){
                abort(404,'你想做什麼壞事？');
            }            
        }
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $school_select = School::orderBy('code_no')->get();

        $files = get_files(storage_path('app/public/report_files/' . $report->id));

        $sections = config('boe.sections');

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'school_select'=>$school_select,
            'old_schools'=>$old_schools,
            'files'=>$files,
            'sections'=>$sections,
        ];

        return view('edus.reports.print',$data);
    }

    public function download($id,$filename)
    {
        $file = storage_path('app/public/report_files/' . $id . '/' . $filename);
        return response()->download($file);
    }

    public function delete_file($id,$filename)
    {
        $file = storage_path('app/public/report_files/' . $id . '/' . $filename);
        if(file_exists($file)){
            unlink($file);
        }
        return redirect()->route('edu_report.edit',$id);
    }

    public function edit(Report $report)
    {
        if($report->situation==3 or $report->situation==4){
            abort(404,'都審核或廢除了，還想偷改？');
        }
        $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
        $files = get_files(storage_path('app/public/report_files/' . $report->id));
        $sections = config('boe.sections');
        $data = [
            'report'=>$report,
            'select_school'=>$select_school,
            'files'=>$files,
            'sections'=>$sections,
        ];

        return view('edus.reports.edit',$data);
    }

    public function copy(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            $a = ['A','B','C','D','E','F','G','H','I','J'];
            $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();
            if(!$user_power){
                abort(404,'你想做什麼壞事？');
            }            
        }
        $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
        $files = get_files(storage_path('app/public/report_files/' . $report->id));
        $sections = config('boe.sections');
        $data = [
            'report'=>$report,
            'select_school'=>$select_school,
            'files'=>$files,
            'sections'=>$sections,
        ];

        return view('edus.reports.copy',$data);
    }
/**
    public function add_one(Request $request)
    {
        $att['report_id'] = $request->input('report_id');
        $att['title'] = $request->input('title');

        $report = Report::where('id',$att['report_id'])->first();
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)) {
            Question::create($att);
        }

        return redirect()->route('edu_report.show',$att['report_id']);
    }

    public function add_one_school(Request $request)
    {
        $report = Report::where('id', $request->input('report_id'))->first();

        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)) {
            $old_schools = $request->input('old_schools');
            $select_school = $old_schools . ',' . $request->input('new_school');

            $sel_school = explode(',', $select_school);

            $att['school_set_0'] = null;
            $att['school_set_1'] = null;
            $att['school_set_2'] = null;
            $att['school_set_3'] = null;
            $att['school_set_4'] = null;

            $report->update($att);

            $school_set = checkbox_val($sel_school);
            foreach ($school_set as $key => $value) {
                $att2['school_set_' . $key] = $value;
            }

            $report->update($att2);
        }

        return redirect()->route('edu_report.show',$report->id);
    }
 * **/

    public function update(Request $request,Report $report)
    {
        //dd($request->all());

        if($report->user_id != auth()->user()->id){
            return back();
        }
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->section_id,auth()->user()->id)){
            $att['name'] = $request->input('name');
            $att['content'] = $request->input('content');
            $att['die_date'] = $request->input('die_date');

            if($request->input('form_action')=="送出審核不再修改"){
                $att['situation'] = "1";
            }elseif($request->input('form_action')=="暫存"){
                $att['situation'] = "-1";
            }
            // 勾選的學校使用 5 個 BigInt 欄位儲存
            if(!empty($request->input('sel_school'))){
                $school_set=checkbox_val($request->input('sel_school'));
                foreach ($school_set as $key => $value) {
                    $att['school_set_'.$key] = $value;
                }
            }

   
      
            $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph'=>false));
            $att['name'] = Purifier::clean($request->input('name'), array('AutoFormat.AutoParagraph'=>false));

            $report->update($att);

            //處理檔案上傳
            $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach($files as $file){
                    $info = [
                        'mime-type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        //'size' => $file->getClientSize(),
                    ];
                    if ( $info['extension'] && !in_array($info['extension'],$allowed_extensions)) {
                      continue;
                    }
                    $extension = pathinfo($info['original_filename'], PATHINFO_EXTENSION);

                    // 2. 取得主檔名 (2023_{台北市}_中山國小)
                    $mainName = pathinfo($info['original_filename'], PATHINFO_FILENAME);

                    // 3. 只清理主檔名的符號
                    $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                    // 4. 處理連續底線並組合回副檔名
                    $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                    $safeName = $safeMainName . '.' . $extension;                       
        
                    $file->storeAs('public/report_files/'.$report->id, $safeName);
                }
            }

            $a['show'] = 0;
            $qs = Question::where('report_id',$report->id)->get();
            foreach($qs as $q){
                $q->update($a);
            }

            foreach($request->input('title') as $k=>$v){
                $att2['title'] = $v;

                $type = $request->input('type');
                $att2['type'] = $type[$k];
                if($att2['type']=="radio" or $att2['type'] == "checkbox"){
                    $options = serialize($request->input('option'.$k));
                }elseif($att2['type']=="text" or $att2['type']=="num"){
                    $options = null;
                }
                $att2['options'] = $options;

                $att2['report_id'] = $report->id;
                $att2['show'] = 1;
                Question::create($att2);
            }


        }
        return redirect()->route('edu_report.index');
    }

    /**
    public function add_file(Request $request)
    {
        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                $file->storeAs('public/report_files/'.$request->input('report_id'), $info['original_filename']);
            }
        }

        return redirect()->route('edu_report.show',$request->input('report_id'));
    }
    */

    public function del_file($id,$file)
    {
        $report = Report::find($id);
        if($report->user_id != auth()->user()->id){
            return back();
        }
        $file_path = storage_path('app/public/report_files/'.$id.'/'.$file);
        if(file_exists($file_path)){
            unlink($file_path);
        }
        return redirect()->route('edu_report.show',$id);
    }

    public function review()
    {
        //取得他管理的科室
        $user_power = UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->first();

        $regular_reports = RegularReport::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->orderBy('id','DESC')
            ->get();

        $reports = Report::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->orderBy('id','DESC')
            ->paginate(15);

        $situation = config('boe.situation');
        $sections = config('boe.sections');        
        $data = [
            'regular_reports'=>$regular_reports,
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
            'power_section_id'=>$user_power->section_id,
        ];
        return view('reports.edu.review',$data);
    }

    public function return(Request $request,Report $report)
    {
        $att['situation'] = 0;
        $report->update($att);
        return redirect()->route('posts.review');
    }

    public function approve(Report $report)
    {
        $att['situation'] = 3;
        $att['passed_at'] = substr(now(),0,19);
        $att['pass_user_id'] = auth()->user()->id;
        $report->update($att);

        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));

        $select_schools = explode(", ", $select_schools);

        $schools = School::whereIn('id', $select_schools)->get();

        // 1. 先把這份 report 已經存在的 code 全部抓出來
        $existingCodes = ReportSchool::where('report_id', $report->id)
                                    ->pluck('code')
                                    ->toArray();

        $postSchools = array();

        foreach ($schools as $school) {
            // 2. 檢查這次要寫入的 code 是否已存在於「資料庫已有的」或「本次迴圈剛加進去的」
            if (!in_array($school->code_no, $existingCodes)) {
                $postSchools[] = [
                    'report_id'  => $report->id,
                    'code'       => $school->code_no,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // 3. 預防傳入的 $schools 變數本身有重複項
                $existingCodes[] = $school->code_no;
            }
        }

        // 4. 使用 insertOrIgnore 進行最終寫入
        if (!empty($postSchools)) {
            // 即使 PHP 沒擋掉，資料庫也會根據 UNIQUE 索引直接跳過重複項
            ReportSchool::insertOrIgnore($postSchools);
        }
        /**
        $postSchools = array();  //要先指定$postSchools是陣列，否則會出錯
        //利用multiple insert的方式寫入資料庫，節省寫入時間
        foreach ($schools as $school) {
            $postSchools[] = [
                'report_id' => $report->id,
                'code' => $school->code_no,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ReportSchool::insert($postSchools);
         */

        return redirect()->route('posts.review');
    }

    public function result(Report $report) 
    {
        if($report->user_id != auth()->user()->id){
            $a = ['A','B','C','D','E','F','G','H','I','J'];
            $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();
            if(!$user_power){
                abort(404,'你想做什麼壞事？');
            }            
        }
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            //$report_school = ReportSchool::find($answer->report_school_id);

            $answer_data[$answer->school_code][$answer->question_id] = $answer->answer;
        }

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('edus.reports.result',$data);
    }

    public function result2(Report $report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $report_school = ReportSchool::find($answer->report_school_id);

            $answer_data[$report_school->code][$answer->question_id] = $answer->answer;
        }

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('reports.edu.result2',$data);
    }

    public function export(Report $report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){           
            $a = str_replace(',','，',$answer->answer);
            $answer_data[$answer->school_code][$answer->question_id] = $a;
        }


        $i=0;
        foreach($schools as $school){
            $rs = ReportSchool::where('code',$school->code_no)
            ->where('report_id',$report->id)
            ->first();
            if($rs->situation==4){
                $no = "-不填報";
            }else{
                $no = null;
            }

            if($rs->signed_user_id){
                $n = $rs->signed_user->name;
            }else{
                $n = "";
            }

            $data[$i] =[
                '學校名稱'=>$school->school_name.$no,
                '填報人'=>$n,
            ];


            $n=1;
            foreach($report->questions as $question){
                            //$report_school = ReportSchool::find($answer->report_school_id);
            
                $school_code = $school->code_no;
            
                // 1. 定義學校群組對照表（把相關的代碼放在同一個群組內）
                $school_groups = [
                    ['074541', '074541074774', '074774', '074774074541'], // 信義組
                    ['074537', '074537074745', '074745', '074745074537'], // 原斗組
                    ['074542', '074542074778', '074778', '074778074542'], // 鹿江組
                    ['074543', '074543074760', '074760', '074760074543'], // 民權組
                ];

                // 2. 只有在當前 $school_code 找不到資料時才執行
                if (!isset($answer_data[$school_code][$question->id])) {
                    
                    foreach ($school_groups as $group) {
                        // 判斷目前的 $school_code 是否屬於這一個學校群組
                        if (in_array($school_code, $group)) {
                            
                            // 如果屬於這組，則在組內依序尋找有資料的代碼
                            foreach ($group as $fallback_code) {
                                if (isset($answer_data[$fallback_code][$question->id])) {
                                    $school_code = $fallback_code;
                                    break 2; // 跳出兩層迴圈 (找到了就直接結束所有檢查)
                                }
                            }
                        }
                    }
                }            
            
                if(isset($answer_data[$school_code][$question->id])){

                    //$get_report_school = ReportSchool::where('code',$school->code_no)
                        //->where('report_id',$report->id)
                        //->first();

                    if($rs->situation===3) {
                        $data[$i]['送出時間'] = substr($rs->updated_at, 0, 16);
                        $data[$i]["(".$n.")".$question->title] = $answer_data[$school_code][$question->id];
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->title] = "";
                    }
                }else{
                    if($rs->situation===4) {
                        $data[$i]['送出時間'] = substr($rs->updated_at, 0, 16);
                        $data[$i]["(".$n.")".$question->title] = "不填報";
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->title] = "";
                    }
                }
                $n++;
            }
            $i++;
        }

        $list = collect($data);

        //return (new FastExcel($list))->download('Report'.$report->id.'.xlsx');
        return (new FastExcel($list))->download('Report'.$report->id.'.csv');
    }

    public function post(Request $request)
    {
        $report = Report::where('id',$request->input('report_id'))
            ->first();
        $schools = explode(',',$request->input('schools'));
        $sel_schools = School::whereIn('school_name',$schools)->get();
        foreach($sel_schools as $sel_school){
            $school_array[] = $sel_school->id;
        }
        $sections = config('boe.sections');
        $data = [
            'schools'=>$request->input('schools'),
            'report'=>$report,
            'school_array'=>$school_array,
            'sections'=>$sections,
        ];
        return view('edus.reports.post',$data);
    }

    public function set_back(ReportSchool $report_school)
    {
        $att['situation'] = 0;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->route('edu_report.result',$report_school->report_id);
    }

    public function set_null(ReportSchool $report_school)
    {
        $att['situation'] = null;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->route('edu_report.result',$report_school->report_id);
    }

    public function obsolete(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            return back();
        }
        $att['situation'] = 4;
        $report->update($att);
        return redirect()->route('edu_report.passing');
    }

    //審查者列出所有的填報
    public function section_all()
    {
        $reports = Report::where('section_id',auth()->user()->section_id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(20);                    
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [            
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,     
            'want'=>null,
        ];
        return view('edus.reports.section_all',$data);
    }

    public function do_search_in_section(Request $request)
    {
        return redirect()->route('reports.do_search', $request->input('want'));
    }

    public function do_search($want){        
        $reports = Report::where('section_id',auth()->user()->section_id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->where(function ($q) use ($want) {
            $q->where('name', 'like', '%' . $want . '%')
                ->orWhere('content', 'like', '%' . $want . '%')
                ->orWhereHas('user', function ($query) use ($want) {
                    $query->where('name', 'like', '%' . $want . '%');
                });
            })            
            ->orderBy('id','DESC')
            ->paginate(20);
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,       
            'want'=>$want,
        ];
        return view('edus.reports.section_all',$data);
    }

    
}
