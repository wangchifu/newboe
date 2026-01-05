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
        @foreach($reports as $report)
            <tr>
                <td data-th="編號" class="td-number">
                    {{ $report->id }}
                </td>
                <td data-th="發佈人" nowrap>
                    {{ $report->user->name }}
                </td>
                <td data-th="名稱" class="td-title">
                    @if( $report->situation ===4)
                    <a href="{{ route('edu_report.show',$report->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span style="color:red">[填報作廢]</span>
                        <strike class="text-primary">
                            {{ $report->name }}
                        </strike></a>                
                    </a>
                    @else
                        <a href="{{ route('edu_report.show',$report->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span style="color:#000088">
                            {{ $report->name }}
                        </span>
                        </a>
                    @endif                
                </td>
                <td data-th="發佈日期" nowrap>
                    <small>{{ substr($report->created_at,0,16) }}</small>
                </td>
                <td data-th="截止日期" nowrap>
                    <small class="text-danger">{{ $report->die_date }}</small>
                    <br>
                    <a href="{{ route('edu_report.date_late',$report->id) }}" class="venobox badge bg-success" data-vbtype="iframe">延期</a>
                </td>
                <td data-th="狀態">
                    {{ $situation[$report->situation] }}
                    @if(date('Ymd') > str_replace('-','',$report->die_date))
                        <span class="text-danger">(已截止)</span>
                    @endif
                </td>
                <td data-th="動作">
                    @if($report->situation ==0)
                        <form id="resend{{ $report->id }}" action="{{ route('edu_report.resend',$report->id) }}" method="post">
                            @csrf
                            @method('patch')
                        </form>
                        <button class="btn btn-outline-primary btn-sm" onclick="sw_confirm2('您確定再次送審嗎?','resend{{ $report->id }}')";>再次送審</button>
                    @endif
                    @if($report->situation == -1 or $report->situation ==0)
                        <a href="{{ route('edu_report.edit',$report->id) }}" class="btn btn-outline-danger btn-sm">修改</a>
                        <button class="btn btn-outline-dark btn-sm" onclick="sw_confirm2('您確定刪除嗎?','del{{ $report->id }}')">刪除</button>
                        <form id="del{{ $report->id }}" action="{{ route('edu_report.destroy',$report->id) }}" method="post" onsubmit="return false">
                            @csrf
                            {{ method_field('DELETE') }}
                        </form>
                    @endif
                    @if($report->situation==3)
                        <a href="{{ route('edu_report.result',$report->id) }}" class="btn btn-info btn-sm">結果顯示</a>
                        <a href="#!" class="btn btn-outline-dark btn-sm" onclick="sw_confirm1('確定作廢？','{{ route('edu_report.obsolete',$report->id) }}')">作廢</a>                        
                    @endif
                    @if($report->situation==3 or $report->situation==4)                        
                        <a href="{{ route('edu_report.copy',$report->id) }}" class="btn btn-outline-primary btn-sm">
                            複製
                        </a>
                    @endif
                    @if(!empty($user_power->id))
                        <div style="float:left;margin-right: 5px">
                            <form action="{{ route('reports.return',$report->id) }}" method="post" id="return_form{{ $report->id }}" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <button class="btn btn-outline-success btn-sm" onclick="sw_confirm2('確定退回？','return_form{{ $report->id }}')">退回</button>
                            </form>
                        </div>
                        <div style="float:left;margin-right: 5px">
                            <form action="{{ route('reports.approve',$report->id) }}" method="post" id="approve_form{{ $report->id }}" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <span class="btn btn-outline-info btn-sm" onclick="sw_confirm4(this,'確定核准嗎？','approve_form{{ $report->id }}',null)">核准</span>
                            </form>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>