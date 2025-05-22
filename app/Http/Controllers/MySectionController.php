<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPower;

class MySectionController extends Controller
{
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
        ];
        return view('my_section.admin', $data);
    }
}
