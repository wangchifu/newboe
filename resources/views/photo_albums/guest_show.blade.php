@extends('layouts.app')

@section('title','相簿秀相片')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>{{ $photo_album->album_name }}</h1>            
    <div class="card mb-4">
        <div class="card-header">
            <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();">
                <i class="fa-solid fa-backward"></i> 返回
            </button>
            相片列表
        </div>
        <div class="card-body">
            @foreach($photos as $photo)
            <a class="venobox" data-gall="gall1" href="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}">
                <img src="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}" class="img-thumbnail" width="18%">            
            </a>   
            @endforeach
        </div>
    </div>    
</div>
@endsection