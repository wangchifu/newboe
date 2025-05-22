document.addEventListener("DOMContentLoaded", function () {
    var dropdowns = document.querySelectorAll(".dropdown-submenu > a");

    dropdowns.forEach(function (dropdown) {
        dropdown.addEventListener("click", function (e) {
            e.preventDefault(); // 阻止連結跳轉
            let nextMenu = this.nextElementSibling;
            let isOpen = nextMenu.style.display === "block";

            // 先關閉所有子選單
            document.querySelectorAll(".dropdown-submenu .dropdown-menu").forEach(function (submenu) {
                submenu.style.display = "none";
            });

            // 切換當前子選單狀態
            if (!isOpen) {
                nextMenu.style.display = "block";
            }
            
            // 讓點擊的 dropdown-toggle 失去焦點，取消 :focus 狀態
            this.blur();

            e.stopPropagation(); // 避免事件冒泡
        });
    });

    // 點擊其他地方時，關閉所有子選單
    document.addEventListener("click", function () {
        document.querySelectorAll(".dropdown-submenu .dropdown-menu").forEach(function (submenu) {
            submenu.style.display = "none";
        });
    });
});


const backToTopBtn = document.getElementById("backToTop");

window.addEventListener("scroll", function () {
    if (window.scrollY > 300) {
        backToTopBtn.classList.add("show");
    } else {
        backToTopBtn.classList.remove("show");
    }
});

backToTopBtn.addEventListener("click", function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});