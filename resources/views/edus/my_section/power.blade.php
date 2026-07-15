@extends('layouts.app_clean')

@section('title','指定審核者')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card mb-4">
        <div class="card-header">
            <img class="card-img-top img-responsive" src="{{ asset('images/small/power_d.png') }}">
        </div>
        <div class="card-body">                    
            <h5>
                {{ $sections[auth()->user()->section_id] }}
            </h5>
            <p>
            方式一：從本科室成員加入
            <form action="{{ route('my_section.power_update1') }}" method="POST" id="type1" onsubmit="return false">
                @csrf
                <div class="form-group">
                    <select name="user_id" class="form-control search_selet">
                        <option value="" disabled selected>選擇使用者</option>
                        @foreach($select_users as $key => $value)
                            <option value="{{ $key }}" {{ old('user_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-sm" onclick="sw_confirm2('確定？','type1')">方式一送出
                    </button>
                </div>
                <input type="hidden" name="section_id" value="{{ auth()->user()->section_id }}">
            </form>
            </p>                
            方式二：輸入本站任一帳號(可加入帳號掛學校的調府教師，例如要加入foo@chc.edu.tw，請輸入foo)
            @include('layouts.errors')
            <form action="{{ route('my_section.power_update2') }}" method="POST" id="type2" onsubmit="return false">
                @csrf
                <div class="form-group">
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-sm" onclick="sw_confirm2('確定？','type2')">方式二送出
                    </button>
                </div>
                <input type="hidden" name="section_id" value="{{ auth()->user()->section_id }}">
            </form>
            </p>                 
        </div>
        <div class="card-footer text-center">
            <div class="py-3 text-right">
                <button type="button" id="closeVeno" class="btn btn-secondary">
                    關閉視窗
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $( ".search_selet" ).chosen({
        search_contains: true,
    });
</script>
@endsection