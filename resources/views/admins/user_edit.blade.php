@extends('layouts.app_clean')

@section('title','帳號編輯')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <img class="card-img-top img-responsive" src="{{ asset('images/small/acc_edit.png') }}">
        </div>
        <div class="card-body">
            <form action="{{ route('admins.user_update',$user->id) }}" method="post" id="user_form">  
            @csrf          
            <div class="form-group">
                <label>
                    <strong>姓名(帳號)</strong>
                </label>
                <div class="form-group">
                    {{ $user->name }}({{ $user->username }})
                </div>
            </div>
            @if($user->code != null)
                <div class="form-group">
                    <label>
                        <strong>學校 職稱</strong>
                    </label>
                    <div class="form-group">
                        {{ $user->school }} {{ $user->title }}<br>
                        <?php
                        $user_power = \App\Models\UserPower::where('section_id',$user->code)
                            ->where('user_id',$user->id)
                            ->where('power_type','A')
                            ->first();
                        $a_checked = ($user_power)?"checked":null;

                        $user_power = \App\Models\UserPower::where('section_id',$user->code)
                            ->where('user_id',$user->id)
                            ->where('power_type','B')
                            ->first();
                        $b_checked = ($user_power)?"checked":null;

                        $other_check1 = ($user->other_code=="099901")?"checked":null;
                        $other_check2 = ($user->other_code=="099902")?"checked":null;
                        $other_check3 = ($user->other_code=="099903")?"checked":null;
                        $other_check4 = ($user->other_code=="099904")?"checked":null;
                        $other_check5 = ($user->other_code=="099905")?"checked":null;

                        ?>
                        @if($user->code !="079998" and $user->code !="079999" and $user->code !="070000" and $user->code !=null)
                            <input type="checkbox" name="a_user" id="a_user" onclick="check_another()" {{ $a_checked }}> <label for="a_user">學校 管理+審核權</label><br>
                            <input type="checkbox" name="b_user" id="b_user" {{ $b_checked }}> <label for="b_user">學校 簽收+填報權</label>
                            <input type="hidden" name="code" value="{{ $user->code }}">
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <strong>屬於可收公告的其他單位</strong>
                    </label>
                    <div class="form-group">
                        <label for='sel_school_251'><input type='checkbox' id='sel_school_251' name='other_code' value='099901' {{ $other_check1 }}>特殊教育學校</label>
                        <label for='sel_school_252'><input type='checkbox' id='sel_school_252' name='other_code' value='099902' {{ $other_check2 }}>向日葵學園</label>
                        <label for='sel_school_253'><input type='checkbox' id='sel_school_253' name='other_code' value='099903' {{ $other_check3 }}>晨陽學園</label>
                        <label for='sel_school_254'><input type='checkbox' id='sel_school_254' name='other_code' value='099904' {{ $other_check4 }}>喜樂之家</label>
                        <label for='sel_school_255'><input type='checkbox' id='sel_school_255' name='other_code' value='099905' {{ $other_check5 }}>教師研習中心</label>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label>
                    <strong>所屬教育處科室</strong><small>(或學校單位的調府教師)</small>
                </label>
                <select name="section_id" class="form-control">
                    <option value="" selected> </option>
                    @foreach($sections as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>
                    <strong>教育處科室權限</strong><small>(基本上是給科長)</small>
                </label>
                <select name="section_admin" class="form-control">
                    <option value="" selected> </option>
                    @foreach($section_admins as $key => $value)
                        <option value="{{ $key }}" {{ $key == $section_admin ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <?php
                    $check_admin = ($user->admin==1)?"checked":"";
                ?>
                <label for="admin">
                    <strong class="text-danger">
                        系統管理權
                    </strong>
                    <input type="checkbox" name="admin" id="admin" value="1" {{ $check_admin }}>
                </label>
            </div>
            <div class="form-group">
                <a class="btn btn-success btn-sm" onclick="sw_confirm2('確定儲存？','user_form')">儲存</a>
            </div>
        </form>
        </div>
    </div>    
</div>
@endsection

@section('my_js')
    <script>
        window.onload = function() {
            // 檢查是否來自 iframe
            if (window.parent && window.parent.$) {
                // 假設儲存成功後，發送消息給父頁面
                $.venobox.close();  // 這會關閉 venobox 視窗
        
                // 可選: 刷新父頁面，或者執行其他操作
                window.parent.location.reload();  // 這會刷新父頁面
            }
        };
    </script>
    <script>
        function check_another(){
            if($('#a_user').prop('checked')){
                $('#b_user').prop("checked", true);
            }
        }
    </script>
@endsection