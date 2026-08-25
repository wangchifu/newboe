@extends('layouts.app')

@section('title','請選擇科室')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-6 mx-auto">
    <h1>尚未被分科室</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">                    
            @if(auth()->user()->my_section_id)
                <?php $sections = config('boe.sections'); ?>
                -->你已經選了「{{ $sections[auth()->user()->my_section_id] ?? '' }}」，等候同意中<br>
                <a href="#!" class="btn btn-danger btn-sm mt-3" onclick="sw_confirm1('確定取消？','{{ route('apply_section.delete',$user->id) }}')">取消此次申請</a>
            @else
                <form action="{{ route('apply_section.update', $user->id) }}" method="POST" id="select_form" onsubmit="return false">
                    @csrf
                    @method('PATCH')
                <div class="form-group">
                    <select name="my_section_id" class="form-control" required>
                        <option value="" disabled selected>選擇一個科室</option>
                        @foreach($sections as $key => $value)
                            <option value="{{ $key }}" {{ old('my_section_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-sm" onclick="sw_confirm2('確定嗎？','select_form')">送出</button>
                </div>
                </form>                
            @endif
        </div>
    </div>
</div>
@endsection