@extends('layouts.app')

@section('title','內容管理')

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('my_js_file')
<script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
@endsection

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-10 mx-auto">
    <h1>新增內容</h1>
    <div class="card mb-4">
        <div class="card-header">表單</div>
        <div class="card-body">
            <form  action="{{ route('contents.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">標題*</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="必填" required>
                </div>    
                <div class="mb-3">
                    <label for="title" class="form-label">內文*</label>
                    <textarea id="mytextarea" name="content" class="form-control"></textarea>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="mb-3">
                    <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();"><i class="fa-solid fa-backward"></i> 返回</button>
                    <button type="submit" class="btn btn-primary btn-sm">儲存</button>
                </div>
            </form>                                   
        </div>
    </div>
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