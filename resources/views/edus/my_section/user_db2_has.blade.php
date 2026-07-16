@extends('layouts.app_clean')

@section('title','帳號管理')

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>更新認證主機帳號</h1>
    <div class="card mb-4">
        <div class="card-header">
            已有帳號資訊
        </div>
        <div class="card-body">            
            <div class="container mt-4" style="max-width: 600px;">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>基本資料填報
                    </div>
                    <div class="card-body p-4">
                        <h3 class="text-danger">此身分證已有帳號記錄在
                            @if($staff_sid=="079999")
                                教育處 079999
                            @elseif($staff_sid=="079998")
                                縣網中心 079998
                            @else
                                沒有單位
                            @endif                            
                            @if($staff_status==1)
                                在職
                            @else
                                已離職
                            @endif
                        </h3>
                        <span>若確定要加他進來本科室，請聯絡系統管理員。</span>                        
                    </div>
                </div>
            </div>                    
        </div>
    </div>      
    
    <div class="text-center mt-3 mb-3">
        <button type="button" id="closeVeno" class="btn btn-secondary">
            關閉視窗
        </button>     
    </div>
</div>
@endsection