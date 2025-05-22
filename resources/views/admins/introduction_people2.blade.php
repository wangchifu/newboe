@extends('layouts.app')

@section('title','長官資料')

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('my_js_file')
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
@endsection

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $section_name }}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admins.introduction_index') }}">教育處介紹管理列表</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $section_name }}</li>
        </ol>
    </nav>
    <form action="{{ route('admins.introduction_store2') }}" method="post" id="store_form">
        @csrf
        <div class="form-group">
            <textarea id="mytextarea" name="content" required>{{ $content }}</textarea>            
        </div>
        
        <input type="hidden" name="section_id" value="{{ $section_id }}">
        <input type="hidden" name="type" value="people2">
        <div class="form-group">
            <a class="btn btn-success btn-sm" onclick="sw_confirm2('確定儲存？','store_form')">儲存設定</a>
        </div>
    </form>       
    <br>    
</div>
@endsection

@section('my_js')
<script>
    tinymce.init({
        selector: 'textarea#mytextarea',
        language: 'zh_TW', // 設置語言為繁體中文
        language_url: '/tinymce/langs/zh_TW.js', // 指定語言檔案路徑
        plugins: 'fullscreen code table,image link lists image paste', // 啟用表格功能
        toolbar: 'fullscreen code undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | table image link unlink openlink | bullist numlist outdent indent | removeformat',                
        //paste_data_images: true,//拖過去上傳
        //images_upload_url: '/contents/upload_image', // Laravel API
        automatic_uploads: true,
        // 不自動清理或修改 HTML
        valid_elements: '*[*]', 
        extended_valid_elements: '*[*]',
        verify_html: false,
        forced_root_block: false,  // 避免自動包裹 `<p>` 標籤
        remove_trailing_brs: false, // 不刪除尾部 <br>
        convert_urls: false, // 禁止 TinyMCE 轉換圖片 URL
        relative_urls: false, // 確保使用絕對 URL
        remove_script_host: false, // 保留完整的 URL，包括 http:// 或 https://

        // 改為使用 Promise 來處理圖片上傳
        images_upload_handler: function (blobInfo) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            return fetch('/contents/upload_image', {//laravel API
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return Promise.reject('伺服器回應錯誤，狀態碼：' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.location) {
                    // 返回圖片 URL，讓 TinyMCE 插入圖片
                    return data.location;
                } else {
                    return Promise.reject('伺服器回傳的 JSON 不包含 `location` 欄位');
                }
            })
            .catch(error => {
                console.error('圖片上傳錯誤:', error);
                return Promise.reject('圖片上傳失敗');
            });
        }
    });
    

</script>
@endsection