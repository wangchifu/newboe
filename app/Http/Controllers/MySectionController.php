<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPower;

class MySectionController extends Controller
{
    public function apply_section(){
        $user = auth()->user();
        $sections = config('boe.sections');
        $data = [
            'user' => $user,
            'sections' => $sections,
        ];
        return view('edus.my_section.apply_section',$data);
    }

    public function section_update(Request $request, User $user)
    {
        $att['my_section_id'] = $request->input('my_section_id');

        $user->update($att);

        return redirect()->route('apply_section');
    }

    public function section_delete(User $user)
    {
        $att['my_section_id'] = "";

        $user->update($att);

        return redirect()->route('apply_section');
    }

    public function admin()
    {
        if (auth()->user()->group_id == 8) {
            $section_id = auth()->user()->section_id;
        } else {
            //取得他管理的科室
            $user_power = UserPower::where('user_id', auth()->user()->id)
                ->where('power_type', 'A')
                ->first();
            $section_id = $user_power->section_id;
        }
        //已是本科室成員
        $users1 = User::where('section_id', $section_id)
            ->orderBy('disable')
            ->get();

        // 檢查 OpenID 成員在 DB2 的身份（本地帳號不查）
        $db2_status = [];
        $openid_users = $users1->filter(fn($u) => in_array($u->login_type, ['open_id', 'gsuite']) && !empty($u->edu_key));
        $edu_keys = $openid_users->pluck('edu_key')->map(fn($k) => strtolower($k))->toArray();
        if (!empty($edu_keys)) {
            try {
                $dbh = connect_DB2();
                $placeholders = implode(',', array_fill(0, count($edu_keys), '?'));
                $sql = "SELECT LOWER(staff_person_id) as pid, staff_sid FROM staff WHERE LOWER(staff_person_id) IN ({$placeholders}) AND staff_kind = '教職員' AND staff_status = '1'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute($edu_keys);
                $found = [];
                foreach ($stmt->fetchAll() as $row) {
                    $found[$row['pid']][] = $row['staff_sid'];
                }
                // 再查一次不限 status，用來判斷是否有離職紀錄
                $sql2 = "SELECT LOWER(staff_person_id) as pid, staff_sid FROM staff WHERE LOWER(staff_person_id) IN ({$placeholders}) AND staff_kind = '教職員' AND staff_status = '0'";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2->execute($edu_keys);
                $found_inactive = [];
                foreach ($stmt2->fetchAll() as $row) {
                    $found_inactive[$row['pid']][] = $row['staff_sid'];
                }

                foreach ($openid_users as $u) {
                    $key = strtolower($u->edu_key);
                    if (!isset($found[$key]) && isset($found_inactive[$key])) {
                        $db2_status[$u->id] = '異常';
                    } elseif (!isset($found[$key])) {
                        $db2_status[$u->id] = '未有人員資料';
                    } else {
                        $sids = $found[$key];
                        $has_bureau = in_array('079999', $sids) || in_array('079998', $sids);
                        $has_school = count(array_filter($sids, fn($s) => $s != '079999' && $s != '079998')) > 0;
                        if ($has_bureau && $has_school) {
                            $db2_status[$u->id] = '調府教師';
                        } elseif (in_array('079999', $sids)) {
                            $db2_status[$u->id] = '教育處';
                        } elseif (in_array('079998', $sids)) {
                            $db2_status[$u->id] = '縣網中心';
                        } else {
                            $db2_status[$u->id] = '調府教師';
                        }
                    }
                }
            } catch (\Exception $e) {
                // DB2 連線失敗就不標註
            }
        }

        //選填本科室者
        $users2 = User::where('my_section_id', $section_id)
            ->get();

        $sections = config('boe.sections');

        $a_admins = UserPower::where('section_id', auth()->user()->section_id)
            ->where('power_type', 'A')
            ->get();

        $data = [
            'users1' => $users1,
            'users2' => $users2,
            'sections' => $sections,
            'a_admins' => $a_admins,
            'section_id' => $section_id,
            'db2_status' => $db2_status,
        ];
        return view('edus.my_section.admin', $data);
    }

    public function power_remove($id)
    {
        $user_power = UserPower::where('id', $id)->first();
        $caller_section = auth()->user()->section_id;
        if ($user_power->section_id !== $caller_section) {
            abort(403, '只能管理自己科室的權限');
        }        
        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 移除了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 在科室的管理權：" . $user_power->section_id;
        logging('2', $event, get_ip());

        $user_power->delete();

        return redirect()->route('my_section.admin');
    }

    public function power()
    {
        $users = User::where('disable', null)
            ->where('section_id',auth()->user()->section_id)
            ->get();
        $sections = config('boe.sections');
        foreach ($users as $user) {
            $s = ($user->section_id) ? $sections[$user->section_id] . "--" : "";
            $select_users[$user->id] = $s . $user->name . "(" . $user->username . ")";
        }

        $data = [
            'sections' => $sections,
            'select_users' => $select_users,
            'sections' => $sections,
        ];
        return view('edus.my_section.power', $data);
    }

    public function power_update1(Request $request)
    {        
        if ($request->input('section_id') !== auth()->user()->section_id) {
            abort(403, '只能管理自己的科室');
        }

        $att['section_id'] = $request->input('section_id');
        $att['user_id'] = $request->input('user_id');
        $att['power_type'] = "A";
        $user_power = UserPower::create($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 有科室：" . $att['section_id'] . " 的A權限";
        logging('2', $event, get_ip());


        //加入科室
        /**
        $user = User::where('id', $request->input('user_id'))
            ->first();
        $att2['section_id'] = $request->input('section_id');
        $user->update($att2);
         * */

        echo "
        <script>
        // 通知父層重整 + 關閉 Venobox
            window.parent.location.reload(); // 父頁刷新
            window.parent.jQuery('.vbox-close').click(); // 關掉 Venobox
        </script>
        ";
    }

    public function power_update2(Request $request)
    {
        if ($request->input('section_id') !== auth()->user()->section_id) {
            abort(403, '只能管理自己的科室');
        }        
        $user = User::where('username', $request->input('username'))
            ->whereNull('disable')
            ->first();

        if ($user) {
            $att['section_id'] = $request->input('section_id');
            $att['user_id'] = $user->id;
            $att['power_type'] = "A";
            $user_power = UserPower::create($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 有科室：" . $att['section_id'] . " 的A權限";
            logging('2', $event, get_ip());

            //加入科室
            /**
            $att2['section_id'] = $request->input('section_id');
            $user->update($att2);
             * */
        } else {
            return back()->withErrors(['errors' => ['無此帳號！']]);;
        }
        echo "
        <script>
        // 通知父層重整 + 關閉 Venobox
            window.parent.location.reload(); // 父頁刷新
            window.parent.jQuery('.vbox-close').click(); // 關掉 Venobox
        </script>
        ";
    }

    public function remove(User $user)
    {
        if ($user->section_id != auth()->user()->section_id) {
            abort(403);
        }                      
        $old_section_id = $user->section_id;
        $att['section_id'] = null;

        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 移除了使用者 id：" . $user->id . " " . $user->name . " 在科室：" . $old_section_id;
        logging('2', $event, get_ip());

        return redirect()->route('my_section.admin');
    }

    public function agree(User $user)
    {
        if ($user->my_section_id != auth()->user()->section_id) {
            abort(403);
        }              
        $att['my_section_id'] = null;
        $att['section_id'] = $user->my_section_id;
        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 同意了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $user->my_section_id;
        logging('2', $event, get_ip());

        return redirect()->route('my_section.admin');
    }

    public function disagree(User $user)
    {
        if ($user->my_section_id != auth()->user()->section_id) {
            abort(403);
        }                      
        $att['my_section_id'] = null;

        $user->update($att);
        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 不同意了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $user->my_section_id;
        logging('2', $event, get_ip());
        return redirect()->route('my_section.admin');
    }

    public function member()
    {
        $users = User::where('group_id', 2)
            ->where('disable', null)
            ->orderBy('section_id')
            ->get();
        $sections = config('boe.sections');
        foreach ($users as $user) {
            $s = ($user->section_id) ? $sections[$user->section_id] . "--" : "";
            $select_users[$user->id] = $s . $user->name . "(" . $user->username . ")";
        }

        $data = [
            'users' => $users,
            'sections' => $sections,
            'select_users' => $select_users,
            'sections' => $sections,
        ];
        return view('edus.my_section.member', $data);
    }

    public function member_update(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))
            ->first();

        $att['section_id'] = $request->input('section_id');
        //echo $att['section_id'];

        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $att['section_id'];
        logging('2', $event, get_ip());

        echo "
        <script>
        // 通知父層重整 + 關閉 Venobox
            window.parent.location.reload(); // 父頁刷新
            window.parent.jQuery('.vbox-close').click(); // 關掉 Venobox
        </script>
        ";
    }

    public function member_update2(Request $request)
    {
        $users = User::where('username', $request->input('username'))
            ->get();

        if ($users->isNotEmpty()) {
            $att['section_id'] = $request->input('section_id');
            foreach ($users as $user) {
                $user->update($att);

                $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user->id . " " . $user->name . "(" . $user->code . ") 屬於科室：" . $att['section_id'];
                logging('2', $event, get_ip());
            }
        } else {
            return back()->withErrors(['errors' => ['無此帳號！']]);
        }


        echo "
        <script>
        // 通知父層重整 + 關閉 Venobox
            window.parent.location.reload(); // 父頁刷新
            window.parent.jQuery('.vbox-close').click(); // 關掉 Venobox
        </script>
        ";
    }
}
