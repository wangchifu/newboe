@extends('layouts.app_clean')

@section('title',$report->title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm my-3">
                    <div class="card-head">
                        <img class="card-img-top img-responsive" src="{{ asset('images/small/report.png') }}">
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edu_report.save_date_late',$report->id) }}" method="post" enctype="multipart/form-data" id="this_form" onsubmit="return false">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <h4>{{ $report->name }}</h4>
                            </div>
                            <div class="form-group">
                                <label for="die_date"><strong>截止日期從 {{ $report->die_date }} 改為*</strong></label>
                                <input type="date" id="die_date" name="die_date" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" value="{{ $report->die_date }}" style="width:250px;">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定？','this_form')">儲存</button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection