<div class="table-responsive">
    <table class="table rwd-table table-hover" style="word-break: break-all;">
        <thead class="thead-light">
        <tr>
            <th nowrap>
                編號
            </th>
            <th nowrap>
                發佈人
            </th>
            <th nowrap>
                名稱
            </th>
            <th nowrap>
                創建時間
            </th>
            <th nowrap>
                開始日期<br>
                截止日期
            </th>
            <th nowrap>
                狀態
            </th>
            <th nowrap>
                動作
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($regular_reports as $regular_report)
            <tr>
                <td data-th="編號" class="td-number">
                    {{ $regular_report->id }}
                </td>
                <td data-th="發佈人" nowrap>
                    {{ $regular_report->user->name }}
                </td>
                <td data-th="名稱" class="td-title">
                    <spna class="badge bg-danger">定期填報</spna><br>
                    @if( $regular_report->situation ===4)
                    <a href="{{ route('edu_regular_report.show', $regular_report->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span style="color:red">[填報作廢]</span>
                        <strike class="text-primary">
                            {{ $regular_report->semester }} {{ $regular_report->regular_sample->name }}
                        </strike></a>                
                    </a>
                    @else
                        <a href="{{ route('edu_regular_report.show', $regular_report->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span style="color:#000088">
                            {{ $regular_report->semester }} {{ $regular_report->regular_sample->name }}
                        </span>
                        </a>
                    @endif                
                </td>
                <td data-th="發佈日期" nowrap>
                    <small>{{ substr($regular_report->created_at,0,16) }}</small>
                </td>
                <td data-th="截止日期" nowrap>
                    <small class="text-danger">{{ $regular_report->start_date }}</small><br>
                    <small class="text-danger">{{ $regular_report->die_date }}</small>
                    <br>
                    <a href="{{ route('edu_regular_report.date_late',$regular_report->id) }}" class="venobox badge bg-success" data-vbtype="iframe">延期</a>
                </td>
                <td data-th="狀態">
                    {{ $situation[$regular_report->situation] }}
                    @if(date('Ymd') > str_replace('-','',$regular_report->die_date))
                        <span class="text-danger">(已截止)</span>
                    @endif
                </td>
                <td data-th="動作">
                    @if($regular_report->situation ==0)
                        <form id="resend{{ $regular_report->id }}" action="{{ route('regular_report.resend',$regular_report->id) }}" method="post">
                            @csrf
                            @method('patch')
                        </form>
                        <button class="btn btn-outline-primary btn-sm" onclick="sw_confirm2('您確定再次送審嗎?','resend{{ $regular_report->id }}')";>再次送審</button>
                    @endif
                    @if($regular_report->situation == -1 or $regular_report->situation ==0)
                        <a href="{{ route('edu_regular_report.edit_by_sample',$regular_report->id) }}" class="btn btn-outline-danger btn-sm">修改</a>
                        <button class="btn btn-outline-dark btn-sm" onclick="sw_confirm2('您確定刪除嗎?','del{{ $regular_report->id }}')">刪除</button>
                        <form id="del{{ $regular_report->id }}" action="{{ route('edu_regular_report.delete_by_sample',$regular_report->id) }}" method="post" onsubmit="return false">
                            @csrf
                            {{ method_field('DELETE') }}
                        </form>
                    @endif
                    @if($regular_report->situation==3)
                        <a href="{{ route('edu_regular_report.result',$regular_report->id) }}" class="btn btn-info btn-sm">結果顯示</a>
                        <a href="#!" class="btn btn-outline-dark btn-sm" onclick="sw_confirm1('確定作廢？','{{ route('edu_regular_report.obsolete',$regular_report->id) }}')">作廢</a>                        
                    @endif                    
                    @if($regular_report->situation==3 or $regular_report->situation==4)                        
                    <!--
                        <a href="" class="btn btn-outline-primary btn-sm">
                            複製
                        </a>
                    -->
                    @endif                    
                    @if(!empty($user_power->id))
                        <div style="float:left;margin-right: 5px">
                            <form action="{{ route('regular_reports.return',$regular_report->id) }}" method="post" id="return_form{{ $regular_report->id }}" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <button class="btn btn-outline-success btn-sm" onclick="sw_confirm2('確定退回？','return_form{{ $regular_report->id }}')">退回</button>
                            </form>
                        </div>
                        <div style="float:left;margin-right: 5px">
                            <form action="{{ route('regular_reports.approve',$regular_report->id) }}" method="post" id="approve_form{{ $regular_report->id }}" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <span class="btn btn-outline-info btn-sm" onclick="sw_confirm4(this,'確定核准嗎？','approve_form{{ $regular_report->id }}',null)">核准</span>
                            </form>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach        
        </tbody>
    </table>
</div>