@extends('layouts.app')

@section('title','修改相關連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>修改相關連結</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admins.other_index') }}">連結列表</a></li>
            <li class="breadcrumb-item active" aria-current="page">修改連結</li>
        </ol>
    </nav>
    <div class="card mb-4">
        <div class="card-header">內容</div>
        <div class="card-body">
            @include('layouts.errors')
            <form action="{{ route('admins.link_update', $link->id) }}" method="POST" id="this_form">
                @method('PATCH')
                @csrf
                <div class="form-group mb-3">
                    <label for="name">名稱*</label>
                    <input type="text" name="name" id="name" class="form-control" required="required" placeholder="名稱" value="{{ $link->name }}">
                </div>
                <div class="form-group mb-3">
                    <label for="type">分類</label>
                    <select name="type" id="type" class="form-select">
                        {{-- 如果 $link->type 是 null，就讓提示字維持 selected --}}
                        <option value="" disabled @selected(is_null($link->type)) hidden>請選擇分類</option>                        
                        <option value="1" @selected($link->type == 1)>1.教學類</option>
                        <option value="2" @selected($link->type == 2)>2.藝文類</option>
                        <option value="3" @selected($link->type == 3)>3.資訊類</option>
                        <option value="4" @selected($link->type == 4)>4.防疫專區</option>
                        <option value="5" @selected($link->type == 5)>5.行政單位</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="url">網址*</label>    
                    <input type="text" name="url" id="url" class="form-control" required="required" placeholder="https://" value="{{ $link->url }}">
                </div>
                <div class="form-group mb-3">
                    <label for="order_by">排序</label>
                    <input type="text" name="order_by" id="order_by" class="form-control" placeholder="數字" value="{{ $link->order_by }}">
                </div>
                <div class="form-group mb-3">
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','this_form')">
                        <i class="fas fa-save"></i> 儲存設定
                    </a>
                </div>
            </form>                            
        </div>
    </div>           
</div>
@endsection