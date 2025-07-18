@extends('layouts.app')

@section('title','變更職稱')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="py-2">
                變更職稱
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('update_title') }}" method="post" onsubmit="return false" id="this_form">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label for="title">請選擇正確的職稱</label>
                    <select class="form-control" id="title" name="title" tabindex="1" required>
                    @foreach($title_array as $k => $v)
                        <option value="{{ $v }}" {{ (auth()->user()->title == $v) ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach                            
                    </select>
                </div>                                  
                <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定？','this_form')"><i class="fas fa-save"></i> 送出</button>
            </form>
            @include('layouts.errors')
        </div>
    </div>
</div>
@endsection