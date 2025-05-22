@extends('layouts.app')

@section('title','首頁')

@section('my_css')
<style>
    .header-marquee {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .social-marquee {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .social-links {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 5px;
    }

    .marquee {
        position: relative;
        width: 100%; /* 跑馬燈的寬度，根據需要調整 */
        height: 30px; /* 可根據文字高度調整 */
        overflow: hidden;
        white-space: nowrap;
    }

    .marquee span {
        position: absolute;
        left: 100%;
        color: red;
        background: transparent;
        font-size: 20px;
        animation: marquee-scroll 30s linear infinite;
    }

    @keyframes marquee-scroll {
        from {
            left: 100%;
        }
        to {
            left: -100%;
        }
    }
</style>
@endsection

@section('header')
<header class="py-5 bg-light border-bottom mb-4">    
    <div class="container" style="margin-top: -50px;margin-bottom:-25px;">
        <div class="social-marquee" style="font-size:25px;">
            <a href="https://education.chcg.gov.tw/00home/index02.aspx" target="_blank"><i class="fa-solid fa-globe text-dark"></i></a>
            <a href="https://www.facebook.com/boe.chc.edu/" target="_blank"><i class="fa-brands fa-square-facebook text-primary"></i></a>
            <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" target="_blank"><i class="fa-brands fa-youtube text-danger"></i></a>
            <a href="#!" target="_blank"><i class="fa-solid fa-square-rss" style="color:orange"></i></a>
            <div class="marquee">
                <span>歡迎來到我的網站！這是一個 HTML + CSS 的跑馬燈示例。</span>
            </div>
        </div>
        @if($title_images->count() > 0)
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                @php $n=0; @endphp
                @foreach($title_images as $title_image)
                    @if($n==0)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide {{ $n+1 }}"></button>
                    @else
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $n }}" aria-label="Slide {{ $n+1 }}"></button>
                    @endif
                    @php $n++; @endphp                
                @endforeach
                </div>
                <div class="carousel-inner">                    
                    @php $n=0; @endphp
                    @foreach($title_images as $title_image)
                        @if($n==0)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="d-block w-100" alt="...">
                            </div>
                        @else
                            <div class="carousel-item">
                                <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="d-block w-100" alt="...">
                            </div>
                        @endif        
                        @php $n++; @endphp                
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">上一張</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">下一張</span>
                </button>
            </div>
        @endif
    </div>
</header>
@endsection

@section('content')
<div class="col-lg-8">
    <h1>最新公告</h1>
    <!-- Featured blog post-->
    <div class="card mb-4">
        <a href="#!"><img class="card-img-top" src="https://dummyimage.com/850x350/dee2e6/6c757d.jpg" alt="..." /></a>
        <div class="card-body">
            <div class="small text-muted">January 1, 2023</div>
            <h2 class="card-title">Featured Post Title</h2>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>
            <a class="btn btn-primary" href="#!">Read more →</a>
        </div>
    </div>
    <!-- Nested row for non-featured blog posts-->
    <div class="row">
        <div class="col-lg-6">
            <!-- Blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">January 1, 2023</div>
                    <h2 class="card-title h4">Post Title</h2>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                    <a class="btn btn-primary" href="#!">Read more →</a>
                </div>
            </div>
            <!-- Blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">January 1, 2023</div>
                    <h2 class="card-title h4">Post Title</h2>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                    <a class="btn btn-primary" href="#!">Read more →</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">January 1, 2023</div>
                    <h2 class="card-title h4">Post Title</h2>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla.</p>
                    <a class="btn btn-primary" href="#!">Read more →</a>
                </div>
            </div>
            <!-- Blog post-->
            <div class="card mb-4">
                <a href="#!"><img class="card-img-top" src="https://dummyimage.com/700x350/dee2e6/6c757d.jpg" alt="..." /></a>
                <div class="card-body">
                    <div class="small text-muted">January 1, 2023</div>
                    <h2 class="card-title h4">Post Title</h2>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam.</p>
                    <a class="btn btn-primary" href="#!">Read more →</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Pagination-->
    <nav aria-label="Pagination">
        <hr class="my-0" />
        <ul class="pagination justify-content-center my-4">
            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Newer</a></li>
            <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
            <li class="page-item"><a class="page-link" href="#!">2</a></li>
            <li class="page-item"><a class="page-link" href="#!">3</a></li>
            <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
            <li class="page-item"><a class="page-link" href="#!">15</a></li>
            <li class="page-item"><a class="page-link" href="#!">Older</a></li>
        </ul>
    </nav>
</div>
<!-- Side widgets-->
<div class="col-lg-4">
    <!-- Search widget-->
    <div class="card mb-4">
        <div class="card-header">搜尋公告</div>
        <div class="card-body">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="關鍵字" aria-label="Enter search term..." aria-describedby="button-search" />
                <button class="btn btn-primary" id="button-search" type="button">Go!</button>
            </div>
        </div>
    </div>
    <!-- Categories widget-->
    <div class="card mb-4">
        <div class="card-header">分類公告</div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <ul class="list-unstyled mb-0">
                        <li><a href="#!">一般公告</a></li>
                        <li><a href="#!">競賽訊息</a></li>                        
                    </ul>
                </div>
                <div class="col-sm-6">
                    <ul class="list-unstyled mb-0">
                        <li><a href="#!">活動成果</a></li>
                        <li><a href="#!">新聞快訊</a></li>                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Side widget-->
    <div class="card mb-4">
        <div class="card-header">教育行政單位連結</div>
        <div class="card-body">
            <ul>
                <li><a href="https://www.edu.tw" target="_blank">教育部</a></li>
                <li>彰化縣政府</li>
                <li>彰化縣政府教育處</li>
                <li>彰化縣教育網路中心</li>
                <li>彰化學校資料平台</li>
                <li>彰化 GSuite 平台</li>
                <li>全國教保資訊網</li>
                <li>彰化縣政府</li>
            </ul>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">其他連結</div>
        <div class="card-body">
            <ul>
                @foreach($others as $other)
                    <li>
                        <a href="{{ $other->url }}" target="_blank">{{ $other->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">聯絡資訊</div>
        <div class="card-body">
            <i class="fa-solid fa-location-dot text-danger"></i> 彰化市中山路二段416號<br>
            <i class="fa-solid fa-phone text-warning"></i> 教育處：04-7222151 <br>
            <i class="fa-solid fa-phone-volume text-success"></i> 教育網路中心：04-7237182 
        </div>
    </div>
</div>
@endsection