<!--當科長審核時，會出現修改、退回、核准的button-->
@if($uri_name == 'review')
    <a href="{{ route('posts.eduadminedit',$post->id) }}"><button class="btn btn-outline-danger btn-sm">修改</button></a>
    <button class="btn btn-outline-success btn-sm" onclick="sw_confirm2('您確定要退回嗎?','return{{ $post->id }}')">退回</button>
    <button class="btn btn-outline-info btn-sm" onclick="sw_confirm3(this,'確定核准嗎？','ok{{ $post->id }}',null)">核准</button>
    <form id="return{{ $post->id }}" class="tr" action="{{ route('posts.return',$post->id) }}" method="post">
        @csrf
        {{ method_field('PATCH') }}
    </form>
    <!--假裝核准，但其實是將公告先寫到post_schools資料表，再導到posts.approve執行update 更新situation狀態為3-->
    <form id="ok{{ $post->id }}" class="tr" action="{{ route('posts.addPostSchools',$post->id) }}" method="post">
        @csrf
        {{ method_field('POST') }}
    </form>

    <!--當送審中時，會依不同的進度，顯示可使用的按鈕-->
@elseif($uri_name == 'reviewing')
    @if ( $post->situation  === 0)
        <a href="{{ route('posts.edit',$post->id) }}">
            <button class="btn btn-outline-danger btn-sm">修改</button>
        </a>
        <button class="btn btn-outline-dark btn-sm" onclick="sw_confirm2('您確定送出嗎?','del{{ $post->id }}');">刪除</button>
        <button class="btn btn-outline-primary btn-sm" onclick="sw_confirm2('您確定送出嗎?','resend{{ $post->id }}');">再次送審</button>
        <form id="del{{ $post->id }}" class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}
        </form>
        <form id="resend{{ $post->id }}" class="tr" action="{{ route('posts.resend',$post->id) }}" method="post">
            @csrf
            {{ method_field('PATCH') }}
        </form>
    @endif

    @if ( $post->situation  === -1)
        <a href="{{ route('posts.edit',$post->id) }}">
            <button class="btn btn-outline-danger btn-sm">修改</button>
        </a>
        <button class="btn btn-outline-dark btn-sm" onclick="sw_confirm2('您確定送出嗎?','del{{ $post->id }}');">刪除</button>
        <form id="del{{ $post->id }}" class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}            
        </form>
    @endif
    @if ( $post->situation  === 2)
        <button class="btn btn-outline-dark btn-sm" onclick="sw_confirm2('您確定送出嗎?','del{{ $post->id }}');">刪除</button>
        <form id="del{{ $post->id }}" class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}            
        </form>
    @endif
    <!--當審核通過時，只能作廢一途-->
@elseif($uri_name == 'passing')
    @if ( $post->situation  === 3)
        <form class="tr" action="{{ route('posts.obsolete',$post->id) }}" method="post" id="obsolete_form" onsubmit="return false">
            @csrf
            {{ method_field('PATCH') }}
            <a href="#!" onclick="sw_confirm2('確定作廢？','obsolete_form')">
                <button class="btn btn-outline-secondary btn-sm">作廢</button>
            </a>
            <a href="{{ route('posts.copy',$post->id) }}" class="btn btn-outline-primary btn-sm">
                複製
            </a>
        </form>
    @endif

@endif
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
</script>