<?php
//記錄登入錯誤次數
function login_error_add($username)
{
    $dt = \Carbon\Carbon::now();
    $t = $dt->subMinutes(15)->format('Y-m-d H:i:s');
    $ip = get_ip();
    $check = \App\Models\LoginError::where('username', $username)
        ->where('ip', $ip)
        ->where('updated_at', '>', $t)
        ->first();
    $att['username'] = $username;
    $att['ip'] = get_ip();

    if (empty($check)) {
        $att['error_count'] = 1;
        \App\Models\LoginError::create($att);
    } else {
        if ($check->error_count < 3) {
            $att['error_count'] = $check->error_count + 1;
            $check->update($att);
        }
    }
}

function login_eroor_count($username)
{
    $dt = \Carbon\Carbon::now();
    $t = $dt->subMinutes(15)->format('Y-m-d H:i:s');
    $ip = get_ip();
    $check = \App\Models\LoginError::where('username', $username)
        ->where('ip', $ip)
        ->where('updated_at', '>', $t)
        ->first();
    if (empty($check)) {
        return 0;
    } else {
        return $check->error_count;
    }
}

//取使用者IP
function get_ip()
{
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // to get shared ISP IP address
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check for IPs passing through proxy servers
        // check if multiple IP addresses are set and take the first one
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (!empty($ip)) {
                // if you prefer, you can check for valid IP address here
                $ipAddress = $ip;
                break;
            }
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

function logging($level, $event, $ip)
{
    $att['level'] = $level;
    $att['event'] = $event;
    $att['user_id'] = auth()->user()->id;
    $att['ip'] = $ip;
    \App\Models\Log::create($att);

    $message = $event . ' ' . auth()->user()->id . ' ' . $ip;
    switch ($level) {
        case 0:
            Log::emergency($message);
            break;
        case 1:
            Log::alert($message);
            break;
        case 2:
            Log::critical($message);
            break;
        case 3:
            Log::error($message);
            break;
        case 4:
            Log::warning($message);
            break;
        case 5:
            Log::notice($message);
            break;
        case 6:
            Log::info($message);
            break;
    }
}

function check_php($file){    
    $fileExtension = $file->getClientOriginalExtension();    
    if ($fileExtension === 'php') {
        return true;
    } 
        
    $mimeType = $file->getClientMimeType();

    if ($mimeType === 'text/x-php' || $mimeType === 'application/x-httpd-php') {
        return true;
    }
    
    $fileContent = file_get_contents($file->getRealPath());

    if (strpos($fileContent, '<?php') !== false) {
        return true;   
    }
}

function get_menu($menus,$i)
{
    foreach ($menus as $menu) {
        if($i==0){            
            if($menu->type == 1) {
                echo "<li class='nav-item dropdown'>";
                echo "<a class='nav-link dropdown-toggle' href='#!' role='button' data-bs-toggle='dropdown' aria-expanded='false'>";
                echo $menu->name;
                echo "</a>";
                echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
                $menu2s = \App\Models\Menu::where('belong', $menu->id)
                ->orderBy('order_by')
                ->get();
                if ($menu2s->count() > 0) {
                    get_menu($menu2s,$i+1);
                }
                echo "</ul></li>";
            }            
            if($menu->type == 2) {
                echo "<a class='nav-link' href='" . $menu->link . "' target='" . $menu->target . "'>" . $menu->name . "</a></li>";                
            }
        }
        if($i==1){
            if($menu->type == 1) {
                echo "<li class='dropdown-submenu'>";
                echo "<a class='dropdown-item dropdown-toggle' href='#!'>".$menu->name."</a>";
                echo "<ul class='dropdown-menu dropdown-menu-end'>";
                $menu2s = \App\Models\Menu::where('belong', $menu->id)
                ->orderBy('order_by')
                ->get();
                if ($menu2s->count() > 0) {
                    get_menu($menu2s,$i+1);
                }
                echo "</ul></li>";
            }            
            if($menu->type == 2) {
                echo "<li><a class='dropdown-item' href='".$menu->link."' target='" . $menu->target . "'>".$menu->name."</a></li>";
            }
        }
        if($i==2){
            echo "<li><a class='dropdown-item' href='".$menu->link."' target='" . $menu->target . "'>".$menu->name."</a></li>";
        }       
    }
}

function del_folder($folder) {
    if (!is_dir($folder)) {
        return false; // 如果不是目錄，直接返回
    }
    
    $files = array_diff(scandir($folder), ['.', '..']);
    
    foreach ($files as $file) {
        $path = "$folder/$file";
        if (is_dir($path)) {
            del_folder($path); // 遞歸刪除子目錄
        } else {
            unlink($path); // 刪除檔案
        }
    }
    
    return rmdir($folder); // 刪除目錄本身
}

//檢查是否為教育處、學校的一級A的管理人員(教育處審核公告、資料填報；學校審核資料填報)
if (!function_exists('check_a_user')) {
    function check_a_user($section_id, $user_id)
    {
        //信義國中小
        if ($section_id === "074774" or $section_id === "074541") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074774')->orWhere('section_id', '074541');
                })
                ->first();
            //原斗國中小
        } elseif ($section_id === "074745" or $section_id === "074537") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074745')->orWhere('section_id', '074537');
                })
                ->first();
            //民權國中小
        } elseif ($section_id === "074760" or $section_id === "074543") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074760')->orWhere('section_id', '074543');
                })
                ->first();
            //鹿江國中小
        } elseif ($section_id === "074542" or $section_id === "074778") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'A')
                ->where(function ($q) {
                    $q->where('section_id', '074542')->orWhere('section_id', '074778');
                })
                ->first();
        } else {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('section_id', $section_id)
                ->where('power_type', 'A')
                ->first();
        }
        if ($user_power) {
            return true;
        } else {
            return false;
        }
    }
}

//檢查是否為教育處、學校的二級B的人員(教育處發公告、資料填報；學校簽收公告、資料填報)
if (!function_exists('check_b_user')) {
    function check_b_user($section_id, $user_id)
    {
        //信義國中小
        if ($section_id === "074774" or $section_id === "074541") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074774')->orWhere('section_id', '074541');
                })
                ->first();
            //原斗國中小
        } elseif ($section_id === "074745" or $section_id === "074537") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074745')->orWhere('section_id', '074537');
                })
                ->first();
            //民權國中小
        } elseif ($section_id === "074760" or $section_id === "074543") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074760')->orWhere('section_id', '074543');
                })
                ->first();
            //鹿江國中小
        } elseif ($section_id === "074542" or $section_id === "074778") {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('power_type', 'B')
                ->where(function ($q) {
                    $q->where('section_id', '074542')->orWhere('section_id', '074778');
                })
                ->first();
        } else {
            $user_power = \App\Models\UserPower::where('user_id', $user_id)
                ->where('section_id', $section_id)
                ->where('power_type', 'B')
                ->first();
        }

        if ($user_power) {
            return true;
        } else {
            return false;
        }
    }
}

function close_system(){
    if(file_exists(storage_path('app/private/close.txt'))){    
        $fp = fopen(storage_path('app/private/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/private/close.txt')));                
        fclose($fp);
        
        if($close == 1) \Illuminate\Support\Facades\Redirect::to('close')->send();
    }

}