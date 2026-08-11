@extends('layouts.app')

@section('title','帳號管理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>帳號管理</h1>
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
                    <a class="nav-link active" href="{{ route('admins.user_db2') }}">科室人員資料</a>
                </li>
            </ul>
            <form action="{{ route('admins.user_db2_search') }}" method="POST" class="w-100 mt-3" style="max-width: 400px;">
                @csrf
                <div class="input-group">
                    <input type="text" name="person_id" class="form-control" placeholder="請輸入身分證字號" maxlength="10" required>
                    <button class="btn btn-primary" type="submit">
                        依身分證送出查詢
                    </button>
                </div>
            </form>
            <div class="container mt-4">
                {{-- 💡 切換分頁的按鈕區 (Nav Pills) --}}
                <ul class="nav nav-pills mb-3 gap-2" id="staff-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-in-tab" data-bs-toggle="pill" data-bs-target="#pills-in" type="button" role="tab" aria-controls="pills-in" aria-selected="true">
                            在職 <span class="badge bg-secondary text-white ms-1">{{ count($staff_in ?? []) }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-out-tab" data-bs-toggle="pill" data-bs-target="#pills-out" type="button" role="tab" aria-controls="pills-out" aria-selected="false">
                            離職 <span class="badge bg-secondary text-white ms-1">{{ count($staff_out ?? []) }}</span>
                        </button>
                    </li>
                </ul>

                {{-- 💡 分頁內容區 (Tab Content) --}}
                <div class="tab-content" id="staff-tabContent">
                    {{-- ========================================== --}}
                    {{-- 🟢 在職教職員分頁 --}}
                    {{-- ========================================== --}}
                    <div class="tab-pane fade show active" id="pills-in" role="tabpanel" aria-labelledby="pills-in-tab">
                        <a href="{{ route('admins.user_db2_create') }}" class="btn btn-success venobox" data-vbtype="iframe">新增</a>
                        <span class="text-danger"><i class="fas fa-arrow-left me-1"></i>這裡新增完資料，本人還是要到 <a href="https://eip.chc.edu.tw" target="_blank">eip.chc.edu.tw</a> 申請資料。</span>
                        @if(!empty($staff_in))
                            @foreach($staff_in as $id => $info)
                            <form action="{{ route('admins.user_db2_change',$id) }}" method="post" id="change_room{{ $id }}">
                                @csrf
                                <input type="hidden" name="person_id" value="{{ $info['person_id'] }}">
                            </form>
                            @endforeach
                            <div class="table-responsive border rounded shadow-sm">
                                <table class="table table-bordered table-hover align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>序號</th>
                                            <th>ID</th>
                                            <th>代碼</th>
                                            <th>姓名</th>
                                            <th>性別</th>
                                            <th>職稱</th>
                                            <th>科別</th>
                                            <th>動作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $n=1; ?>
                                        @foreach($staff_in as $id => $info)
                                        <tr>
                                            <td class="fw-bold text-secondary">{{ $n }}</td>
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
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-success btn-sm" type="button" onclick="sw_confirm2('左列資料都確定了嗎？','change_room{{ $id }}')">
                                                        儲存
                                                    </button>
                                                    <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('你確定要離職 {{ $info['name'] }}','{{ route('admins.user_db2_out',$id) }}')">
                                                        離職他
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $n++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">目前無在職教職員資料。</div>
                        @endif
                    </div>

                    {{-- ========================================== --}}
                    {{-- 🔴 離職/非在職教職員分頁 --}}
                    {{-- ========================================== --}}
                    <div class="tab-pane fade" id="pills-out" role="tabpanel" aria-labelledby="pills-out-tab">
                        @if(!empty($staff_out))
                            <div class="table-responsive border rounded shadow-sm">
                                <table class="table table-bordered table-hover align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>序號</th>
                                            <th>ID</th>
                                            <th>代碼</th>
                                            <th>姓名</th>
                                            <th>性別</th>
                                            <th>職稱</th>
                                            <th>科別</th>
                                            <th>動作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $n=1; ?>
                                        @foreach($staff_out as $id => $info)
                                        <tr>
                                            <td class="fw-bold text-secondary"><!--刪除的程式有寫 -->
                                                {{ $n }}
                                            </td>
                                            <td>{{ $id }}</td>
                                            <td>
                                                @if($info['sid'] == '079998')
                                                    縣網中心 {{ $info['sid'] }}
                                                @elseif($info['sid'] == '079999')
                                                    教育處 {{ $info['sid'] }}
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td class="text-muted text-decoration-line-through">{{ $info['name'] ?? '---' }}</td>
                                            <td>{{ $info['sex'] ?? '---' }}</td>
                                            <td><span class="badge bg-light text-secondary border">{{ $info['title'] ?? '---' }}</span></td>
                                            <td>
                                                @if(isset($sections[$info['staff_curr_class_num']]))
                                                    {{ $sections[$info['staff_curr_class_num']] }} ({{ $info['staff_curr_class_num'] }})
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td><a href="#!" class="btn btn-success btn-sm" onclick="sw_confirm1('你確定要復職 {{ $info['name'] }}','{{ route('admins.user_db2_in',$id) }}')">復職他</a></td>
                                        </tr>
                                        <?php $n++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-secondary text-center">目前無離職/非在職教職員資料。</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection