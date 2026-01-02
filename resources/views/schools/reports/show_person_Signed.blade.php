@extends('layouts.app')

@section('title','資料填報')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>資料填報</h1>
    <div class="card mb-4">
        <div class="card-header">
            <a class="btn btn-light btn-sm" href="{{ route('posts.showSigned') }}">公告簽收 ({{ session('posts_not') }})</a>
            <a class="btn btn-success btn-sm" href="{{ route('school_report.index') }}">資料填報 ({{ session('reports_not') }})</a>
        </div>
        <div class="card-body">                   
            @include('schools.reports.search_nav')
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_report.index') }}">全部 ({{ session('reports_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_report_not.index') }}">未填報 ({{ session('reports_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_report.show_person_Signed') }}">個人已填報</a>
                </li>
            </ul>
            <div class="table-responsive">
                <table class="table rwd-table table-hover">
                    <thead>
                    <tr>
                        <th nowrap>
                            編號
                        </th>
                        <th nowrap>
                            發佈時間<br>
                            截止日期
                        </th>
                        <th>
                            名稱
                        </th>
                        <th nowrap>
                            發佈人
                        </th>
                        <th nowrap>
                            狀態
                        </th>
                        <th nowrap>
                            動作
                        </th>
                        <th nowrap>
                            審核
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report_schools as $report_school)
                        <tr>
                            <td nowrap data-th="編號" class="td-number">
                                <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$report_school->code] }}">{{ $report_school->report_id }}</span>
                            </td>
                            <td nowrap data-th="日期">
                                <small>{{ substr($report_school->report->passed_at,0,16) }}</small>
                                <br>
                                <small class="text-danger">{{ $report_school->report->die_date }}</small>
                            </td>
                            <td data-th="名稱" style="color:#000000;" class="td-title">
                                <a href="{{ route('school_report.show',$report_school->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none;color:#000088;">
                                    @if($report_school->report->situation==3)
                                        {{ $report_school->report->name }}
                                    @endif
                                    @if($report_school->report->situation==4)
                                        <del>
                                            {{ $report_school->report->name }}
                                        </del>
                                        <span class="text-danger">(已作廢)</span>
                                    @endif
                                </a>
                            </td>
                            <td nowrap data-th="發佈人">
                                {{ $sections[$report_school->report->section_id] }}<br>
                                {{ $report_school->report->user->name }}
                            </td>
                            <td nowrap data-th="狀態">
                                @if($report_school->situation === null or $report_school->situation === 5)
                                    未填報
                                @elseif($report_school->situation == 1)
                                    <span class="text-info">校審中</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                @elseif($report_school->situation == 3)
                                    <span class="text-success">已通過</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                @elseif($report_school->situation == 4)
                                    <span class="text-danger">已不填報</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                @elseif($report_school->situation === 0)
                                    <span class="text-danger">已退回</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                @endif
                                @if(date('Ymd') > str_replace('-','',$report_school->report->die_date))
                                    <span class="text-danger">(已截止)</span>
                                @endif
                            </td>
                            <td nowrap data-th="動作">
                                @if($report_school->situation === null)
                                    @if(date('Ymd') <= str_replace('-','',$report_school->report->die_date))
                                        @if($report_school->report->situation != 4)
                                            <a href="javascript:open_report('{{ route('school_report.create',$report_school->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                                填報
                                            </a>
                                        @endif
                                    @endif
                                    <!--20230815
                                        <a href="{{ route('school_report.no_report',$report_school->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                            不填報
                                        </a>
                                    -->
                                @elseif($report_school->situation ===4)

                                @elseif($report_school->situation ===5)

                                @else
                                    <!--
                                    <a href="{{ route('school_report.show',$report_school->id) }}" class="btn btn-success btn-sm venobox" data-vbtype="iframe">
                                        查看
                                    </a>
                                    -->
                                @endif

                                @if($report_school->situation === 0)
                                    @if(date('Ymd') <= str_replace('-','',$report_school->report->die_date))
                                        @if($report_school->signed_user_id == auth()->user()->id or $report_school->review_user_id == auth()->user()->id or check_a_user(auth()->user()->code,auth()->user()->id))
                                            <a href="javascript:open_report('{{ route('school_report.edit',$report_school->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                                編輯
                                            </a>
                                        @endif
                                    @else
                                        <!--20230815
                                        <a href="{{ route('school_report.no_report',$report_school->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                            不填報
                                        </a>
                                        -->
                                    @endif
                                @endif
                                    <a href="{{ route('school_report.print',$report_school->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> <i class="fas fa-sort-amount-up"></i></a>
                            </td>
                            <td nowrap data-th="審核">
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                                    @if($report_school->situation === 1 and date('Ymd') <= str_replace('-','',$report_school->report->die_date) and $report_school->report->situation != 4)
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_report.back',$report_school->id) }}" method="post" id="back_form" onsubmit="return false">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定退回？','back_form')">退回</button>
                                            </form>
                                        </div>
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_report.passing',$report_school->id) }}" method="post" id="passing_form" onsubmit="return false">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-success btn-sm" onclick="sw_confirm2('確定通過？','passing_form')">通過</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if(($report_school->situation === 1 or $report_school->situation === 0) and date('Ymd') > str_replace('-','',$report_school->report->die_date))
                                        <form action="{{ route('school_report.delay',$report_school->id) }}" method="post" id="delay_form1" onsubmit="return false">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定已知逾期未交？','delay_form1')">已知逾期未交</button>
                                        </form>
                                    @endif
                                    @if(date('Ymd') > str_replace('-','',$report_school->report->die_date) and $report_school->situation === null)
                                        <form action="{{ route('school_report.delay',$report_school->id) }}" method="post" id="delay_form2" onsubmit="return false">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('行政公告及填報視同公文，請勿再缺交填報。','delay_form2')">已知逾期未交</button>
                                        </form>
                                    @endif
                            @endif
                            @if($report_school->situation === 3 or $report_school->situation === 0 or $report_school->situation === 5 or $report_school->situation === 6)
                                @if($report_school->situation === 5)
                                    已知未交<br>
                                @endif
                                @if($report_school->situation === 6)
                                    已知作廢<br>
                                @endif
                                @if($report_school->review_user_id)
                                    <small class="text-secondary">審:{{ $report_school->review_user->name }}</small>
                                @endif
                            @endif
                            @if($report_school->report->situation===4 and $report_school->situation != 6 and check_a_user(auth()->user()->code,auth()->user()->id))
                            <form action="{{ route('school_report.cancel',$report_school->id) }}" method="post" id="cancel_form" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定已知此填報作廢？','cancel_form')">已知作廢</button>
                            </form>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>                                            
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                <div class="text-center">
                    {{ $report_schools->links('layouts.simple-pagination') }}
                </div>
            </div>                                         
        </div>
    </div>
</div>
@endsection