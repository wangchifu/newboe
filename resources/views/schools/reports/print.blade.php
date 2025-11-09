@extends('layouts.app_print')

@section('title','列印填報 | ')

@section('content')
<style>
        table {
            border: 2px solid ; border-collapse: collapse;
            margin: 20px;
            }
        tr,th,td {
                border: 2px solid ;
            }
        th,td{
                padding: 10px;
            }
</style>
<div class="container-fluid">
    <div class="row">
            <table class="col-11">
                <tr>
                    <th nowrap width="100">
                        負責處室
                    </th>
                    <th>
                        資料填報主旨
                    </th>
                    <th nowrap width="80">
                        發佈人
                    </th>
                    <th nowrap width="80">
                        公告日期<br>
                        截止日期
                    </th>                    
                </tr>
                @foreach($report_schools as $report_school)
                    <tr>
                        <td nowrap>
                            教務 學務<br>
                            總務 輔導
                        </td>
                        <td>
                            <strong>【資料填報】</strong> 編號：{{ $report_school->report->id }}<br>
                            {{ $report_school->report->name }}
                        </td>
                        <td nowrap>
                            {{ $sections[$report_school->report->user->section_id] }}<br>{{ $report_school->report->user->name }}
                        </td>
                        <td nowrap>
                            {{ substr($report_school->report->created_at,0,10) }}<br>
                            {{ $report_school->report->die_date }}
                        </td>                        
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection