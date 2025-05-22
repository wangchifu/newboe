@extends('layouts.app')

@section('title','修改相簿')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>相簿管理</h1>    
    <div class="card mb-4">
        <div class="card-header">修改相簿</div>
        <div class="card-body">
            <form action="{{ route('photo_albums.update',$photo_album->id) }}" method="POST" id="photo_form">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">名稱*</label>
                    <input type="text" class="form-control" name="album_name" value="{{ $photo_album->album_name }}" required placeholder="請輸入相簿名稱">  
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                </div> 
                <a class="btn btn-secondary btn-sm" onclick="window.history.back();">
                    <i class="fa-solid fa-backward"></i> 返回
                </a>
                <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','photo_form')">
                    <i class="fas fa-save"></i> 儲存相簿
                </a>
                </form>
            </form>
        </div>
    </div>    
</div>
@endsection