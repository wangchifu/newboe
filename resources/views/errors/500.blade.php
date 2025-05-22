@extends('layouts.app')

@section('title','500錯誤頁面')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>500錯誤！伺服器錯誤！</h1>
    <div class="card mb-4">
        <div class="card-header">呃...</div>
        <div class="card-body text-center">
            <img src="{{ asset('images/error-500.png') }}" class="img-thumbnail">
        </div>
    </div>
</div>
@endsection