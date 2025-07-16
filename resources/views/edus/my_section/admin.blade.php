@extends('layouts.app')

@section('title','科室成員管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<h1>{{ $sections[$section_id] }} 科室成員管理</h1>
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="py-2">具審核權(科長)</h4>
            <?php
            $user_power = \App\Models\UserPower::where('power_type','A')->where('section_id',$section_id)->first();
            ?>            
        </div>
        <div class="card-body">
            <a href="{{ route('my_section.power') }}" class="btn btn-primary btn-sm venobox" data-vbtype="iframe">指定審核者</a>
            <table class="table table-hover">
                @foreach($a_admins as $a_admin)
                    <tr>
                        <td><strong>
                                審核者：
                                {{ $a_admin->user->name }}({{ $a_admin->user->username }})
                            </strong></td>
                        <td>
                            @if($a_admin->user->id != auth()->user()->id)
                                <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定移除？','{{ route('my_section.power_remove',$a_admin->id) }}')">移除</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>                     
        </div>
    </div>           
</div>
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="py-2">成員列表</h4>
        </div>
        <div class="card-body">                        
            <a href="{{ route('my_section.member') }}" class="btn btn-primary btn-sm venobox" data-vbtype="iframe">新增成員</a>                                                                         
            <table class="table table-hover">
                @foreach($users1 as $user)
                    <tr>
                        <td>
                            {{ $user->name }}({{ $user->username }})
                        </td>
                        <td>
                            @if($user->group_id != 8 and $user->id != auth()->user()->id)
                                <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定移除？','{{ route('my_section.remove',$user->id) }}')">移除</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>                                        
        </div>
    </div>           
</div>
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="py-2">選填本科室者</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                @foreach($users2 as $user)
                    <tr>
                        <td>
                            {{ $user->name }}({{ $user->username }})
                        </td>
                        <td>
                            <a href="#!" class="btn btn-success btn-sm" onclick="sw_confirm1('確定同意？','{{ route('my_section.agree',$user->id) }}')">同意</a>
                        </td>
                        <td>
                            <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定不同意？','{{ route('my_section.disagree',$user->id) }}')">不同意</a>
                        </td>
                    </tr>
                @endforeach
            </table>                     
        </div>
    </div>           
</div>
<script>
    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
        spinner: 'rotating-plane'
    });

    $(document).on('click', '.vbox-close', function() {
        vb.close();
    });

</script>
@endsection