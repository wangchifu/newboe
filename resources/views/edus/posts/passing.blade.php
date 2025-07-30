@extends('layouts.app')

@section('title','個人通過區')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>個人專區：<span class="badge bg-secondary"><i class="fas fa-check-circle"></i> 公告通過區</span></h1>
        @include('edus.posts.nav')         
        <div class="card my-4">
            <div class="card-header text-center">                
            </div>
            <div class="card-body">
                @include('edus.posts.list')
            </div>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>    
</div>
@endsection