@extends('layouts.app_clean')

@section('title','帳號管理')

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>新增科室成員資料</h1>
    <div class="card mb-4">
        <div class="card-header">
            人員資料
        </div>
        <div class="card-body">            
            <div class="container mt-4" style="max-width: 600px;">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>基本資料填報
                    </div>
                    <div class="card-body p-4">
                        <!-- 💡 加上 needs-validation 與 novalidate 啟用 Bootstrap 5 驗證機制 -->
                        <form action="{{ route('admins.db2_store') }}" method="POST" class="needs-validation" novalidate id="this_form">
                            @csrf
                            <!-- 第一欄位：單位 (下拉 - 必填) -->
                            <div class="mb-3">
                                <label for="unit" class="form-label fw-bold">
                                    第一欄位：單位 <span class="text-danger">*</span>
                                </label>
                                <div class="text-secondary ps-1">
                                    @if(auth()->user()->username == 'admin9' or auth()->user()->code == '079998')
                                        縣網中心 079998
                                    @else
                                        教育處 079999
                                    @endif
                                </div>
                            </div>

                            <!-- 第二欄位：科別 (下拉 - 必填) -->
                            <div class="mb-3">
                                <label for="section" class="form-label fw-bold">
                                    第二欄位：科別 <span class="text-danger">*</span>
                                </label>
                                <div class="text-secondary ps-1">
                                    {{ $sections[auth()->user()->section_id] ?? '' }}
                                </div>
                            </div>

                            <!-- 第三欄位：身分證 (必填) -->
                            <div class="mb-3">
                                <label for="id_card" class="form-label fw-bold">
                                    第三欄位：身分證字號 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="id_card" name="id_card" placeholder="請輸入身分證字號" required>
                                <div class="invalid-feedback">
                                    請務必輸入身分證字號。
                                </div>
                            </div>

                            <!-- 第四欄位：姓名 (必填) -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    第四欄位：姓名 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="staff_name" placeholder="請輸入完整姓名" required>
                                <div class="invalid-feedback">
                                    請務必輸入姓名。
                                </div>
                            </div>

                            <!-- 第五欄位：性別 (下拉 - 必填) -->
                            <div class="mb-3">
                                <label for="gender" class="form-label fw-bold">
                                    第五欄位：性別 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="gender" name="staff_sex" required>
                                    <option value="" selected disabled>-- 請選擇性別 --</option>
                                    <option value="男">男</option>
                                    <option value="女">女</option>
                                </select>
                                <div class="invalid-feedback">
                                    請選擇性別。
                                </div>
                            </div>

                            <!-- 第六欄位：職稱 (文字型 - 選填) -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">第六欄位：職稱</label>
                                <input type="text" class="form-control" id="title" name="staff_title" placeholder="例如：科員、管理員 (選填)">
                            </div>

                            <!-- 按鈕區 -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary fw-bold" onclick="sw_confirm2('確定送出？','this_form')">送出表單</button>
                                <button type="reset" class="btn btn-outline-secondary">重填</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- 💡 Bootstrap 5 官方原生驗證 JavaScript --}}
            <script>
            (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
                }, false)
            })
            })()
            </script>            
        </div>
    </div>           
</div>
@endsection