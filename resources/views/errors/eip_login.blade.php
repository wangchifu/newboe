@extends('layouts.app')

@section('title','無法登入')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<style>
    .eip-error { max-width: 560px; margin: 1rem auto 3rem; padding: 3rem 2.5rem; text-align: center; background: #fff; border: 1px solid #e9ecef; border-top: 4px solid #dc3545; border-radius: .75rem; box-shadow: 0 .75rem 2rem rgba(33, 37, 41, .08); }
    .eip-error__icon { display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; margin-bottom: 1.5rem; color: #dc3545; background: #fdecee; border-radius: 50%; }
    .eip-error__system { margin-bottom: .5rem; font-size: 1rem; color: #6c757d; }
    .eip-error__title { margin-bottom: 1.25rem; font-size: 1.75rem; font-weight: 700; color: #212529; }
    .eip-error__hint { margin-bottom: 2rem; line-height: 1.8; color: #6c757d; }
    .eip-error__action { padding: .625rem 2rem; border-radius: 2rem; }
    @media (max-width: 575.98px) { .eip-error { padding: 2.25rem 1.25rem; } .eip-error__title { font-size: 1.5rem; } }
</style>

<div class="col-12 col-lg-8 mx-auto">
<div class="eip-error" role="alert">
    <div class="eip-error__icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>
    <div class="eip-error__system">彰化縣數位學習師生單一帳號系統(EIP)</div>
    <h1 class="eip-error__title">發生錯誤 無法登入</h1>
    <p class="eip-error__hint">請稍後再試一次。</p>
    <a href="{{ route('logins') }}" class="btn btn-primary eip-error__action">返回登入頁</a>
</div>
</div>
@endsection
