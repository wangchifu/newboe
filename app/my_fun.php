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

//顯示某目錄下的檔案
if (!function_exists('get_files')) {
    function get_files($folder)
    {
        $files = [];
        $i = 0;
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) {
                while (false !== ($name = readdir($handle))) {
                    if ($name != "." && $name != "..") {
                        //去除掉..跟.
                        $files[$i] = $name;
                        $i++;
                    }
                }
                closedir($handle);
            }
        }
        $files = array_values(array_sort($files, function ($value) {
            return $value;
        }));
        return $files;
    }
}

if (!function_exists('array_sort')) {
    function array_sort(array $array, callable $callback = null)
    {
        if ($callback) {
            uasort($array, $callback);
        } else {
            asort($array);
        }
        return $array;
    }
}

function filesizekb($file) {
    if (!file_exists($file)) return 0;
    return round(filesize($file) / 1024, 2); // 取小數兩位
}

//字串截短
function smart_truncate_clean($string, $length) {
    $encoding = 'UTF-8';

    // 1. 去除 HTML 標籤
    $cleanString = strip_tags($string);

    $cleanString = str_replace('&nbsp;','',$cleanString);

    // 2. 判斷長度是否已足夠
    if (mb_strlen($cleanString, $encoding) <= $length) {
        return $cleanString;
    }

    // 3. 截字並加上 ...
    return mb_substr($cleanString, 0, $length, $encoding) . '...';
}

//自訂 array_get
if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }

        if ($key === null) {
            return $array;
        }

        $keys = explode('.', $key);
        foreach ($keys as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }
}

//自動判斷url是否有http，否則自動補齊
if (!function_exists('transfer_url_http')) {
    function transfer_url_http($url)
    {
        if (!($url)) {
            return null;
        } else {
            if (substr($url, 0, 8) == 'https://') {
                return $url;
            } elseif (substr($url, 0, 7) == 'http://') {
                return $url;
            } else {
                return 'http://' . $url;
            }
        }
    }
}

// 多個儲存值(陣列形式)轉換成 1,2,3,8,9,22 ... 的字串
if (!function_exists('checkbox_str_num')) {
    function checkbox_str_num($value_array, $split = ', ')
    {

        $out = '';
        $set_idx = 0;
        foreach ($value_array as $value) {
            $mask = 1;
            for ($i = 1; $i <= 63; $i++) {
                if (($value & $mask) > 0) {
                    if ($out != '')
                        $out .= $split;
                    $out .= $i + $set_idx * 63;
                }
                $mask <<= 1;
            }
            $set_idx++;
        }
        return $out;
    }
}

// 勾選的資料用 bit 的概念儲存成 5 個值
if (!function_exists('checkbox_val')) {
    function checkbox_val($value_array)
    {
        $item_value = array(0, 0, 0, 0, 0);
        foreach ($value_array as $value) {
            if ($value > 0) {
                $set_idx = floor(($value - 1) / 63);
                if ($set_idx > 0)
                    $value %= 63;
                if ($value == 0)
                    $value = 63;
                $item_value[$set_idx] += pow(2, $value - 1);
            }
        }
        return $item_value;
    }
}