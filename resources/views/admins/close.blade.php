<?php
if(file_exists(storage_path('app/private/close.txt'))){    
        $fp = fopen(storage_path('app/private/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/private/close.txt')));                
        fclose($fp);
}else{
    $close = 0;
}
if($close==1){
    $app = "layouts.app_clean";
}else{
    $app = "layouts.app";
}
?>
@extends($app)

@section('title','本系統狀態')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>本系統狀態</h1>
    <div class="card mb-4">
        <div class="card-header">
            @if($close==0)
            「彰化縣教育處新雲端」系統開放中
            @elseif($close==1)
            「彰化縣教育處新雲端」系統已關閉
            @endif
        </div>
        <div class="card-body">            
            @auth
                @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                    @if($close==0)
                    <a class="btn btn-danger" href="#!" onclick="sw_confirm1('確定關閉嗎？所有人都無法使用了喔！','{{ route('close_system') }}')">把系統關閉</a>
                    @elseif($close==1)
                    <a class="btn btn-success" href="#!" onclick="sw_confirm1('確定打開嗎？','{{ route('close_system') }}')">把系統打開</a>
                    @endif
                @endif
            @endauth

            @if($close==0)                                
                <img src="{{ asset('images/run.png') }}" class="img-thumbnail d-block mx-auto">  
            @elseif($close==1)                                
                <img src="{{ asset('images/close.png') }}" class="img-thumbnail d-block mx-auto">  
            @endif                    
        </div>
    </div>           
</div>
@endsection