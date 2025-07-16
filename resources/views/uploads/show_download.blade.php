@extends('layouts.app')

@section('title','檔案下載')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>檔案下載</h1>
    <?php
    $icons = [
        'folder'=>'images/file_icons/folder.svg',
        'unknown'=>'images/file_icons/unknown.svg',
        'csv'=>'images/file_icons/csv.svg',
        'gif'=>'images/file_icons/gif.svg',
        'svg'=>'images/file_icons/svg.svg',
        'jpg'=>'images/file_icons/jpg.svg',
        'mp3'=>'images/file_icons/mp3.svg',
        'mp4'=>'images/file_icons/mp4.svg',
        'ods'=>'images/file_icons/ods.svg',
        'odt'=>'images/file_icons/odt.svg',
        'pdf'=>'images/file_icons/pdf.svg',
        'png'=>'images/file_icons/png.svg',
        'txt'=>'images/file_icons/txt.svg',
        'zip'=>'images/file_icons/zip.svg',
    ];


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
            <i class="fa fa-folder-open text-warning"></i> <a href="{{ route('uploads.show_download',$p) }}">{{$v}}</a>/
        @else
            <i class="fa fa-folder text-warning"></i> <a href="{{ route('uploads.show_download',$p) }}">{{$v}}</a>/
        @endif
    @endforeach
    <hr>
    <div class="container-fluid">
        <div class="row">
            @foreach($folders as $folder)
                @if($folder->name !="07_體設科")
                    <?php
                    $folder_p = $path.'&'.$folder->id;
                    $n = \App\Models\Upload::where('folder_id',$folder->id)->count();
                    ?>
                    <figure class="figure col-lg-1 col-md2 col-sm-3 col-4">
                        <a href="{{ route('uploads.show_download',$folder_p) }}">
                            <img src="{{ asset($icons['folder']) }}" class="figure-img img-fluid">
                        </a>
                        <figcaption class="figure-caption">
                            <small style="word-break: break-all;">{{ $folder->name }} ({{ $n }} 個項目)</small>
                        </figcaption>
                    </figure>
                @endif
            @endforeach
            @foreach($files as $file)
                <?php
                $file_p = $path.'&'.$file->id;
                ?>
                @if(file_exists(storage_path($f.'/'.$file->name)))
                    <?php
                        $file_size = filesizekb(storage_path($f.'/'.$file->name));
                        $co = substr($file->name,-3);
                        if(isset($icons[$co])){
                            $file_icon = $icons[$co];
                        }else{
                            $file_icon = $icons['unknown'];
                        }

                    ?>
                        <figure class="figure col-lg-1 col-md2 col-sm-3 col-4">
                            <a href="{{ route('uploads.download',$file_p) }}">
                                <img src="{{ asset($file_icon) }}" class="figure-img img-fluid">
                            </a>
                            <figcaption class="figure-caption">
                                <small style="word-break: break-all;">{{ $file->name }} ({{ $file_size }} KB)</small>
                            </figcaption>
                        </figure>
                @endif
            @endforeach
            @foreach($urls as $url)
                <figure class="figure col-lg-1 col-md2 col-sm-3 col-4">
                    <a href="{{ $url->url }}" target="_blank">
                        @if($url->type=="31")
                            <img src="{{ asset('images/file_icons/folder_link.svg') }}" class="figure-img img-fluid">
                        @elseif($url->type=="32")
                            <img src="{{ asset('images/file_icons/file_link.svg') }}" class="figure-img img-fluid">
                        @endif
                    </a>
                    <figcaption class="figure-caption">
                        <small style="word-break: break-all;">{{ $url->name }}</small>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</div>
@endsection