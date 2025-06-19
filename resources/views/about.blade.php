@extends('layouts.app')

@section('title','關於系統')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>關於系統</h1>
    <div class="card mb-4">
        <div class="card-header">系統沿革</div>
        <div class="card-body">
            <h3>我所知道的彰化縣「行政公告」系統</h3>
            <ul>
                <li>
                    猶記筆者剛進入學校工作時，學校時常要登入「教育資源網」，有時是為了研習報名，有時是看公告，那時行政最流行的一句話是「公告視同公文」。<br>
                    <figure class="figure">
                        <img src="{{ asset('images/about/01.png') }}" class="figure-img img-fluid rounded" alt="..." width="300px">
                        <figcaption class="figure-caption">教育資源網</figcaption>
                    </figure>              
                </li>
                <li>
                    約在2012年時，縣府委外架設「教育處雲端系統」，取代了「教育資源網」，上方橫幅用的是當時最流行的 Flash 動畫顯示雲的畫面。<br>
                    <figure class="figure">
                        <img src="{{ asset('images/about/02.png') }}" class="figure-img img-fluid rounded" alt="..." width="300px">
                        <figcaption class="figure-caption">教育處雲端系統</figcaption>
                    </figure>              
                </li>                        
                <li>
                    2019年時由縣網黃技士招集一群熱心的老師，自己動手寫出符合「教育處」及「學校端」收發公告需求的「新雲端」，用於取代上一代的「行政公告」雲端系統，「新雲端」於2020年上線。
                </li>
                <li>
                    委外的網站在初次招標、每年維運都可能要花費百萬以上，而我們自己寫網站，維護伺服器端，不管網站前端、後端、伺服端，都要一手包辦，毫無費用，除此之外，還要面對資安檢查的評比，這不是一件輕鬆且容易的工作，我們所秉持的只是想服務的心而已。
                </li>
                <li>
                    本網站設有「問題回報與建議」，對於教育伙伴的建議，能做到的都努力達成，希望能解決、減輕、完成各項教育行政工作，請多加利用。
                </li>
                <li>
                    本系統目前由和東國小資訊組長王老師負責維護，我不是專業的資訊公司，也不以這份工作維生，我只是一個老師。但是相信在維護網站上，我比任何一家資訊公司更努力，也不用收費。所以，當系統有什麼問題時，請給我一點時間解決，雖然我不是什麼都會，但想解決問題的心是堅定的。
                <br>
                    <figure class="figure">
                        <img src="{{ asset('images/about/03-1.png') }}" class="figure-img img-fluid rounded" alt="..." width="300px">
                        <figcaption class="figure-caption">教育處新雲端-介面1代</figcaption>
                    </figure>              
                    <figure class="figure">
                        <img src="{{ asset('images/about/03-2.png') }}" class="figure-img img-fluid rounded" alt="..." width="300px">
                        <figcaption class="figure-caption">教育處新雲端-介面2代</figcaption>
                    </figure>              
                    <figure class="figure">
                        <img src="{{ asset('images/about/03-3.png') }}" class="figure-img img-fluid rounded" alt="..." width="300px">
                        <figcaption class="figure-caption">教育處新雲端-介面3代</figcaption>
                    </figure>              
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection