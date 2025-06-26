@extends('layouts.app')

@section('title','新增跑馬燈')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>新增跑馬燈</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('marquees.index') }}">跑馬燈列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增跑馬燈</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增跑馬燈
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    {{ Form::open(['route' => '', 'method' => 'POST']) }}
                    
                </div>            
            </div>
        </div>
    </div>
</div>
@endsection