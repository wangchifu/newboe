<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;

// RP申請獲得
define('CLIENT_ID', env('CLIENT_ID'));
define('CLIENT_SECRET', env('CLIENT_SECRET'));
//
define('AUTH_SECRET', '');
//define('REDIR_URI0', 'http://openid.zipko.info/callback.php');
define('REDIR_URI0', env('APP_URL').'/auth/callback');
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
            $auth_ep=$obj->getEndPoint()->authorization_endpoint;
        }
        $link = $auth_ep . "?response_type=code&client_id=". CLIENT_ID ."&redirect_uri=".urlencode(REDIR_URI0) ."&scope=openid+email+profile+eduinfo+personid&state=".session('azp_state')."&nonce=".session('nonce');
    //       dd($link);
        return redirect($link);

    }

    public function callback(Request $request){
      $code = $request->input('code');
      $state = $request->input('state');
      
      // 1. 驗證 $state 與 $code
      if( !$code || !$state ){
        die("【除錯 1 失敗】認證伺服器回傳結果失敗！未取得 code 或 state。<br>GET 參數：" . json_encode($_GET));
      }
      
      if( strcmp($state, session('azp_state')) !== 0 ){
        die("【除錯 2 失敗】錯誤的認證狀態！<br>Session azp_state: " . session('azp_state') . "<br>GET state: " . $state);
      }      
      
      $obj = new openid();
      
      $token_ep = TOKEN_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
         $token_ep = $obj->getEndPoint()->token_endpoint; // 修正原始程式碼中 $ep 變數未定義的問題
      }
      
      // 2. 取得 Access Token
      $acctoken = $obj->getAccessToken($token_ep, $code, REDIR_URI0);
      if( !$acctoken || !isset($acctoken->access_token) ) {
        die("【除錯 3 失敗】無法取得 ACCESS TOKEN！<br>SSO 端點回傳內容：<pre>" . print_r($acctoken, true) . "</pre>");
      }
      
      session(['access_token' => $acctoken->access_token]);
      session(['id_token' => $acctoken->id_token]);
      
      if(!session()->has('access_token')){
        die("【除錯 4 失敗】Session 無法存入 access_token！");
      }

      $token_ep2 = USERINFO_ENDPOINT;
      if(DYNAMICAL_ENDPOINT){
        $token_ep2 = $obj->getEndPoint()->userinfo_endpoint; // 修正原始程式碼中 $ep 變數未定義的問題
      }

      // 3. 取得 UserInfo / Profile / Edufile 資料
      $userinfo = $obj->getUserinfo($token_ep2, session('access_token'), true);
      $profile  = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/personid", session('access_token'), true);
      $edufile  = $obj->getUserinfo("https://chc.sso.edu.tw/cncresource/api/v1/eduinfo", session('access_token'), true);

      // 印出 SSO 回傳的所有 raw data 供比對（若需要看完整結構可將下一行的註解拆掉）
      // dd(['userinfo' => $userinfo, 'profile' => $profile, 'edufile' => $edufile]);

      if( !$userinfo ) {
        die("【除錯 5 失敗】無法取得 USER INFO 資料。");
      }

      if( !$profile ){
        die("【除錯 6 失敗】無法取得 PROFILE 資料。");
      }

      if( empty($profile['personid']) ){
        die("【除錯 7 失敗】PROFILE 中無法取得 PERSONID！<br>Profile 內容：<pre>" . print_r($profile, true) . "</pre>");
      }      

      if( !$edufile ){
        die("【除錯 8 失敗】無法取得 EDUFILE 資料。");
      }

      // 4. 安全檢查 edufile 陣列結構 (防止陣列 Key 缺失造成 500)
      if (empty($edufile['schoolid'])) {
        die("【除錯 9 失敗】EDUFILE 中缺少 schoolid！<br>Edufile 內容：<pre>" . print_r($edufile, true) . "</pre>");
      }

      if (!isset($edufile['titles'][0]['titles'][0])) {
        die("【除錯 10 失敗】EDUFILE 中的 titles 結構不符或為空！<br>Edufile 內容：<pre>" . print_r($edufile, true) . "</pre>");
      }

      $user_obj['username'] = $userinfo['sub'] ?? '';
      $user_obj['password'] = "openID";
      $user_obj['success']  = 1;
      $user_obj['name']     = $userinfo['name'] ?? '';      
      $user_obj['personid'] = $profile['personid'];
      $user_obj['code']     = $edufile['schoolid'];
      $user_obj['title']    = $edufile['titles'][0]['titles'][0];

      $user_obj['kind'] = "";      
      if ($user_obj['title'] == "學生") {
        $message = "學生禁止訪問";
        $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
        $post_logout_redirect_uri = url('logins');
        $id_token_hint = session('id_token');
        $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
        return redirect($link)->withErrors(['gsuite_error' => [$message]]);
      } else {
        $title_array = $edufile['titles'][0]['titles'] ?? [];
        $title = implode(',', $title_array);
        $user_obj['kind'] = $title;        
      }
      
      // 5. 檢查 Config 設定
      $schools_id = config('boe.schools_id');
      if (is_null($schools_id)) {
        die("【除錯 11 失敗】無法讀取到 config('boe.schools_id')，請確認 config/boe.php 檔案是否存在。");
      }
      $school_id = !isset($schools_id[$user_obj['code']]) ? 0 : $schools_id[$user_obj['code']];

      $schools_name = config('boe.schools_name');
      $user_obj['school'] = !isset($schools_name[$user_obj['code']]) ? "查無學校" : $schools_name[$user_obj['code']];

      // 6. 資料庫操作除錯 (使用 try-catch 包覆)
      try {
          $user = User::where('edu_key', strtoupper($user_obj['personid']))      
              ->where('code', $user_obj['code'])
              ->where('login_type','open_id')                    
              ->first();

          if (empty($user)) {
              $att['username']   = $user_obj['username'];
              $att['password']   = $user_obj['password'];
              $att['group_id']   = ($user_obj['code'] == "079998" or $user_obj['code'] == "079999") ? "2" : "1";
              $att['name']       = $user_obj['name'];
              $att['code']       = $user_obj['code'];
              $att['school']     = $user_obj['school'];
              $att['kind']       = $user_obj['kind'];
              $att['title']      = $user_obj['title'];
              $att['edu_key']    = strtoupper($user_obj['personid']);
              $att['uid']        = "";
              $att['login_type'] = "open_id";
              $att['school_id']  = $school_id;

              $user = User::create($att);
          } else {
              if ($user->disable == 1 and $user->code == $user_obj['code']) {
                $message = "你已被停用，請聯絡學校管理者至「學校管理/學校帳號」內啟用！";
                $url = "https://chc.sso.edu.tw/oidc/v1/logout-to-go";
                $post_logout_redirect_uri = url('logins');
                $id_token_hint = session('id_token');
                $link = $url . "?post_logout_redirect_uri=".$post_logout_redirect_uri."&id_token_hint=" . $id_token_hint;
                return redirect($link)->withErrors(['gsuite_error' => [$message]]);                                                        
              }

              if ($user->code != $user_obj['code']) {
                  $att_change['disable'] = null;
                  $att_change['disabled_at'] = null;
                  $user->update($att_change);
              }

              $att['group_id'] = ($user_obj['code'] == "079998" or $user_obj['code'] == "079999") ? "2" : "1";
              $att['name']     = $user_obj['name'];
              $att['username'] = $user_obj['username'];
              $att['password'] = $user_obj['password'];
              $att['code']     = $user_obj['code'];
              $att['school']   = $user_obj['school'];
              $att['kind']     = $user_obj['kind'];
              
              if(empty($user->title)){
                $att['title'] = $user_obj['title'];                
              }else{
                $att['title'] = $user->title;
              }
              
              $att['edu_key']    = strtoupper($user_obj['personid']);
              $att['uid']        = "";
              $att['disable']    = null;
              $att['login_type'] = "open_id";
              $att['school_id']  = $school_id;
              
              $title_array = explode(',', $user_obj['kind']);
              $att['school_admin'] = null;
              foreach($title_array as $k => $v) {
                  if ($v == '教務主任' or $v == '教導主任' or $v == '校長') {
                      $att['school_admin'] = "1";                        
                  }
              }

              $user->update($att);
          }
      } catch (\Exception $e) {
          die("【除錯 12 失敗】User 資料庫寫入/查詢異常！<br>錯誤訊息：" . $e->getMessage());
      }

      // 7. 權限設定與更新
      try {
          $title_array = explode(',', $user_obj['kind']);
          $att['school_admin'] = null;
          foreach($title_array as $k => $v) {
              if ($v == '教務主任' or $v == '教導主任' or $v == '校長') {
                  $att['school_admin'] = "1";                        
              }
          }

          if ($att['school_admin'] == "1") {
              $user_power = UserPower::where('section_id', $user_obj['code'])
                  ->where('user_id', $user->id)
                  ->where('power_type', 'A')
                  ->first();
              if (!$user_power) {
                  UserPower::create([
                      'user_id' => $user->id,
                      'section_id' => $user_obj['code'],
                      'power_type' => "A"
                  ]);
              }

              $user_power = UserPower::where('section_id', $user_obj['code'])
                  ->where('power_type', 'B')
                  ->where('user_id', $user->id)
                  ->first();
              if (!$user_power) {
                  UserPower::create([
                      'user_id' => $user->id,
                      'section_id' => $user_obj['code'],
                      'power_type' => "B"
                  ]);
              }
          }

          $user->update(['logined_at' => now()]);
      } catch (\Exception $e) {
          die("【除錯 13 失敗】UserPower 資料庫更新異常！<br>錯誤訊息：" . $e->getMessage());
      }

      // 8. DB2 外部連線檢查
      if (in_array($user_obj['code'], ['079999', '079998']) && empty($user->section_id) && !empty($user->edu_key)) {
          if (!function_exists('connect_DB2')) {
              die("【除錯 14 失敗】找不到 connect_DB2() 函式！請檢查相關庫是否已 include。");
          }
          try {
              $dbh = connect_DB2();
              $sql = "SELECT staff_curr_class_num FROM staff WHERE LOWER(staff_person_id) = ? AND staff_sid = ? AND staff_kind = '教職員' AND staff_status = '1' LIMIT 1";
              $stmt = $dbh->prepare($sql);
              $stmt->execute([strtolower($user->edu_key), $user_obj['code']]);
              $staff = $stmt->fetch();
              if ($staff && !empty($staff['staff_curr_class_num'])) {
                  $user->update(['section_id' => $staff['staff_curr_class_num']]);
              }
          } catch (\Exception $e) {
              // DB2 連線失敗不阻擋登入
          }
      }

      // 9. 登入與 Log 檢查
      Auth::login($user);

      if (!function_exists('logging') || !function_exists('get_ip')) {
          die("【除錯 15 失敗】找不到 logging() 或 get_ip() 函式！");
      }

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
      
      // 10. 檢查轉向路由是否存在
      try {
          if (auth()->user()->section_id) {
              return redirect()->route('posts.reviewing');
          }
          if (auth()->user()->other_code) {
              return redirect()->route('posts.showSigned_other');
          }
          if (auth()->user()->code) {
              return redirect()->route('posts.showSigned');
          }
          return redirect()->route('index');            
      } catch (\Exception $e) {
          die("【除錯 16 失敗】轉向路由 (Route) 不存在！<br>錯誤訊息：" . $e->getMessage());
      }
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