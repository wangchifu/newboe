<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container d-flex align-items-center">        
        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('images/banner.png') }}" style="height: 30px;">            
        </a>
        <?php
        //不用一直查詢選單            
            if (!session('menus')) {
                $menus= \App\Models\Menu::where('belong', '0')->orderBy('order_by')->orderBy('type')->get();                 
                session(['menus' => $menus]);              
            }                                          
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                {{ get_menu(session('menus'),0) }}                
                <!--
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Dropdown link
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                     
                        <li class="dropdown-submenu">
                        <a class="dropdown-item dropdown-toggle" href="#">More Options</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Submenu Item 1</a></li>
                            <li><a class="dropdown-item" href="#">Submenu Item 2</a></li>
                            <li><a class="dropdown-item" href="#">Submenu Item 3</a></li>
                        </ul>
                        </li>
                    </ul>
                </li>
                -->
                @guest
                    <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('glogin') }}">登入</a></li>
                @endguest
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#!" onclick="sw_alert('Hi~你好~')">{{ auth()->user()->school }}{{ auth()->user()->name }}</a></li>                            
                        @if(auth()->user()->group_id=="8" or auth()->user()->group_id=="9")
                            <li><a class="dropdown-item" href="{{ route('edit_password') }}">更改密碼</a></li>
                            <li class="dropdown-divider"></li>
                        @endif
                        @if(auth()->user()->group_id==1)
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                            <li><a class="dropdown-item" href="{{ route('school_acc.index') }}">學校帳號管理</a></li>
                            <li><a class="dropdown-item" href="{{ route('school_introduction.index') }}">學校簡介</a></li>
                            @endif
                            @if(check_b_user(auth()->user()->code,auth()->user()->id))
                            <li><a class="dropdown-item" href="{{ route('posts.showSigned') }}">公告簽收</a></li>
                            <li><a class="dropdown-item" href="{{ route('school_report.index') }}">資料填報</a></li>
                            <li class="dropdown-divider"></li>
                            @endif
                        @endif
                        @if(auth()->user()->other_code)
                            <li><a class="dropdown-item" href="{{ route('posts.people_other') }}">其他單位人員管理</a></li>
                            <li><a class="dropdown-item" href="{{ route('posts.showSigned_other') }}">其他單位公告簽收</a></li>
                            <li class="dropdown-divider"></li>
                        @endif
                        @if((auth()->user()->group_id == 2 or !empty(auth()->user()->section_id)) and auth()->user()->group_id !=8)
                            <li><a class="dropdown-item" href="{{ route('posts.reviewing') }}">公告系統</a></li>
                            <li><a class="dropdown-item" href="{{ route('edu_report.index') }}">填報系統</a></li>
                            <li class="dropdown-divider"></li>
                            @if(!empty(auth()->user()->section_id))
                                <?php
                                $num = [
                                    'A' => 1,
                                    'B' => 2,
                                    'C' => 3,
                                    'D' => 4,
                                    'E' => 5,
                                    'F' => 6,
                                    'G' => 7,
                                    'H' => 8,
                                    'I' => 9,
                                    'J' => 7019,
                                ];
                                ?>
                                <li><a class="dropdown-item" href="{{ route('introductions.upload','&'.$num[auth()->user()->section_id]) }}">檔案上傳</a></li>
                                <li><a class="dropdown-item" href="{{ route('marquees.index') }}">跑馬燈系統</a></li>
                                <li class="dropdown-divider"></li>
                            @endif
                        @endif
                        <?php
                        if (!session('user_power')) {
                          $user_power = \App\Models\UserPower::where('user_id', auth()->user()->id)
                            ->where('power_type', 'A')
                            ->first();
                          session(['user_power' => $user_power]);
                        }                            
                        ?>
                        @if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty(session('user_power'))))                            
                            <li><a class="dropdown-item" href="{{ route('introductions.organization') }}">科室頁面管理</a></li>
                            <li><a class="dropdown-item" href="{{ route('my_section.admin') }}">科室成員管理</a></li>
                            <li class="dropdown-divider"></li>
                        @endif
                        @if(auth()->user()->group_id=="8" or auth()->user()->group_id=="9" or auth()->user()->admin=="1" or (!empty(auth()->user()->section_id) and !empty(session('user_power'))))
                            <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">站台管理</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('title_image_index') }}">橫幅廣告</a></li>
                                <li><a class="dropdown-item" href="{{ route('menu_index') }}">選單連結</a></li>
                                <li><a class="dropdown-item" href="{{ route('contents.index') }}">內容管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('photo_albums.index') }}">相簿管理</a></li>
                            </ul>
                            </li>                                                                    
                            <li class="dropdown-divider"></li>
                        @endif
                        @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                            <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">系統管理</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admins.user_index') }}">帳號管理</a></li>                            
                                <li><a class="dropdown-item" href="{{ route('admins.introduction_index') }}">教育處介紹管理</a></li>                        
                                <li><a class="dropdown-item" href="{{ route('admins.other_index') }}">其他連結</a></li>
                                <!--
                                <li><a class="dropdown-item" href="">特殊處理</a></li>
                                -->
                                <li><a class="dropdown-item" href="{{ route('logs') }}">log 記錄</a></li>
                                <!--
                                <li><a class="dropdown-item" href="">系統公告</a></li>
                                -->
                                <li><a class="dropdown-item" href="{{ route('close') }}">關閉系統</a></li> 
                            </ul>
                            </li>                                                       
                            <li class="dropdown-divider"></li>
                        @endif
                        @impersonating                            
                            <li><a class="dropdown-item" href="#!" onclick="sw_confirm1('確定結束模擬？','{{ route('sims.impersonate_leave') }}')">結束模擬</a></li>
                            <li class="dropdown-divider"></li>
                        @endImpersonating
                            <li><a class="dropdown-item" href="{{ route('logout') }}">登出系統</a></li>
                        </ul>
                    </li>                    
                @endauth
            </ul>
        </div>
    </div>
</nav>