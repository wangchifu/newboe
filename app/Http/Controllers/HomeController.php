<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserPower;
use App\Models\LoginError;
use App\Models\TitleImage;
use App\Models\Link;
use App\Models\Other;
use App\Models\Marquee;
use App\Models\Post;
use App\Models\UserRead;

class HomeController extends Controller
{
    public function index(){        
        $title_images = TitleImage::where('disable', null)->get();     
        $links = Link::whereNotNull('type')
            ->where('type', '!=', '')
            ->orderBy('type')
            ->orderBy('order_by')
            ->get();
        $link2s = Link::whereNull('type')            
            ->orderBy('order_by')
            ->get();                       
        $others = Other::orderBy('order_by')->get();
        $marquees = Marquee::where('start_date', '<=', date('Ymd'))
            ->where('stop_date', '>', date('Ymd'))
            ->orderBy('id','DESC')
            ->get();

        $posts = Post::where('situation', '3')
            ->where(function ($q) {
                $q->whereIn('category_id', [1,2,3,4])
                ->orWhere(function ($q2) {
                    $q2->where('category_id', 5)
                        ->where('another', 1);
                });
            })
            ->orderBy('passed_at', 'DESC')
            ->paginate(13); 
        $category_array = config('boe.categories');
        $data = [
            'title_images'=>$title_images,       
            'links'=>$links,
            'link2s'=>$link2s,            
            'others'=>$others,
            'marquees'=>$marquees,
            'posts'=>$posts,
            'category_array'=>$category_array,
        ];
        return view('index',$data);
    }

    public function logins()
    {
        if(auth()->check()){
            return redirect()->route('index');
        }
        return view('auth.logins');
    }

    public function glogin(){        
        return view('auth.glogin');
    }

    public function mlogin(){       
        if(auth()->check()){
            return redirect()->route('index');
        } 
        return view('auth.mlogin');
    }

    //產生認證圖片
    public function pic()
    {
        $key = rand(10000, 99999);
        $back = rand(0, 9);
        //$r = rand(0, 255);
        $r = 0;
        //$g = rand(0, 255);
        $g = 0;
        //$b = rand(0, 255);
        $b = 0;

        session(['captcha' => $key]);

        $cht = array(0 => "零", 1 => "壹", 2 => "貳", 3 => "參", 4 => "肆", 5 => "伍", 6 => "陸", 7 => "柒", 8 => "捌", 9 => "玖");
        //$cht = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        $images = public_path('images/captcha/captcha_bk' . $back . '.gif');

        $fileContent = file_get_contents($images);
        $im = imagecreatefromstring($fileContent);
        $text_color = imagecolorallocate($im, $r, $g, $b);

        imagettftext($im, 50, 0, 50, 50, $text_color, public_path('wt071.ttf'), $cht_key);
        ob_start();
        imagegif($im);
        $imageData = ob_get_clean();
        imagedestroy($im);

        return response($imageData, 200, ['Content-Type' => 'image/gif']);
    }

    public function gauth(Request $request)
    {
        //記錄登入錯誤次數
        if ($request->input('captcha') != session('captcha')) {
            if (!session('login_error')) {
                session(['login_error' => 1]);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }
            //記錄在DB
            login_error_add($request->input('username'));

            return back()->withErrors(['error' => ['驗證碼錯誤！']]);
        }

        if (session('login_error') > 2 ) {
            return back()->withErrors(['error' => ['登入錯誤次數過多！']]);
        }

        //15分鐘內三次登入錯誤者鎖定
        if (login_eroor_count($request->input('username')) >= 3) {
            return back()->withErrors(['error' => ['該帳號已被鎖定，請15分鐘後再試！']]);
        }

        $username = explode('@', $request->input('username'));
        $data = array("email" => $username[0], "password" => $request->input('password'));
        $data_string = json_encode($data);
        $ch = curl_init(env('GSUITE_AUTH'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $obj = json_decode($result, true);
        
        //學生禁止訪問
        if ($obj['success']) {
            if ($obj['kind'] == "學生") {
                return back()->withErrors(['errors' => ['學生不得登入']]);
            }

            // 找出隸屬於哪一所學校 id 代號
            //$school = School::where('code_no', 'like', $obj['code'] . '%')->first();
            $schools_id = config('boe.schools_id');
            $school_id = !isset($schools_id[$obj['code']]) ? 0 : $schools_id[$obj['code']];

            //是否已有此帳號
            $user = User::where('edu_key', $obj['edu_key'])     
                ->where('code', $obj['code'])           
                ->first();    

            if (empty($user)) {
                //查有無曾用openid登入者
                //已取消openid登入 
                //$user2 = User::where('edu_key', $obj['edu_key'])
                //    ->where('login_type', 'gsuite')
                //    ->first();

                $att['username'] = $username[0];
                $att['password'] = bcrypt($request->input('password'));
                $att['group_id'] = ($obj['code'] == "079998" or $obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $obj['name'];
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['edu_key'] = $obj['edu_key'];
                $att['uid'] = $obj['uid'];
                $att['login_type'] = "gsuite";
                $att['school_id'] = $school_id;
                //if (empty($user2)) {
                    //無使用者，即建立使用者資料
                    $user = User::create($att);
                //} else {
                //    $user2->update($att);
                //}
            } else {

                //停用者，沒有換學校，不得登入
                if ($user->disable == 1 and $user->code == $obj['code']) {
                    return back()->withErrors(['errors' => ['你已被停用']]);
                }

                //如果換了學校，初次登入刪除權限
                if ($user->code != $obj['code']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);

                    //刪除原學校的權限 //為了讓兼兩所學校的人事會計可用，不刪
                    //$user_power_change = UserPower::where('section_id',$user->code)
                    //->where('user_id',$user->id)
                    //->delete();
                }

                //有此使用者，即更新使用者資料
                $att['group_id'] = ($obj['code'] == "079998" or $obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['edu_key'] = $obj['edu_key'];
                $att['uid'] = $obj['uid'];
                $att['disable'] = null;
                $att['school_id'] = $school_id;
                //是主任就是單位管理者
                $att['school_admin'] = ($obj['title'] == '教務主任' or $obj['title'] == '教導主任' or $obj['title'] == '校長') ? "1" : null;
                $user->update($att);
            }

            //是教務主任、教導主任就是學校管理者
            if ($obj['title'] == '教務主任' or $obj['title'] == '教導主任' or $obj['title'] == '校長') {
                $user_power = UserPower::where('section_id', $obj['code'])
                    ->where('user_id', $user->id)
                    ->where('power_type', 'A')
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $obj['code'];
                    $att2['power_type'] = "A";
                    UserPower::create($att2);
                }

                $user_power = UserPower::where('section_id', $obj['code'])
                    ->where('power_type', 'B')
                    ->where('user_id', $user->id)
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $obj['code'];
                    $att2['power_type'] = "B";
                    UserPower::create($att2);
                }
            }

            if (Auth::attempt([
                'username' => $username[0],
                'password' => $request->input('password')
            ])) {
                //記錄最後登入
                $att_login['logined_at'] = now();
                $user->update($att_login);
                //log
                if (auth()->user()->group_id == 9 or auth()->user()->admin == 1) {
                    $event = "系統管理者 " . auth()->user()->name . "(" . $request->input('username') . ") 登入";
                    logging('6', $event, get_ip());
                }
                $user_power = UserPower::where('user_id', auth()->user()->id)
                    ->where('power_type', 'A')
                    ->first();
                if (auth()->user()->group_id == 8 or (!empty(auth()->user()->section_id) and !empty($user_power))) {
                    $event = "科室管理者 " . auth()->user()->name . "(" . $request->input('username') . ") 登入";
                    logging('6', $event, get_ip());
                }

                //清掉login_error
                if (session('login_error')) {
                    session(['login_error' => 0]);
                }
                
                //教育處人員
                if (auth()->user()->section_id) {
                    //return redirect()->route('posts.reviewing');
                }
                //其他學校單位
                if (auth()->user()->other_code) {
                    //return redirect()->route('posts.showSigned_other');
                }
                //學校單位
                if (auth()->user()->code) {
                    //return redirect()->route('posts.showSigned');
                }
                //其餘者
                return redirect()->route('index');
            }
        };

        //密碼錯了，就記錄
        login_error_add($request->input('username'));

        //session 也記錄一下
        if (!session('login_error')) {
            session(['login_error' => 1]);
        } else {
            $a = session('login_error');
            $a++;
            session(['login_error' => $a]);
        }
        return back()->withErrors(['errors' => ['帳號密碼錯誤']]);;
    }

    public function mauth(Request $request){
        if($request->input('captcha') != session('captcha')){
            if (!session('login_error')) {
                session(['login_error' => 1]);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
        }

        if (session('login_error') > 2 ) {
            return back()->withErrors(['error' => ['登入錯誤次數過多！']]);
        }

        //15分鐘內三次登入錯誤者鎖定
        if (login_eroor_count($request->input('username')) >= 3) {
            return back()->withErrors(['error' => ['該帳號已被鎖定，請15分鐘後再試！']]);
        }

        if (Auth::attempt([
            'username' => $request->input('username'),
            'password'=>$request->input('password'),
            'disable' => null,
            'login_type'=>'local',
        ])) {
            // 如果認證通過...

            //log
            if(auth()->user()->group_id==9 or auth()->user()->admin==1){
                $event = "系統管理者 ".auth()->user()->name."(".$request->input('username').") 登入";
                logging('6',$event,get_ip());
            }
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();
            if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty($user_power))){
                $event = "科室管理者 ".auth()->user()->name."(".$request->input('username').") 登入";
                logging('6',$event,get_ip());
            }

            //記錄最後登入
            $att_login['logined_at'] = now();
            auth()->user()->update($att_login);

            //清除login_error
            if (session('login_error')) {
                session(['login_error' => 0]);
            }

            return redirect()->route('index');
        }else{
            $user = User::where('username',$request->input('username'))
                ->first();

            if(empty($user)){
                if (!session('login_error')) {
                    session(['login_error' => 1]);
                } else {
                    $a = session('login_error');
                    $a++;
                    session(['login_error' => $a]);
                }

                return back()->withErrors(['error'=>['帳號密碼錯誤']]);
            }else{
                if(password_verify($request->input('password'), $user->password)){
                    if($user->disable == "1"){
                        if (!session('login_error')) {
                            session(['login_error' => 1]);
                        } else {
                            $a = session('login_error');
                            $a++;
                            session(['login_error' => $a]);
                        }

                        return back()->withErrors(['error'=>['你的帳號已被停用']]);
                    }
                    if($user->login_type == "gsuite"){
                        if (!session('login_error')) {
                            session(['login_error' => 1]);
                        } else {
                            $a = session('login_error');
                            $a++;
                            session(['login_error' => $a]);
                        }
                        
                        return back()->withErrors(['error'=>['這個登入頁面是本機帳號']]);
                    }
                }else{
                    //密碼錯了，就記錄
                    login_error_add($request->input('username'));

                    if (!session('login_error')) {
                        session(['login_error' => 1]);
                    } else {
                        $a = session('login_error');
                        $a++;
                        session(['login_error' => $a]);
                    }
                    return back()->withErrors(['error'=>['帳號密碼錯誤！']]);
                }
            }
        }
    }

    public function login(){
        return redirect()->route('logins');
    }
    public function logout(Request $request){                
        Auth::logout();
        
        if(empty(session('id_token'))){
            $link = env('APP_URL');
        }else{
            $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
            $post_logout_redirect_uri = env('APP_URL');
            $id_token_hint = session('id_token');
            $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        }        
        Session::flush();
        return redirect($link);
    }

    public function search(Request $request)
    {        
        $want = $request->input('want');
        if(mb_strlen(trim($want), 'UTF-8') < 2){
            //return back()->withErrors(['errors' => ['關鍵字要二字元以上']]);
        }
        return redirect('https://www.google.com/search?q=' . $want . '+site%3Anewboe.chc.edu.tw');
    }

    public function edit_password()
    {
        return view('auth.edit_password');
    }    

    public function update_password(Request $request)
    {

        if (!password_verify($request->input('password0'), auth()->user()->password)) {

            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 更改密碼失敗(舊密碼錯誤)";
            logging('2', $event, get_ip());

            return back()->withErrors(['error' => ['舊密碼錯誤！你不是本人！？']]);
        }
        if ($request->input('password1') != $request->input('password2')) {

            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 更改密碼失敗(兩次新密碼不同)";
            logging('2', $event, get_ip());
            return back()->withErrors(['error' => ['兩次新密碼不相同']]);
        }


        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password1'));
        $user = User::where('id', $att['id'])->first();
        $user->update($att);
        return redirect()->route('index');
    }

    public function edit_title()
    {
        $title_array = explode(',',auth()->user()->kind);
        if (in_array(auth()->user()->code, ['079999', '079998'])) {
            foreach (['科長', '專員', '督學', '組員', '辦事員', '課程督學', '調府教師'] as $extra) {
                if (!in_array($extra, $title_array)) {
                    $title_array[] = $extra;
                }
            }
        }
        $data = [
            'title_array'=>$title_array,
        ];
        return view('auth.edit_title',$data);
    }

    public function update_title(Request $request)
    {
        $att['title'] = $request->input('title');
        auth()->user()->update($att);

        return redirect()->route('index');
    }

    public function qanda(){
        return view('qanda');
    }

    public function about(){
        return view('about');
    }

    public function bulletin($category)
    {
        $category_id = $category;

        if ($category == 0) {
            $posts = Post::where('situation', '3')
            ->where(function ($q) {
                $q->whereIn('category_id', [1,2,3,4])
                ->orWhere(function ($q2) {
                    $q2->where('category_id', 5)
                        ->where('another', 1);
                });
            })
            ->orderBy('passed_at', 'DESC')
            ->paginate(30);             
        }elseif($category == 5){
            $posts = Post::where('category_id', $category)
                ->where('situation', '3')
                ->where('another', '1')                
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }else{
            $posts = Post::where('category_id', $category)
                ->where('situation', '3')
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }

        $categories = config('boe.categories');
        $categories[0] = "全部公告";
        $category = $categories[$category];


        $data = [
            'posts' => $posts,
            'category' => $category,
            'category_id' => $category_id,
        ];
        return view('bulletin', $data);
    }

    public function bulletin_search(Request $request)
    {
        if ($request->input('check') != session('search')) {
            return back()->withErrors(['error' => ['驗證碼不對！']]);
        }
        

        $category_id = $request->input('category_id');
        //$want = $request->input('want');
        $want = strip_tags($request->input('want'));
        $want = str_replace("<","",$want);
        $want = str_replace(">","",$want);
        if (mb_strlen($want) < 2) {
            return back()->withErrors(['error' => ['關鍵字必須二字元以上！']]);
        }
        return redirect()->route('bulletin_search_result', ['category_id' => $category_id, 'want' => $want]);
    }

    public function bulletin_search_result($category_id, $want)
    {
        if ($category_id == 0) {
            $posts = Post::where('situation', '3')
                ->where(function ($q) {
                    $q->whereIn('category_id', [1,2,3,4])
                    ->orWhere(function ($q2) {
                    $q2->where('category_id', 5)
                        ->where('another', 1);
                });
            })
            ->where(function ($q3) use ($want) {
                $q3->where('title', 'like', '%' . $want . '%')
                    ->orWhere('content', 'like', '%' . $want . '%')
                    ->orWhereHas('user', function ($query) use ($want) {
                $query->where('name', 'like', '%' . $want . '%');
                });
            })
            ->orderBy('passed_at', 'DESC')
            ->paginate(30);             
        }elseif($category_id == 5){
            $posts = Post::where('category_id', $category_id)
                ->where('situation', '3')
                ->where('another', '1')
                ->where(function ($q) use ($want) {
                    $q->where('title', 'like', '%' . $want . '%')
                        ->orWhere('content', 'like', '%' . $want . '%')
                        ->orWhereHas('user', function ($query) use ($want) {
                            $query->where('name', 'like', '%' . $want . '%');
                        });
                })
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }else{
            $posts = Post::where('category_id', $category_id)
                ->where('situation', '3')
                ->where(function ($q) use ($want) {
                    $q->where('title', 'like', '%' . $want . '%')
                        ->orWhere('content', 'like', '%' . $want . '%')
                        ->orWhereHas('user', function ($query) use ($want) {
                            $query->where('name', 'like', '%' . $want . '%');
                        });
                })
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }

        

        $categories = config('boe.categories');
        $categories[0] = "全部公告";
        $category = $categories[$category_id];        

        $data = [
            'posts' => $posts,
            'category' => $category,
            'category_id' => $category_id,
            'want' => $want,
        ];
        return view('bulletin_search', $data);
    }    

    public function rss()
    {
        $posts = Post::where('category_id', '<>', 5)
            ->where('situation', '3')
            ->orWhere(function ($q) {
                $q->where('category_id', '5')
                    ->where('another', '1')
                    ->where('situation', '3');
            })
            ->orderBy('passed_at', 'DESC')
            ->paginate('50');

        $categories = config('boe.categories');
        $sections = config('boe.sections');

        $items = "";        
        foreach ($posts as $post) {
            $title = str_replace('&', '及', $post->title);
            $safe_title = htmlspecialchars($title, 19, 'UTF-8');
            $content = str_replace('&', '及', $post->content);
            $safe_content = htmlspecialchars($content, 19, 'UTF-8');
            $items .= '
            <item>
                <link>
                ' . env('APP_URL') . '/posts_show/' . $post->id . '
                </link>
                <title>' . $safe_title . '</title>
                <dc:creator>' . array_get($sections, $post->section_id) . ' / ' . $post->user->name . '</dc:creator>
                <category>
                    <![CDATA[ ' . $categories[$post->category_id] . ' ]]>
                </category>
                <pubDate>' . date(DATE_RSS, strtotime(substr($post->passed_at, 0, 16))) . '</pubDate>
                <guid isPermaLink="true">' . env('APP_URL') . '/posts_show/' . $post->id . '</guid>
                <description>
                    <![CDATA[
                        ' . $safe_content . '
                    ]]>
                </description>
            </item>
            ';
        }

        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
                <channel>
                <title><![CDATA[ 彰化縣教育處新雲端 ]]></title>
                <link>https://newboe.chc.edu.tw</link>
                <description>
                    <![CDATA[
                        歡迎光臨教育處新雲端！分享彰化縣教育的大小事！
                    ]]>
                </description>
                <language>zh-tw</language>
                <atom:link href="https://newboe.chc.edu.tw/rss" rel="self" type="application/rss+xml" />
                <copyright>
                    <![CDATA[
                        版權來自：newboe.chc.edu.tw
                    ]]>
                </copyright>
                ' . $items . '
                </channel>
            </rss>

        ';
        $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
        $content = preg_replace($invalid_characters, '', $content);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }    
    
    public function user_reads($no_read_sp){
        $no_read_sp_array = explode(',',$no_read_sp);
        foreach($no_read_sp_array as $k=>$v){
            $att['user_id'] = auth()->user()->id;
            $att['system_post_id'] = $v;
            UserRead::create($att);
        }
        
        $user_read_ids = UserRead::where('user_id',auth()->user()->id)->pluck('id')->toArray();    
        session(['user_read_ids' => $user_read_ids]);
        session(['user_all_read' => 1]);
        return redirect()->back();
        
    }

}
