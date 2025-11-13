@extends('layouts.app')

@section('title','檔案下載')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $section_name }} 檔案上傳</h1>
    <div class="card mb-4">
        <div class="card-header">
            <?php
            $final = end($folder_path);
            $final_key = key($folder_path);
            $p="";
            $f="app/public/open_files";
            $last_folder = "";
            ?>
            路徑：
            @foreach($folder_path as $k=>$v)
                <?php
                if($k=="0"){
                    $k = null;

                }else{
                    $p .= '&'.$k;
                    $f .=  '/'.$v;
                }
                if($k != $final_key and !empty($k)){
                    $last_folder .= '&'.$k;
                }

                ?>
                @if($v == $final)
                    <i class="fa fa-folder-open text-warning"></i> <a href="{{ route('uploads.index',$p) }}">{{$v}}</a>/
                @else
                    <i class="fa fa-folder text-warning"></i> <a href="{{ route('uploads.index',$p) }}">{{$v}}</a>/
                @endif
            @endforeach
        </div>
        <div class="card-body">                        
            <table class="table table-striped">
                <thead class="thead-light">
                <tr>
                    <th>目錄 / 檔案名稱</th>
                    <th>類型</th>
                    <th>數量</th>
                    <th>建立者</th>
                    <th>建立時間</th>
                </tr>
                </thead>
                <tbody>
                @if($path!=null)
                    <tr>
                        <td><i class="fas fa-arrow-circle-left"></i> <a href="{{ route('uploads.index',$last_folder) }}">上一層</a></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                @foreach($folders as $folder)
                    <?php
                    $folder_p = $path.'&'.$folder->id;
                    ?>
                    <tr>
                        <td>
                            <i class="fas fa-folder text-warning"></i> <a href="{{ route('uploads.index',$folder_p) }}">{{ $folder->name }}</a></td>
                        </td>
                        <td>
                            <?php $n = \App\Models\Upload::where('folder_id',$folder->id)->count();?>
                            <strong>目錄</strong>
                                <a href="{{ route('uploads.edit',['upload'=>$folder->id,'path'=>$folder_p]) }}" class="venobox" data-vbtype="iframe">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                            @if($n == 0)
                                <a href="#!" id="delete_folder{{ $folder->id }}" onclick="sw_confirm1('確定刪除目錄嗎？','{{ route('uploads.delete',$folder_p) }}')"><i class="fas fa-minus-square text-danger"></i></a>
                            @endif
                        </td>
                        <td>
                            {{ $n }} 個項目
                        </td>
                        <td>
                            {{ $folder->user->title }}
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$folder->name)))
                                {{ date ("Y-m-d H:i:s",filemtime(storage_path($f.'/'.$folder->name))) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                @foreach($files as $file)
                    <?php
                    $file_p = $path.'&'.$file->id;
                    ?>
                    <tr>
                        <td>
                            <i class="fas fa-file text-info"></i> <a href="{{ route('uploads.download',$file_p) }}">{{ $file->name }}</a></td>
                        </td>
                        <td>
                            檔案
                            <a href="{{ route('uploads.edit',['upload'=>$file->id,'path'=>$file_p]) }}" class="venobox" data-vbtype="iframe">
                                <i class="fas fa-edit text-primary"></i>
                            </a>
                            <a href="#!" id="delete_file{{ $file->id }}" onclick="sw_confirm1('確定刪除？','{{ route('uploads.delete',$file_p) }}')"><i class="fas fa-minus-square text-danger"></i></a>
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ filesizekb(storage_path($f.'/'.$file->name)) }} kB
                            @else
                                <span class="badge text-bg-danger">遺失請刪除</span>
                            @endif

                        </td>
                        <td>
                            {{ $file->user->title }}
                        </td>
                        <td>
                            @if(file_exists(storage_path($f.'/'.$file->name)))
                                {{ date ("Y-m-d H:i:s",filemtime(storage_path($f.'/'.$file->name))) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                @foreach($urls as $url)
                    <?php
                    $url_p = $path.'&'.$url->id;
                    ?>
                    <tr>
                        <td>
                            @if($url->type=="31")
                                <i class="fa-solid fa-paperclip text-warning"></i> 
                            @endif
                            @if($url->type=="32")
                                <i class="fas fa-external-link-square-alt text-primary"></i> 
                            @endif
                            <a href="{{ $url->url }}" target="_blank">{{ $url->name }}</a></td>
                        </td>
                        <td>
                            連結
                            <a href="{{ route('uploads.edit',['upload'=>$url->id,'path'=>$url_p]) }}" class="venobox" data-vbtype="iframe">
                                <i class="fas fa-edit text-primary"></i>
                            </a>
                            <a href="#!" id="delete_url{{ $url->id }}" onclick="sw_confirm1('確定刪除？','{{ route('uploads.delete',$url_p) }}')"><i class="fas fa-minus-square text-danger"></i></a>
                        </td>
                        <td>
                            -
                        </td>
                        <td>
                            {{ $url->user->title }}
                        </td>
                        <td>
                            {{ $url->created_at }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr>
            <div class="card my-4">
                <h3 class="card-header">新增</h3>
                <div class="card-body">                    
                    <form action="{{ route('uploads.create_folder') }}" method="POST" id="create_folder" onsubmit="return false">
                        @csrf
                    <div class="form-group">
                        <label for="name"><strong>1.子目錄</strong></label>                        
                        <input type="text" name="name" id="name" class="form-control" placeholder="名稱" required value="{{ old('name') }}">

                    </div>
                    <div class="form-group">
                        <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                        <input type="hidden" name="path" value="{{ $path }}">
                        <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定新增子目錄','create_folder')"><i class="fas fa-plus"></i> 新增子目錄</button>
                    </div>
                    </form>
                    <hr>
                    @include('layouts.errors')
                    <form action="{{ route('uploads.upload_file') }}" method="POST" id="upload_file" enctype="multipart/form-data" onsubmit="return false">
                        @csrf
                    <div class="form-group">
                        <label for="file"><strong>2.檔案( 不大於10MB，若為文字檔，請改為[ <a href="https://www.ndc.gov.tw/cp.aspx?n=d6d0a9e658098ca2" target="_blank">ODF格式</a> ] )</strong><small class="text-secondary">txt, zip, jpg, png, gif, pdf, odt, ods, mp3, mp4 檔</small></label>                        
                        <input type="file" name="files[]" class="form-control" multiple required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                        <input type="hidden" name="path" value="{{ $path }}">
                        <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定新增檔案','upload_file')"><i class="fas fa-plus"></i> 新增檔案</button>
                    </div>
                    </form>
                    <hr>
                    <form action="{{ route('uploads.create_url') }}" method="POST" id="create_url" onsubmit="return false">
                        @csrf
                    <div class="form-group">
                        <label for="file"><strong>3.連結</strong><small class="text-secondary">方便連至雲端硬碟</small></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="名稱" required value="{{ old('name') }}">
                        <input type="text" name="url" id="url" class="form-control" placeholder="網址" required value="{{ old('url') }}">
                        <input type="radio" value="31" name="type" id="type31" checked> <label for="type31">雲端目錄</label> <input type="radio" value="32" name="type" id="type32"> <label for="type32">雲端檔案</label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="folder_id" value="{{ $folder_id }}">
                        <input type="hidden" name="path" value="{{ $path }}">
                        <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定新增連結','create_url')"><i class="fas fa-plus"></i> 新增連結</button>
                    </div>
                    </form>
                </div>
            </div>            
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