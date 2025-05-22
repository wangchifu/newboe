@extends('layouts.app')

@section('title','帳號管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>帳號管理</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            @include('admins.search_nav')
            <?php
                if($group_id =="1"){
                    $active1 = "active";
                    $active2 = "";
                    $active3 = "";
                }
                if($group_id =="2"){
                    $active1 = "";
                    $active2 = "active";
                    $active3 = "";
                }
                if($group_id =="3"){
                    $active1 = "";
                    $active2 = "";
                    $active3 = "active";
                }
            ?>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_index') }}">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active1 }}" href="{{ route('admins.user_group','1') }}">學校</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active2 }}" href="{{ route('admins.user_group','2') }}">教育處</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active3 }}" href="{{ route('admins.user_group','3') }}">系統管理者</a>
                </li>
            </ul>
            @include('admins.form')
            {{ $users->links('layouts.simple-pagination') }}            
        </div>
    </div>    
       
</div>
@endsection