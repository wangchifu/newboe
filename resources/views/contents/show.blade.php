@extends('layouts.app')

@section('title','內容管理')

@section('content')
<div class="col-lg-8 mx-auto" style="margin-top: 20px;">
    <h1>{{ $content->title }}</h1>
    <div class="card mb-4">
        <div class="card-header">內容</div>
        <div class="card-body">
            {!! $content->content !!}       
        </div>
    </div>
</div>
@endsection