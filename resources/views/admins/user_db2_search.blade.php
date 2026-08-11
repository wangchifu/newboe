@extends('layouts.app')

@section('title','帳號管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>帳號管理-搜尋身分證結果</h1>
    <div class="card mb-4">
        <div class="card-header">
            @include('admins.search_nav')
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_index') }}">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','1') }}">學校</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','2') }}">教育處</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_group','3') }}">系統管理者</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admins.user_check') }}">重複身分證帳號</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admins.user_db2') }}">認證主機帳號</a>
                </li>
            </ul>
            <div class="container mt-4">
                <a href="{{ route('admins.user_db2') }}" class="btn btn-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i> 返回</a>
                @foreach($staff as $id => $info)
                <form action="{{ route('admins.user_db2_change2',$id) }}" method="post" id="change_room{{ $id }}">
                    @csrf
                    <input type="hidden" name="person_id" value="{{ $info['person_id'] }}">
                </form>
                @endforeach
                <table class="table table-bordered table-hover align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>代碼</th>
                            <th>姓名</th>
                            <th>性別</th>
                            <th>職稱</th>
                            <th>科別</th>
                            <th>在職?</th>
                            <th>動作</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($staff as $id => $info)
                        <tr>
                            <td>{{ $id }}</td>
                            <td>
                                <select name="staff_sid" form="change_room{{ $id }}" class="form-select" autocomplete="off">
                                    <option value="079998" {{ (isset($info['sid']) && $info['sid'] == '079998') ? 'selected' : '' }}>
                                        縣網中心 (079998)
                                    </option>

                                    <option value="079999" {{ (isset($info['sid']) && $info['sid'] == '079999') ? 'selected' : '' }}>
                                        教育處 (079999)
                                    </option>
                                </select>
                            </td>
                            <td class="fw-bold text-dark">
                                <input type="text" name="staff_name" form="change_room{{ $id }}" class="form-control fw-bold text-dark" value="{{ $info['name'] ?? '' }}" placeholder="請輸入姓名" required>
                            </td>
                            <td>
                                <select name="staff_sex" form="change_room{{ $id }}"
                                        class="form-select text-center fw-bold {{ (isset($info['sex']) && $info['sex'] === '女') ? 'text-danger' : 'text-primary' }}"
                                        onchange="this.className = 'form-select text-center fw-bold ' + (this.value === '女' ? 'text-danger' : 'text-primary')">
                                    <option class="text-primary" value="男" {{ (isset($info['sex']) && $info['sex'] === '男') ? 'selected' : '' }}>男</option>
                                    <option class="text-danger" value="女" {{ (isset($info['sex']) && $info['sex'] === '女') ? 'selected' : '' }}>女</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="staff_title" form="change_room{{ $id }}" class="form-control fw-bold text-primary" value="{{ $info['title'] ?? '' }}" placeholder="請輸入職稱">
                            </td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 250px;">
                                    <select name="staff_curr_class_num" form="change_room{{ $id }}" class="form-select" autocomplete="off">
                                        <option value="">---</option>
                                        @foreach($sections as $key => $section_name)
                                            <option value="{{ $key }}" {{ (isset($info['staff_curr_class_num']) && $info['staff_curr_class_num'] === $key) ? 'selected' : '' }}>
                                                {{ $section_name }} ({{ $key }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <select name="staff_status" form="change_room{{ $id }}" class="form-select">
                                    <option value="1" {{ $info['staff_status'] == 1 ? 'selected' : '' }}>在職</option>
                                    <option value="0" {{ $info['staff_status'] == 0 ? 'selected' : '' }}>離職</option>
                                </select>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-success btn-sm" type="button" onclick="sw_confirm2('左列資料都確定了嗎？','change_room{{ $id }}')">
                                        儲存
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection