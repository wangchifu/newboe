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
        <div class="card-header d-flex justify-content-center">
            @include('admins.search_nav')
        </div>
        <div class="card-body">            
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admins.user_index') }}">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','1') }}">學校</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','2') }}">教育處</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','3') }}">系統管理者</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_check') }}">重複身分證帳號</a>
                </li>
            </ul>
            @include('admins.form')
            {{ $users->links('layouts.simple-pagination') }}            
        </div>
    </div>           
</div>
@endsection