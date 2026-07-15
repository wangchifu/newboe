@extends('layouts.app_clean')

@section('title','帳號管理')

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>更新認證主機帳號</h1>
    <div class="card mb-4">
        <div class="card-header">
            已有帳號資訊
        </div>
        <div class="card-body">            
            <div class="container mt-4" style="max-width: 600px;">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i>基本資料填報
                    </div>
                    <div class="card-body p-4">
                        <h3 class="text-danger">此身分證已有帳號記錄在{{ $staff_sid }}，
                            @if($staff_status==1)
                                在職
                            @else
                                已離職
                            @endif
                        </h3>
                        <span>如果記錄是在</span>
                        <!-- 💡 加上 needs-validation 與 novalidate 啟用 Bootstrap 5 驗證機制 -->
                        <form action="{{ route('admins.user_db2_store2') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <!-- 第一欄位：單位 (下拉 - 必填) -->
                            <div class="mb-3">
                                <label for="unit" class="form-label fw-bold">
                                    第一欄位：單位 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="unit" name="staff_sid" required>
                                    <option value="" {{ empty($new_staff_sid) ? 'selected' : '' }} disabled>-- 請選擇單位 --</option>
                                    
                                    <option value="079998" {{ (isset($new_staff_sid) && $new_staff_sid == '079998') ? 'selected' : '' }}>
                                        縣網中心
                                    </option>
                                    
                                    <option value="079999" {{ (isset($new_staff_sid) && $new_staff_sid == '079999') ? 'selected' : '' }}>
                                        教育處
                                    </option>
                                </select>                                
                                <div class="invalid-feedback">
                                    請選擇所屬單位。
                                </div>
                            </div>

                            <!-- 第二欄位：科別 (下拉 - 必填) -->
                            <div class="mb-3">
                                <label for="section" class="form-label fw-bold">
                                    第二欄位：科別 <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="section" name="staff_curr_class_num" required>
                                    <option value="" {{ (!isset($new_staff_curr_class_num) || $new_staff_curr_class_num === '') ? 'selected' : '' }} disabled>-- 請選擇科別 --</option>
                                    
                                    <option value="0" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == '0') ? 'selected' : '' }}>未分類</option>
                                    <option value="A" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'A') ? 'selected' : '' }}>督學室</option>
                                    <option value="B" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'B') ? 'selected' : '' }}>學管科</option>
                                    <option value="C" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'C') ? 'selected' : '' }}>國教科</option>
                                    <option value="D" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'D') ? 'selected' : '' }}>社教科</option>
                                    <option value="E" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'E') ? 'selected' : '' }}>體健科</option>
                                    <option value="F" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'F') ? 'selected' : '' }}>學特科</option>
                                    <option value="H" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'H') ? 'selected' : '' }}>幼教科</option>
                                    <option value="I" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'I') ? 'selected' : '' }}>縣網中心</option>    
                                    <option value="J" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == 'J') ? 'selected' : '' }}>體發中心</option>                                                    
                                    <option value="99" {{ (isset($new_staff_curr_class_num) && $new_staff_curr_class_num == '99') ? 'selected' : '' }}>全域管理</option>                                                    
                                </select>
                                <div class="invalid-feedback">
                                    請選擇所屬科別。
                                </div>
                            </div>

                            <!-- 第三欄位：身分證 (必填) -->
                            <div class="mb-3">
                                <label for="id_card" class="form-label fw-bold">
                                    第三欄位：身分證字號 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="id_card" name="id_card" value="已輸入" readonly  placeholder="請輸入身分證字號" required>
                                <div class="invalid-feedback">
                                    請務必輸入身分證字號。
                                </div>
                            </div>

                            <!-- 第四欄位：姓名 (必填) -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">
                                    第四欄位：姓名 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" value="{{ $new_staff_name }}" name="staff_name" placeholder="請輸入完整姓名" required>
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
                                    <option value="" {{ (!isset($new_staff_sex) || $new_staff_sex === '') ? 'selected' : '' }} disabled>-- 請選擇性別 --</option>
                                    
                                    <option value="男" {{ (isset($new_staff_sex) && $new_staff_sex === '男') ? 'selected' : '' }}>男</option>
                                    
                                    <option value="女" {{ (isset($new_staff_sex) && $new_staff_sex === '女') ? 'selected' : '' }}>女</option>
                                </select>
                                <div class="invalid-feedback">
                                    請選擇性別。
                                </div>
                            </div>

                            <!-- 第六欄位：職稱 (文字型 - 選填) -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">第六欄位：職稱</label>
                                <input type="text" class="form-control" id="title" name="staff_title" value="{{ $new_staff_title }}" placeholder="例如：科員、管理員 (選填)">
                            </div>
                            <input type="hidden" name="id" value="{{ $id }}">
                            <!-- 按鈕區 -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-bold">送出表單更新他的資料</button>
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