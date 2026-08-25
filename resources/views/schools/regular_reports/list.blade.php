                <table class="table rwd-table table-hover">
                    <thead>
                    <tr>
                        <th nowrap>
                            編號
                        </th>
                        <th nowrap>
                            對象
                        </th>
                        <th nowrap>
                            開始時間<br>
                            截止日期
                        </th>
                        <th>
                            學期 名稱
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
                    @foreach($regular_report_schools as $regular_report_school)
                        <tr>
                            <td data-th="編號" class="td-number">
                                <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$regular_report_school->code] }}">{{ $regular_report_school->regular_report_id }}</span>
                            </td>
                            <td data-th="對象" class="td-school-name">
                                <?php
                                    $school = \App\Models\School::where('code_no',$regular_report_school->code)->first();
                                ?>
                                {{ $school->school_name }}
                            </td>
                            <td nowrap data-th="日期">
                                <small>{{ $regular_report_school->regular_report->start_date }}</small>
                                <br>
                                <small class="text-danger">{{ $regular_report_school->regular_report->die_date }}</small>
                            </td>
                            <td data-th="名稱" style="color:#000000;" class="td-title">
                                <a href="{{ route('school_regular_report.show',$regular_report_school->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none;color:#000088;">
                                    @if($regular_report_school->regular_report->situation==3)
                                        {{ $regular_report_school->regular_report->semester }} {{ $regular_report_school->regular_report->regular_sample->name }}
                                    @endif
                                    @if($regular_report_school->regular_report->situation==4)
                                        <del>
                                            {{ $regular_report_school->regular_report->semester }} {{ $regular_report_school->regular_report->regular_sample->name }}
                                        </del>
                                        <span class="text-danger">(已作廢)</span>
                                    @endif
                                </a>
                            </td>
                            <td nowrap data-th="發佈人">
                                {{ $sections[$regular_report_school->regular_report->section_id] ?? '' }}<br>
                                {{ $regular_report_school->regular_report->user->name }}
                            </td>
                            <td nowrap data-th="狀態">
                                @if($regular_report_school->situation === null or $regular_report_school->situation === 5)
                                    未填報
                                @elseif($regular_report_school->situation == 1)
                                    <span class="text-info">校審中</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $regular_report_school->signed_user->name }}</small>
                                @elseif($regular_report_school->situation == 3)
                                    <span class="text-success">已通過</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $regular_report_school->signed_user->name }}</small>
                                @elseif($regular_report_school->situation == 4)
                                    <span class="text-danger">已不填報</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $regular_report_school->signed_user->name }}</small>
                                @elseif($regular_report_school->situation === 0)
                                    <span class="text-danger">已退回</span>
                                    <br>
                                    <small class="text-secondary">填:{{ $regular_report_school->signed_user->name }}</small>
                                @endif
                                @if(date('Ymd') < str_replace('-','',$regular_report_school->regular_report->start_date))
                                    <span class="text-danger">(非填報日期)</span>
                                @endif
                                @if(date('Ymd') > str_replace('-','',$regular_report_school->regular_report->die_date))
                                    <span class="text-danger">(已逾期)</span>
                                @endif
                                
                            </td>
                            <td nowrap data-th="動作">
                                @if($regular_report_school->situation === null)
                                    @if(date('Ymd') <= str_replace('-','',$regular_report_school->regular_report->die_date) and date('Ymd') >= str_replace('-','',$regular_report_school->regular_report->start_date))
                                        @if($regular_report_school->regular_report->situation != 4)
                                            <a href="{{ route('school_regular_report.create',$regular_report_school->id) }}" class="btn btn-primary btn-sm venobox" data-vbtype="iframe">
                                                填報
                                            </a>
                                        @endif
                                    @endif                                    
                                @elseif($regular_report_school->situation ===4)

                                @elseif($regular_report_school->situation ===5)

                                @else
                                    
                                @endif

                                @if($regular_report_school->situation === 0)
                                    @if(date('Ymd') <= str_replace('-','',$regular_report_school->regular_report->die_date) and date('Ymd') >= str_replace('-','',$regular_report_school->regular_report->start_date))
                                        @if($regular_report_school->signed_user_id == auth()->user()->id or $regular_report_school->review_user_id == auth()->user()->id or check_a_user(auth()->user()->code,auth()->user()->id))
                                            <a href="{{ route('school_regular_report.edit',$regular_report_school->id) }}" class="btn btn-outline-primary btn-sm venobox" data-vbtype="iframe">
                                                編輯
                                            </a>
                                        @endif
                                    @else

                                    @endif
                                @endif
                                    <a href="{{ route('school_regular_report.print',$regular_report_school->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> <i class="fas fa-sort-amount-up"></i></a>
                            </td>
                            <td nowrap data-th="審核">
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                                    @if($regular_report_school->situation === 1 and date('Ymd') <= str_replace('-','',$regular_report_school->regular_report->die_date) and date('Ymd') >= str_replace('-','',$regular_report_school->regular_report->start_date) and $regular_report_school->regular_report->situation != 4)
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_regular_report.back',$regular_report_school->id) }}" method="post" id="back_form{{ $regular_report_school->id }}" onsubmit="return false">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定退回？','back_form{{ $regular_report_school->id }}')">退回</button>
                                            </form>
                                        </div>
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_regular_report.passing',$regular_report_school->id) }}" method="post" id="passing_form{{ $regular_report_school->id }}" onsubmit="return false">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-success btn-sm" onclick="sw_confirm2('確定通過？','passing_form{{ $regular_report_school->id }}')">通過</button>
                                            </form>
                                        </div>                                                                               
                                    @endif
                                    @if(($regular_report_school->situation === 1 or $regular_report_school->situation === 0) and date('Ymd') > str_replace('-','',$regular_report_school->regular_report->die_date))
                                        <form action="{{ route('school_regular_report.delay',$regular_report_school->id) }}" method="post" id="delay_form1{{ $regular_report_school->id }}" onsubmit="return false">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定已知逾期未交？','delay_form1{{ $regular_report_school->id }}')">已知逾期未交</button>
                                        </form>
                                    @endif
                                    @if(date('Ymd') > str_replace('-','',$regular_report_school->regular_report->die_date) and $regular_report_school->situation === null)
                                        <form action="{{ route('school_regular_report.delay',$regular_report_school->id) }}" method="post" id="delay_form2{{ $regular_report_school->id }}" onsubmit="return false">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('行政公告及填報視同公文，請勿再缺交填報。','delay_form2{{ $regular_report_school->id }}')">已知逾期未交</button>
                                        </form>
                                    @endif
                            @endif
                            @if($regular_report_school->situation === 3 or $regular_report_school->situation === 0 or $regular_report_school->situation === 5 or $regular_report_school->situation === 6)
                                @if($regular_report_school->situation === 5)
                                    已知未交<br>
                                @endif
                                @if($regular_report_school->situation === 6)
                                    已知作廢<br>
                                @endif
                                @if($regular_report_school->review_user_id)
                                    <small class="text-secondary">審:{{ $regular_report_school->review_user->name }}</small>
                                @endif
                            @endif
                            @if($regular_report_school->regular_report->situation===4 and $regular_report_school->situation != 6 and check_a_user(auth()->user()->code,auth()->user()->id))
                            <form action="{{ route('school_regular_report.cancel',$regular_report_school->id) }}" method="post" id="cancel_form{{ $regular_report_school->id }}" onsubmit="return false">
                                @csrf
                                @method('patch')
                                <button class="btn btn-outline-danger btn-sm" onclick="sw_confirm2('確定已知此填報作廢？','cancel_form{{ $regular_report_school->id }}')">已知作廢</button>
                            </form>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>