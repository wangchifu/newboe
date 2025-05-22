@extends('layouts.app')

@section('title','內容管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>內容管理</h1>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">
            <a href="{{ route('contents.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> 新增內容</a>
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="table-secondary">
                <tr>
                    <th>標題</th>
                    <th>動作</th>
                    <th class="col-3">最後編輯</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr>
                        <td>
                            <a href="{{ route('contents.show',$content->id) }}">{{ $content->title }}</a><br>                            
                        </td>
                        <td>
                            <a href="{{ route('contents.show',$content->id) }}" class="btn btn-outline-primary btn-sm" target="_blank"><i class="fa-solid fa-magnifying-glass"></i> 顯示</a>
                            <a href="{{ route('contents.edit',$content->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> 修改</a>
                            <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm2('確定刪除？','delete{{ $content->id }}')"><i class="fas fa-trash"></i> 刪除</a>
                        </td>
                        <td>
                            <?php $section_name = (!empty($content->section_id))?$sections[$content->section_id]:""; ?>
                            {{ $content->updated_at }}<br>{{ $section_name }} {{ $content->user->name }}
                        </td>
                    </tr>                        
                    <form action="{{ route('contents.destroy', $content->id) }}" method="POST" id="delete{{ $content->id }}">
                        @csrf                        
                    </form>
                @endforeach
                </tbody>
            </table>            
        </div>
    </div>
</div>
@endsection