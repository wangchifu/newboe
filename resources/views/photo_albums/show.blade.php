@extends('layouts.app')

@section('title','新增相片')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>相簿管理</h1>    
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fa-solid fa-plus text-primary"></i> 新增相片(按一下)
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form action="{{ route('photo_albums.store_photo',$photo_album->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label">選擇圖檔*(可多選)</label>
                        <input class="form-control" type="file" accept="image/*" id="formFile" name='files[]' required multiple>
                    </div>                    
                    <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();">
                        <i class="fa-solid fa-backward"></i> 返回
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                        <i class="fas fa-save"></i> 上傳相片
                    </button>
                    </form>
                </form>                    
              </div>
            </div>
        </div>          
    </div> 
    <br>
    <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();">
        <i class="fa-solid fa-backward"></i> 返回
    </button>
    <div class="card mb-4">
        <div class="card-header">相片列表</div>
        <div class="card-body">
            @foreach($photos as $photo)
            <img src="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}" class="img-thumbnail" width="18%">
            <a href="{{ route('photo_albums.delete_photo',$photo->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-trash-alt text-danger"></i></a>
            @endforeach
        </div>
    </div>    
</div>
@endsection