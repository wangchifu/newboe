@extends('layouts.app_clean')

@section('title','修改選單連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>檔案下載</h1>    
    <div class="card mb-4">
        <div class="card-header">修改此檔案或目錄名稱</div>
        <div class="card-body">
            <form action="{{ route('uploads.store_name') }}" method="POST" id="store_name" onsubmit="return false">
                @csrf
            <div class="form-group">
                <label for="name"><strong>名稱</strong><small class="text-secondary">目錄/檔案/連結</small></label>        
                <input type="text" name="name" id="name" class="form-control" placeholder="名稱" required value="{{ old('name', $upload->name) }}">
            </div>
            <div class="form-group">
                <input type="hidden" name="id" value="{{ $upload->id }}">
                <input type="hidden" name="path" value="{{ $path }}">
                <button class="btn btn-primary btn-sm" onclick="sw_confirm2('確定修改名稱？','store_name')"><i class="fas fa-save"></i> 修改名稱</button>
            </div>
            </form>            
        </div>
    </div>    
</div>
@endsection