@auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let sessionLifetime = {{ config('session.lifetime') }};
            let remainingSeconds = sessionLifetime * 60;

            const timerEls = document.querySelectorAll('.session-timer');

            let warned = false; // 防止 alert 重複跳

            function formatTime(sec) {
                let m = Math.floor(sec / 60);
                let s = sec % 60;
                return `${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            }

            function updateTimer() {

                // **剩 30 秒跳出提醒**
                if (!warned && remainingSeconds === 30) {
                    warned = true;
                    sw_alert('提醒','您即將在 30 秒後登出！');
                }

                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    window.location.href = '/logout';
                } else {

                    timerEls.forEach(function (el) {
                        el.textContent = `剩餘時間: ${formatTime(remainingSeconds)} 後登出`;
                    });

                    remainingSeconds--;
                }
            }

            updateTimer();
            let timerInterval = setInterval(updateTimer, 1000);

        });         
    </script>
@endauth
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; 彰化縣教育處新雲端 {{ date('Y') }}</p></div>
    <div class="session-timer text-light text-center">剩餘時間: -- 後登出</div>
</footer>