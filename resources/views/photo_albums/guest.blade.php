@extends('layouts.app')

@section('title','相簿')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-8 mx-auto">
    <h1>所有相簿</h1>    
    <div class="card mb-4">
        <div class="card-header">列表</div>
        <div class="card-body">                                                
            <table class="table table-striped" style="word-break:break-all;">
                <thead class="table-secondary">
                <tr>
                    <th>相簿</th>                    
                </tr>
                </thead>
                <tbody>
                    @foreach($photo_albums as $photo_album)
                    <tr>
                        <td>
                            @php
                                $check = \App\Models\Photo::where('photo_album_id',$photo_album->id)->first();
                                if(!empty($check)){
                                    $img = asset('storage/photo_albums/'.$photo_album->id.'/'.$check->photo_name);
                                }else{
                                    $img = asset('images/no-image.png');
                                }
                            @endphp
                            <a href="{{ route('photo_albums.guest_show',$photo_album->id) }}">
                                <img src="{{ $img }}" style="height:10rem;">
                            </a><br>
                            {{ $photo_album->album_name }} ({{ count($photo_album->photos) }})
                        </td>                        
                    </tr>                                                                                                     
                    @endforeach
                </tbody>
            </table>            
        </div>
    </div>
</div>
@endsection