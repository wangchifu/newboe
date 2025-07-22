@extends('layouts.app')

@section('title','學校之美-列表版')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1 class="text-center">彰化縣縣轄學校</h1>
    <div class="card mb-4">
        <div class="card-header">學校列表</div>
        <div class="card-body">
            @foreach($town_ships as $k=>$v)
                <h4><i class="fab fa-fort-awesome"></i> {{ $v }}</h4>
                @foreach($all_school[$k] as $k1=>$v2)
                    <?php 
                    //去掉潭漧國小074752
                    if($k1=="074752"){
                        continue;
                    }
                    ?>
                    @if($v2['type'] == 1)
                        <a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-primary btn-sm me-1 mb-1">{{ $v2['school'] }}<span class="badge bg-light text-dark">國小</span></a>
                    @endif
                    @if($v2['type'] == 2)
                        <a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-success btn-sm me-1 mb-1">{{ $v2['school'] }}<span class="badge bg-light text-dark">國中</span></a>                        
                    @endif
                    @if($v2['type'] == 12)
                        <a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-info btn-sm me-1 mb-1">{{ $v2['school'] }}<span class="badge bg-light text-dark">國中小</span></a>                        
                    @endif
                    @if($v2['type'] == 23)
                        <a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-warning btn-sm me-1 mb-1">{{ $v2['school'] }}<span class="badge bg-light text-dark">國高中</span></a>
                    @endif
                @endforeach
                <br><br>
            @endforeach            
        </div>
    </div>
</div>
@endsection