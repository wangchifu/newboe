<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegularSample;
use App\Models\RegularReport;
use App\Models\RegularAnswer;
use App\Models\RegularReportSchool;
use App\Models\RegularReportTemp;
use App\Models\School;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class RegularReportController extends Controller
{
    public function index()
    {
        $regular_reports = RegularReport::where('user_id',auth()->user()->id)
            ->where('situation','<>',3)
            ->where('situation','<>',4)
            ->orderBy('id','DESC')
            ->paginate(20);              
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'regular_reports'=>$regular_reports,            
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('edus.regular_reports.index',$data);
    }

    public function passing()
    {
        $regular_reports = RegularReport::where('user_id',auth()->user()->id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(20);        
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'regular_reports'=>$regular_reports,            
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('edus.regular_reports.passing',$data);
    }

    //審查者列出所有的填報
    public function section_all()
    {        
        $regular_reports = RegularReport::where('section_id',auth()->user()->section_id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(20);            
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'regular_reports'=>$regular_reports,            
            'situation'=>$situation,
            'sections'=>$sections,     
            'want'=>null,
        ];
        return view('edus.regular_reports.section_all',$data);
    }    

    public function create(){
        $sections = config('boe.sections');
        $regular_samples = RegularSample::where('section_id',auth()->user()->section_id)->get();
        $data = [            
            'sections'=>$sections,
            'regular_samples'=>$regular_samples,
        ];

        return view('edus.regular_reports.create',$data);
    }

    public function show(RegularReport $regular_report){   
        $files = get_files(storage_path('app/public/regular_report_files/' . $regular_report->id));             
        $data = [
            'regular_report'=>$regular_report,            
            'files'=>$files,
        ];

        return view('edus.regular_reports.show',$data);
    }

    public function show_sample(RegularSample $regular_sample){               
        $sample_num = auth()->user()->section_id.$regular_sample->id;
        $data = [
            'sample_num'=>$sample_num,
            'regular_sample'=>$regular_sample,
        ];
        return view('edus.regular_reports.sample',$data);
    }

    public function download($id,$filename)
    {
        $file = storage_path('app/public/regular_report_files/' . $id . '/' . $filename);
        return response()->download($file);
    }

    public function date_late(RegularReport $regular_report)
    {

        $data = [
            'regular_report'=>$regular_report,
        ];
        return view('edus.regular_reports.date_late',$data);
    }

    public function save_date_late(Request $request,RegularReport $regular_report)
    {
        if($regular_report->user_id == auth()->user()->id) {
            $regular_report->update($request->all());
        }
        $att1['situation'] = null;
        $att1['review_user_id'] = null;
        RegularReportSchool::where('regular_report_id',$regular_report->id)->where('situation',5)->update($att1);

        $att2['situation'] = 1;
        $att2['review_user_id'] = null;
        RegularReportSchool::where('regular_report_id',$regular_report->id)->where('situation',0)->update($att2);

        $att3['situation'] = 1;
        $att3['review_user_id'] = null;
        RegularReportSchool::where('regular_report_id',$regular_report->id)->where('situation',2)->update($att3);

        $att4['situation'] = 1;
        $att4['review_user_id'] = null;
        RegularReportSchool::where('regular_report_id',$regular_report->id)->where('situation',4)->update($att4);

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

    public function create_by_sample(RegularSample $regular_sample){        
        $sections = config('boe.sections');
        $data = [
            'select_school'=>'',
            'sections'=>$sections,
            'regular_sample'=>$regular_sample,            
        ];        

        return view('edus.regular_reports.create_by_sample',$data);
    }

    public function store_by_sample(Request $request){        
        $request->validate([
            'semester' => 'required',
            'die_date' => 'required',
            'die_date' => 'required|date_format:Y-m-d',           
            'files.*'=>'nullable|max:10240', 
            'sel_school'=>'required',
        ]);        
        $att['user_id'] = auth()->user()->id;
        $att['section_id'] = auth()->user()->section_id;
        $att['semester'] = $request->input('semester');
        $att['start_date'] = $request->input('start_date');
        $att['die_date'] = $request->input('die_date');
        $att['regular_sample_id'] = $request->input('regular_sample_id');
        $att['situation'] = "1";                

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if(!empty($request->input('sel_school'))){
            $school_set=checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_'.$key] = $value;
            }
        }                
        $regular_report = RegularReport::create($att);
        //檢查檔案
        $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
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
                $file->storeAs('public/regular_report_files/'.$regular_report->id, $safeName);
            }
        }

        return redirect()->route('edu_regular_report.index');
        
    }

    public function edit_by_sample(RegularReport $regular_report){ 
        if($regular_report->situation==3 or $regular_report->situation==4){
            abort(404,'都審核或廢除了，還想偷改？');
        }
        $select_school = checkbox_str_num(array($regular_report->school_set_0, $regular_report->school_set_1, $regular_report->school_set_2, $regular_report->school_set_3, $regular_report->school_set_4));
        $files = get_files(storage_path('app/public/regular_report_files/' . $regular_report->id));
        $sections = config('boe.sections');
        $data = [
            'regular_report'=>$regular_report,
            'select_school'=>$select_school,
            'files'=>$files,
            'sections'=>$sections,
        ];

        return view('edus.regular_reports.edit_by_sample',$data);    
    }

    public function update_by_sample(Request $request,RegularReport $regular_report){ 
        $request->validate([
            'semester' => 'required',
            'die_date' => 'required',
            'die_date' => 'required|date_format:Y-m-d',           
            'files.*'=>'nullable|max:10240', 
            'sel_school'=>'required',
        ]);                        
        $att['semester'] = $request->input('semester');
        $att['start_date'] = $request->input('start_date');
        $att['die_date'] = $request->input('die_date');                

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if(!empty($request->input('sel_school'))){
            $school_set=checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_'.$key] = $value;
            }
        }                
        $regular_report->update($att);
        //檢查檔案
        $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
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
                $file->storeAs('public/regular_report_files/'.$regular_report->id, $safeName);
            }
        }

        return redirect()->route('edu_regular_report.index');    
    }

    public function del_file($id,$file)
    {
        $regular_report = RegularReport::find($id);
        if($regular_report->user_id != auth()->user()->id){
            return back();
        }
        $file_path = storage_path('app/public/regular_report_files/'.$id.'/'.$file);
        if(file_exists($file_path)){
            unlink($file_path);
        }
        return back();
    }

    public function return(Request $request,RegularReport $regular_report)
    {
        $att['situation'] = 0;
        $regular_report->update($att);
        return redirect()->route('posts.review');
    }

    public function approve(RegularReport $regular_report)
    {
        $att['situation'] = 3;
        $att['passed_at'] = substr(now(),0,19);
        $att['pass_user_id'] = auth()->user()->id;
        $regular_report->update($att);

        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_schools = checkbox_str_num(array($regular_report->school_set_0, $regular_report->school_set_1, $regular_report->school_set_2, $regular_report->school_set_3, $regular_report->school_set_4));

        $select_schools = explode(", ", $select_schools);

        $schools = School::whereIn('id', $select_schools)->get();

        // 1. 先把這份 report 已經存在的 code 全部抓出來
        $existingCodes = RegularReportSchool::where('regular_report_id', $regular_report->id)
                                    ->pluck('code')
                                    ->toArray();

        $postSchools = array();

        foreach ($schools as $school) {
            // 2. 檢查這次要寫入的 code 是否已存在於「資料庫已有的」或「本次迴圈剛加進去的」
            if (!in_array($school->code_no, $existingCodes)) {
                $postSchools[] = [
                    'regular_report_id'  => $regular_report->id,
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
            RegularReportSchool::insertOrIgnore($postSchools);
        }        

        return redirect()->route('posts.review');
    }

    public function resend(RegularReport $regular_report)
    {
        if($regular_report->user_id == auth()->user()->id){
            $att['situation'] = 1;
            $regular_report->update($att);
        }

        return redirect()->route('edu_regular_report.index');
    }

    public function set_back(RegularReportSchool $regular_report_school)
    {        
        $att['situation'] = 0;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);
        return redirect()->route('edu_regular_report.result',$regular_report_school->regular_report_id);
    }

    public function set_null(RegularReportSchool $regular_report_school)
    {
        $att['situation'] = null;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);
        return redirect()->route('edu_regular_report.result',$regular_report_school->regular_report_id);
    }

    public function obsolete(RegularReport $regular_report)
    {
        if($regular_report->user_id != auth()->user()->id){
            return back();
        }
        $att['situation'] = 4;
        $regular_report->update($att);
        return redirect()->route('edu_regular_report.passing');
    }

    public function delete_by_sample(RegularReport $regular_report)
    {
        if($regular_report->situation==3 or $regular_report->situation==4){
            abort(404,'都審核或廢除了，還想偷改？');
        }
        
        if($regular_report->user_id == auth()->user()->id){                        
            RegularAnswer::where('regular_report_id',$regular_report->id)->delete();
            RegularReportSchool::where('regular_report_id',$regular_report->id)->delete();
            $file_path = storage_path('app/public/regular_report_files/'.$regular_report->id);
            if(file_exists($file_path)){
                del_folder($file_path);
            }
            $regular_report->delete();            
        }        
        return redirect()->route('edu_regular_report.index');
    }

    public function edit(RegularReport $regular_report)
    {
        if($regular_report->situation==3 or $regular_report->situation==4){
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

        return view('edus.regular_reports.edit',$data);
    }

    public function result(RegularReport $regular_report) 
    {
        if($regular_report->user_id != auth()->user()->id){
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
        $old_schools = checkbox_str_num(array($regular_report->school_set_0, $regular_report->school_set_1, $regular_report->school_set_2, $regular_report->school_set_3, $regular_report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = RegularAnswer::where('regular_report_id',$regular_report->id)
            ->get();
        $answer_data = [];

        foreach($answers as $answer){            
            $answer_data[$answer->school_code][$answer->regular_question_id] = $answer->answer;
        }        

        $data = [
            'regular_report'=>$regular_report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('edus.regular_reports.result',$data);
    }

    public function export(RegularReport $regular_report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($regular_report->school_set_0, $regular_report->school_set_1, $regular_report->school_set_2, $regular_report->school_set_3, $regular_report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = RegularAnswer::where('regular_report_id',$regular_report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){           
            $a = str_replace(',','，',$answer->answer);
            $answer_data[$answer->school_code][$answer->regular_question_id] = $a;
        }


        $i=0;
        foreach($schools as $school){
            $rs = RegularReportSchool::where('code',$school->code_no)
            ->where('regular_report_id',$regular_report->id)
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
            foreach($regular_report->regular_sample->regular_questions as $question){
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
                        $data[$i]["(".$n.")".$question->cht_title] = $answer_data[$school_code][$question->id];
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->cht_title] = "";
                    }
                }else{
                    if($rs->situation===4) {
                        $data[$i]['送出時間'] = substr($rs->updated_at, 0, 16);
                        $data[$i]["(".$n.")".$question->cht_title] = "不填報";
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->cht_title] = "";
                    }
                }
                $n++;
            }
            $i++;
        }

        $list = collect($data);

        //return (new FastExcel($list))->download('Report'.$report->id.'.xlsx');
        return (new FastExcel($list))->download('RegularReport'.$regular_report->id.'.csv');
    }

    public function school_index()    
    {
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                }
            }

            $reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            $regular_reports_not = \App\Models\RegularReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['reports_not'=>$reports_not]);
            session(['regular_reports_not'=>$regular_reports_not]);
        $regular_report_schools = RegularReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->orderBy('id','DESC')
            ->simplePaginate(20);

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'regular_report_schools'=>$regular_report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.regular_reports.index',$data);
    }

    public function not_index()
    {
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                }
            }

            $regular_reports_not = \App\Models\RegularReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['regular_reports_not'=>$regular_reports_not]);
        $regular_report_schools = RegularReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where(function($q){
                $q->where('situation','=',0)
                    ->orWhere('situation','=',1)
                    ->orWhere('situation','=',2)
                    ->orWhere('situation',null);
            })
            ->orderBy('id','DESC')
            ->simplePaginate(20); 

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'regular_report_schools'=>$regular_report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.regular_reports.not_index',$data);
    }

    public function show_person_Signed()
    {
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                }
            }

            $regular_reports_not = \App\Models\RegularReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['regular_reports_not'=>$regular_reports_not]);
        $regular_report_schools = RegularReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where('signed_user_id',auth()->user()->id)
            ->orderBy('id','DESC')
            ->simplePaginate(20);

        $sections = config('boe.sections');
        $schools = School::all()->pluck('school_name','code_no')->toArray();

        $data = [
            'regular_report_schools'=>$regular_report_schools,
            'sections'=>$sections,
            'schools'=>$schools,
        ];
        return view('schools.regular_reports.show_person_Signed',$data);
    }

    public function school_back(RegularReportSchool $regular_report_school)
    {
        $att['situation'] = 0;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);
        return redirect()->back();
    }

    public function school_delay(RegularReportSchool $regular_report_school)
    {
        $att['situation'] = 5;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);        
        return redirect()->back();
    }

    public function school_cancel(RegularReportSchool $regular_report_school)
    {
        $att['situation'] = 6;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);
        return redirect()->back();
    }

    public function school_passing(RegularReportSchool $regular_report_school)
    {
        $att['situation'] = 3;
        $att['review_user_id'] = auth()->user()->id;
        $regular_report_school->update($att);

        //重算        
        $regular_reports_not = \App\Models\RegularReportSchool::where('code','like', "%".auth()->user()->code."%")
            ->where(function($q){
                $q->where('situation','=',0)
                    ->orWhere('situation','=',1)
                   ->orWhere('situation','=',2)
                    ->orWhere('situation',null);
            })
            ->get()->count();            
        session(['regular_reports_not'=>$regular_reports_not]);
        return redirect()->back();
    }

    public function school_create(RegularReportSchool $regular_report_school)
    {
        if(date('Ymd') > str_replace('-','',$regular_report_school->regular_report->die_date) or date('Ymd') < str_replace('-','',$regular_report_school->regular_report->start_date)){
            return back();
        }
        $sections = config('boe.sections');
        $sample_num = $regular_report_school->regular_report->section_id.$regular_report_school->regular_report->regular_sample->id;
        
        //檢查是否曾經填過
        $question_array = $regular_report_school->regular_report->regular_sample->regular_questions->pluck('id')->toArray();
        $check = RegularAnswer::where('school_code','like', "%".auth()->user()->code."%")
            ->whereIn('regular_question_id',$question_array)->orderBy('regular_report_id','DESC')->first();            
        $answer_data = [];
        if($check){
            //拿到最新的

            $answers = RegularAnswer::where('regular_report_school_id',$check->regular_report_school_id)
            ->get();            
            foreach($answers as $answer){
                $answer_data[$answer->regular_question->title] = $answer->answer;
            }            
        }


        $data = [
            'sample_num'=>$sample_num,
            'sections'=>$sections,
            'regular_report_school'=>$regular_report_school,
            'answer_data'=>$answer_data,
        ];

        return view('schools.regular_reports.create',$data);
    }

    public function school_store(Request $request)
    {        
        
        $regular_report_school = RegularReportSchool::where('id',$request->input('regular_report_school_id'))
        ->first();
        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['situation'] = 1;

        $regular_report_school->update($att);
        
        // 1. 先準備一個空陣列，用來收集所有要寫入的資料
        $insertData = [];

        // 2. 取得當前時間（因為使用 DB::insert 必須手動填入時間戳記）
        $now = now();

        foreach($regular_report_school->regular_report->regular_sample->regular_questions as $question) {
            // 收集每一筆資料，改為純陣列操作
            $insertData[] = [
                'answer'                    => $request->input($question->title),
                'regular_report_id'         => $regular_report_school->regular_report_id,
                'regular_question_id'       => $question->id,
                'regular_report_school_id'  => $regular_report_school->id,
                'school_code'               => $regular_report_school->code,
                'created_at'                => $now, // ✨ 大宗寫入記得補上時間
                'updated_at'                => $now,
            ];
        }

        // 3. 只有當陣列裡面有資料時，才「一次性」送進資料庫
        if (!empty($insertData)) {
            RegularAnswer::insert($insertData);
        }        
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

    public function school_show(RegularReportSchool $regular_report_school){           
        $sections = config('boe.sections');
        $sample_num = $regular_report_school->regular_report->section_id.$regular_report_school->regular_report->regular_sample->id;       
        $answer_data = [];
        if(!empty($regular_report_school->signed_user_id)){
            $answers = RegularAnswer::where('regular_report_school_id',$regular_report_school->id)
            ->get();            
            foreach($answers as $answer){
                $answer_data[$answer->regular_question->title] = $answer->answer;
            }
        }
        $data = [
            'regular_report_school'=>$regular_report_school,  
            'sample_num'=>$sample_num,
            'sections'=>$sections,
            'readonly'=>1,
            'answer_data'=>$answer_data,
        ];

        return view('schools.regular_reports.show',$data);
    }

    public function school_edit(RegularReportSchool $regular_report_school)
    {
        $answers = RegularAnswer::where('regular_report_school_id',$regular_report_school->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $answer_data[$answer->regular_question->title] = $answer->answer;
        }
        $sections = config('boe.sections');
        $sample_num = $regular_report_school->regular_report->section_id.$regular_report_school->regular_report->regular_sample->id;
        $data = [
            'sample_num'=>$sample_num,
            'answer_data'=>$answer_data,
            'regular_report_school'=>$regular_report_school,
            'sections'=>$sections,
        ];
        return view('schools.regular_reports.edit',$data);
    }

    public function school_update(Request $request,RegularReportSchool $regular_report_school)
    {                        
        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['situation'] = 1;

        $regular_report_school->update($att);
        
        $updateData = [];
        $now = now();

        foreach($regular_report_school->regular_report->regular_sample->regular_questions as $question) {
            $updateData[] = [
                'regular_report_id'         => $regular_report_school->regular_report_id,
                'regular_question_id'       => $question->id,
                'regular_report_school_id'  => $regular_report_school->id,
                'school_code'               => $regular_report_school->code,
                'answer'                    => $request->input($question->title),
                'created_at'                => $now, // 如果是剛好要新增時會用到
                'updated_at'                => $now,
            ];
        }

        if (!empty($updateData)) {
            // 使用 upsert，只跟資料庫對話一次
            RegularAnswer::upsert(
                $updateData, 
                // 1. 這裡填入在資料庫中能組合出「唯一性（Unique）」的欄位
                ['regular_report_school_id', 'regular_question_id'], 
                // 2. 當資料已存在時，只更新以下欄位
                ['answer', 'updated_at']
            );
        }               
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

    public function school_save_temp(Request $request)
    {        
        $att = $request->all();        
        

        $att_temp['content'] = serialize($att);
        $att_temp['regular_report_id'] = $att['regular_report_id'];
        $att_temp['code'] = auth()->user()->code;
        $att_temp['user_id'] = auth()->user()->id;

        $check = RegularReportTemp::where('code',$att_temp['code'])
            ->where('regular_report_id',$att_temp['regular_report_id'])
            ->first();

        if($check){
            $check->update($att_temp);
        }else{
            $check = RegularReportTemp::create($att_temp);
        }
        $data = $check->id;

        $result = json_encode($data,true);        
        echo $result;
        return ;
    }

    public function school_pull_temp($regular_report_id)
    {
        $regular_report_temp = RegularReportTemp::where('code','like', "%".auth()->user()->code."%")
            ->where('regular_report_id',$regular_report_id)
            ->first();
        $data = unserialize($regular_report_temp->content);

        $result = json_encode($data,true);
        echo $result;
        return ;
    }

    public function school_print($id)
    {
        $regular_report_schools = RegularReportSchool::where('code','like',"%".auth()->user()->code."%")
            ->where('id',">=",$id)
            ->orderBy('id','DESC')
            ->get();
        $sections = config('boe.sections');
        $data = [
            'regular_report_schools'=>$regular_report_schools,
            'sections'=>$sections,
        ];
        return view('schools.regular_reports.print',$data);
    }

    public function school_print2(RegularReportSchool $regular_report_school)
    {
        $answers = RegularAnswer::where('regular_report_school_id',$regular_report_school->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $answer_data[$answer->regular_question->title] = $answer->answer;
        }
        $sections = config('boe.sections');
        $sample_num = $regular_report_school->regular_report->regular_sample->section_id.$regular_report_school->regular_report->regular_sample->id;
        $data = [
            'sections'=>$sections,
            'answer_data'=>$answer_data,
            'regular_report_school'=>$regular_report_school,
            'sample_num'=>$sample_num,
        ];
        return view('schools.regular_reports.print2',$data);        
    }
}
