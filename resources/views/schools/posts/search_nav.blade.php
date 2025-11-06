<table>
    <tr>
        <td>
            <form action="{{ route('posts.search') }}" method="post" id="this_form">
                @csrf
                編號/主旨/內文/承辦人：<input type="text" maxlength="8" name="want" required>
                <input type="submit" value="搜尋公告">
            </form>
        </td>
        <td>
            <?php 
             $sections = config('boe.sections');
            ?>
            科室分類：
            <select id="select_section" onchange="go_jump()">
                <option value="all">
                    全部
                </option>
                @foreach($sections as $k=>$v)
                    @if($k <> "G")
                    <?php
                        $selected = ($k==$section_id)?"selected":"";
                    ?>
                        <option value="{{ $k }}" {{ $selected }}>
                            {{ $v }}
                        </option>
                    @endif
                @endforeach
            </select>
        </td>
    </tr>
</table>
<script>    
    function go_jump(){
        if($("#select_section").val()!='' & $("#select_section").val()!='all'){
            location="{{ url('posts/search_by_section') }}/" + $("#select_section").val();
        }
        if($("#select_section").val()=='all'){
            location="{{ url('posts/showSigned') }}";
        }
    }
</script>