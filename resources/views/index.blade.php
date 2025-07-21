@extends('layouts.app')

@section('title','首頁')

@section('my_css')
<style>
    .social-marquee {
        display: flex;
        align-items: center;
        gap: 15px;
    }
</style>
@endsection

@section('header')
<header class="py-5 bg-light border-bottom mb-4">    
    <div class="container" style="margin-top: -50px;margin-bottom:-25px;">
        <div class="d-flex align-items-center my-2" style="height: 40px;overflow: hidden;">

            <!-- 左邊社群 icon -->
            <div class="social-marquee me-3" style="font-size: 30px;">
                <a href="https://education.chcg.gov.tw/00home/index02.aspx" target="_blank">
                    <i class="fa-solid fa-globe text-dark"></i>
                </a>
                <a href="https://www.facebook.com/boe.chc.edu/" target="_blank">
                    <i class="fa-brands fa-square-facebook text-primary"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" target="_blank">
                    <i class="fa-brands fa-youtube text-danger"></i>
                </a>
                <a href="#!" target="_blank">
                    <i class="fa-solid fa-square-rss" style="color: orange;"></i>
                </a>
            </div>

            <!-- 右邊公告跑馬燈 -->
            <div style="flex: 1; height: 100%;">
                <marquee behavior="scroll" direction="up" scrollamount="1" style="height: 100%;background-color: #fffbe6;">
                    @if(!empty(count($marquees)))
                        @foreach($marquees as $marquee)
                            <p class="mb-0" style="font-size: 20px; line-height: 40px; color: #6f4e37;">★ {{ $marquee->title }}</p>
                        @endforeach
                    @else
                        <p class="mb-0" style="font-size: 20px; line-height: 40px; color: #6f4e37;">★ 歡迎光臨 彰化縣教育處新雲端~~~~~~~</p>
                    @endif
                </marquee>
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
    <h1>最新公告
    @if(!empty($_GET['page']))
        <span class="text-primary">第 {{ $_GET['page'] }} 頁</span>
    @endif
    </h1>
    <!-- Featured blog post-->
    <div class="row">
        <?php $n = 1; ?>
        @foreach($posts as $post)
            <?php
                $images[$n] = get_files(storage_path('app/public/post_photos/' . $post->id));
            ?>
            @if($n<4)
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="position-relative">
                            <!-- 左上標籤 -->
                            <?php $page = (empty($_GET['page']))?0:$_GET['page']-1; ?>
                            <div class="position-absolute top-0 start-0 bg-info text-white px-2 py-1 fw-bold" style="font-size: 0.9rem; border-bottom-right-radius: 5px;">
                                {{ $page*13+$n }}
                            </div>
                            <!-- 圖片本體 -->
                            @if(!empty($images3[0]))
                                <img class="card-img-top object-fit-cover" src="{{ asset('storage/post_photos/'.$id3[0].'/'.$images3[0][0]) }}" style="width: 100%; height: 200px;">
                            @else
                                <img class="card-img-top object-fit-cover" src="{{ asset('images/image.jpg') }}" style="width: 100%; height: 100px;">
                            @endif                            
                        </div>
                        <div class="card-body">
                            <div class="small text-muted">{{ substr($post->passed_at,0,10) }} / <span class="badge bg-secondary">{{ $category_array[$post->category_id] }}</span> / {{ $post->views }}</div>
                            <a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;"><h2 class="card-title fw-bold">{{ $post->title }}</h2></a>
                            <p class="card-text" style="color: #000080;">{{ smart_truncate_clean($post->content,200) }}</p>
                            <a class="btn btn-primary venobox" href="{{ route('posts.show',$post->id) }}" data-vbtype="iframe">閱讀更多 →</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="position-relative">
                            <!-- 左上標籤 -->
                            <?php $page = (empty($_GET['page']))?0:$_GET['page']-1; ?>
                            <div class="position-absolute top-0 start-0 bg-info text-white px-2 py-1 fw-bold" style="font-size: 0.9rem; border-bottom-right-radius: 5px;">
                                {{ $page*13+$n }}
                            </div>
                            <!-- 圖片本體 -->
                            @if(!empty($images3[0]))
                                <img class="card-img-top object-fit-cover" src="{{ asset('storage/post_photos/'.$id3[0].'/'.$images3[0][0]) }}" style="width: 100%; height: 200px;">
                            @else
                                <img class="card-img-top object-fit-cover" src="{{ asset('images/image.jpg') }}" style="width: 100%; height: 100px;">
                            @endif                            
                        </div>                        
                        <div class="card-body">
                            <div class="small text-muted">{{ substr($post->passed_at,0,10) }} / <span class="badge bg-secondary">{{ $category_array[$post->category_id] }}</span> / {{ $post->views }}</div>
                            <a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;"><h2 class="card-title h4 fw-bold">{{ $post->title }}</h2></a>
                            <p class="card-text" style="color: #000080;">{{ smart_truncate_clean($post->content,200) }}</p>
                            <a class="btn btn-primary venobox" href="{{ route('posts.show',$post->id) }}" data-vbtype="iframe">閱讀更多 →</a>
                        </div>
                    </div>
                </div>
            @endif
            <?php $n++; ?>
        @endforeach
    </div>
    <!-- Nested row for non-featured blog posts-->    
    <!-- Pagination-->
    {{ $posts->links('layouts.pagination') }}
</div>
<!-- Side widgets-->
<div class="col-lg-4">
    <!-- Search widget-->
    <div class="card mb-4">
        <div class="card-header">搜尋本站</div>
        <div class="card-body">
            <div class="input-group">
                <form action="{{ route('search') }}" class="search-form" method="get" target="_blank">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="want" placeholder="請輸入2字元以上的關鍵字" aria-label="want_word" aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">搜尋</button>
                    </div>
                </form>
                @include('layouts.errors')
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
    <div class="card mb-4">
        <div class="card-header">相關連結</div>
        <div class="card-body">
            <ul>
                <li><a href="https://www.edu.tw" target="_blank">教育部</a></li>
                <li><a href="https://www.chcg.gov.tw/" target="_blank">彰化縣政府</a></li>
                <li><a href="https://education.chcg.gov.tw/00home/index02.aspx" target="_blank">彰化縣政府教育處</a></li>
                <li><a href="https://newboe.chc.edu.tw/introduction/organization/show/I" target="_blank">彰化縣教育網路中心</a></li>                                        
            </ul>
        </div>
    </div>
    <!-- Side widget-->
    <div class="card mb-4">
        <div class="card-header">教育行政單位連結</div>
        <div class="card-body">
            <ul>
                <li><a href="https://www.edu.tw" target="_blank">教育部</a></li>
                <li><a href="https://www.chcg.gov.tw/" target="_blank">彰化縣政府</a></li>
                <li><a href="https://education.chcg.gov.tw/00home/index02.aspx" target="_blank">彰化縣政府教育處</a></li>
                <li><a href="https://newboe.chc.edu.tw/introduction/organization/show/I" target="_blank">彰化縣教育網路中心</a></li>                                        
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
            <p class="fw-bold">教育處</p>
            <i class="fa-solid fa-location-dot text-danger"></i> 500 彰化市中山路二段416號<br>
            <i class="fa-solid fa-phone text-warning"></i> 電話：04-7222151
            <hr>
            <p class="fw-bold">教育網路中心</p>
            <i class="fa-solid fa-location-dot text-danger"></i> 彰化市中正路二段530號彰安國中實踐樓4F<br>
            <i class="fa-solid fa-phone-volume text-success"></i> 電話：04-7237182 
        </div>
    </div>
</div>
<script>
    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
        spinner: 'rotating-plane'
    });

    $(document).on('click', '.vbox-close', function() {
        vb.close();
    });

</script>
@endsection