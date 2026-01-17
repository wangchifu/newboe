@auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        // 1. 取得初始設定（分轉毫秒）
        const sessionLifetimeMs = {{ config('session.lifetime') }} * 60 * 1000;
        
        // 2. 計算「目標過期時間點」：現在時間 + Session 長度
        // 這樣即使標籤頁休眠，Date.now() 永遠是準確的
        let expireTime = Date.now() + sessionLifetimeMs;

        const timerEls = document.querySelectorAll('.session-timer');
        let warned = false;

        function formatTime(ms) {
            let totalSeconds = Math.floor(ms / 1000);
            let m = Math.floor(totalSeconds / 60);
            let s = totalSeconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }

        function updateTimer() {
            const now = Date.now();
            const diff = expireTime - now; // 剩餘毫秒數

            // **剩 30 秒 (30000ms) 跳出提醒**
            if (!warned && diff <= 30000 && diff > 0) {
                warned = true;
                sw_alert('提醒', '您即將在 30 秒後登出！');
            }

            if (diff <= 0) {
                clearInterval(timerInterval);
                // 避免重複導向，確保只執行一次
                //window.location.href = '/logout';
                document.getElementById('logout-form').submit();                
            } else {
                const timeString = formatTime(diff);
                timerEls.forEach(function (el) {
                    el.textContent = `剩餘時間: ${timeString} 後登出`;
                });
            }
        }

        // 每秒檢查一次 (即使被節流延遲，計算結果依然會是準確的)
        updateTimer();
        let timerInterval = setInterval(updateTimer, 1000);

        // [進階] 如果使用者回來分頁，立即強制觸發一次檢查
        window.addEventListener('focus', updateTimer);
        });        
    </script>
@endauth
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; 彰化縣教育處新雲端 {{ date('Y') }}</p></div>
    @auth
        <div class="session-timer text-light text-center">剩餘時間: -- 後登出</div>
    @endauth
</footer>