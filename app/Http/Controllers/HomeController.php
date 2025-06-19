<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserPower;
use App\Models\LoginError;
use App\Models\TitleImage;
use App\Models\Other;

class HomeController extends Controller
{
    public function index(){        
        $title_images = TitleImage::where('disable', null)->get();       
        $others = Other::orderBy('order_by')->get();
        $data = [
            'title_images'=>$title_images,            
            'others'=>$others,
        ];
        return view('index',$data);
    }

    public function glogin(){        
        return view('auth.glogin');
    }

    public function mlogin(){        
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

        header("Content-type: image/gif");
        $images = asset('images/captcha/captcha_bk' . $back . '.gif');

        $context = stream_context_create([
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false
            ]
        ]);

        $fileContent = file_get_contents($images, false, $context);
        $im = imagecreatefromstring($fileContent);
        $text_color = imagecolorallocate($im, $r, $g, $b);

        imagettftext($im, 50, 0, 50, 50, $text_color, public_path('wt071.ttf'), $cht_key);
        imagegif($im);
        imagedestroy($im);
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

    public function logout(){        
        Auth::logout();
        Session::flush();
        return redirect()->route('index');
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

    public function qanda(){
        return view('qanda');
    }

    public function about(){
        return view('about');
    }


}
