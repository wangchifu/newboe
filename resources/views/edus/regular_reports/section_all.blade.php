@extends('layouts.app')

@section('title',$sections[auth()->user()->section_id].'全數定期填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge border border-dark text-dark bg-white"><i class="fas fa-list"></i> 全數定期填報</span></h1>
        @include('edus.posts.nav')                 
        <div class="card my-4">
            <div class="card-header">                
                列表                
            </div>
            <div class="card-body">
                @include('edus.regular_reports.list')
            </div>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                {{ $regular_reports->links('layouts.pagination') }}
            </div>
        </div>
    </div>    
</div>
@endsection