@extends('layouts.app')

@section('title','待審公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}] <span class="badge bg-primary"><i class="fas fa-user-cog"></i> 審核區</span></h1>
    @include('edus.posts.nav')
    <div class="card my-4">
        <div class="card-header text-center">
            <h3 class="py-2">
                [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審公告
            </h3>
        </div>
        <div class="card-body">
            @include('edus.posts.list')
        </div>
    </div>
    <div class="card my-4">
        <div class="card-header text-center bg-info">
            <h3 class="py-2">
                [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審填報
            </h3>
        </div>
        <div class="card-body">
            <table class="table rwd-table">
                <thead class="thead-light">
                <tr>
                    <th>
                        編號
                    </th>
                    <th>
                        發佈人
                    </th>
                    <th>
                        名稱
                    </th>
                    <th>
                        創建時間
                    </th>
                    <th>
                        狀態
                    </th>
                    <th>
                        動作
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td data-th="編號">
                            {{ $report->id }}
                        </td>
                        <td data-th="發佈人">
                            {{ $report->user->name }}
                        </td>
                        <td data-th="名稱">
                            <a href="{{ route('edu_report.show',$report->id) }}" class="venbox" data-vbtype="iframe">
                                <span style="color:#000088">
                                {{ str_limit($report->name,60) }}
                                </span>
                            </a>
                        </td>
                        <td data-th="創建時間">
                            <small>{{ substr($report->created_at,0,16) }}</small>
                        </td>
                        <td data-th="狀態">
                            {{ $situation[$report->situation] }}
                        </td>
                        <td data-th="動作">
                            <div style="float:left;margin-right: 5px">
                                <form action="{{ route('reports.return',$report->id) }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <button class="btn btn-outline-success btn-sm" onclick="return confirm('確定退回？')">退回</button>
                                </form>
                            </div>
                            <div style="float:left;margin-right: 5px">
                                <form action="{{ route('reports.approve',$report->id) }}" method="post" id="f{{ $report->id }}">
                                    @csrf
                                    @method('patch')
                                    <span class="btn btn-outline-info btn-sm" onclick="sw_confirm3(this,'確定核准嗎？','');" id="b{{ $report->id }}">核准</span>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
</script>
@endsection