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
            
            {{-- ✨ 新增：防空機制，萬一沒有範本時顯示提示 --}}
            @if($regular_samples->isEmpty())
                <div class="alert alert-warning text-center" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> 目前沒有可用的定期填報範本，若有需求，請洽縣網中心。
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">範本名稱</th>
                                <th style="width: 50%;">內容說明</th>
                                <th style="width: 20%;" class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- 🔄 開始跑範本迴圈 --}}
                            @foreach($regular_samples as $regular_sample)
                                <tr>
                                    <td>
                                        {{-- ⚠️ 記得把這裡的 route 修改為你專案實際檢視範本或下載的路由，目前先用 # 代替 --}}
                                        <a href="{{ route('edu_regular_report.show_sample', $regular_sample->id) }}" class="fw-bold text-decoration-none venobox" data-vbtype="iframe">
                                            <i class="far fa-file-alt text-primary me-1"></i> {{ $regular_sample->name }}
                                        </a>
                                    </td>
                                    
                                    <td>
                                        {{-- 使用 nl2br 保持換行，並用 e() 確保安全防禦 XSS 攻擊 --}}
                                        {!! nl2br(e($regular_sample->content ?? '（無說明）')) !!}
                                    </td>
                                    
                                    <td class="text-center">
                                        {{-- 點擊直接觸發跳轉，去真正的填報表單頁面（附帶範本 id 作為參數） --}}
                                        <a href="{{ route('edu_regular_report.create_by_sample',$regular_sample->id) }}" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-paper-plane"></i> 建立填報
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
                     
        </div>
    </div>
</div>
@endsection