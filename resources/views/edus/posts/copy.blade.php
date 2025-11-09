@extends('layouts.app')

@section('title','複製公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}] <span class="badge bg-success"><i class="fas fa-plus"></i> 複製公告</span></h1>
        @include('edus.posts.nav')                
        <form action="{{ route('posts.store') }}" method="POST" id="copy_form" enctype="multipart/form-data" onsubmit="change_button()">
            @csrf
        <div class="card my-4">
            <div class="card-header text-center">
                <h3 class="py-2">複製公告</h3>
            </div>
            <div class="card-body">
                @include('layouts.errors')
                <script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
                <div class="form-group my-2">
                    <label for="category_id"><strong class="text-danger">公告類別*</strong></label>
                    <select name="category_id" id="category_id" class="form-control" onchange="show_type(this)">
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" {{ $id == $post->category_id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group my-2">
                    <label for="title"><strong class="text-danger">公告主旨*</strong></label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required="required" value="{{ old('title', $post->title) }}">
                </div>
                <div class="form-group  my-2">
                    <label for="telephone">公務電話</label>
                    <?php
                        $telephone = (empty($post->user->id))?auth()->user()->telephone:$post->user->telephone;
                    ?>
                    <input type="text" name="telephone" id="telephone" class="form-control" placeholder="請輸入聯絡電話" value="{{ $telephone }}">
                </div>
                <div class="form-group my-2">
                    <label for="content"><strong class="text-danger">公告內容*</strong></label>
                    <label for="content"><strong  class="text-danger">文字縮排請不要自行鍵入空格，請用編輯器的功能<span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M7 5h12c.6 0 1 .4 1 1s-.4 1-1 1H7a1 1 0 1 1 0-2zm5 4h7c.6 0 1 .4 1 1s-.4 1-1 1h-7a1 1 0 0 1 0-2zm0 4h7c.6 0 1 .4 1 1s-.4 1-1 1h-7a1 1 0 0 1 0-2zm-5 4h12a1 1 0 0 1 0 2H7a1 1 0 0 1 0-2zm-2.6-3.8L6.2 12l-1.8-1.2a1 1 0 0 1 1.2-1.6l3 2a1 1 0 0 1 0 1.6l-3 2a1 1 0 1 1-1.2-1.6z" fill-rule="evenodd"></path></svg></span></strong></label>
                    <textarea name="content" id="mytextarea" class="form-control" rows="10" placeholder="請輸入內容" required="required">{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="form-group my-2">
                    <label for="url">相關網址( 請記得加上http://或https://)</label>
                    <input type="text" name="url" id="url" class="form-control" value="{{ old('url', $post->url) }}">
                </div>
                <div class="form-group my-2">
                    <label for="files[]">附加檔案( 單檔不大於10MB，請以ODF格式附加 ) <small class="text-secondary">csv,txt,zip,jpeg,png,pdf,odt,ods</small></label>                            
                    <input type="file" name="files[]" class="form-control" multiple="multiple" onchange="checkfile(this);">
                </div>
                <div class="form-group my-2">
                    <label for="photos[]">相關照片( 四張以內，單檔不大於5MB的圖檔 )</label>
                    <input type="file" name="photos[]" class="form-control" multiple="multiple" accept="image/*">
                </div>
                <script>
                    function checkfile(sender) {

                        // 可接受的附檔名
                        var validExts = new Array(".csv", ".txt", ".zip", ".jpg", ".jpeg", ".png", ".pdf", ".odt", ".ods", ".PDF", ".JPG", ".JPEG");

                        var fileExt = sender.value;
                        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
                        if (validExts.indexOf(fileExt) < 0) {
                            alert("檔案類型錯誤，可接受的副檔名有：" + validExts.toString());
                            sender.value = null;
                            return false;
                        }
                        else return true;
                    }
                </script>

                <div id='show_type'
                        @if($post->category_id!=5)
                        style="display:none"
                        @endif
                >
                    <div class="form-group my-2">
                        <label>緊急程度</label><br>
                        <input name="type" type="checkbox" value="1" id="type"
                            @if($post->type===1)
                                checked
                            @endif
                        > <label for="type">[最速件]</label>
                    </div>
                    <div class="form-group my-2">
                        <label>公開為「一般公告」給訪客？</label><br>
                        <input name="another" type="checkbox" value="1" id="another"
                                @if($post->another===1)
                                checked
                            @endif
                        > <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                    </div>
                    <div class="form-group my-2">
                        <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>
                        @include('edus.posts.select_school')
                    </div>

                </div>                
                <div class="form-group my-2">
                    <input type="button" class="btn btn-outline-primary" value="暫存" onclick="sw_confirm3(this,'確定暫存？','copy_form','暫存')">
                    <input type="button" class="btn btn-primary" value="送出審核不再修改" onclick="sw_confirm3(this,'送出後，無法再修改喔！','copy_form','送出審核不再修改')">
                    <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>
                </div>
            </div>
        </div>
        </form>        
    </div>    
</div>
<script>
    $(document).ready(function() {
        show_type($('#category_id').val());
    });
    function show_type(G) {
        if(G == '5'){
            $("#show_type").show();
        } else {
            $("#show_type").hide();
        }
    }    

    function sw_confirm3(button, msg, form_id, action_value) {
        // 先讓按鈕消失
        button.style.display = 'none';

        Swal.fire({
            title: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '確定',
            cancelButtonText: '取消',
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById(form_id);
                let hidden = document.createElement("input");
                hidden.type = "hidden";
                hidden.name = "form_action";
                hidden.value = action_value;
                form.appendChild(hidden);

                form.submit();
            } else {
                // 如果取消，要把按鈕再顯示回來
                button.style.display = '';
            }
        });
    }
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