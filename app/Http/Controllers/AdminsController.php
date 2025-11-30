<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPower;
use App\Models\Introduction;
use App\Models\Other;
use App\Models\Log;
use App\Models\Post;
use App\Models\PostSchool;
use App\Models\Report;
use App\Models\Question;
use App\Models\Answer;
use App\Models\ReportSchool;
use App\Models\SystemPost;

use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
{
    public function impersonate(User $user)
    {
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 模擬了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        Auth::user()->impersonate($user);
        $user_power = UserPower::where('user_id', auth()->user()->id)
        ->where('power_type', 'A')
        ->first();
        session(['user_power' => $user_power]);
        session(['user_read_ids' => null]);
        session(['user_all_read' => null]);
        return redirect()->route('index');

    }

    public function impersonate_leave()
    {
        Auth::user()->leaveImpersonation();
        $user_power = UserPower::where('user_id', auth()->user()->id)
        ->where('power_type', 'A')
        ->first();
        session(['user_power' => $user_power]);
        return redirect()->route('index');
    }
    
    public function user_index()
    {
        $users = User::orderBy('disable')
            ->orderBy('group_id')
            ->orderBy('section_id')
            ->Paginate('20'); 
        $sections = config('boe.sections');
        $groups = config('boe.groups');
        $other_schools = config('boe.other_schools');
        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'other_schools'=>$other_schools,
        ];
        return view('admins.user_index',$data);
    }

    public function user_check(){
        $users = User::where('edu_key','!=','')
            ->where('edu_key','!=',null)            
            ->get();
        foreach($users as $user){
          $check_user[$user->id] = $user->edu_key;
          $userid2name[$user->id]['name'] = $user->name;          
          $userid2name[$user->id]['school'] = $user->school;
          $userid2name[$user->id]['title'] = $user->title;
          $userid2name[$user->id]['date'] = $user->updated_at;
          $userid2name[$user->id]['disable'] = $user->disable;
        }

        $valuesMap = [];

        foreach ($check_user as $key => $value) {
            $valuesMap[$value][] = $key;
        }

        $duplicates = array_filter($valuesMap, function ($keys) {
            return count($keys) > 1;
        });

        //dd($duplicates);

        $data = [
            'userid2name'=>$userid2name,
            'duplicates'=>$duplicates,
        ];
        return view('admins.user_check',$data);
    }

    public function user_group($group_id)
    {

        if($group_id=="1"){
            $users = User::where('group_id',"1")
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->Paginate('20');
        }
        if($group_id=="2"){
            $users = User::where('group_id',"2")
                ->orWhere('group_id','8')
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->Paginate('20');
        }
        if($group_id=="3"){
            $users = User::where('group_id',"9")
                ->orWhere('admin','1')
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->Paginate('20');
        }

        $sections = config('boe.sections');
        $groups = config('boe.groups');
        $other_schools = config('boe.other_schools');
        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'other_schools'=>$other_schools,
            'group_id'=>$group_id,
        ];
        return view('admins.user_group',$data);
    }
    
    public function user_search(Request $request,$want=null)
    {
        $want = (empty($want))?$request->input('want'):$want;
        session(['want'=>$want]);
        $sections = config('boe.sections');
        $groups = config('boe.groups');

        $show_s = array_flip($sections);
        if(isset($show_s[$want])){
            $s = $show_s[$want];
        }else{
            $s = 0;
        }

        $other_schools = config('boe.other_schools');
        $show_o = array_flip($other_schools);
        if(isset($show_o[$want])){
            $o = $show_o[$want];
        }else{
            $o = "找不到";
        }

        $users = User::where('username','like','%'.$want.'%')
            ->orWhere('name','like','%'.$want.'%')
            ->orWhere('school','like','%'.$want.'%')
            ->orWhere('title','like','%'.$want.'%')
            ->orWhere('section_id','like','%'.$s.'%')
            ->orWhere('other_code','like','%'.$o.'%')
            ->paginate('20');

        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'want'=>$want,
            'other_schools'=>$other_schools,
        ];
        return view('admins.user_search',$data);
    }

    public function user_edit(User $user)
    {
        $sections = config('boe.sections');
        $section_admins = config('boe.section_admins');

        $user_power = UserPower::where('user_id',$user->id)
            ->where('power_type','A')
            ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
            ->first();
        $section_admin = ($user_power)?$user_power->section_id:null;

        $data = [
            'user'=>$user,
            'sections'=>$sections,
            'section_admins'=>$section_admins,
            'section_admin'=>$section_admin,
        ];
        return view('admins.user_edit',$data);
    }

    public function user_update(Request $request,User $user)
    {
        $att['section_id'] = ($request->input('section_id'))?$request->input('section_id'):null;
        $att['admin'] = $request->input('admin');
        $att['other_code'] = $request->input('other_code');
        $user->update($att);
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 更改了使用者 id：".$user->id." 名稱：".$user->name."的資料，科室為：".$att['section_id'].",系統管理者為：".$att['admin'].",其他單位為：".$att['other_code'];
        logging('2',$event,get_ip());

        if($request->input('a_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->first();
            if(!$user_power){
                $att2['section_id'] = $request->input('code');
                $att2['user_id'] = $user->id;
                $att2['power_type'] = "A";
                UserPower::create($att2);

                //log
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('code')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }else{
            $check = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->get();
            UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了單位代碼 ".$request->input('code')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }

        if($request->input('b_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->first();
            if(!$user_power){
                $att3['section_id'] = $request->input('code');
                $att3['user_id'] = $user->id;
                $att3['power_type'] = "B";
                UserPower::create($att3);

                //log
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('code')." 的B權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }else{
            $check = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->get();
            UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了單位代碼 ".$request->input('code')." 的B權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }


        if($request->input('section_admin')){
            UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->delete();

            $att4['user_id'] = $user->id;
            $att4['section_id'] = $request->input('section_admin');
            $att4['power_type'] = "A";
            UserPower::create($att4);

            //log
            $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('section_admin')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
            logging('2',$event,get_ip());

        }else{
            $check = UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->get();
            UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了所有科室的權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }

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
                //window.parent.location.reload();
                window.parent.location.href = '/admin/user_search/".session('want')."';
            }
        };
        </script>";
    }

    public function user_destroy(User $user)
    {
        $att['disable'] = 1;
        $att['my_section_id'] = null;
        $att['section_id'] = null;
        $att['disabled_at'] = now();
        $user->update($att);
        UserPower::where('user_id',$user->id)->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 停用了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        return redirect()->back();
    }

    public function user_reback(User $user)
    {
        $att['disable'] = null;
        $att['disabled_at'] = null;
        $user->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 啟用了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        return redirect()->back();
    }

    public function reback_password(User $user)
    {
        $att['password'] = bcrypt('Plz90-Change-Pwd!!!');
        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 還原了使用者 id：" . $user->id . " " . $user->name . " 的密碼";
        logging('2', $event, get_ip());

        return redirect()->back();
    }

    //最高管理使用
    public function introduction_index()
    {
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
        ];
        return view('admins.introduction_index',$data);
    }
    public function introduction_organization($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->organization;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('admins.introduction_organization',$data);
    }

    public function introduction_people($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('admins.introduction_people',$data);
    }

    public function introduction_people2($type)
    {
        $sections = [
            '1'=>'處長',
            '2'=>'副處長',
            '3'=>'專員',
        ];
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('admins.introduction_people2',$data);
    }

    public function introduction_site($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->site;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('admins.introduction_site',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function introduction_store(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->route('admins.introduction_'.$request->input('type'),$request->input('section_id'));
    }

    public function introduction_store2(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->route('admins.introduction_people2',$request->input('section_id'));
    }    

    public function other_index()
    {
        $others = Other::orderBy('order_by')
            ->get();
        return view('admins.other_index',compact('others'));
    }

    public function other_create()
    {
        return view('admins.other_create');
    }

    public function other_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
        ]);
        $other = Other::create($request->all());

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('admins.other_index');
    }

    public function other_edit(Other $other)
    {
        $data = [
            'other'=>$other,
        ];
        return view('admins.other_edit',$data);
    }

    public function other_update(Request $request, Other $other)
    {
        $other->update($request->all());

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('admins.other_index');
    }

    public function other_destroy(Other $other)
    {
        $other->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('admins.other_index');
    }

    public function logs()
    {
        $level_array = [
            0 => 'EMERG',
            1 => 'ALERT',
            2 => 'CRIT',
            3 => 'ERR',
            4 => 'WARN',
            5 => 'NOTICE',
            6 => 'INFO',
        ];

        $select_level = (isset($_GET['select_level'])) ? $_GET['select_level'] : "all";

        if ($select_level == "all") {
            $logs = Log::orderBy('created_at', 'DESC')->paginate(20);
        } else {
            $logs = Log::where('level', $select_level)
                ->orderBy('created_at', 'DESC')->paginate(20);
        }



        $data = [
            'select_level' => $select_level,
            'level_array' => $level_array,
            'logs' => $logs,
        ];
        return view('admins.logs', $data);
    }

    public function special()
    {        
        $data = [
            
        ];
        return view('admins.special',$data);
    }

    public function special_post(Request $request)
    {
        $id = $request->input('post_id');
        $post = Post::where('id',$id)->first();
        $post_schools = PostSchool::where('post_id',$id)->get();
        $categories = config('boe.categories');
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'post'=>$post,
            'post_schools'=>$post_schools,
            'categories'=>$categories,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('admins.special_post',$data);
    }

    public function special_post_delete(Request $request)
    {
        $id = $request->input('post_id');
        $post = Post::where('id',$id)->first();
        $post_schools = PostSchool::where('post_id',$id)->delete();        

        $folder = storage_path('app/public/post_files/'.$post->id);
        if (is_dir($folder)) {
            del_folder($folder);
        }
        $folder = storage_path('app/public/post_photos/'.$post->id);
        if (is_dir($folder)) {
            del_folder($folder);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了公告 id：".$post->id." 名稱：".$post->title;
        logging('4',$event,get_ip());

        $post->delete();

        return redirect()->route('admins.special');

    }

    public function special_report(Request $request)
    {
        $id = $request->input('report_id');
        $report = Report::where('id',$id)->first();
        $questions = Question::where('report_id',$id)->get();
        $answers = Answer::where('report_id',$id)->get();
        $report_schools = ReportSchool::where('report_id',$id)->get();
        $categories = config('boe.categories');
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $types = [
            'radio'=>'1.單選題',
            'checkbox'=>'2.多選題',
            'text'=>'3.文字題',
            'num'=>'4.數字題',
        ];
        $data = [
            'report'=>$report,
            'questions'=>$questions,
            'answers'=>$answers,
            'report_schools'=>$report_schools,
            'categories'=>$categories,
            'situation'=>$situation,
            'sections'=>$sections,
            'types'=>$types,
        ];
        return view('admins.special_report',$data);
    }

    public function special_report_delete(Request $request)
    {
        $id = $request->input('report_id');
        $report = Report::where('id',$id)->first();
        $questions = Question::where('report_id',$id)->delete();
        $answers = Answer::where('report_id',$id)->delete();
        $report_schools = ReportSchool::where('report_id',$id)->delete();

        $folder = storage_path('app/public/report_files/'.$id);
        if (is_dir($folder)) {
            del_folder($folder);
        }        

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了資料填報 id：".$report->id." 名稱：".$report->name;
        logging('4',$event,get_ip());

        $report->delete();

        return redirect()->route('admins.special');

    }    

    public function clean_index()
    {        
        $data = [
            
        ];
        return view('admins.clean_index',$data);
    }

    function clean_do_post(Request $request){                
        $posts = Post::where('id','<',$request->input('post_id'))->get();                
        foreach($posts as $post){            
            $folder = storage_path('app/public/post_files/' . $post->id);
            if (is_dir($folder)) {
                del_folder($folder);
            }
            $folder = storage_path('app/public/post_photos/' . $post->id);
            if (is_dir($folder)) {
                del_folder($folder);
            }            
            $post->delete();
        }                
        $limit = 1000;
        do {
            $records = PostSchool::where('post_id', '<', $request->input('post_id'))
                ->limit($limit)
                ->get();

            $count = $records->count();

            if ($count > 0) {
                $ids = $records->pluck('id')->toArray();
                PostSchool::whereIn('id', $ids)->delete();
                usleep(100000); // 等 0.1 秒避免系統過載
            }
            
        } while ($count > 0);

        return back()->withErrors(['errors' => ['刪除成功！']]);
    }

    function clean_do_report(Request $request){
        $reports = Report::where('id','<',$request->input('report_id'))->get();
        foreach($reports as $report){
            $folder = storage_path('app/public/report_files/' . $report->id);
            if (is_dir($folder)) {
                del_folder($folder);
            }            
            $report->delete();
        }        
        $limit = 1000;
        do {
            $records = ReportSchool::where('report_id', '<', $request->input('report_id'))
                ->limit($limit)
                ->get();

            $count = $records->count();

            if ($count > 0) {
                $ids = $records->pluck('id')->toArray();
                ReportSchool::whereIn('id', $ids)->delete();
                usleep(100000); // 等 0.1 秒避免系統過載
            }
            
        } while ($count > 0);

        do {
            $records = Question::where('report_id', '<', $request->input('report_id'))
                ->limit($limit)
                ->get();

            $count = $records->count();

            if ($count > 0) {
                $ids = $records->pluck('id')->toArray();
                Question::whereIn('id', $ids)->delete();
                usleep(100000); // 等 0.1 秒避免系統過載
            }
            
        } while ($count > 0);

        do {
            $records = Answer::where('report_id', '<', $request->input('report_id'))
                ->limit($limit)
                ->get();

            $count = $records->count();

            if ($count > 0) {
                $ids = $records->pluck('id')->toArray();
                Answer::whereIn('id', $ids)->delete();
                usleep(100000); // 等 0.1 秒避免系統過載
            }
            
        } while ($count > 0);

        
        

        return back()->withErrors(['errors' => ['刪除成功！']]);
    }


    public function close()
    {
        if(!file_exists(storage_path('app/private/close.txt'))){    
            if(!file_exists(storage_path('app/private'))){
                mkdir(storage_path('app/private'));
            }
            touch(storage_path('app/private/close.txt'));
            $fp = fopen(storage_path('app/private/close.txt'), 'w');
            fwrite($fp, '0');          
            fclose($fp);
        }
        $fp = fopen(storage_path('app/private/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/private/close.txt')));                
        fclose($fp);
        
        $data = [
            'close'=>$close,
        ];
        return view('admins.close',$data);
    }

    public function close_system(){
        $fp = fopen(storage_path('app/private/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/private/close.txt'))); 
        fclose($fp);
        if($close == 0 ) $do =1;
        if($close == 1 ) $do =0;
        $fp = fopen(storage_path('app/private/close.txt'), 'w');
        fwrite($fp, $do);  
        fclose($fp);
        return back();
    }

    public function sys_post_index(){
        $system_posts = SystemPost::orderBy('id','DESC')->simplePaginate('15');
        $data = [
            'system_posts'=>$system_posts,
        ];
        return view('admins.sys_post_index',$data);
    }
    
    public function sys_post_store(Request $request){
        $att = $request->all();
        SystemPost::create($att);
        return redirect()->back();
    }

    public function sys_post_destroy(SystemPost $system_post){
        $system_post->delete();
        return redirect()->back();
    }

}
