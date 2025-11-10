@extends('layouts.app')

@section('title','新增填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge bg-success"><i class="fas fa-plus"></i> 新增填報</span></h1>
    @include('edus.posts.nav')
    <div class="card my-4">
        <div class="card-header text-center">     
            <h3><i class="fa-solid fa-list"></i> 新增 [{{ $sections[auth()->user()->section_id] }}] 填報</h3>
        </div>
        <div class="card-body">
            @include('layouts.errors')
            <script src=" https://cdn.jsdelivr.net/npm/tinymce@7.9.1/tinymce.min.js "></script>
            <form action="{{ route('edu_report.store') }}" method="post" enctype="multipart/form-data" id="create_form" onsubmit="return false">
                @csrf
            <div class="form-group my-2">
                <label for="name"><strong class="text-danger">1.請務必先選擇對象*</strong></label>                
                @include('edus.posts.select_school')
            </div>
            <div class="form-group my-2">
                <label for="name"><strong class="text-danger">2.填報名稱*</strong></label>                
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group my-2">
                <label for="telephone">公務電話</label>                
                <input type="text" name="telephone" id="telephone" placeholder="請輸入聯絡電話" class="form-control">
                </div>
            <div class="form-group my-2">
                <label for="die_date"><strong class="text-danger">3.截止日期*</strong></label>
                <input id="die_date" name="die_date" type="date" required maxlength="10" class="form-control" style="width:180px;">                
            </div>
            <div class="form-group my-2">
                <label for="content">4.填報說明</label>  
                <br><span class="session-timer text-danger">剩餘時間: -- 後登出</span>，請善用暫存功能。              
                <textarea name="content" id="content" class="form-control" placeholder="請輸入內容"></textarea>                                             
            </div>
            <div class="form-group my-2">
                <label for="files[]">5.附加檔案( 單檔不大於10MB )</label>
                <input type="file" name="files[]" class="form-control" multiple>
            </div>

            <div class="form-group my-2">
                <label><strong class="text-danger">6.設計題目*</strong></label>
                <?php
                    $types = [
                        'radio'=>'1.單選題',
                        'checkbox'=>'2.多選題',
                        'text'=>'3.文字題',
                        'num'=>'4.數字題',
                    ];
                ?>
                <div id='show_question'>
                    <div style="border-style:dashed;padding: 10px;margin: 15px;">
                        <div class="form-group">
                            <label for="title1"><strong>題目1*</strong></label>                            
                            <input type="text" name="title[1]" id="title1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="type1"><strong>題目1-題型*</strong></label>
                            <select name="type[1]" id="type1" onchange="show_type(this,1);" required>
                                <option value="">選擇題型</option>
                                <!-- $types 需要自己用後端印出 -->
                                <?php foreach ($types as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" id='show_type1' style="display:none">
                            <p>
                                <label for='var11'>選項*：</label>
                                <input type='text' name='option1[]' id='option1'>
                            </p>
                            <p>
                                <label for='var12'>選項*：</label>
                                <input type='text' name='option1[]' id='option1'>
                                <i class='fas fa-plus-circle text-success' onclick="add_a(1)"></i>
                            </p>
                        </div>
                        <button type="button" onclick="add()">+增題</button>
                    </div>
                </div>
            </div>                                        
            <input type="button" class="btn btn-outline-primary" value="暫存" onclick="sw_confirm4(this,'確定暫存？','create_form','暫存')">
            <input type="button" class="btn btn-primary" value="送出審核不再修改" onclick="sw_confirm4(this,'送出後，無法再修改喔！','create_form','送出審核不再修改')">            
            <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>                        
            </form>            
        </div>
    </div>
</div>
<script>
    var q = 1;

     function add() {
        var content;
        q++;
        content = "<div style='border-style:dashed;padding: 10px;margin: 15px;'>" +
            "<div class='form-group'>"+
            "<label for='title"+q+"'><strong>題目"+q+"*</strong></label>" +
            "<input type='text' name='title["+q+"]' id='title"+q+"' class='form-control' required> " +
            "</div>"+
            "<div class='form-group'>"+
            "<label for='type"+q+"'><strong>題目"+q+"-題型*</strong></label>" +
            "<select name='type["+q+"]' id='type"+q+"' required onchange='show_type(this,"+q+");'>"+
            "<option value=''>選擇題型</option>"+
            "<option value='radio'>1.單選題</option>"+
            "<option value='checkbox'>2.多選題</option>"+
            "<option value='text'>3.文字題</option>"+
            "<option value='num'>4.數字題</option>"+
            "</select>"+
            "</div>"+
            "<div class='form-group' id='show_type"+q+"' style='display:none'>"+
            "<p>"+
            "<label for='var"+q+"1'>選項*：</label>"+
            "<input type='text' name='option"+q+"[]' id='option"+q+"'>"+
            "</p>"+
            "<p>"+
            "<label for='var"+q+"2'>選項*：</label>"+
            "<input type='text' name='option"+q+"[]' id='option"+q+"'>"+
            "<i class='fas fa-plus-circle text-success' onclick='add_a("+q+")'></i>"+
            "</p>"+
            "</div>"+
            "<button type='button' onclick='add()'>+增題</button>"+
            "<button type='button' onclick='remove(this)'>-刪題</button>"+
            "</div>";
        $("#show_question").append(content);
    }

    function remove(obj) {
        $(obj).parent().remove();
        q--;
    }

    function show_type(G,this_q) {
        if(G.value == 'radio' || G.value == 'checkbox'){
            $("#show_type"+this_q).show();
            $("[id='option"+this_q+"']").attr("required", true);
        } else {
            $("#show_type"+this_q).hide();
            $("[id='option"+this_q+"']").attr("required", false);
        }
    }

    function add_a(this_q) {
        var content;
        content = "<p>" +
            "<label for='var"+this_q+"'>選項*：</label>" +
            "<input type='text' name='option"+this_q+"[]'> " +
            "<i class='fas fa-trash text-danger' onclick='remove_a(this)'></i>" +
            "</p>";
        $("#show_type"+this_q).append(content);
    }

    function remove_a(obj) {
        $(obj).parent().remove();
    }    

    function change_button(){
        $("#submit_button").attr('disabled','disabled');
        $("#submit_button").addClass('disabled');
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