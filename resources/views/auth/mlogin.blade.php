@extends('layouts.app')

@section('title','登入')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">系統管理員登入</div>
        <div class="card-body">
            @if((session('login_error')) < 3)
            <form action="{{ route('mauth') }}" method="post" id="login_form">
                @csrf
                <label for="username" class="form-label">帳號</label>
                <div class="input-group mb-3">                        
                    <input type="text" class="form-control" id="username" name="username" placeholder="" autofocus tabindex="1" required>                            
                </div>
                <label for="password" class="form-label">密碼</label>
                <div class="input-group mb-3">                        
                    <input type="password" class="form-control" id="password" name="password" placeholder="" tabindex="2" required>                        
                </div>
                <div class="input-group mb-3">                        
                    <a href="{{ route('mlogin') }}"><img src="{{ route('pic') }}" class="img-fluid"></a><small class="text-secondary"> (按一下更換)</small>
                </div>
                <label for="password" class="form-label">驗證碼</label>
                <div class="input-group mb-3">                        
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="上圖轉數字" name="captcha" aria-label="" aria-describedby="button-login" tabindex="3" required>
                        <button class="btn btn-primary" id="button-login" type="submit" tabindex="4">本機登入</button>
                    </div>        
                    <div class="text-end mt-3">
                        <a href="https://newboe.chc.edu.tw/sso" class="image-button"><img src="https://newboe.chc.edu.tw/images/chc.jpg" alt="彰化chc的logo" width="80"></a>
                        <br>OpenID登入
                    </div>                
                </div>
            </form>
            @else
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                        暫時禁止登入！15分鐘後，或請按 ctrl+shift+del 清掉快取後再試！
                    </div>
                </div>
                <br>                        
            @endif
            @include('layouts.errors')
            @if((session('login_error')))
                <div class="input-group mb-3">                        
                    您已登入錯誤 {{ session('login_error') }}次
                </div>                
            @endif
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                本登入頁面為<strong>系統管理員</strong>登入之用! 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>                       
        </div>
    </div>
</div>
@endsection