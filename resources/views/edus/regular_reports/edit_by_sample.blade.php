@extends('layouts.app')

@section('title','修改定期填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge bg-success"><i class="fas fa-plus"></i> 修改定期填報</span></h1>
    @include('edus.posts.nav')
    <div class="card my-4">
        <div class="card-header text-center">     
            <h3><i class="fa-solid fa-list"></i> 修改 [{{ $sections[auth()->user()->section_id] }}] 定期填報</h3>
        </div>
        <div class="card-body">
            <h4>{{ $regular_report->regular_sample->name }}</h4>
            <div class="container my-2">
                <form action="{{ route('edu_regular_report.update_by_sample',$regular_report->id) }}" method="POST" enctype="multipart/form-data" id="create_form" onsubmit="return false">
                    @csrf
                    @method('patch')
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
                                            value="{{ $regular_report->semester }}"
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
                                            value="{{ $regular_report->start_date }}"
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
                                            value="{{ $regular_report->die_date }}"
                                            id="die_date" 
                                            class="form-control" 
                                            required>
                                    </div>
                                    <div class="form-text">超過此日期後，將會鎖定無法填寫。</div>
                                </div>                                
                            </div>
                            <div class="form-group my-2">
                                <label for="files[]">附加檔案( 單檔不大於10MB )</label>
                                <div class="form-group p-3 bg-white rounded border">
                                    <label class="form-label fw-bold text-secondary mb-2">
                                        <i class="fas fa-paperclip text-secondary"></i> 相關附加檔案
                                    </label>
                                    
                                    {{-- 💡 判斷是否有檔案（請根據你實作的檔案關聯或欄位調整，例如 $regular_report->files） --}}
                                    @if(isset($files) && count($files) > 0)
                                        <div class="list-group">
                                            @foreach($files as $file)
                                                {{-- 檔案下載列 --}}                                                
                                                    <div>
                                                        <a href="{{ route('edu_regular_report.download',['id'=>$regular_report->id,'filename'=>$file]) }}">
                                                            <i class="far fa-file-alt text-primary me-2"></i>
                                                            <span class="text-dark fw-semibold">{{ $file ?? '未知檔案' }}</span>                                
                                                        </a>
                                                        <a href="#!" onclick="sw_confirm1('確定刪除？','{{ route('edu_regular_report.del_file',['id'=>$regular_report->id,'file'=>$file]) }}')">
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                        </a>
                                                    </div>                                                                                                                                                   
                                            @endforeach                                            
                                        </div>
                                    @else
                                        {{-- 無檔案時的提示 --}}
                                        <div class="text-muted py-2 ps-1">
                                            <i class="fas fa-ban small"></i> 本次填報未附加任何檔案
                                        </div>
                                    @endif
                                </div>                                
                                <input type="file" name="files[]" class="form-control" multiple>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center my-4">
                        <button type="button" class="btn btn-success btn-lg px-5 shadow" onclick="sw_confirm2('確定？','create_form')">
                            <i class="fas fa-check-circle"></i> 確認修改填報
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