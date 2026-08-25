@extends('layouts.app')

@section('title','學校帳號')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ auth()->user()->school }} 學校帳號-權限列表</h1>
    <div class="card mb-4">
        <div class="card-header">                        
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_acc.index') }}">指定帳號</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_acc.list') }}">權限列表</a>
                </li>
            </ul>
                    <table class="table table-hover">
                        <tr>
                            <td>
                                姓名(帳號)
                            </td>
                            <td>
                                學校
                            </td>
                            <td>
                                職稱
                            </td>
                            <td>
                                權限
                            </td>
                            <td>
                                動作
                            </td>
                        </tr>
                        @foreach($user_powers as $user_power)
                            <tr>
                                <td>
                                    {{ $user_power->user->name }}(
                                    @if($user_power->user->username)
                                        {{ $user_power->user->username }}
                                    @else
                                        {{ $user_power->user->openid }}
                                    @endif
                                    )
                                </td>
                                <td>
                                    {{ $user_power->user->school }}
                                </td>
                                <td>
                                    {{ $user_power->user->title }}
                                </td>
                                <td>
                                    {{ $school_powers[$user_power->power_type] ?? '' }}
                                </td>
                                <td>
                                    @if($user_power->user_id != auth()->user()->id)
                                        <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定要移除？','{{ route('school_acc.power_remove',$user_power->id) }}')">移除權限</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>                       
        </div>
    </div>
</div>
@endsection