@extends('layouts.app')

@section('title','個人作業區')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>個人專區：<span class="badge bg-warning"><i class="fas fa-exclamation-circle"></i> 填報作業區</span></h1>
    @include('edus.posts.nav')
    @if(!empty(auth()->user()->section_id))
        <div class="card my-4">
            <div class="card-header text-center">                        
            </div>
            <div class="card-body">
                @include('edus.reports.list')
            </div>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                {{ $reports->links() }}
            </div>
        </div>
    @endif     
</div>
@endsection