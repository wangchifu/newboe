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
                    1.建議本站使用彰化 GSuite 帳號登入，方能帶出正確職稱。<br>
                    2.學校若使用 OpenID 登入者，僅帶出職稱為「教師」。<br>
                    3.若帳密確定正確而無法登入，試著去「學籍系統」更改密碼看看，此動作會同步到認證的伺服器。<br>
                    4.學籍系統內的帳號必須填寫<span class="text-danger">正確</span>的身分證號碼。
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        新就任與離職問題
                    </h5>
                    1.新就任職員，請先於校務系統(cloudschool)設定完成後，登入本站以建立帳號或更新職稱。<br>
                    2.請具學校帳號管理權者，給予新就任職員適當的權限，找不到該職員，就是他沒有登入過本站。<br>
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
                    2.請該帳號先登入本系統，以建立系統內的帳號，再由有「帳號管理權」的帳號給予權限。<br>
                    3.若仍無法解決，請洽縣網中心。
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <h5>
                        有兼任其他學校的同仁（人事、幹事、會計...）
                    </h5>
                    1.該同仁須在兩所學校內的學籍系統各自建立帳號，使用不同密碼<br>
                    2.該同仁可以在本系統使用同一個 GSuite 帳號，<span class="text-danger">不同密碼</span>來切換兩所學校。<br>
                    3.請有帳號管理者至「學校帳號管理」<br>
                    4.先看「(1)本校帳號」有無該同仁帳號<br>
                    5.若無，則在「(2)他校兼任」欄中，輸入該同仁的 GSuite 進行權限給予。
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection