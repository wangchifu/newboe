@extends('layouts.app')

@section('title','新增定期填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge bg-success"><i class="fas fa-plus"></i> 新增定期填報</span></h1>
    @include('edus.posts.nav')
    <div class="card my-4">
        <div class="card-header text-center">     
            <h3><i class="fa-solid fa-list"></i> 新增 [{{ $sections[auth()->user()->section_id] }}] 定期填報</h3>
        </div>
        <div class="card-body">
            <h4>{{ $regular_sample->name }}</h4>
            <div class="container my-2">
                <form action="{{ route('edu_regular_report.store_by_sample') }}" method="POST" enctype="multipart/form-data" id="create_form" onsubmit="return false">
                    @csrf

                    <div class="form-group my-2">
                        <label for="name"><strong class="text-danger">1.請務必先選擇對象*</strong></label>                                        @include('edus.posts.select_school')
                    </div>
                    
                    <div class="card mb-4 border-primary shadow-sm">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i class="fas fa-paper-plane"></i> 2.發布定期填報設定
                        </div>
                        <div class="card-body bg-light">                            
                            <div class="row g-3">
                                
                                <div class="col-md-4">
                                    <label for="semester" class="form-label fw-bold">填報學期</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                        <input type="text" 
                                            name="semester" 
                                            id="semester" 
                                            class="form-control" 
                                            placeholder="例如：115-1 或 115-2" 
                                            required>
                                    </div>
                                    <div class="form-text">請輸入本調查所屬的學期。</div>
                                </div>

                                <div class="col-md-4">
                                    <label for="start_date" class="form-label fw-bold">開始填報日期</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                        <input type="date" 
                                            name="start_date" 
                                            id="start_date" 
                                            class="form-control" 
                                            required>
                                    </div>
                                    <div class="form-text">到達此日期後，學校才能開始填寫。</div>
                                </div>

                                <div class="col-md-4">
                                    <label for="die_date" class="form-label fw-bold">結束填報日期 (截止)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-times text-danger"></i></span>
                                        <input type="date" 
                                            name="die_date" 
                                            id="die_date" 
                                            class="form-control" 
                                            required>
                                    </div>
                                    <div class="form-text">超過此日期後，將會鎖定無法填寫。</div>
                                </div>                                
                            </div>
                            <div class="form-group my-2">
                                <label for="files[]">附加檔案( 單檔不大於10MB )</label>
                                <input type="file" name="files[]" class="form-control" multiple>
                            </div>
                        </div>
                    </div>

                    {{-- 💡 這裡可以放置隱藏的欄位，例如傳遞從上頁點選的 sample_id --}}
                    <input type="hidden" name="regular_sample_id" value="{{ $regular_sample->id }}">                    
                    <div class="text-center my-4">
                        <button type="button" class="btn btn-success btn-lg px-5 shadow" onclick="sw_confirm2('確定？','create_form')">
                            <i class="fas fa-check-circle"></i> 確認儲存填報
                        </button>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-lg ms-2">
                            取消返回
                        </a>
                    </div>
                </form>
            </div>            
                     
        </div>
    </div>
</div>
@endsection