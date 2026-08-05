@extends('layouts.app')

@section('title','常見問題')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>常見問題</h1>
    <div class="card mb-4">
        <div class="card-header">問題集</div>
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <h5>
                        登入問題
                    </h5>
                    1.本站使用彰化 EIP 帳號登入，無法登入均是 EIP 問題，請檢查密碼或是按 <a href="https://eip.chc.edu.tw/recovery-password" target="_blank" class="btn btn-warning">忘記密碼？</a>。<br>
                    2.使用 EIP 登入後，若帶出的職稱若不正確，請至右上角 <i class="fas fa-user"></i> 人像按鈕下拉，選擇「 <a href="https://newboe.chc.edu.tw/edit_title">變更職稱</a> 」修正。<br>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        教育處新進科員科長
                    </h5>
                    先確認他是否有 EIP 的帳號<br>
                    1.無 EIP 帳號，申請一個，EIP審核通過後，然後用 EIP 登入新雲端，再申請科室，由新雲端該該室管理員審核通過。<br>
                    2.有 EIP 帳號，可以登入 EIP，則直接登入新雲端申請科室即可，若為學校調府教師，請教育處科室管理員，直接加帳號進入科室。<br>
                    3.曾經有過 EIP 帳號，但目前無單位無法登入，請縣網中心系統管理員在新雲端「認證主機帳號」上面更改帳號資訊。
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        新就任與離職問題
                    </h5>
                    1.新就任職員，請先於 EIP 系統(cloudschool)設定完成後，<span class="text-danger">他本人要登入本站才能建立帳號</span>。<br>
                    2.請具學校帳號管理權者(至少有教務主任)，給予新就任教職員適當的權限，<span class="text-danger">找不到該職員，就是他沒有登入過本站</span>。<br>
                    3.離職者，請具學校帳號管理權者，紿他移除權限或停用。
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        學校端帳號沒有任何權限
                    </h5>
                    1.系統預設各校「校長」、「教務(導)主任」有帳號管理權。<br>
                    2.請該帳號先登入本系統，以建立系統內的帳號，再由已經有「帳號管理權」的帳號給予他權限。<br>
                    3.<strong>功能在「學校管理/學校帳號」 找到人員後按"編輯"，再給予對應的功能權限。</strong><br>
                    4.若仍無法解決，請洽縣網中心。
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        有兼任其他學校的同仁（人事、幹事、會計...）
                    </h5>                    
                    1.該同仁可以在本系統使用同一個 EIP 帳號，登入時選擇<span class="text-danger">不同學校</span>來切換兩所學校。<br>
                    2.其餘權限問題請看上一個說明。
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection