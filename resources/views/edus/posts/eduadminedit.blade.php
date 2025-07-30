@extends('layouts.app')

@section('title', '修改公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
    <div class="col-lg-12 mx-auto">
        @include('edus.posts.nav')
        <form action="{{ route('posts.eduadminupdate', $post->id) }}" method="POST" id="edit_form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        <div class="card my-4">
            <div class="card-header text-center">
                <h3 class="py-2">
                    公告列表-主管修改公告
                </h3>
            </div>
            <div class="card-body">
                @include('edus.posts.form',['post'=>$post])
                <div class="form-group">
                    現有附件：<br>
                    @if(!empty($files))
                        @foreach($files as $v)
                            <a href="#!" onclick="sw_confirm1('確定刪除？','{{ route('posts.del_att',['id'=>$post->id,'filename'=>$v]) }}');" class="text-danger"><i class="fas fa-trash"></i></a>
                            <a href="{{ route('posts.download',['filename'=>$v,'id'=>$post->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download"></i> {{ $v }}
                            </a>
                        @endforeach
                    @endif
                </div>
                <div class="form-group">
                    現有圖片：<br>
                    @if(!empty($images))
                        @foreach($images as $v)                            
                            <?php
                                    $image_path = $images_path.'/'.$v;
                                    $file_path = str_replace('/','&',$image_path);
                            ?>
                            <a href="#!" onclick="sw_confirm1('確定刪除？','{{ route('posts.del_img',['id'=>$post->id,'filename'=>$v]) }}');" class="text-danger"><i class="fas fa-trash"></i></a>
                            <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$v) }}" target="_blank">
                                <img src="{{ route('posts.img',$file_path) }}" height="50">
                            </a>                            
                        @endforeach
                    @endif
                </div>
                <br>
                <div id='show_type'
                        @if($post->category_id!=5)
                        style="display:none"
                        @endif
                >
                    <div class="form-group">
                        <label>緊急程度</label><br>
                        <input name="type" type="checkbox" value="1" id="type"
                                @if($post->type===1)
                                checked
                            @endif
                        > <label for="type">[最速件]</label>
                    </div>
                    <div class="form-group">
                        <label>公開為「一般公告」給訪客？</label><br>
                        <input name="another" type="checkbox" value="1" id="another"
                                @if($post->another===1)
                                checked
                            @endif
                        > <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                    </div>
                    <div class="form-group">

                        <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>

                        @include('edus.posts.select_school')
                    </div>

                </div>

                <div class="form-group">
                    <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>
                    <input type="button" class="btn btn-primary" value="送出審核不再修改" onclick="sw_confirm3(this,'送出後，無法再修改喔！','edit_form','送出審核不再修改')">
                </div>
            </div>
        </div>
        </form>        
    </div>
<script>
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
</script>
@endsection