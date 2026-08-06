@extends('layouts.app')

@section('title','登入')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <a href="https://eip.chc.edu.tw" target="_blank"><img src="{{ asset('images/chc2.png') }}" alt="CHC Logo" width="50" class="me-2" style="margin-right:10px; border:1px solid #000000;"></a>
                彰化縣教育雲端帳號登入
            </div>
            <div class="card-body">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="text-center">
                        <a href="{{ route('sso') }}" class="image-button">
                            <img src="{{ asset('images/chc.jpg') }}" alt="彰化chc的logo" width="120">
                        </a>
                        <br>OpenID登入
                    </div>
                    @include('layouts.errors')
                    <div class="text-center mt-3">
                        <a href="https://eip.chc.edu.tw/recovery-password" target="_blank" class="btn btn-warning">
                            忘記密碼？
                        </a>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('mlogin') }}" style="color: inherit; text-decoration: none;"><i class="fas fa-cog"></i> 使用本機帳號</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3 border-warning">
            <div class="card-body">
                <h6 class="card-title fw-bold text-warning"><i class="fas fa-exclamation-triangle"></i> 調校登入說明</h6>
                老師調校後，請至校務系統建立該教師資料後，無法立即登入，最晚等待一天，即可用原本帳號登入使用。
            </div>
        </div>
        <div class="card mb-3 border-info">
            <div class="card-body">
                <h6 class="card-title fw-bold text-info"><i class="fas fa-info-circle"></i> 學校端帳號沒有任何權限</h6>
                1.請該帳號先登入本系統，以建立系統內的帳號，再由已經有「帳號管理權」的帳號給予他權限。<br>
                2.功能在「學校管理/學校帳號」 找到人員後按"編輯"，再給予對應的功能權限。
            </div>
        </div>
        <div class="text-center mb-4">
            <a href="{{ route('qanda') }}" class="btn btn-outline-secondary fw-bold"><i class="fas fa-question-circle"></i> 更多常見問題</a>
        </div>
    </div>
</div>
@endsection
