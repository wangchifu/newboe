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
            <hr>
            <label class="text-danger"><strong>*轉移公告及填報至另一位 user</strong></label>
            <form action="{{ route('admins.special_change') }}" method="POST" class="w-100">
                <!-- 這裡放 Laravel 的 CSRF Token，若非 Laravel 可刪除 -->
                @csrf 

                <!-- 💡 使用 row 建立橫列，g-2 控制欄位之間的間距，align-items-end 讓輸入框與按鈕底部對齊 -->
                <div class="row g-2 align-items-end">
                    
                    <!-- 原來的 User ID -->
                    <div class="col-sm-4">
                        <label for="old_user_id" class="form-label fw-bold small">原來的 User ID</label>
                        <input type="text" 
                            name="old_user_id" 
                            id="old_user_id" 
                            class="form-control" 
                            placeholder="請輸入舊 ID" 
                            required>
                    </div>

                    <!-- 後來的 User ID -->
                    <div class="col-sm-4">
                        <label for="new_user_id" class="form-label fw-bold small">後來的 User ID</label>
                        <input type="text" 
                            name="new_user_id" 
                            id="new_user_id" 
                            class="form-control" 
                            placeholder="請輸入新 ID" 
                            required>
                    </div>

                    <!-- 送出按鈕 -->
                    <div class="col-sm-2">
                        <!-- 💡 w-100 讓按鈕填滿它所在的格線寬度 -->
                        <button type="submit" class="btn btn-primary w-100">
                            執行
                        </button>
                    </div>
                    
                </div>
            </form>
            @include('layouts.errors')                                   
        </div>
    </div>           
</div>
@endsection