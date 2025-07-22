@extends('layouts.app')

@section('title',$section_name)

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $section_name }}</h1>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('introductions.show',['type'=>'organization','section_id'=>$section_id]) }}">業務簡介</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('introductions.show',['type'=>'people','section_id'=>$section_id]) }}">科室成員</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('introductions.show',['type'=>'site','section_id'=>$section_id]) }}">資源網站</a>
        </li>
        @foreach($section_pages as $sp)
            <?php $active=($section_page->id == $sp->id)?"active":""; ?>
            <li class="nav-item">
                <a class="nav-link {{ $active }}" href="{{ route('introductions.section_page_show',[$section_id,$sp->id]) }}">{{ $sp->title }}</a>
            </li>
        @endforeach
    </ul>    
    <div class="card mb-4">
        <div class="card-header">簡介資料</div>
        <div class="card-body">
            {!! $section_page->content !!}
        </div>
    </div>
</div>
@endsection