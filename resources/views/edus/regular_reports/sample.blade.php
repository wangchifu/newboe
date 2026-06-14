@extends('layouts.app_clean')

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $regular_sample->name }}</h1>    
    @include('edus.regular_reports.sample_'.$sample_num)    
</div>
                                        
@endsection