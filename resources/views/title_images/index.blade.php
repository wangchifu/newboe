@extends('layouts.app')

@section('title','橫幅廣告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>橫幅廣告</h1>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fa-solid fa-plus text-primary"></i> 新增橫幅廣告(按一下)
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form action="{{ route('title_image_add') }}" method="POST" enctype="multipart/form-data" id="image_form">
                    @csrf
                    <label for="pic"><strong class="text-danger">圖檔寬高比例約為1:2.8 (1400 x 500)</strong> [<a href="https://www.iloveimg.com/zh-tw/crop-image" target="_blank">線上裁切圖片</a>]</label>
                    <div class="input-group mb-3">                
                        <input type="file" class="form-control" accept="image/*" id="pic" name="pic" required>
                        <label class="input-group-text" for="pic">請選擇圖檔</label>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">連結</label>
                        <input type="text" class="form-control" id="link" name="link">
                    </div>
                    <div class="mb-3">
                        <a type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','image_form')">
                            <i class="fas fa-save"></i> 新增圖片
                        </a>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                </form>                                  
              </div>
            </div>
        </div>          
    </div>    
    <br>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-secondary">
                <tr>
                    <th class="col-4">已上傳的圖片</th>
                    <th>說明</th>
                </tr>
                </thead>
                <tbody>
                @foreach($title_images as $title_image)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="img-fluid">
                        </td>
                        <td>
                            <a href="{{ route('title_image_edit',$title_image) }}" class="btn btn-success btn-sm">編輯</a>
                            <a href="#!" class="btn btn-danger btn-sm" onclick="return sw_confirm1('確定刪除？','{{ route('title_image_delete',$title_image->id) }}')">刪除</a><br>
                            @if($title_image->title)
                                <strong>{{ $title_image->title }}</strong><br>
                            @endif
                            @if($title_image->content)
                                {{ $title_image->content }}<br>
                            @endif
                            @if($title_image->link)
                                連結：<a href="{{ $title_image->link }}" target="_blank">{{ $title_image->link }}</a><br>
                            @endif
                            <small class="text-secondary">by {{ $title_image->user->name }}</small>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>        
</div>
@endsection