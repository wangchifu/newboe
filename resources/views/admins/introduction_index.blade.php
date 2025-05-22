@extends('layouts.app')

@section('title','科室列表')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>教育處介紹管理-科室列表</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            <a href="{{ route('admins.introduction_people2',1) }}" class="btn btn-info" style="margin: 5px;">處長</a>
            <a href="{{ route('admins.introduction_people2',2) }}" class="btn btn-info" style="margin: 5px;">副處長</a>
            <a href="{{ route('admins.introduction_people2',3) }}" class="btn btn-info" style="margin: 5px;">專員</a>
            @foreach($sections as $k=>$v)
                <a href="{{ route('admins.introduction_organization',$k) }}" class="btn btn-primary" style="margin: 5px;">{{ $v }}</a>
            @endforeach                  
        </div>
    </div>    
       
</div>
@endsection