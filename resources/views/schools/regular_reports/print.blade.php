@extends('layouts.app_print')

@section('title','列印定期填報 | ')

@section('content')
<style>
        table {
            border: 2px solid ; border-collapse: collapse;
            margin: 5px;
            }
        tr,th,td {
                border: 2px solid ;
            }
        th,td{
                padding: 1px;
                font-size:14px;
            }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
            <table class="col-12">
                <tr>
                    <th nowrap width="80">
                        負責處室
                    </th>
                    <th>
                        資料填報主旨
                    </th>
                    <th nowrap width="60">
                        發佈人
                    </th>
                    <th nowrap width="80">
                        開炲日期<br>
                        截止日期
                    </th>                    
                </tr>
                @foreach($regular_report_schools as $regular_report_school)
                    <tr>
                        <td nowrap style="text-align: center; vertical-align: middle;">
                            教務 學務<br>
                            總務 輔導
                        </td>
                        <td>
                            <strong>【定期資料填報】</strong> 編號：{{ $regular_report_school->regular_report_id }}<br>
                            {{ $regular_report_school->regular_report->semester }} {{ $regular_report_school->regular_report->regular_sample->name }}
                        </td>
                        <td nowrap>
                            {{ $sections[$regular_report_school->regular_report->user->section_id] }}<br>{{ $regular_report_school->regular_report->user->name }}
                        </td>
                        <td nowrap style="text-align: center; vertical-align: middle;">
                            {{ $regular_report_school->regular_report->start_date }}<br>
                            {{ $regular_report_school->regular_report->die_date }}
                        </td>                        
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection