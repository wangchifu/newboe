<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegularSample;
use App\Models\RegularReport;
use App\Models\RegularAnswer;
use App\Models\RegularReportSchool;
use App\Models\School;
use Illuminate\Support\Str;

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

        $answers = RegularAnswer::where('report_id',$report->id)
            ->get();
        $answer_data = [];

        //foreach($answers as $answer){            
        //    $answer_data[$answer->school_code][$answer->question_id] = $answer->answer;
        //}

        $data = [
            'regular_report'=>$regular_report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('edus.regular_reports.result',$data);
    }
}
