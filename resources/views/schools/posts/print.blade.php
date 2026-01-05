@extends('layouts.app_print')

@section('title','列印公告 | ')

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
                    行政公告主旨
                </th>
                <th nowrap width="60">
                    發佈人
                </th>
            </tr>
            @foreach($posts as $post)
                    <tr>
                        <td nowrap style="text-align: center; vertical-align: middle;">
                            教務 學務<br>
                            總務 輔導
                        </td>
                        <td>
                            <strong>【行政公告】</strong> 編號：{{ $post->post_no }}<br>
                            {{ $post->title }}
                        </td>
                        <td nowrap style="text-align: center; vertical-align: middle;">
                            {{ array_get($sections,$post->section_id) }} / {{ $post -> name }}<br>
                            <span class="small">{{ substr($post->passed_at,0,10) }}</span>
                        </td>                        
                    </tr>
            @endforeach
        </table>
    </div>
</div>    
@endsection