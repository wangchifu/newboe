@extends('layouts.app')

@section('title','學校簡介')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1 class="text-center">{{ $school_web[$code_no]['school'] }}</h1>
    <div class="card mb-4">
        <div class="card-header">簡介</div>
        <div class="card-body">
            <div class="col-12 mt3">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        @if(!empty($school_introduction))
                                            @if(file_exists(storage_path('app/public/school_introductions/'. $code_no.'/'.$school_introduction->pic1)) and $school_introduction->pic1)
                                                <div class="text-left">
                                                    <img src ="{{ asset('storage/school_introductions/'. $code_no.'/'.$school_introduction->pic1) }}" class="col-12">
                                                </div>          
                                            @endif
                                        @else
                                            <img src="{{ asset('images/working1.png') }}">
                                        @endif
                                    </div>							
                                    <div class="col-12" style="margin: 10px;">
                                        @if(!empty($introduction1))
                                                {!! $introduction1 !!}												
                                        @endif
                                    </div>                                    
                                </div>
                            </div>						
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        @if(!empty($school_introduction))
                                            @if(file_exists(storage_path('app/public/school_introductions/'. $code_no.'/'.$school_introduction->pic2)) and $school_introduction->pic2)
                                                <div class="text-left">
                                                    <img src ="{{ asset('storage/school_introductions/'. $code_no.'/'.$school_introduction->pic2) }}" class="col-12">
                                                </div>          
                                            @endif
                                        @else
                                            <img src="{{ asset('images/working2.png') }}">
                                        @endif		
                                    </div>
                                    <div class="col-12" style="margin: 10px;">
                                        @if(!empty($introduction2))
                                        {!! $introduction2 !!}												
                                        @endif
                                    </div>
                                    <div class="col-12" style="margin: 10px;">
                                        <table>
                                            <tr><td>
                                            學校基本資料連結：<a href="https://school.chc.edu.tw/user/basic/view/school/{{ $code_no }}" target="_blank" class="btn btn-success btn-sm">{{ $school_web[$code_no]['school'] }}</a>
                                            </td></tr>
                        
                                            <tr>
                                                <td>
                                            @if(!empty($website))
                                                學校網站：<a href="https://{{ $website }}" target="_blank" class="btn btn-success btn-sm">連結</a>
                                            @else
                                                學校網站：<a href="https://{{ $school_web[$code_no]['website'] }}" target="_blank" class="btn btn-success btn-sm">連結</a>
                                            @endif
                                            </td>
                                            </tr>
                                            
                                            @if(!empty($facebook))
                                            <tr><td>
                                                FB 粉絲團：<a href="https://{{ $facebook }}" target="_blank" class="btn btn-success btn-sm">連結</a>
                                                </td></tr>
                                            @endif
                        
                                            @if(!empty($wiki))
                                            <tr><td>
                                                維基百科介紹：<a href="https://{{ $wiki }}" target="_blank" class="btn btn-success btn-sm">連結</a>	</td></tr>
                                        @endif
                                        </table>
                                    </div>
                                </div>
                            </div>																																																
                        </div>
                    </div>										
                </div>
            </div>           
        </div>
    </div>
</div>
@endsection