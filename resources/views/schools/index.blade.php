@extends('layouts.app')

@section('title','學校帳號')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ auth()->user()->school }} 學校帳號-指定帳號</h1>
    <div class="card mb-4">
        <div class="card-header">            
            <h4 class="py-2" style="color:red">
                1.若職務調動，請先至校務系統變更，並經同步 EIP 後，請該教職登出本系統，然後再次登入後即可。<br>
                2.若職稱仍錯誤，請該教職至右上角 <i class="fas fa-user"></i> 點出下拉選項 [ <a href="{{ route('edit_title') }}">變更職稱</a> ]。
            </h4>            
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_acc.index') }}">指定帳號</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_acc.list') }}">權限列表</a>
                </li>
            </ul>
            <h2>
                1.本校帳號
            </h2>
            <table class="table table-hover">
                <tr>
                    <td>
                        姓名(帳號)
                    </td>
                    <td>
                        職稱
                    </td>
                    <td colspan="2">
                        權限
                    </td>
                    <td>
                        動作
                    </td>
                </tr>
                @foreach($users as $user)
                    @if($user->disable)
                        
                    @else
                        <tr>
                            <td>
                                {{ $user->name }}(
                                @if($user->username)
                                    {{ $user->username }})
                                @else
                                    {{ $user->openid }})
                                @endif
                            </td>
                            <td>
                                {{ $user->school }} {{ $user->title }}
                            </td>
                            <td>
                                <?php
                                //信義國中小
                                if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','A')
                                        ->where(function($q){
                                            $q->where('section_id','074774')->orWhere('section_id','074541');
                                        })
                                        ->first();
                                //原斗國中小
                                }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','A')
                                        ->where(function($q){
                                            $q->where('section_id','074745')->orWhere('section_id','074537');
                                        })
                                        ->first();
                                //民權國中小
                                }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','A')
                                        ->where(function($q){
                                            $q->where('section_id','074760')->orWhere('section_id','074543');
                                        })
                                        ->first();
                                //鹿江國中小
                                }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','A')
                                        ->where(function($q){
                                            $q->where('section_id','074542')->orWhere('section_id','074778');
                                        })
                                        ->first();
                                }else{
                                    $user_power = \App\Models\UserPower::where('section_id',$user->code)
                                        ->where('user_id',$user->id)
                                        ->where('power_type','A')
                                        ->first();
                                }



                                ?>
                                @if($user_power)
                                    審核+管理權
                                @endif
                            </td>
                            <td>
                                <?php
                                //信義國中小
                                if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','B')
                                        ->where(function($q){
                                            $q->where('section_id','074774')->orWhere('section_id','074541');
                                        })
                                        ->first();
                                //原斗國中小
                                }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','B')
                                        ->where(function($q){
                                            $q->where('section_id','074745')->orWhere('section_id','074537');
                                        })
                                        ->first();
                                //民權國中小
                                }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','B')
                                        ->where(function($q){
                                            $q->where('section_id','074760')->orWhere('section_id','074543');
                                        })
                                        ->first();
                                //鹿江國中小
                                }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
                                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                        ->where('power_type','B')
                                        ->where(function($q){
                                            $q->where('section_id','074542')->orWhere('section_id','074778');
                                        })
                                        ->first();
                                }else{
                                    $user_power = \App\Models\UserPower::where('section_id',$user->code)
                                        ->where('user_id',$user->id)
                                        ->where('power_type','B')
                                        ->first();
                                }

                                ?>
                                @if($user_power)
                                    簽收+填報權
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('school_acc.edit',$user->id) }}" class="btn btn-primary btn-sm venobox" data-vbtype="iframe">編輯</a>
                                @if($user->id != auth()->user()->id and $user->admin !=1)
                                    <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定要停用？','{{ route('school_acc.destroy',$user->id) }}')">停用</a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <hr>            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4><i class="fa-solid fa-user-minus text-danger"></i> 帳號已移除名單(按一下)</h4>
                </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table class="table table-hover">
                            <tr>
                                <td>
                                    姓名(帳號)
                                </td>
                                <td>
                                    職稱
                                </td>
                                <td colspan="2">
                                    權限
                                </td>
                                <td>
                                    動作
                                </td>
                            </tr>
                            @foreach($users as $user)
                                @if($user->disable)
                                    <tr style="color:#cccccc">
                                        <td>
                                            {{ $user->name }}(
                                            @if($user->username)
                                                {{ $user->username }}，帳號已移除)
                                            @else
                                                {{ $user->openid }}，帳號已移除)
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->title }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <a href="#!" class="btn btn-secondary btn-sm" onclick="sw_confirm1('確定要復原？','{{ route('school_acc.reback',$user->id) }}')">復原</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>                        
                    </div>
                </div>
            </div>            
            <hr>
            <h2>
                2.他校兼任功能已由 EIP 選校取代，請將下列名單移除，確保安全 
            </h2>
            <!--
            <form action="{{ route('school_acc.other') }}" method="POST">
                @csrf
            <div class="form-group">
                <input type="text" name="username" class="form-control" required placeholder="請輸入成員GSuite帳號">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm">編輯成員
                </button>
            </div>
            <input type="hidden" name="section_id" value="{{ auth()->user()->other_code }}">
            </form>
            @include('layouts.errors')
            -->
            <table class="table table-hover">
                <tr>
                    <td>
                        姓名(帳號)
                    </td>
                    <td>
                        職稱
                    </td>
                    <td colspan="2">
                        權限
                    </td>
                </tr>
                @foreach($user_not_data as $k=>$v)
                    <tr>
                        <td>
                            {{ $v['name'] }}(
                            @if($v['username'])
                                {{ $v['username'] }})
                            @else
                                {{ $v['openid'] }})
                            @endif
                        </td>
                        <td>
                            {{ $v['school'] }} {{ $v['title'] }}
                        </td>
                        <?php
                        $user_powerA = \App\Models\UserPower::where('section_id',auth()->user()->code)
                            ->where('user_id',$k)
                            ->where('power_type','A')
                            ->first();

                        $user_powerB = \App\Models\UserPower::where('section_id',auth()->user()->code)
                            ->where('user_id',$k)
                            ->where('power_type','B')
                            ->first();
                        ?>
                        <td>
                            @if(!empty($user_powerA))
                                審核+管理權
                                <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定移除權限？','{{ route('school_acc.power_remove',$user_powerA->id) }}')">移除權限</a>
                            @endif
                        </td>
                        <td>
                            @if(!empty($user_powerB))
                                簽收+填報權
                                <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定移除權限？','{{ route('school_acc.power_remove',$user_powerB->id) }}')">移除權限</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>            
        </div>
    </div>
</div>
@endsection