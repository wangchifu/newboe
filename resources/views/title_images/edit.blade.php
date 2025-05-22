@extends('layouts.app')

@section('title','修改橫幅廣告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <div class="card mb-4">
        <div class="card-header">修改橫幅廣告</div>
        <div class="card-body">
            <form action="{{ route('title_image_update',$title_image->id) }}" method="POST" id="image_form">
                @csrf                                
                <div class="input-group mb-3">                
                    <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="img img-thumbnail">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">連結</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{ $title_image->link }}">
                </div>
                <div class="mb-3">
                    <a class="btn btn-secondary btn-sm" onclick="window.history.back();">
                        <i class="fa-solid fa-backward"></i> 返回
                    </a>
                    <a class="btn btn-primary btn-sm" href="#!" onclick="sw_confirm2('確定儲存嗎？','image_form')">
                        <i class="fas fa-save"></i> 修改圖片
                    </a>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            </form>
        </div>
    </div>    
</div>
@endsection