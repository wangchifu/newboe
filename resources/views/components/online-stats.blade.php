<div class="d-flex flex-wrap align-items-center justify-content-center gap-3 text-white small">
    <!-- 所有人皆可看的線上人數統計數字 -->
    <div>
        <span>🌐 目前線上：<strong class="text-white">{{ $stats['total'] }}</strong> 人</span>
        <span class="mx-1">|</span>
        <span>👤 會員：<strong class="text-info">{{ $stats['logged_in'] }}</strong></span>
        <span class="mx-1">|</span>
        <span>👀 訪客：<strong class="text-light">{{ $stats['guest'] }}</strong></span>
    </div>

    <!-- 🔑 只有登入且為「系統管理者」時才顯示會員下拉選單 -->
    @auth
        @if((auth()->user()->group_id == 9 || auth()->user()->admin == 1) && !empty($stats['online_users']) && $stats['online_users']->isNotEmpty())
            <div style="min-width: 240px; max-width: 320px;">
                <select class="form-select form-select-sm bg-dark text-white-50 border-secondary" aria-label="線上會員名單">
                    <option selected disabled>🔍 查看線上會員名單 ({{ $stats['logged_in'] }} 人)</option>
                    @foreach($stats['online_users'] as $user)
                        <option disabled>
                            {{ $user->name }} ({{ $user->username }}) 
                            - {{ $user->school ?? '未填寫學校' }} 
                            {{ $user->title ? '[' . $user->title . ']' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    @endauth
</div>