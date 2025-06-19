@extends('layouts.app')

@section('title','清理資料')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>清理資料</h1>
    <div class="card mb-4">
        <div class="card-header">清除過往公告及填報</div>
        <div class="card-body">            
            <form action="{{ route('admins.clean_do_post') }}" method="post" id="post_form">
                @csrf            
                <div class="form-group">
                    <label class="text-danger"><strong>*從哪一個 ID 開始往前刪公告</strong></label>
                    <input type="number" name="post_id" value="" class="form-control" required>
                </div>                        
                <div class="form-group">
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定送出？無法回覆喔！','post_form')">送出</a>
                </div>            
            </form>
            <hr>    
            <form action="{{ route('admins.clean_do_report') }}" method="post" id="report_form">
                @csrf
                <div class="form-group">
                    <label class="text-danger"><strong>*從哪一個 ID 開始往前刪填報</strong></label>
                    <input type="number" name="report_id" value="" class="form-control" required>
                </div>                        
                <div class="form-group">
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定送出？無法回覆喔！','report_form')">送出</a>
                </div>
            </form>
            @include('layouts.errors')                                   
        </div>
    </div>           
</div>
@endsection