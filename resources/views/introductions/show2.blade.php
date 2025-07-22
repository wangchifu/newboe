@extends('layouts.app')

@section('title',$section_name)

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $section_name }}</h1>
    <div class="card mb-4">
        <div class="card-header">簡介資料</div>
        <div class="card-body">
            {!! $content !!}
        </div>
    </div>
</div>
@endsection