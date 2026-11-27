@extends('layouts.app')

@section('title','學生基本資料')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_css')
<style>
    /* 只補 Bootstrap 沒有的：凍結表頭與前三欄、表格捲動箭頭、手機卡片 */
    #ss-app .ss-scroll { max-height: calc(100vh - 220px); overflow: auto; }
    #ss-app .ss-table { width: 100%; min-width: max-content; margin-bottom: 0; border-collapse: separate; border-spacing: 0; }
    #ss-app .ss-table th, #ss-app .ss-table td { text-align: center; white-space: nowrap; padding: .5rem; vertical-align: middle; }
    #ss-app .ss-table td.ss-name { text-align: left; }
    #ss-app .ss-table .br { border-right: 1px solid var(--bs-border-color); }
    #ss-app .ss-table thead th { position: sticky; z-index: 3; }
    #ss-app .ss-table thead tr:first-child th { top: 0; }
    #ss-app .ss-table thead tr:nth-child(2) th { top: var(--ss-head1, 2.5rem); }
    #ss-app .ss-table .sc1, #ss-app .ss-table .sc2, #ss-app .ss-table .sc3 { position: sticky; z-index: 2; background: var(--bs-body-bg); }
    #ss-app .ss-table thead .sc1, #ss-app .ss-table thead .sc2, #ss-app .ss-table thead .sc3 { z-index: 4; background: var(--bs-tertiary-bg); }
    #ss-app .ss-table tfoot td { background: var(--bs-secondary-bg); font-weight: 600; border-top: 2px solid var(--bs-border-color); }
    #ss-app .ss-table tbody tr:nth-child(even) td { background: var(--bs-tertiary-bg); }
    #ss-app .ss-table tbody tr:hover td { background: var(--bs-primary-bg-subtle); }
    #ss-app .ss-table th.ss-sort { cursor: pointer; user-select: none; }
    #ss-app .ss-arrow { position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 2.5rem; height: 2.5rem; border-radius: 50%; opacity: .8; }
    #ss-app .ss-arrow:hover { opacity: 1; }
    #ss-app details > summary { list-style: none; cursor: pointer; }
    #ss-app details > summary::-webkit-details-marker { display: none; }
    #ss-app details .ss-chev { transition: transform .15s; }
    #ss-app details[open] .ss-chev { transform: rotate(90deg); }
    #ss-app .ss-bucket { cursor: pointer; text-align: left; }
    #ss-app .ss-bucket.active { box-shadow: 0 0 0 .2rem currentColor; }
    #ss-app .nav-tabs .nav-link { cursor: pointer; }
</style>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1 class="text-center">學生基本資料</h1>
    <div id="ss-app" class="mb-4"
         data-data-url="{{ route('school_stats.data', '__KEY__') }}"
         data-export-url="{{ route('school_stats.export', '__KEY__') }}">
        <ul class="nav nav-tabs mb-4" id="ss-tabs"></ul>
        <div id="ss-body"><p class="text-secondary py-4 text-center">載入中…</p></div>
    </div>
</div>
@endsection

@section('my_js')
<script src="{{ asset('js/school_stats.js') }}?v={{ filemtime(public_path('js/school_stats.js')) }}"></script>
@endsection
