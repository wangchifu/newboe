@extends('layouts.app_clean')

@section('title','定期填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $regular_report->regular_sample->name }}</h1>    
    <div class="card my-4">
        <div class="card-header text-center">     
            <h3><i class="fa-solid fa-list"></i> 填報內容</h3>
        </div>
        <div class="card-body">
            <div class="card mb-4 border-info shadow-sm">
    <div class="card-header bg-info text-white fw-bold">
        <i class="fas fa-info-circle"></i> 定期填報發布資訊
    </div>
    <div class="card-body bg-light">
        <div class="row g-3 mb-4">
            
            <div class="col-md-4">
                <div class="p-3 bg-white rounded border h-100">
                    <small class="text-muted d-block mb-1 fw-bold">
                        <i class="fas fa-graduation-cap text-primary"></i> 填報學期
                    </small>
                    <span class="fs-5 fw-bold text-dark">
                        {{ $regular_report->semester ?? '未設定' }}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 bg-white rounded border h-100">
                    <small class="text-muted d-block mb-1 fw-bold">
                        <i class="fas fa-calendar-plus text-success"></i> 開始填報日期
                    </small>
                    <span class="fs-5 fw-bold text-dark">
                        {{ $regular_report->start_date ?? '未設定' }}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 bg-white rounded border h-100">
                    <small class="text-muted d-block mb-1 fw-bold">
                        <i class="fas fa-calendar-times text-danger"></i> 結束填報日期 (截止)
                    </small>
                    <span class="fs-5 fw-bold text-danger">
                        {{ $regular_report->die_date ?? '未設定' }}
                    </span>
                </div>
            </div>

        </div>

        <div class="form-group p-3 bg-white rounded border">
            <label class="form-label fw-bold text-secondary mb-2">
                <i class="fas fa-paperclip text-secondary"></i> 相關附加檔案
            </label>
            
            {{-- 💡 判斷是否有檔案（請根據你實作的檔案關聯或欄位調整，例如 $regular_report->files） --}}
            @if(isset($files) && count($files) > 0)
                <div class="list-group">
                    @foreach($files as $file)
                        {{-- 檔案下載列 --}}
                        <a href="{{ route('edu_regular_report.download',['id'=>$regular_report->id,'filename'=>$file]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                            <div>
                                <i class="far fa-file-alt text-primary me-2"></i>
                                <span class="text-dark fw-semibold">{{ $file ?? '未知檔案' }}</span>                                
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <i class="fas fa-download"></i> 下載
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- 無檔案時的提示 --}}
                <div class="text-muted py-2 ps-1">
                    <i class="fas fa-ban small"></i> 本次填報未附加任何檔案
                </div>
            @endif
        </div>

    </div>
</div>
            @include('edus.regular_reports.sample_'.auth()->user()->section_id.$regular_report->regular_sample_id)                              
        </div>
    </div>
</div>
@endsection