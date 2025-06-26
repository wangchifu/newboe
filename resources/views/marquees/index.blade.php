@extends('layouts.app')

@section('title','跑馬燈')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>跑馬燈</h1>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fa-solid fa-plus text-primary"></i> 新增跑馬燈(按一下)
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form action="{{ route('marquees.store') }}" method="post" id="marquee_form">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">標題*(50字內)</label>
                        <input type="text" name="title" class="form-control" required maxlength="50">
                    </div>
                    <div class="form-group mb-3">
                        <label for="url">開始日期*</label>
                        <input type="date" id="start_date" name="start_date" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" width="250">
                    </div>
                    <div class="form-group mb-3">
                        <label for="url">結束日期*</label>
                        <input type="date" id="stop_date" name="stop_date" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" width="250">
                    </div>
                    <div class="form-group mb-3">
                        <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定儲存嗎？','marquee_form')">
                            <i class="fas fa-save"></i> 儲存設定
                        </a>
                    </div>                    
                </form>                                 
              </div>
            </div>
        </div>          
    </div>
    <br>
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">            
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="thead-light">
                <tr>
                    <th>id</th>
                    <th>標題</th>
                    <th>開始日期</th>
                    <th>結束日期</th>
                    <th>上架者</th>
                    <th>動作</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0;$j=0; ?>
                @foreach($marquees as $marquee)
                    <tr>
                        <td>
                            {{ $marquee->id }}
                        </td>
                        <td>
                            {{ $marquee->title }}
                        </td>
                        <td>
                            {{ $marquee->start_date }}
                        </td>
                        <td>
                            {{ $marquee->stop_date }}
                        </td>
                        <td>
                            {{ $marquee->user->name }}
                        </td>
                        <td>
                            @if($marquee->user_id == auth()->user()->id)                                
                                <a href="#!" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定刪除？','{{ route('marquees.destroy',$marquee->id) }}')"><i class="fas fa-trash"></i> 刪除</a>
                            @endif
                        </td>
                    </tr>                                        
                    </form>                    
                @endforeach
                </tbody>
            </table>      
            {{ $marquees->links('layouts.pagination') }}
        </div>
    </div>
</div>
@endsection