<div class="table-responsive">
    <table class="table rwd-table table-hover" style="word-break: break-all;">
        <thead class="thead-light">
        <tr>
            <th nowrap>
                編號
            </th>
            <th nowrap>
                類別
            </th>
            <th nowrap>
                發佈人
            </th>
            <th nowrap>
                主旨
            </th>
            <th nowrap>
                創建時間<br>
                發佈時間
            </th>
            <th nowrap>
                狀態
            </th>
            @if($uri_name !="all")
                <th nowrap>
                    動作
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td data-th="編號" nowrap class="td-number">
                    @if($post->post_no)
                        {{ $post->post_no }}
                    @else
                        {{ $post->id }}
                    @endif
                </td>
                <td data-th="類別" nowrap>
                    {{ $categories[$post->category_id] ?? '' }}
                </td>
                <td data-th="發佈人">
                    {{ $post->user?->name }}
                </td>
                <td data-th="主旨" class="td-title">
                    @if($post->another ===1)
                        <span class="text-success">
                            <i class="fas fa-eye"></i>
                        </span>
                    @endif
                    @if($post->type ===1)
                        <span class="text-danger">
                            [最速件]
                        </span>
                    @endif
                    @if( $post->situation ===4)
                            <a href="{{ route('posts.show_doing_post',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                            <span style="color:red">[公告作廢]</span>
                            <strike class="text-primary">
                                {{ $post->title }}
                            </strike></a>
                    @else
                            <a href="{{ route('posts.show_doing_post',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                            <span style="color:#000088">
                            {{ $post->title }}
                            </span>
                        </a>
                    @endif
                </td>
                <td data-th="創建時間" nowrap>
                    {{ substr($post->created_at,0,16) }}<br>
                    {{ substr($post->passed_at,0,16) }}
                </td>
                <td data-th="狀態" nowrap>
                    {{ $situation[$post->situation] ?? '' }}
                    @if($post->situation==3)
                        @if(isset($post->pass_user))
                        <i class="fas fa-user" data-toggle="tooltip" data-placement="bottom" title="{{ $post->pass_user->name }} 於 {{ substr($post->passed_at,0,16) }}"></i>
                        @endif
                    @endif
                </td>
                @if($uri_name !="all")
                    <td data-th="動作" style="white-space: nowrap;">
                        @include('edus.posts.button_action')
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>