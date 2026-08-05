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
                $bg_color[1] = "bg-success";
                $bg_color[2] = "bg-info";
                $bg_color[3] = "bg-secondary";
                $bg_color[4] = "bg-dark";
                $bg_color[5] = "bg-warning";                
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
                            <a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                                @if(!empty($images[$n]))                                
                                    <img class="card-img-top object-fit-cover" src="{{ asset('storage/post_photos/'.$post->id.'/'.$images[$n][0]) }}" style="width: 100%; height: 200px;">                                
                                @else
                                    <img class="card-img-top object-fit-cover" src="{{ asset('images/image.jpg') }}" style="width: 100%; height: 100px;">
                                @endif                            
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="small text-muted">{{ substr($post->passed_at,0,10) }} / <span class="badge {{ $bg_color[$post->category_id] }}">{{ $category_array[$post->category_id] }}</span> / {{ $post->views }}</div>
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
                            <a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                                @if(!empty($images[$n]))
                                    <img class="card-img-top object-fit-cover" src="{{ asset('storage/post_photos/'.$post->id.'/'.$images[$n][0]) }}" style="width: 100%; height: 200px;">
                                @else
                                    <img class="card-img-top object-fit-cover" src="{{ asset('images/image.jpg') }}" style="width: 100%; height: 100px;">
                                @endif                  
                            </a>          
                        </div>                        
                        <div class="card-body">
                            <div class="small text-muted">{{ substr($post->passed_at,0,10) }} / <span class="badge {{ $bg_color[$post->category_id] }}">{{ $category_array[$post->category_id] }}</span> / {{ $post->views }}</div>
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
    {{ $posts->links('layouts.simple-pagination') }}
</div>
<!-- Side widgets-->
<div class="col-lg-4">
    <!-- Search widget-->
    <div class="card mb-4">
        <div class="card-header">教育處相關</div>
        <div class="card-body">
                <!-- 左邊社群 icon -->
            <div class="social-marquee me-3" style="font-size: 24px;">
                <a href="https://education.chcg.gov.tw/00home/index02.aspx" target="_blank">
                    <i class="fa-solid fa-globe text-dark"></i>
                </a>
                <a href="https://www.facebook.com/boe.chc.edu/" target="_blank">
                    <i class="fa-brands fa-square-facebook text-primary"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" target="_blank">
                    <i class="fa-brands fa-youtube text-danger"></i>
                </a>
                <a href="{{ route('rss') }}" target="_blank">
                    <i class="fa-solid fa-square-rss" style="color: orange;"></i>
                </a>
            </div>
            <div class="input-group">
                <form action="{{ route('search') }}" class="search-form" method="get" target="_blank">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="want" placeholder="請輸入關鍵字" aria-label="want_word" aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa-brands fa-google"></i> 搜尋</button>
                    </div>
                </form>
                @include('layouts.errors')
            </div>
        </div>
    </div>
    @php
        // 1. 這是你定義的分類對應表
        $type_array = [
            1 => '1.教學類',
            2 => '2.藝文類',
            3 => '3.資訊類',
            4 => '4.防疫專區',
            5 => '5.行政單位',
        ];

        // 2. 為了符合 Bootstrap Accordion 的 id 規範（不建議使用純數字做 id），
        // 我們建立一個數字轉英文單字的對應表，用來動態生成 #collapseOne, #collapseFive 等識別碼。
        $id_map = [
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
        ];

        // 3. 把有分類的 $links 按照 type 進行分組
        $grouped_links = $links->groupBy('type');
    @endphp

    <div class="card mb-4">
        <div class="card-header">相關連結</div>
        <div class="card-body">
            <div class="accordion" id="accordionExample">
                
                {{-- 🔄 1. 跑迴圈：依序檢查你設定的 1 ~ 5 分類 --}}
                @foreach($type_array as $type_id => $type_name)
                    {{-- 安全防護：只有當資料庫裡「真的有該分類的連結」時，才渲染這個折疊 item --}}
                    @if($grouped_links->has($type_id))
                        @php 
                            // 取得對應的英文單字，萬一沒定義就用數字替代
                            $word_id = $id_map[$type_id] ?? $type_id; 
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $word_id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $word_id }}" aria-expanded="false" aria-controls="collapse{{ $word_id }}">
                                    {{-- 這裡會自動去除「1.」等數字前綴，只顯示「教學類」、「行政單位」 --}}
                                    {{ Str::after($type_name, '.') }}
                                </button>
                            </h2>
                            <div id="collapse{{ $word_id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $word_id }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <ul>
                                        {{-- 🔄 抓出該分類下的所有連結 --}}
                                        @foreach($grouped_links->get($type_id) as $link)
                                            <li><a href="{{ $link->url }}" target="_blank">{{ $link->name }}</a></li>
                                        @endforeach
                                    </ul>                    
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>      
            
            {{-- 🔄 2. 最下方獨立清單：渲染完全沒有 type 值 ($link2->type 為 null) 的 $link2s --}}
            @if($link2s->isNotEmpty())
                <ul class="mt-3">
                    @foreach($link2s as $link2)
                        <li><a href="{{ $link2->url }}" target="_blank">{{ $link2->name }}</a></li>
                    @endforeach
                </ul>
            @endif
                            
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
        </div>
    </div>
</div>
@endsection