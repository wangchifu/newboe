@extends('layouts.app')

@section('title','修改密碼')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="py-2">
                變更密碼
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('update_password') }}" method="post">
                @csrf
                @method('patch')
                <label for="password0" class="form-label">舊密碼*</label>
                <div class="input-group mb-3">                        
                    <input type="password" class="form-control" id="password0" name="password0" placeholder="" tabindex="1" required>                        
                </div>
                <label for="password1" class="form-label">新密碼*</label>
                <div class="input-group mb-3">                        
                    <input type="password" class="form-control" id="password1" name="password1" placeholder="" tabindex="2" required>                        
                </div>
                <label for="password2" class="form-label">確認新密碼*</label>
                <div class="input-group mb-3">                        
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="" tabindex="3" required>                        
                </div>                
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> 送出</button>
            </form>
            @include('layouts.errors')
        </div>
    </div>
</div>
@endsection