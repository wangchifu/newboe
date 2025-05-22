@extends('layouts.app')

@section('title','修改選單連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>選單連結</h1>
    {!! $path !!}
    <div class="card mb-4">
        <div class="card-header">修改此層連結</div>
        <div class="card-body">
            <form action="{{ route('menu_update',$menu->id) }}" method="POST" id="menu_form">
                @csrf
                <div class="mb-3">
                    <label for="this_menu_name" class="form-label">所屬目錄*</label>
                    <input type="text" class="form-control" style="background-color: #b7b9bb;" id="this_menu_name" value="{{ $this_menu_name }}" readonly>
                    <input type="hidden" name="belong" value="{{ $this_menu_id }}">
                </div>            
                <div class="mb-3">
                    <label for="type" class="form-label">類型*</label>
                    @php
                      $select_type1 = ($menu->type==1)?"selected"  :null;
                      $select_type2 = ($menu->type==2)?"selected"  :null;
                    @endphp
                    <select class="form-select form-select mb-3" aria-label=".form-select example" id="type" name="type">                    
                        <option value="1" {{ $select_type1 }}>可下拉目錄</option>
                        <option value="2" {{ $select_type2 }}>連結</option>                    
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">名稱*</label>
                    <input type="text" class="form-control" name="name" value="{{ $menu->name }}" placeholder="必填" required>                
                </div> 
                <div class="mb-3">
                    <label for="link" class="form-label">連結</label>
                    <input type="text" class="form-control" name="link" value="{{ $menu->link }}" placeholder="下拉目錄非必填">                
                </div> 
                <div class="mb-3">
                    <label for="order_by" class="form-label">排序</label>
                    <input type="number" class="form-control" name="order_by" value="{{ $menu->order_by }}" placeholder="非必填">                
                </div> 
                <div class="mb-3">
                    <label for="target" class="form-label">開啟方式*</label>
                    <select class="form-select form-select mb-3" aria-label=".form-select example" id="target" name="target">                    
                        @php
                            $select_target0 = ($menu->target==null)?"selected"  :null;
                            $select_target1 = ($menu->target=="_blank")?"selected"  :null;
                            $select_target2 = ($menu->target=="_self")?"selected"  :null;
                        @endphp
                        <option value="" {{ $select_target0 }}>可不選</option>
                        <option value="_blank" {{ $select_target1 }}>新視窗</option>
                        <option value="_self" {{ $select_target2 }}>本視窗</option>                    
                    </select>
                </div>
                <a class="btn btn-secondary btn-sm" onclick="window.history.back();">
                    <i class="fa-solid fa-backward"></i> 返回
                </a>
                <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','menu_form')">
                    <i class="fas fa-save"></i> 儲存選單
                </a>
                </form>
            </form>
        </div>
    </div>    
</div>
@endsection