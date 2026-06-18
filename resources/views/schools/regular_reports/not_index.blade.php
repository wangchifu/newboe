@extends('layouts.app')

@section('title','資料填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>定期資料填報</h1>
    <div class="card mb-4">
        <div class="card-header">
            <a class="btn btn-light btn-sm" href="{{ route('posts.showSigned') }}">公告簽收 ({{ session('posts_not') }})</a>
            <a class="btn btn-light btn-sm" href="{{ route('school_report.index') }}">資料填報 ({{ session('reports_not') }})</a>
            <a class="btn btn-success btn-sm" href="{{ route('school_regular_report.index') }}">定期資料填報 ({{ session('regular_reports_not') }})</a>
        </div>
        <div class="card-body">                               
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_regular_report.index') }}">全部 ({{ session('regular_reports_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_regular_report_not.index') }}">未填報 ({{ session('regular_reports_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_regular_report.show_person_Signed') }}">個人已填報</a>
                </li>
            </ul>
            <div class="table-responsive">
                @include('schools.regular_reports.list')
            </div>                                            
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                <div class="text-center">
                    {{ $regular_report_schools->links('layouts.simple-pagination') }}
                </div>
            </div>                                         
        </div>
    </div>
</div>
@endsection