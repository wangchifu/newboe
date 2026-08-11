@extends('layouts.app_clean')

@section('title','帳號管理')

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>科室人員資料(不含調府教師)</h1>
    <div class="card mb-4">
        <div class="card-header">
            帳號資訊
        </div>
        <div class="card-body">
            <div class="container mt-4">

                {{-- 💡 切換分頁的按鈕區 (Nav Pills) --}}
                <ul class="nav nav-pills mb-3 gap-2" id="staff-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-in-tab" data-bs-toggle="pill" data-bs-target="#pills-in" type="button" role="tab" aria-controls="pills-in" aria-selected="true">
                            在職 <span class="badge bg-white text-primary ms-1">{{ count($staff_in ?? []) }}</span>
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
                        <a href="{{ route('admins.db2_create') }}" class="btn btn-success">新增</a>
                        <span class="text-danger"><i class="fas fa-arrow-left me-1"></i>這裡新增完資料，本人還是要到 <a href="https://eip.chc.edu.tw" target="_blank">eip.chc.edu.tw</a> 申請資料。</span>
                        @if(!empty($staff_in))
                            @foreach($staff_in as $id => $info)
                            <form action="{{ route('my_section.db2_change', $id) }}" method="post" id="change_room{{ $id }}">
                                @csrf
                                <input type="hidden" name="person_id" value="{{ $info['person_id'] ?? '' }}">
                                <input type="hidden" name="staff_sid" value="{{ $info['sid'] ?? '' }}">
                                <input type="hidden" name="staff_curr_class_num" value="{{ $info['staff_curr_class_num'] ?? '' }}">
                            </form>
                            @endforeach
                            <div class="table-responsive border rounded shadow-sm">
                                <table class="table table-bordered table-hover align-middle text-center mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>序號</th>
                                            {{-- <th>ID</th> --}}
                                            {{-- <th>代碼</th> --}}
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
                                            {{-- <td>{{ $id }}</td> --}}
                                            {{-- <td>
                                                @if($info['sid'] == '079998')
                                                    縣網中心 {{ $info['sid'] }}
                                                @elseif($info['sid'] == '079999')
                                                    教育處 {{ $info['sid'] }}
                                                @else
                                                    ---
                                                @endif
                                            </td> --}}
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
                                                @if(isset($sections[$info['staff_curr_class_num']]))
                                                    {{ $sections[$info['staff_curr_class_num']] }} ({{ $info['staff_curr_class_num'] }})
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-success btn-sm" type="button" onclick="sw_confirm2('左列資料都確定了嗎？','change_room{{ $id }}')">
                                                        儲存
                                                    </button>
                                                    <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('離職後將無法登入 eip 及新雲端，如果只是換科室，不要離職他，只要移除權限即可','{{ route('admins.db2_out',$id) }}')">
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
                                            {{-- <th>ID</th> --}}
                                            {{-- <th>代碼</th> --}}
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
                                            <td class="fw-bold text-secondary">{{ $n }}</td>
                                            {{-- <td>{{ $id }}</td> --}}
                                            {{-- <td>
                                                @if($info['sid'] == '079998')
                                                    縣網中心 {{ $info['sid'] }}
                                                @elseif($info['sid'] == '079999')
                                                    教育處 {{ $info['sid'] }}
                                                @else
                                                    ---
                                                @endif
                                            </td> --}}
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
                                            <td><a href="#!" class="btn btn-success btn-sm" onclick="sw_confirm1('你確定要復職 {{ $info['name'] }}','{{ route('admins.db2_in',$id) }}')">復職他</a></td>
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