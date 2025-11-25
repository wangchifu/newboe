@extends('layouts.app')

@section('title','特殊處理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>特殊處理</h1>
    <div class="card mb-4">
        <div class="card-header">特殊處理</div>
        <div class="card-body">            
            <form action="{{ route('admins.special_post') }}" method="post" id="post_form">
                @csrf            
                <div class="form-group">
                    <label class="text-danger"><strong>*公告 ID</strong></label>
                    <input type="number" name="post_id" placeholder="請輸入公告 ID" class="form-control" required>
                </div>
                <div class="form-group">                    
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plane"></i> 秀出此公告的資訊
                    </button>
                </div>                                                    
            </form>
            <hr>    
            <form action="{{ route('admins.special_report') }}" method="post" id="report_form">
                @csrf            
                <div class="form-group">
                    <label class="text-danger"><strong>*填報 ID</strong></label>
                    <input type="number" name="report_id" placeholder="請輸入公告 ID" class="form-control" required>
                </div>
                <div class="form-group">                    
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plane"></i> 秀出此填報的資訊
                    </button>
                </div>                                               
            </form>
            @include('layouts.errors')                                   
        </div>
    </div>           
</div>
@endsection