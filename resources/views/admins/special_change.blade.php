@extends('layouts.app')

@section('title','特殊處理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>特殊處理-轉移公文及填報</h1>
    <a href="{{ route('admins.special') }}"             
            class="awesome-back-btn btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center my-4">
        
        <!-- 💡 Font Awesome 經典向左箭頭，搭配 me-2 與按鈕文字隔開 -->
        <i class="fas fa-arrow-left fa-fw me-2 arrow-icon"></i>
        
        <span class="fw-bold">回到上一頁</span>
</a>
    <div class="card mb-4">
        <div class="card-header">特殊處理</div>
        <div class="card-body">           
            <h3>{{ $old_user->code }} {{ $old_user->title }} {{ $old_user->name }}</h3>
            <ul class="list-group my-4" style="max-width: 400px;">
                <!-- 公告總數 -->
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-megaphone-fill text-primary me-2"></i>公告總數
                    </div>
                    <!-- 💡 這裡可以帶入你的 PHP 變數，例如 {{ $post_count }} -->
                    <span class="badge bg-primary rounded-pill fs-6">{{ $post_count }} 筆</span>
                </li>
                
                <!-- 填報總數 -->
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-file-earmark-text-fill text-success me-2"></i>填報總數
                    </div>
                    <!-- 💡 這裡可以帶入你的 PHP 變數，例如 {{ $report_count }} -->
                    <span class="badge bg-success rounded-pill fs-6">{{ $report_count }} 筆</span>
                </li>
                
                <!-- 定期填報總數 -->
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-calendar-event-fill text-warning me-2"></i>定期填報總數
                    </div>
                    <!-- 💡 這裡可以帶入你的 PHP 變數，例如 {{ $regular_report_count }} -->
                    <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $regular_report_count }} 筆</span>
                </li>
            </ul>    
            <h3>轉移給 {{ $new_user->code }} {{ $new_user->title }} {{ $new_user->name }}</h3>   
            <form action="{{ route('admins.special_change_go') }}" method="POST" class="w-100" id='this_form'>
                @csrf
                <input type="hidden" name="old_user_id" value="{{ $old_user->id }}">
                <input type="hidden" name="new_user_id" value="{{ $new_user->id }}">
                <div class="col-sm-2">
                    <!-- 💡 w-100 讓按鈕填滿它所在的格線寬度 -->
                    <button type="button" class="btn btn-primary w-100" onclick="sw_confirm2('確定？動了就改不回來了喔！','this_form')">
                        確定執行
                    </button>
                </div>
            </form>     
        </div>
    </div>           
</div>
@endsection