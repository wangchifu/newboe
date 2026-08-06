@extends('layouts.app')

@section('title','登入')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <a href="https://eip.chc.edu.tw" target="_blank"><img src="{{ asset('images/chc2.png') }}" alt="CHC Logo" width="50" class="me-2" style="margin-right:10px; border:1px solid #000000;"></a>
            彰化縣教育雲端帳號登入
        </div>
        <div class="card-body">
            <div id="loginCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="alert alert-warning fw-bold mb-0" role="alert">
                            老師調校後，請至校務系統建立該教師資料後，無法立即登入，最晚等待一天，即可用原本帳號登入使用。
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="alert alert-info fw-bold mb-0" role="alert">
                            學校端帳號沒有任何權限<br>
                            1.請該帳號先登入本系統，以建立系統內的帳號，再由已經有「帳號管理權」的帳號給予他權限。<br>
                            2.功能在「學校管理/學校帳號」 找到人員後按"編輯"，再給予對應的功能權限。
                        </div>
                    </div>
                </div>
            </div>
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
@endsection