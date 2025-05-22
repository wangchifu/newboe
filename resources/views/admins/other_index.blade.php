@extends('layouts.app')

@section('title','其他連結')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>其他連結</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            <a href="{{ route('admins.other_create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增連結</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>排序</th>
                    <th>名稱</th>
                    <th>網址</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0;$j=0; ?>
                @foreach($others as $other)
                    <tr>
                        <td>
                            {{ $other->order_by }}
                        </td>
                        <td>
                            {{ $other->name }}
                        </td>
                        <td>
                            <a href="{{ $other->url }}" target="_blank"><i class="fas fa-globe"></i></a>
                        </td>

                        <td>
                            <a href="{{ route('admins.other_edit',$other->id) }}" class="btn btn-info btn-sm">修改</a>
                            <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm2('確定刪除？','delete{{ $other->id }}')">刪除</a>
                        </td>
                    </tr>
                    <form action="{{ route('admins.other_destroy', $other->id) }}" method="POST" id="delete{{ $other->id }}" onsubmit="return false;">
                        @method('DELETE')
                        @csrf
                    </form>                            
                @endforeach
                </tbody>
            </table>                    
        </div>
    </div>    
       
</div>
@endsection