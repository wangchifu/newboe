@extends('layouts.app')

@section('title','待審公告/填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}] <span class="badge bg-primary"><i class="fas fa-user-cog"></i> 審核區</span></h1>
    @include('edus.posts.nav')
    <div class="card my-4">
        <div class="card-header text-center">
            <h3 class="py-2">
                [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審公告
            </h3>
        </div>
        <div class="card-body">
            @include('edus.posts.list',$user_power)
        </div>
    </div>
    <div class="card my-4">
        <div class="card-header text-center bg-info-subtle">
            <h3 class="py-2">
                [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審填報
            </h3>
        </div>
        <div class="card-body">
            @include('edus.reports.list',$user_power)   
        </div>
    </div>
    <div class="card my-4">
        <div class="card-header text-center bg-success-subtle">
            <h3 class="py-2">
                [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審定期填報
            </h3>
        </div>
        <div class="card-body">
            @include('edus.regular_reports.list',$user_power)   
        </div>
    </div>
</div>
@endsection