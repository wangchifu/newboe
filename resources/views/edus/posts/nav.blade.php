<?php
        $a = ['A','B','C','D','E','F','G','H','I','J'];
        $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
        ->where('power_type','A')
        ->whereIn('section_id',$a)
        ->first();
    ?>
@if(auth()->user()->group_id==2 or !empty(auth()->user()->section_id))
    <?php $sections = config('boe.sections'); ?>
    @if(!empty(auth()->user()->section_id))
        <div class="btn-group flex-wrap" role="group" aria-label="Basic example">
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-plus"></i> 新增 [{{ $sections[auth()->user()->section_id] }}]
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li><a class="dropdown-item" href="{{ route('posts.create') }}">新增公告</a></li>
                  <li><a class="dropdown-item" href="{{ route('edu_report.create') }}">新增填報</a></li>                  
                  <li><a class="dropdown-item" href="{{ route('edu_regular_report.create') }}">新增定期填報</a></li>                  
                </ul>
            </div>
        <!--
            <a href="{{ route('posts.backing') }}" class="btn btn-danger btn-sm">退回區</a>
            <a href="{{ route('posts.reading') }}" class="btn btn-info btn-sm">已讀未審</a>
            -->
            <?php
            $c_r1 = \App\Models\Post::where('user_id',auth()->user()->id)
                ->whereNotIn('situation',[3,4])->count();
            $c_r2 = \App\Models\Report::where('user_id',auth()->user()->id)
                ->whereNotIn('situation',[3,4])->count();
            $c_r3 = \App\Models\RegularReport::where('user_id',auth()->user()->id)
                ->whereNotIn('situation',[3,4])->count();

            $c_p1 = \App\Models\Post::where('user_id',auth()->user()->id)
                ->where('situation','3')->count();
            $c_p2 = \App\Models\Report::where('user_id',auth()->user()->id)
                ->where('situation','3')->count();
            $c_p3 = \App\Models\RegularReport::where('user_id',auth()->user()->id)
                ->where('situation','3')->count();
            ?>
            <div class="dropdown">
                <button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-exclamation-circle"></i> 作業區 ({{ $c_r1+$c_r2+$c_r3 }})
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <li><a class="dropdown-item" href="{{ route('posts.reviewing') }}">公告作業區 ({{ $c_r1 }})</a></li>
                <li><a class="dropdown-item" href="{{ route('edu_report.index') }}">填報作業區  ({{ $c_r2 }})</a></li>                                  
                <li><a class="dropdown-item" href="{{ route('edu_regular_report.index') }}">定期填報作業區  ({{ $c_r3 }})</a></li>                                  
                </ul>
            </div>            
            <div class="dropdown">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-check-circle"></i> 通過區 ({{ $c_p1+$c_p2+$c_p3 }})
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                <li><a class="dropdown-item" href="{{ route('posts.passing') }}">公告通過區 ({{ $c_p1 }})</a></li>
                <li><a class="dropdown-item" href="{{ route('edu_report.passing') }}">填報通過區 ({{ $c_p2 }})</a></li>                  
                <li><a class="dropdown-item" href="{{ route('edu_regular_report.passing') }}">定期填報通過區 ({{ $c_p3 }})</a></li>                  
                </ul>
            </div>       
            @if($user_power)
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-list"></i> [{{ $sections[auth()->user()->section_id] }}] 公告與填報
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                    <li><a class="dropdown-item" href="{{ route('posts.section_all') }}">全數公告</a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.section_all') }}">全數填報</a></li>                  
                    <li><a class="dropdown-item" href="{{ route('regular_reports.section_all') }}">全數定期填報</a></li>                  
                    </ul>
                </div>  
            @else
                <a href="{{ route('posts.section_all2') }}" class="btn btn-outline-dark btn-sm"><i class="fas fa-list"></i> [{{ $sections[auth()->user()->section_id] }}] 全數公告</a>       
            @endif        
            @if( (auth()->user()->title =="處長" or auth()->user()->title == "副處長" or auth()->user()->title =="科長" or auth()->user()->title == "督學") and auth()->user()->code=="079999")
                <a href="{{ route('posts.all') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-list"></i> [教育處] 全數公告</a>
            @endif
        </div>
    @else
        尚未被分科室
        @if(auth()->user()->my_section_id)
            <?php $sections = config('boe.sections'); ?>
            -->{{ $sections[auth()->user()->my_section_id] ?? '' }}，等候同意
        @else
            <a href="{{ route('my_section.index') }}" class="btn btn-success btn-sm">選填我的科室</a>
        @endif
    @endif    
    @if($user_power)
        <?php
        $c_p = \App\Models\Post::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->count();
        $c_r = \App\Models\Report::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->count();
        $c_r2 = \App\Models\RegularReport::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->count();
        ?>
        　　<a href="{{ route('posts.review') }}" class="btn btn-primary btn-sm">[{{ $sections[$user_power->section_id] ?? '' }}] <i class="fas fa-user-cog"></i> 審核區 ({{ $c_p+$c_r+$c_r2 }})</a>        
    @endif
@endif