@extends('layouts.app')

@section('title','登入')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">登入</div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="{{ route('glogin') }}">GSuite 登入</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">雲端帳號登入</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('mlogin') }}">本機登入</a>
                </li>
            </ul>
            @if((session('login_error')) < 3)
                    <form action="{{ route('gauth') }}" method="post" id="login_form">
                        @csrf
                        <label for="username" class="form-label">GSuite 帳號</label>
                        <div class="input-group mb-3">                        
                            <input type="text" class="form-control" id="username" name="username" placeholder="" aria-label="Username" aria-describedby="basic-addon1" autofocus tabindex="1" required>
                            <span class="input-group-text" id="basic-addon1">@chc.edu.tw</span>
                        </div>
                        <label for="password" class="form-label">密碼</label>
                        <div class="input-group mb-3">                        
                            <input type="password" class="form-control" id="password" name="password" placeholder="" tabindex="2" required>                        
                        </div>
                        <div class="input-group mb-3">                        
                            <a href="{{ route('glogin') }}"><img src="{{ route('pic') }}" class="img-fluid"></a><small class="text-secondary"> (按一下更換)</small>
                        </div>
                        <label for="password" class="form-label">驗證碼</label>
                        <div class="input-group mb-3">                        
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="上圖轉數字" name="captcha" aria-label="" aria-describedby="button-login" tabindex="3" required>
                                <button class="btn btn-primary" id="button-login" type="submit" tabindex="4">GSuite 登入</button>
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
                        <strong>忘掉密碼?</strong> 請由 <a href="https://cloudschool.chc.edu.tw" target="_blank">校務系統</a> 修改!<br><span style="color:red">調校者</span>請登入後職稱會自動修改!<br><span style="color:black">兼任者</span>用不同學校校務密碼即可區分! 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>                       
        </div>
    </div>
</div>
@endsection