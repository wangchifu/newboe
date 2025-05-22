@extends('layouts.app')

@section('title','選單連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>選單連結</h1>
    {!! $path !!}
    @if($this_menu_type==1)
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fa-solid fa-plus text-primary"></i> 新增選單(按一下)
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form action="{{ route('menu_add') }}" method="POST" id="menu_form">
                    @csrf
                    <div class="mb-3">
                        <label for="this_menu_name" class="form-label">所屬目錄*</label>
                        <input type="text" class="form-control" style="background-color: #b7b9bb;" id="this_menu_name" value="{{ $this_menu_name }}" readonly>
                        <input type="hidden" name="belong" value="{{ $this_menu_id }}">
                    </div>            
                    <div class="mb-3">
                        <label for="type" class="form-label">類型*</label>
                        <select class="form-select form-select mb-3" aria-label=".form-select example" id="type" name="type">                    
                            <option value="1">可下拉目錄</option>
                            <option value="2">連結</option>                    
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">名稱*</label>
                        <input type="text" class="form-control" name="name" placeholder="必填" required>                
                    </div> 
                    <div class="mb-3">
                        <label for="link" class="form-label">連結</label>
                        <input type="text" class="form-control" name="link" placeholder="下拉目錄非必填">                
                    </div> 
                    <div class="mb-3">
                        <label for="order_by" class="form-label">排序</label>
                        <input type="number" class="form-control" name="order_by" placeholder="非必填">                
                    </div> 
                    <div class="mb-3">
                        <label for="target" class="form-label">開啟方式*</label>
                        <select class="form-select form-select mb-3" aria-label=".form-select example" id="target" name="target">                    
                            <option value="" selected>可不選</option>
                            <option value="_blank">新視窗</option>
                            <option value="_self">本視窗</option>                    
                        </select>
                    </div>
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？登出才會生效喔！','menu_form')">
                        <i class="fas fa-save"></i> 新增選單
                    </a>
                    </form>
                </form>                    
              </div>
            </div>
        </div>          
    </div>        
    @endif
    <br>    
    <div class="card mb-4">
        <div class="card-header">此層「{{ $this_menu_name }}」下的連結</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-secondary">
                <tr>
                    <th style="width:50px;">排序</th>      
                    <th style="width:500px;">連結或下拉選單</th> 
                    <th style="width:80px;">子選單</th>            
                    <th>動作</th>     
                </tr>
                </thead>
                <tbody>
                @foreach($menus as $menu)
                    <tr>
                        <td>{{ $menu->order_by }}</td>
                        @if($menu->type==1)
                            @php
                                $sub_menu_number = \App\Models\Menu::where('belong', $menu->id)->count();                    
                            @endphp
                            <td><i class="fa-solid fa-folder text-warning"></i> <a href="{{ route('menu_index',['id'=>$menu->id]) }}">{{ $menu->name }}</a></td>
                        @endif
                        @if($menu->type==2)
                            <td><i class="fa-solid fa-link text-primary"></i> {{ $menu->name }}</td>
                        @endif
                        <td>
                            @if($menu->type==1)
                                {{ $sub_menu_number }}
                            @endif
                            @if($menu->type==2)
                                <a href="{{ $menu->link }}" target="_blank">
                                    連結                            
                                </a>
                                @if($menu->target=="_blank")
                                    <br><span class="small">(新視窗)</span>
                                @endif
                                @if($menu->target=="_self")
                                    <br><span class="small">(本視窗)</span>
                                @endif
                            @endif
                        </td>                
                        <td>                    
                            <a href="{{ route('menu_edit',$menu->id) }}" class="btn btn-success btn-sm">編輯</a>
                            <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('連同底下的一起刪除喔！登出才會生效喔！','{{ route('menu_delete',$menu->id) }}')">刪除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>            
        </div>
    </div>        
</div>
@endsection