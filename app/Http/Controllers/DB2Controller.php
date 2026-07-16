<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DB2Controller extends Controller
{
    public function user_db2()
    {        
        $dbh = connect_DB2();
        $sql = "
        SELECT 
            id,
            staff_person_id,
            staff_sid,
            staff_name,
            staff_sex,            
            staff_status,            
            staff_title,            
            staff_curr_class_num
        FROM 
            staff
        WHERE 
            staff_sid IN ('079999', '079998')
            AND staff_kind = '教職員'                 
        ORDER BY
            staff_sid,
            staff_curr_class_num
        ";        

        $result=$dbh->query($sql);        
        $staff_in = [];
        $staff_out = [];
        foreach ($result as $row) {
            if($row['staff_status']==1){
                $staff_in[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_in[$row['id']]['sid'] = $row['staff_sid'];
                $staff_in[$row['id']]['name'] = $row['staff_name'];
                $staff_in[$row['id']]['sex'] = $row['staff_sex'];
                $staff_in[$row['id']]['title'] = $row['staff_title'];
                $staff_in[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
                //記錄在職的科室
                $staff_db2[$row['staff_sid']][strtoupper($row['staff_person_id'])]['room'] = $row['staff_curr_class_num'];
                $staff_db2[$row['staff_sid']][strtoupper($row['staff_person_id'])]['id'] = $row['id'];
            }else{
                $staff_out[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_out[$row['id']]['sid'] = $row['staff_sid'];
                $staff_out[$row['id']]['name'] = $row['staff_name'];
                $staff_out[$row['id']]['sex'] = $row['staff_sex'];
                $staff_out[$row['id']]['title'] = $row['staff_title'];
                $staff_out[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
            }                        
        }    

        //查詢新雲端內的usrer 是教育處或縣網中心
        $users = User::whereIn('code',['079998','079999'])
                    ->whereNull('disable')
                    ->whereNotNull('section_id')
                    ->get();
        $update_user = [];                
        foreach($users as $user){            
            if(isset($staff_db2[$user->code][$user->edu_key]['room'])){                
                if($user->section_id != $staff_db2[$user->code][$user->edu_key]['room']){                                        
                    $update_user[$staff_db2[$user->code][$user->edu_key]['id']] = $user->section_id;
                    //修正 staff_if 對的科室
                    $staff_in[$staff_db2[$user->code][$user->edu_key]['id']]['staff_curr_class_num'] = $user->section_id;
                }
            }
        }

        if (!empty($update_user)) {
            $ids = array_keys($update_user);
            $cases = [];
            $bindings = [];
            $where_placeholders = [];

            // 1. 建立 CASE WHEN 語法與對應的綁定參數
            foreach ($update_user as $id => $section_id) {
                // SQL CASE 語法
                $cases[] = "WHEN id = :id_{$id} THEN :sec_{$id}";
                
                // 綁定 CASE 的值
                $bindings[":id_{$id}"] = $id;
                $bindings[":sec_{$id}"] = $section_id;
            }

            // 2. 建立 WHERE IN 專用的命名佔位符（例如 :where_id_123）
            foreach ($ids as $id) {
                $where_placeholders[] = ":where_id_{$id}";
                $bindings[":where_id_{$id}"] = $id; // 同樣塞進綁定陣列中
            }

            $cases_sql = implode(' ', $cases);
            $where_in_sql = implode(',', $where_placeholders);
            
            // 3. 組合最終的 SQL（完全不含問號 ?）
            $sql = "UPDATE staff SET 
                        staff_curr_class_num = CASE 
                            {$cases_sql} 
                            ELSE staff_curr_class_num 
                        END 
                    WHERE id IN ({$where_in_sql})";

            // 4. 準備 PDO Statement
            $stmt = $dbh->prepare($sql);

            // 5. 一口氣綁定所有命名參數並執行
            // 由於我們把 CASE 和 WHERE 的參數都整理在 $bindings 陣列中，可以直接傳入 execute
            $stmt->execute($bindings);

            // 6. 取得受影響的總筆數
            $affected_rows = $stmt->rowCount();            
        }


        $sections = config('boe.sections');
        $data = [            
            'staff_in'=>$staff_in,
            'staff_out'=>$staff_out,
            'sections'=>$sections,
        ];
        return view('admins.user_db2',$data);
    }

    function user_db2_create(){
        $data = [

        ];
        return view('admins.user_db2_create',$data);
    }

    function user_db2_delete($id){
        $dbh = connect_DB2();
        $sql = "delete from staff where id = '{$id}'";
        $result=$dbh->query($sql);   
        return back();
    }

    function user_db2_search(Request $request){
        $person_id = hash('sha256', strtoupper(trim($request->input('person_id'))));
        $dbh = connect_DB2();
        $sql = "
        SELECT 
            id,
            staff_person_id,
            staff_sid,
            staff_name,
            staff_sex,            
            staff_status,            
            staff_title,            
            staff_curr_class_num
        FROM 
            staff
        WHERE 
            staff_sid IN ('079999', '079998')
            AND staff_kind = '教職員'   
            AND staff_person_id = '{$person_id}'              
        ";        
        $result=$dbh->query($sql);   
        
        $staff = [];        
        foreach ($result as $row) {
            $staff[$row['id']]['person_id'] = $row['staff_person_id'];
            $staff[$row['id']]['sid'] = $row['staff_sid'];
            $staff[$row['id']]['name'] = $row['staff_name'];
            $staff[$row['id']]['sex'] = $row['staff_sex'];
            $staff[$row['id']]['title'] = $row['staff_title'];
            $staff[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];            
            $staff[$row['id']]['staff_status'] = $row['staff_status'];
        }
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
            'staff'=>$staff,
        ];
        return view('admins.user_db2_search',$data);
    }

    function user_db2_store(Request $request){
        $dbh = connect_DB2();
        $att = $request->all();
        $att['staff_person_id'] = hash('sha256', strtoupper(trim($att['id_card'])));
        if($att['staff_sid'] =="079998") $att['staff_curr_class_num'] = "I";
        $att['staff_username'] = generateRandomString(10);
        $att['staff_password'] = generateRandomString(12);

        $check_sql = "SELECT id,staff_sid,staff_status FROM staff WHERE staff_person_id = :staff_person_id LIMIT 1";

        $stmt = $dbh->prepare($check_sql);

        // 2. 執行並綁定變數
        $stmt->execute([
            ':staff_person_id' => $att['staff_person_id']
        ]);

        // 3. 取得查詢結果
        $exists = $stmt->fetch();

        // 4. 判斷並回傳結果
        if ($exists) {
            // 有找到資料，代表已經申請過
            $id = $exists['id'];
            $staff_sid = $exists['staff_sid'];
            $staff_status = $exists['staff_status'];
            $data = [
                'id'=>$id,
                'staff_sid'=>$staff_sid,  
                'staff_status'=>$staff_status,
                'new_staff_sid'=>$att['staff_sid'],
                'new_staff_curr_class_num'=>$att['staff_curr_class_num'],
                'new_staff_name'=>$att['staff_name'],
                'new_staff_sex'=>$att['staff_sex'],
                'new_staff_title'=>$att['staff_title'],
            ];
            return view('admins.user_db2_has',$data);
        }


        $sql = "INSERT INTO staff (
            staff_sid,
            staff_curr_class_num,
            staff_person_id,
            staff_name,
            staff_sex,
            staff_title,
            staff_kind,
            staff_username,
            staff_password,
            staff_status
        ) VALUES (
            '{$att['staff_sid']}',
            '{$att['staff_curr_class_num']}',
            '{$att['staff_person_id']}',
            '{$att['staff_name']}',
            '{$att['staff_sex']}',
            '{$att['staff_title']}',
            '教職員',
            '{$att['staff_username']}',
            '{$att['staff_password']}',
            '1'
        )";   
        
        $result=$dbh->query($sql);

        //新雲端是否已有此帳號
        $schools_id = config('boe.schools_id');
        $schools_name = config('boe.schools_name');
        $school_id = !isset($schools_id[$att['staff_sid']]) ? 0 : $schools_id[$att['staff_sid']];        
        $unit = !isset($schools_name[$att['staff_sid']]) ? "查無學校" : $schools_name[$att['staff_sid']];   

        // 1. 準備好大寫與小寫的陣列
        $personIds = [
            strtolower($att['staff_person_id']), // 轉小寫
            strtoupper($att['staff_person_id'])  // 轉大寫
        ];

        // 2. 使用 whereIn 進行查詢
        $user = User::whereIn('edu_key', $personIds)      
            ->whereIn('code', ['079998','079999'])                    
            ->first();
        $att2['username'] = $att['staff_username'];
        $att2['password'] = $att['staff_password'];
        $att2['group_id'] = "2";
        $att2['name'] = $att['staff_name'];
        $att2['code'] = $att['staff_sid'];
        $att2['school'] = $unit;
        $att2['kind'] = "教職員";
        $att2['title'] = $att['staff_title'];
        $att2['edu_key'] = strtoupper($att['staff_person_id']);
        $att2['uid'] = "";
        $att2['login_type'] = "open_id";
        $att2['school_id'] = $school_id;
        $att2['section_id'] = $att['staff_curr_class_num'];
        if (empty($user)) {                
                $user = User::create($att2);
            } else {
                //如果換了學校，初次登入刪除權限
                if ($user->code != $att['staff_sid']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);
                }

                //有此使用者，即更新使用者資料
                $att3['group_id'] = "2";
                $att3['name'] = $att['staff_name'];                
                $att3['code'] = $att['staff_sid'];
                $att3['school'] = $unit;
                $att3['kind'] = "教職員";
                $att3['title'] = $att['staff_title'];                
                $att3['edu_key'] = strtoupper($att['staff_person_id']);
                $att3['uid'] = "";
                $att3['disable'] = null;
                $att3['login_type'] = "open_id";
                $att3['school_id'] = $school_id;        
                $att3['section_id'] = $att['staff_curr_class_num'];                     
                $user->update($att3);
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

    function user_db2_store2(Request $request){
        $dbh = connect_DB2();
        $att = $request->all();
        
        if($att['staff_sid'] =="079998") $att['staff_curr_class_num'] = "I";

        $sql = "UPDATE staff SET 
            staff_sid = '{$att['staff_sid']}',
            staff_curr_class_num = '{$att['staff_curr_class_num']}',            
            staff_name = '{$att['staff_name']}',
            staff_sex = '{$att['staff_sex']}',
            staff_title = '{$att['staff_title']}',
            staff_kind = '教職員',            
            staff_status = '1'
        WHERE id = '{$att['id']}'";
        
        $result=$dbh->query($sql);

        if ($result) {
            // 💡 取得真正被更新的資料筆數
            $count = $result->rowCount(); 
            
            if ($count > 0) {                
            } else {
                // 註：如果使用者「沒有修改任何欄位就直接送出」，也會回傳 0 喔！
                dd('沒有更新！');
            }
        } else {
            dd('更新失敗！');
        }

        //新雲端是否已有此帳號
        $schools_id = config('boe.schools_id');
        $schools_name = config('boe.schools_name');
        $school_id = !isset($schools_id[$att['staff_sid']]) ? 0 : $schools_id[$att['staff_sid']];        
        $unit = !isset($schools_name[$att['staff_sid']]) ? "查無學校" : $schools_name[$att['staff_sid']];   

        // 1. 準備好大寫與小寫的陣列
        $personIds = [
            strtolower($att['staff_person_id']), // 轉小寫
            strtoupper($att['staff_person_id'])  // 轉大寫
        ];

        // 2. 使用 whereIn 進行查詢
        $user = User::whereIn('edu_key', $personIds)      
            ->whereIn('code', ['079998','079999'])                    
            ->first();
        $att2['username'] = $att['staff_username'];
        $att2['password'] = $att['staff_password'];
        $att2['group_id'] = "2";
        $att2['name'] = $att['staff_name'];
        $att2['code'] = $att['staff_sid'];
        $att2['school'] = $unit;
        $att2['kind'] = "教職員";
        $att2['title'] = $att['staff_title'];
        $att2['edu_key'] = strtolower($att['staff_person_id']);
        $att2['uid'] = "";
        $att2['login_type'] = "open_id";
        $att2['school_id'] = $school_id;
        $att2['section_id'] = $att['staff_curr_class_num'];
        if (empty($user)) {                
                $user = User::create($att2);
            } else {
                //如果換了學校，初次登入刪除權限
                if ($user->code != $att['staff_sid']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);
                }

                //有此使用者，即更新使用者資料
                $att3['group_id'] = "2";
                $att3['name'] = $att['staff_name'];                
                $att3['code'] = $att['staff_sid'];
                $att3['school'] = $unit;
                $att3['kind'] = "教職員";
                $att3['title'] = $att['staff_title'];                
                $att3['edu_key'] = strtolower($att['staff_person_id']);
                $att3['uid'] = "";
                $att3['disable'] = null;
                $att3['login_type'] = "open_id";
                $att3['school_id'] = $school_id;        
                $att3['section_id'] = $att['staff_curr_class_num'];                     
                $user->update($att3);
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

    function user_db2_out($id){
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_status = '0'         
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql); 

        return back();
    }

    function user_db2_in($id){
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_status = '1'         
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql); 

        return back();
    }

    function user_db2_change(Request $request,$id){
        $person_id = $request->input('person_id');
        $staff_sid = $request->input('staff_sid');
        $staff_name = $request->input('staff_name');
        $staff_sex = $request->input('staff_sex');
        $staff_title = $request->input('staff_title');
        $staff_curr_class_num = $request->input('staff_curr_class_num');
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_sid = '{$staff_sid}',
        staff_name = '{$staff_name}',
        staff_sex = '{$staff_sex}',
        staff_title = '{$staff_title}',
        staff_curr_class_num = '{$staff_curr_class_num}'
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql);    
        
        //如果他在新雲端也有帳號，把他科室改為正確的
        $user = User::where('edu_key',strtoupper($person_id))
            ->whereIn('code',['079999','079998'])
            ->whereNull('disable')            
            ->first();
        $att['section_id'] = $staff_curr_class_num;
        $att['code'] = $staff_sid;
        if($user) $user->update($att);

        return back();
    }

    function user_db2_change2(Request $request,$id){
        $person_id = $request->input('person_id');
        $staff_sid = $request->input('staff_sid');
        $staff_name = $request->input('staff_name');
        $staff_sex = $request->input('staff_sex');
        $staff_title = $request->input('staff_title');
        $staff_curr_class_num = $request->input('staff_curr_class_num');
        $staff_status = $request->input('staff_status');
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_sid = '{$staff_sid}',
        staff_name = '{$staff_name}',
        staff_sex = '{$staff_sex}',
        staff_title = '{$staff_title}',
        staff_curr_class_num = '{$staff_curr_class_num}',
        staff_status = '{$staff_status}'
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql);         
        //如果他在新雲端也有帳號，把他科室改為正確的
        $user = User::where('edu_key',strtoupper($person_id))
            ->whereIn('code',['079999','079998'])
            ->whereNull('disable')            
            ->first();
        $att['section_id'] = $staff_curr_class_num;
        $att['code'] = $staff_sid;
        if($user) $user->update($att);

        return redirect()->route('admins.user_db2');
    }

    function admin_db2(){
        $dbh = connect_DB2();    
        //先同步一下新雲端和DB2
        $sql = "
        SELECT 
            id,
            staff_person_id,
            staff_sid,
            staff_name,
            staff_sex,            
            staff_status,            
            staff_title,            
            staff_curr_class_num
        FROM 
            staff
        WHERE 
            staff_sid IN ('079999', '079998')
            AND staff_kind = '教職員'                 
        ORDER BY
            staff_sid,
            staff_curr_class_num
        ";        

        $result=$dbh->query($sql);        
        $staff_in = [];
        $staff_out = [];
        foreach ($result as $row) {
            if($row['staff_status']==1){
                $staff_in[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_in[$row['id']]['sid'] = $row['staff_sid'];
                $staff_in[$row['id']]['name'] = $row['staff_name'];
                $staff_in[$row['id']]['sex'] = $row['staff_sex'];
                $staff_in[$row['id']]['title'] = $row['staff_title'];
                $staff_in[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
                //記錄在職的科室
                $staff_db2[$row['staff_sid']][strtoupper($row['staff_person_id'])]['room'] = $row['staff_curr_class_num'];
                $staff_db2[$row['staff_sid']][strtoupper($row['staff_person_id'])]['id'] = $row['id'];
            }else{
                $staff_out[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_out[$row['id']]['sid'] = $row['staff_sid'];
                $staff_out[$row['id']]['name'] = $row['staff_name'];
                $staff_out[$row['id']]['sex'] = $row['staff_sex'];
                $staff_out[$row['id']]['title'] = $row['staff_title'];
                $staff_out[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
            }                        
        }    

        //查詢新雲端內的usrer 是教育處或縣網中心
        $users = User::whereIn('code',['079998','079999'])
                    ->whereNull('disable')
                    ->whereNotNull('section_id')
                    ->get();
        $update_user = [];                
        foreach($users as $user){            
            if(isset($staff_db2[$user->code][$user->edu_key]['room'])){                
                if($user->section_id != $staff_db2[$user->code][$user->edu_key]['room']){                                        
                    $update_user[$staff_db2[$user->code][$user->edu_key]['id']] = $user->section_id;
                    //修正 staff_if 對的科室
                    $staff_in[$staff_db2[$user->code][$user->edu_key]['id']]['staff_curr_class_num'] = $user->section_id;
                }
            }
        }

        if (!empty($update_user)) {
            $ids = array_keys($update_user);
            $cases = [];
            $bindings = [];
            $where_placeholders = [];

            // 1. 建立 CASE WHEN 語法與對應的綁定參數
            foreach ($update_user as $id => $section_id) {
                // SQL CASE 語法
                $cases[] = "WHEN id = :id_{$id} THEN :sec_{$id}";
                
                // 綁定 CASE 的值
                $bindings[":id_{$id}"] = $id;
                $bindings[":sec_{$id}"] = $section_id;
            }

            // 2. 建立 WHERE IN 專用的命名佔位符（例如 :where_id_123）
            foreach ($ids as $id) {
                $where_placeholders[] = ":where_id_{$id}";
                $bindings[":where_id_{$id}"] = $id; // 同樣塞進綁定陣列中
            }

            $cases_sql = implode(' ', $cases);
            $where_in_sql = implode(',', $where_placeholders);
            
            // 3. 組合最終的 SQL（完全不含問號 ?）
            $sql = "UPDATE staff SET 
                        staff_curr_class_num = CASE 
                            {$cases_sql} 
                            ELSE staff_curr_class_num 
                        END 
                    WHERE id IN ({$where_in_sql})";

            // 4. 準備 PDO Statement
            $stmt = $dbh->prepare($sql);

            // 5. 一口氣綁定所有命名參數並執行
            // 由於我們把 CASE 和 WHERE 的參數都整理在 $bindings 陣列中，可以直接傳入 execute
            $stmt->execute($bindings);

            // 6. 取得受影響的總筆數
            $affected_rows = $stmt->rowCount();            
        }


        //正式算自己科室的
        $section_id = auth()->user()->section_id;
        $code = auth()->user()->code;
        
        $sql2 = "
        SELECT 
            id,
            staff_person_id,
            staff_sid,
            staff_name,
            staff_sex,            
            staff_status,
            staff_title,            
            staff_curr_class_num
        FROM 
            staff
        WHERE 
            staff_sid = '{$code}'
            AND staff_kind = '教職員'
            AND staff_curr_class_num = '{$section_id}'             
        ORDER BY
            staff_sid,
            staff_curr_class_num                        
        ";        

        $result=$dbh->query($sql2);     
        $staff_in = [];
        $staff_out = [];
        foreach ($result as $row) {
            if($row['staff_status']=="1"){
                $staff_in[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_in[$row['id']]['sid'] = $row['staff_sid'];
                $staff_in[$row['id']]['name'] = $row['staff_name'];
                $staff_in[$row['id']]['sex'] = $row['staff_sex'];
                $staff_in[$row['id']]['title'] = $row['staff_title'];
                $staff_in[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
            }else{
                $staff_out[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_out[$row['id']]['sid'] = $row['staff_sid'];
                $staff_out[$row['id']]['name'] = $row['staff_name'];
                $staff_out[$row['id']]['sex'] = $row['staff_sex'];
                $staff_out[$row['id']]['title'] = $row['staff_title'];
                $staff_out[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
            }                        
        }        
        $sections = config('boe.sections');
        $data = [            
            'staff_in'=>$staff_in,
            'staff_out'=>$staff_out,
            'sections'=>$sections,
        ];
        return view('edus.my_section.user_db2',$data);                               
    }

    function admin_db2_out($id){
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_status = '0'         
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql); 

        return back();
    }

    function admin_db2_in($id){
        $dbh = connect_DB2();
        $sql = "
        UPDATE staff 
        SET staff_status = '1'         
        WHERE 
            id = '{$id}'                    
        ";        
        $result=$dbh->query($sql); 

        return back();
    }

    function admin_db2_create(){
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
        ];
        return view('edus.my_section.user_db2_create',$data);
    }

    function admin_db2_store(Request $request){
        $dbh = connect_DB2();
        $att = $request->all();
        $att['staff_person_id'] = hash('sha256', strtoupper(trim($att['id_card'])));
        $att['staff_sid'] = auth()->user()->code;
        $att['staff_curr_class_num'] = auth()->user()->section_id;
        if($att['staff_sid'] =="079998") $att['staff_curr_class_num'] = "I";
        $att['staff_username'] = generateRandomString(10);
        $att['staff_password'] = generateRandomString(12);

        $check_sql = "SELECT id,staff_sid,staff_status FROM staff WHERE staff_person_id = :staff_person_id LIMIT 1";

        $stmt = $dbh->prepare($check_sql);

        // 2. 執行並綁定變數
        $stmt->execute([
            ':staff_person_id' => $att['staff_person_id']
        ]);

        // 3. 取得查詢結果
        $exists = $stmt->fetch();

        // 4. 判斷並回傳結果
        if ($exists) {            
            // 有找到資料，代表已經申請過
            $id = $exists['id'];
            $staff_sid = $exists['staff_sid'];
            $staff_status = $exists['staff_status'];            
            $data = [
                'id'=>$id,
                'staff_sid'=>$staff_sid,  
                'staff_status'=>$staff_status,
                'new_staff_sid'=>$att['staff_sid'],
                'new_staff_curr_class_num'=>$att['staff_curr_class_num'],
                'new_staff_name'=>$att['staff_name'],
                'new_staff_sex'=>$att['staff_sex'],
                'new_staff_title'=>$att['staff_title'],                
            ];
            return view('edus.my_section.user_db2_has',$data);
        }


        $sql = "INSERT INTO staff (
            staff_sid,
            staff_curr_class_num,
            staff_person_id,
            staff_name,
            staff_sex,
            staff_title,
            staff_kind,
            staff_username,
            staff_password,
            staff_status
        ) VALUES (
            '{$att['staff_sid']}',
            '{$att['staff_curr_class_num']}',
            '{$att['staff_person_id']}',
            '{$att['staff_name']}',
            '{$att['staff_sex']}',
            '{$att['staff_title']}',
            '教職員',
            '{$att['staff_username']}',
            '{$att['staff_password']}',
            '1'
        )";   
        
        $result=$dbh->query($sql);

        //新雲端是否已有此帳號
        $schools_id = config('boe.schools_id');
        $schools_name = config('boe.schools_name');
        $school_id = !isset($schools_id[$att['staff_sid']]) ? 0 : $schools_id[$att['staff_sid']];        
        $unit = !isset($schools_name[$att['staff_sid']]) ? "查無學校" : $schools_name[$att['staff_sid']];   

        // 1. 準備好大寫與小寫的陣列
        $personIds = [
            strtolower($att['staff_person_id']), // 轉小寫
            strtoupper($att['staff_person_id'])  // 轉大寫
        ];

        // 2. 使用 whereIn 進行查詢
        $user = User::whereIn('edu_key', $personIds)      
            ->whereIn('code', ['079998','079999'])                    
            ->first();
        $att2['username'] = $att['staff_username'];
        $att2['password'] = $att['staff_password'];
        $att2['group_id'] = "2";
        $att2['name'] = $att['staff_name'];
        $att2['code'] = $att['staff_sid'];
        $att2['school'] = $unit;
        $att2['kind'] = "教職員";
        $att2['title'] = $att['staff_title'];
        $att2['edu_key'] = strtoupper($att['staff_person_id']);
        $att2['uid'] = "";
        $att2['login_type'] = "open_id";
        $att2['school_id'] = $school_id;
        $att2['section_id'] = $att['staff_curr_class_num'];
        if (empty($user)) {                
                $user = User::create($att2);
            } else {
                //如果換了學校，初次登入刪除權限
                if ($user->code != $att['staff_sid']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);
                }

                //有此使用者，即更新使用者資料
                $att3['group_id'] = "2";
                $att3['name'] = $att['staff_name'];                
                $att3['code'] = $att['staff_sid'];
                $att3['school'] = $unit;
                $att3['kind'] = "教職員";
                $att3['title'] = $att['staff_title'];                
                $att3['edu_key'] = strtoupper($att['staff_person_id']);
                $att3['uid'] = "";
                $att3['disable'] = null;
                $att3['login_type'] = "open_id";
                $att3['school_id'] = $school_id;        
                $att3['section_id'] = $att['staff_curr_class_num'];                     
                $user->update($att3);
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
}
