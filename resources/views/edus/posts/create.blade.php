@extends('layouts.app')

@section('title','新增公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge bg-success"><i class="fas fa-plus"></i> 新增公告</span></h1>
        @include('edus.posts.nav')
        <form action="{{ route('posts.store') }}" method="POST" id="create_form" enctype="multipart/form-data" onsubmit="return false">
            @csrf
        <div class="card my-4">
            <div class="card-header text-center">
            </div>
            <div class="card-body">
                @include('edus.posts.form')
                <div id='show_type'>
                    <div class="form-group my-2">
                        <label>緊急程度</label><br>
                        <input name="type" type="checkbox" value="1" id="type"> <label for="type">[最速件]</label>
                    </div>
                    <div class="form-group my-2">
                        <label>公開為「一般公告」給訪客？</label><br>
                        <input name="another" type="checkbox" value="1" id="another"> <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                    </div>
                    <div class="form-group my-2">
                    <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>
                    @include('edus.posts.select_school')
                    </div>
                </div>
                <div class="form-group my-2">
                    <input type="button" class="btn btn-outline-primary" value="暫存" onclick="sw_confirm3(this,'確定暫存？','create_form','暫存')">
                    <input type="button" class="btn btn-primary" value="送出審核不再修改" onclick="sw_confirm3(this,'送出後，無法再修改喔！','create_form','送出審核不再修改')">
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
</script>
@endsection