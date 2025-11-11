<?php

namespace App\Http\Controllers;

use App\School;
use App\User;
use App\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;

// RP申請獲得
define('CLIENT_ID', env('CLIENT_ID'));
define('CLIENT_SECRET', env('CLIENT_SECRET'));
//
define('AUTH_SECRET', '');
//define('REDIR_URI0', 'http://openid.zipko.info/callback.php');
define('REDIR_URI0', 'https://newboe.chc.edu.tw/auth/callback');
define('WELL_KNOWN_URL', 'https://chc.sso.edu.tw/.well-known/openid-configuration');
// 預設0由設定檔的URL決定；設定為1則每次皆由WELL_KNOWN取回END POINT URL
define('DYNAMICAL_ENDPOINT', 0);
// DYNAMICAL_ENDPOINT設為0下方三項需填寫
define('AUTH_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/azp');
define('TOKEN_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/token');
define('USERINFO_ENDPOINT', 'https://chc.sso.edu.tw/oidc/v1/userinfo');
define('JWKS_URI', 'https://chc.sso.edu.tw/oidc/v1/jwksets');
//
// PROFILE URL
define('PROFILE_ENDPOINT', 'https://chc.sso.edu.tw/moeresource/api/v1/oidc/profile');

class OpenIDLoginController extends Controller
{
    
    public function sso(){
        //session_start();
        $obj= new openid();
        session(['azp_state'=>rand(0,9999999)]);
        if(!session()->has('nonce')){
            session(['nonce'=>base64_encode(session('azp_state'))]);
        }else{
            session(['nonce'=>session('nonce')]);
        }

        //$_SESSION['azp_state']=rand(0,9999999); //隨機產生state值
        //$_SESSION['nonce']=isset($_SESSION['nonce'])? $_SESSION['nonce']:base64_encode($_SESSION['azp_state']);

        $auth_ep=AUTH_ENDPOINT;
        if(DYNAMICAL_ENDPOINT){
            $auth_ep=$ep->getEndPoint()->authorization_endpoint;
        }
        $link = $auth_ep . "?response_type=code&client_id=". CLIENT_ID ."&redirect_uri=".urlencode(REDIR_URI0) ."&scope=openid+email+profile+eduinfo+personid&state=".session('azp_state')."&nonce=".session('nonce');
    //       dd($link);
        return redirect($link);

    }

    public function callback(Request $request){
      $code= $_GET['code'];
      $state= $_GET['state'];
      
      //驗證 $state
      if( !isset($_GET['code']) ||  !isset($_GET['state'])){
        die ("認證伺服器回傳結果失敗！");
      }
      
      if( strcmp($state, session('azp_state'))){
        die ("錯誤的認證狀態，請重新嘗試！");
      }      
      
      $obj= new openid();
      
      $token_ep=TOKEN_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
         $token_ep=$ep->getEndPoint()->token_endpoint;
      }
      
      $acctoken= $obj->getAccessToken($token_ep ,$code, REDIR_URI0);
      if( !$acctoken || !isset($acctoken->access_token) ) {
        die ("無法取得ACCESS TOKEN");
      }
      // 把access token, id_token記到session中
      // 未來需要取得其他scope再用此access token 來做
      session(['access_token'=>$acctoken->access_token]);
      session(['id_token'=>$acctoken->id_token]);
      
        //驗證 access token
      if(!session()->has('access_token')){
        die ("無存取用權杖，無法取回使用者資料！");
      }

      //取回access token
      //include "config.php";
      //include "library.class.php";
      //$obj= new openid();

      $token_ep2=USERINFO_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
        $token_ep2=$ep->getEndPoint()->userinfo_endpoint;
      }

      $userinfo = $obj->getUserinfo($token_ep2 ,session('access_token'), true);
      $profile = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/personid" ,session('access_token'), true);
      $edufile = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/eduinfo" ,session('access_token'), true);
      if( !$userinfo) {
        die ("無法取得 USER INFO");
      }


      // 把access token記到session中
      //print_r($userinfo);
      //echo "<hr>";
      //print_r($profile);      
      //echo "<hr>";
      //print_r($edufile);     
      
      $user_obj['username'] = $userinfo['sub'];
      $user_obj['password'] = "openID";
      $user_obj['success'] = 1;
      $user_obj['name'] = $userinfo['name'];      
      $user_obj['personid'] = $profile['personid'];
      $user_obj['code'] = $edufile['schoolid'];
      $user_obj['title'] = $edufile['titles'][0]['titles'][0];

      $user_obj['kind'] = "";      
      if ($user_obj['title'] == "學生") {
        $message = "學生禁止訪問";
        $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
        $post_logout_redirect_uri = url('logins');
        $id_token_hint = session('id_token');
        $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        return redirect($link)->withErrors(['gsuite_error' => [$message]]);
      }else{
        $title_array = $edufile['titles'][0]['titles'];
        $title = "";
        foreach($title_array as $k =>$v){
          $title .= $v.',';
        }
        $title = rtrim($title, ',');
        $user_obj['kind'] = $title;        
      }
      

// 找出隸屬於哪一所學校 id 代號            
            $schools_id = config('boe.schools_id');
            $school_id = !isset($schools_id[$user_obj['code']]) ? 0 : $schools_id[$user_obj['code']];
            $schools_name = config('boe.schools_name');
            $user_obj['school'] = !isset($schools_name[$user_obj['code']]) ? "查無學校" : $schools_name[$user_obj['code']];

            //是否已有此帳號
            $user = User::where('edu_key', $user_obj['personid'])      
                ->where('code', $user_obj['code'])                    
                ->first();

            if (empty($user)) {
                $att['username'] = $user_obj['username'];
                $att['password'] = $user_obj['password'];
                $att['group_id'] = ($user_obj['code'] == "079998" or $user_obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $user_obj['name'];
                $att['code'] = $user_obj['code'];
                $att['school'] = $user_obj['school'];
                $att['kind'] = $user_obj['kind'];
                $att['title'] = $user_obj['title'];
                $att['edu_key'] = $user_obj['personid'];
                $att['uid'] = "";
                $att['login_type'] = "open_id";
                $att['school_id'] = $school_id;

                $user = User::create($att);

            } else {

                //停用者，沒有換學校，不得登入
                if ($user->disable == 1 and $user->code == $user_obj['code']) {
                  $message = "你已被停用，請聯絡學校管理者！";
                  $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
                  $post_logout_redirect_uri = url('logins');
                  $id_token_hint = session('id_token');
                  $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
                  return redirect($link)->withErrors(['gsuite_error' => [$message]]);                                                        
                }

                //如果換了學校，初次登入刪除權限
                if ($user->code != $user_obj['code']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);
                }

                //有此使用者，即更新使用者資料
                $att['group_id'] = ($user_obj['code'] == "079998" or $user_obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $user_obj['name'];
                $att['username'] = $user_obj['username'];
                $att['password'] = $user_obj['password'];
                $att['code'] = $user_obj['code'];
                $att['school'] = $user_obj['school'];
                $att['kind'] = $user_obj['kind'];
                $att['title'] = $user_obj['title'];
                $att['edu_key'] = $user_obj['personid'];
                $att['uid'] = "";
                $att['disable'] = null;
                $att['login_type'] = "open_id";
                $att['school_id'] = $school_id;
                //是主任就是單位管理者
                $title_array = explode(',', $user_obj['kind']);
                $att['school_admin'] = null;
                foreach($title_array as $k => $v) {
                    if ($v == '教務主任' or $v == '教導主任' or $v == '校長') {
                        $att['school_admin'] = "1";                        
                    }
                }
                //$att['school_admin'] = ($user_obj['title'] == '教務主任' or $user_obj['title'] == '教導主任' or $user_obj['title'] == '校長') ? "1" : null;

                $user->update($att);
            }

            $title_array = explode(',', $user_obj['kind']);
            $att['school_admin'] = null;
            foreach($title_array as $k => $v) {
                if ($v == '教務主任' or $v == '教導主任' or $v == '校長') {
                    $att['school_admin'] = "1";                        
                }
            }

            //是教務主任、教導主任就是學校管理者
            if ($att['school_admin'] == "1") {
                $user_power = UserPower::where('section_id', $user_obj['code'])
                    ->where('user_id', $user->id)
                    ->where('power_type', 'A')
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $user_obj['code'];
                    $att2['power_type'] = "A";
                    UserPower::create($att2);
                }

                $user_power = UserPower::where('section_id', $user_obj['code'])
                    ->where('power_type', 'B')
                    ->where('user_id', $user->id)
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $user_obj['code'];
                    $att2['power_type'] = "B";
                    UserPower::create($att2);
                }
            }

            $att_login['logined_at'] = now();
            $user->update($att_login);

            //登入
            Auth::login($user);


            //log
            if (auth()->user()->group_id == 9 or auth()->user()->admin == 1) {
                $event = "系統管理者 " . auth()->user()->name . "(" .auth()->user()->username. ") 登入";
                logging('6', $event, get_ip());
            }
            $user_power = UserPower::where('user_id', auth()->user()->id)
                ->where('power_type', 'A')
                ->first();
            if (auth()->user()->group_id == 8 or (!empty(auth()->user()->section_id) and !empty($user_power))) {
                $event = "科室管理者 " . auth()->user()->name . "(" .auth()->user()->username. ") 登入";
                logging('6', $event, get_ip());
            }

            if (session('login_error')) {
                session(['login_error' => 0]);
            }            
            
            //教育處人員
            if (auth()->user()->section_id) {
                return redirect()->route('posts.reviewing');
            }
            //其他學校單位
            if (auth()->user()->other_code) {
                return redirect()->route('posts.showSigned_other');
            }
            //學校單位
            if (auth()->user()->code) {
                return redirect()->route('posts.showSigned');
            }
            //其餘者
            return redirect()->route('index');            



    }

    
}

class openid {
    /**
     *
    */
    public function getEndPoint($rtn_array=false){
      $options = array(
        'http' => array(
          'header'  => '',
          'method'  => 'GET',
          'content' => ''
        ));
      $context = stream_context_create($options);
      $result = file_get_contents(WELL_KNOWN_URL, false, $context);
      $u= json_decode($result, $rtn_array);
      return $u; //object
    }
  
    public function getAccessToken($token_ep='' ,$code='', $redir_uri='' ,$rtn_array=false){
      $hash = base64_encode( CLIENT_ID . ":" . CLIENT_SECRET);
      $data = array('grant_type' => 'authorization_code', 'code'=> $code,
        'redirect_uri' => $redir_uri);
      $header= array( "Content-type: application/x-www-form-urlencoded",
         "Authorization: Basic $hash" ) ;
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'POST',
            'content' => http_build_query($data)
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $j= json_decode($result, $rtn_array);
      return $j;
    }
    public function getModnExp($jwks_uri){
      $options = array(
        'http' => array(
          'header'  => '',
          'method'  => 'GET',
          'content' => ''
        ));
      $context = stream_context_create($options);
      $result = file_get_contents($jwks_uri, false, $context);
      $u= json_decode($result, true);
      return $u; //object
    }
   
    public function getUserinfo($token_ep='' ,$accesstoken='',$rtn_array=false){
      $header= array( "Authorization: Bearer $accesstoken" );
      $options = array(
          'http' => array(
            'header'  => $header,
            'method'  => 'GET',
            'content' => ''
          ));
      $context = stream_context_create($options);
      $result = file_get_contents($token_ep, false, $context);
      $u= json_decode($result,$rtn_array);
      return $u;
    }
    public function urlsafeB64Encode($input)
    {
      return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }
  
    public function urlsafeB64Decode($input)
    {
      $remainder = strlen($input) % 4;
      if ($remainder) {
         $padlen = 4 - $remainder;
         $input .= str_repeat('=', $padlen);
      }
      return base64_decode(strtr($input, '-_', '+/'));
    }
   
  
  }