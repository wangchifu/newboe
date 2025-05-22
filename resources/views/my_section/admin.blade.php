@extends('layouts.app')

@section('title','科室成員管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $sections[$section_id] }} 科室成員管理</h1>
    <div class="card mb-4">
        <div class="card-header">
            具審核權(科長)
            <?php
            $user_power = \App\Models\UserPower::where('power_type','A')->where('section_id',$section_id)->first();
            ?>
            <a href="javascript:open_user('{{ route('my_section.power') }}')" class="btn btn-primary btn-sm">指定審核者</a>
        </div>
        <div class="card-body">
                     
        </div>
    </div>           
</div>
@endsection