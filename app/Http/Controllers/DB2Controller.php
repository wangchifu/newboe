<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $staff_in = [];
        foreach ($result as $row) {
            if($row['staff_status']==1){
                $staff_in[$row['id']]['person_id'] = $row['staff_person_id'];
                $staff_in[$row['id']]['sid'] = $row['staff_sid'];
                $staff_in[$row['id']]['name'] = $row['staff_name'];
                $staff_in[$row['id']]['sex'] = $row['staff_sex'];
                $staff_in[$row['id']]['title'] = $row['staff_title'];
                $staff_in[$row['id']]['staff_curr_class_num'] = $row['staff_curr_class_num'];
            }else{
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
        return view('admins.user_db2',$data);
    }

    function user_db2_create(){
        $data = [

        ];
        return view('admins.user_db2_create',$data);
    }

    function user_db2_store(Request $request){
        $dbh = connect_DB2();
        $att = $request->all();
        $att['staff_person_id'] = hash('sha256', strtoupper(trim($att['id_card'])));
        if($att['staff_sid'] =="079998") $att['staff_curr_class_num'] = "I";
        $att['staff_username'] = generateRandomString(10);
        $att['staff_password'] = generateRandomString(12);

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

        echo "<body onload='sw_alert()'>";

        return back();
    }
}
