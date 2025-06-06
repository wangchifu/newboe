@extends('layouts.app')

@section('title','帳號管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>帳號管理</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            @include('admins.search_nav')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_index') }}">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','1') }}">學校</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','2') }}">教育處</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','3') }}">系統管理者</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admins.user_check') }}">重複身分證帳號</a>
                </li>
            </ul>
            <table class="table table-striped">
                <tr>
                    <td class="text-danger">身分證的重複帳號(理應為多校任職及曾調校者)</td>                                                        
                </tr>
                @foreach($duplicates as $k=>$v)
                    <tr>
                        <td>
                            @foreach($v as $v2)         
                                @if($userid2name[$v2]['disable'])
                                    <span class="text-danger">[停用]</span>
                                @endif
                                {{ $userid2name[$v2]['school'] }} {{ $userid2name[$v2]['title'] }} {{ $userid2name[$v2]['name'] }}({{ $v2 }}) <small class="text-secondary">最後登入{{ $userid2name[$v2]['date'] }}</small><br>
                            @endforeach
                        </td>                                
                    </tr>
                @endforeach
            </table>            
        </div>
    </div>           
</div>
@endsection