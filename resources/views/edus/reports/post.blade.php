@extends('layouts.app')

@section('title','催促公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge bg-success"><i class="fas fa-plus"></i> 催促公告</span></h1>
        @include('edus.posts.nav')
        <form action="{{ route('posts.store') }}" method="POST" id="create_form" enctype="multipart/form-data" onsubmit="return false">
            @csrf
        <div class="card my-4">
            <div class="card-header text-center">
                <h3><i class="fa-solid fa-signs-post"></i> 新增 [{{ $sections[auth()->user()->section_id] }}] 催促公告</h3>
            </div>
            <div class="card-body">
                <div class="form-group my-2">
                    <label for="category_id"><strong>公告類別*</strong></label>
                    <br>
                    行政公告
                    <input type="hidden" name="category_id" value="5">
                </div>
                <div class="form-group my-2">
                    <label for="title"><strong>公告主旨*</strong></label>                    
                    <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required value="催促填報!! 資料填報編號<?= $report->id ?> 未送達!!">
                </div>
                <div class="form-group my-2">
                    <label for="content"><strong>公告內容*</strong></label>
                    <textarea name="content" id="content" class="form-control" rows="10" placeholder="請輸入內容" required><?= "請貴校盡速填報編號：" . $report->id . "-" . $report->name ?></textarea>
                </div>
                <div class="form-group my-2">
                    <label for="schools"><strong>發送對象學校*</strong></label>
                    <br>
                    {{ $schools }}
                    @foreach($school_array as $k=>$v)
                        <input type="hidden" name="sel_school[]" value="{{ $v }}">
                    @endforeach
                </div>
                <div class="form-group my-2">
                    <input type="button" class="btn btn-outline-primary" value="暫存" onclick="sw_confirm4(this,'確定暫存？','create_form','暫存')">
                    <input type="button" class="btn btn-primary" value="送出審核不再修改" onclick="sw_confirm4(this,'送出後，無法再修改喔！','create_form','送出審核不再修改')">
                    <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>
                </div>
            </div>
        </div>
        </form>
    </div>    
</div>
<script>
	tinyMCE.init({
		selector: "textarea",
			plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table emoticons template paste help code codesample'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link | ' +
      'forecolor backcolor emoticons | preview fullscreen',
    menu: {
      favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: false,
    language: 'zh_TW',
    language_url: '{{ asset('js/zh_TW.js') }}' // 加這行
});
</script>
@endsection