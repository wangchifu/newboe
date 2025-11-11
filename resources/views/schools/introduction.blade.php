@extends('layouts.app')

@section('title','學校簡介')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
<div class="col-lg-12 mx-auto">
    <h1>{{ auth()->user()->school }} 學校簡介</h1>
    <div class="card mb-4">
        <div class="card-header">                        
        </div>
        <div class="card-body">
            <div class="container">
                <form action="{{ route('school_introduction.store') }}" method="POST" enctype="multipart/form-data" id="this_form" onsubmit="return false">
                    @csrf
                <div class="row">
                    <div class="col-12">    
                        [<a href="images/school_sample.png" target="_blank">參考範本</a>] <a href={{"/school/" . auth()->user()->code . "/school_show"}} class="btn btn-sm btn-primary" target="_blank">瀏覽本校目前樣式</a>
                    </div>                   
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="pic1">左欄圖片(尺寸約515 *146)</label>
                            <input type="file" name="pic1" class="form-control" accept="image/*">
                        </div>
                        @if(!empty($school_introduction))
                            @if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic1)) and $school_introduction->pic1)
                            <div class="text-center">
                                <img src ="{{ asset('storage/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic1) }}" class="col-12">
                            </div>                                
                            @endif
                        @endif
                        <hr>
                        <div class="form-group">
                            <label for="introduction1">左欄文字</label>
                            <textarea name="introduction1" id="introduction1" class="form-control" rows="21" placeholder="請輸入內容">{{ $introduction1 }}</textarea>
                        </div>                                                        
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="pic2">右欄圖片(尺寸約972 *550)</label>
                            <input type="file" name="pic2" id="pic2" class="form-control" accept="image/*">
                        </div>                        
                        @if(!empty($school_introduction))
                            @if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic2)) and $school_introduction->pic2)
                                <img src ="{{ asset('storage/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic2) }}" class="col-12">
                            @endif
                        @endif       
                        <hr>
                        <div class="form-group">
                            <label for="introduction2">右欄文字</label>
                            <textarea name="introduction2" id="introduction2" class="form-control" rows="10" placeholder="請輸入內容">{{ $introduction2 }}</textarea>
                        </div>      
                        <hr>                    
                        <div class="form-group">
                            <label for="website">學校網址</label>
                            <input type="text" name="website" id="website" class="form-control" value="{{ $website }}">

                        </div>
                        <div class="form-group">
                            <label for="facebook">facebook 粉絲團</label>
                            <input type="text" name="facebook" id="facebook" class="form-control" value="{{ $facebook }}">
                        </div>
                        <div class="form-group">
                            <label for="wiki">維基百科介紹</label>
                            <input type="text" name="wiki" id="wiki" class="form-control" value="{{ $wiki }}">
                        </div>                   
                    </div>                        
                </div>
                <br>                    
                <button class="btn btn-success" onclick="sw_confirm2('確定送出？','this_form')">儲存</button>
                </form>
            </div>                             
        </div>
    </div>
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