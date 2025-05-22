@extends('layouts.app')

@section('title','修改其他連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>修改其他連結</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admins.other_index') }}">其他連結列表</a></li>
            <li class="breadcrumb-item active" aria-current="page">修改連結</li>
        </ol>
    </nav>
    <div class="card mb-4">
        <div class="card-header">內容</div>
        <div class="card-body">
            @include('layouts.errors')
            <form action="{{ route('admins.other_update', $other->id) }}" method="POST" id="this_form">
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="name">名稱*</label>
                    <input type="text" name="name" id="name" class="form-control" required="required" placeholder="名稱" value="{{ $other->name }}">
                </div>
                <div class="form-group">
                    <label for="url">網址*</label>    
                    <input type="text" name="url" id="url" class="form-control" required="required" placeholder="https://" value="{{ $other->url }}">
                </div>
                <div class="form-group">
                    <label for="order_by">排序</label>
                    <input type="text" name="order_by" id="order_by" class="form-control" placeholder="數字" value="{{ $other->order_by }}">
                </div>
                <div class="form-group">
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','this_form')">
                        <i class="fas fa-save"></i> 儲存設定
                    </a>
                </div>
            </form>                            
        </div>
    </div>           
</div>
@endsection