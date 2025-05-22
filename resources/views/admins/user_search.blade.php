@extends('layouts.app')

@section('title','搜尋帳號')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>搜尋帳號</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admins.user_index') }}">帳號管理</a></li>
            <li class="breadcrumb-item active" aria-current="page">搜尋「{{ $want }}」結果</li>
        </ol>
    </nav>
    <div class="card mb-4">
        <div class="card-header">搜尋「{{ $want }}」使用者列表</div>
        <div class="card-body">
            @include('admins.search_nav')
            @include('admins.form')            
            {{ $users->appends(['want' => $want])->links('layouts.simple-pagination') }}         
        </div>
    </div>    
       
</div>
@endsection