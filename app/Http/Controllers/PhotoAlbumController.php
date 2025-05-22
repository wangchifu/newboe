<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoAlbum;
use App\Models\Photo;
//use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Purifier;

class PhotoAlbumController extends Controller
{
    public function index()
    {
        $photo_albums = PhotoAlbum::orderBy('id','DESC')->get();
        $data = [
            'photo_albums'=>$photo_albums,
        ];
        return view('photo_albums.index',$data);
    }

    public function guest()
    {
        $photo_albums = PhotoAlbum::orderBy('id','DESC')->get();
        $data = [
            'photo_albums'=>$photo_albums,
        ];
        return view('photo_albums.guest',$data);
    }

    public function guest_show(PhotoAlbum $photo_album)
    {
        $photos = Photo::where('photo_album_id',$photo_album->id)
            ->orderBy('id','ASC')
            ->get();
        $data = [
            'photo_album'=>$photo_album,
            'photos'=>$photos,
        ];
        return view('photo_albums.guest_show',$data);
    }

    public function store(Request $request)
    {
        $att = $request->all();
        $att['album_name'] = Purifier::clean($att['album_name'], array('AutoFormat.AutoParagraph'=>false));

        $photo_album = PhotoAlbum::create($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        return redirect()->route('photo_albums.index');
    }

    public function edit(PhotoAlbum $photo_album)
    {
        $data = [
            'photo_album'=>$photo_album,
        ];
        return view('photo_albums.edit',$data);
    }

    public function update(Request $request,PhotoAlbum $photo_album)
    {
        $att = $request->all();
        $att['album_name'] = Purifier::clean($att['album_name'], array('AutoFormat.AutoParagraph'=>false));
        $photo_album->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        return redirect()->route('photo_albums.index');
    }

    public function show(PhotoAlbum $photo_album)
    {
        $photos = Photo::where('photo_album_id',$photo_album->id)
            ->orderBy('id','ASC')
            ->get();
        $data = [
            'photo_album'=>$photo_album,
            'photos'=>$photos,
        ];
        return view('photo_albums.show',$data);
    }


    public function store_photo(Request $request,PhotoAlbum $photo_album)
    {        
        $arrExt = array('jpg','jpeg','JPG','JPEG','gif','png','PNG','svg','bmp','webp','heic');
        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');            
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                ];
                //$filename = substr(hash('sha256',$info['original_filename']),0,10).".".$info['extension'];                
                $filename = date('YmdHis').".".$info['extension'];                
                if(!in_array($info['extension'],$arrExt)){
                    return back()->withErrors(['error' => ['上傳失敗，有非相片檔案！']]);
                }else{
                        $att['photo_album_id'] = $photo_album->id;
                        $att['photo_name'] = $filename;
                        $att['user_id'] = auth()->user()->id;
                        $photo = Photo::create($att);
                        $file->storeAs('public/photo_albums/'.$photo_album->id, $filename);
                        
                        $manager = new ImageManager(new Driver());
                        if(!file_exists(storage_path('app/public/photo_albums'))){
                            mkdir(storage_path('app/public/photo_albums'));
                        }

                        if(!file_exists(storage_path('app/public/photo_albums/'.$photo_album->id))){
                            mkdir(storage_path('app/public/photo_albums/'.$photo_album->id));
                        }
                        $image = $manager->read(storage_path('app/public/photo_albums/'.$photo_album->id.'/'.$filename));
                        $image->scale(width: 1300);
                        $image->save(storage_path('app/public/photo_albums/'.$photo_album->id.'/'.$filename));

                        //log
                        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了相片 id：".$photo->id." 名稱：".$photo->photo_name;
                        logging('4',$event,get_ip());                                            
                }
            }
        }        
        return redirect()->back();
    }

    public function delete_photo(Photo $photo)
    {
        if(file_exists(storage_path('app/public/photo_albums/'.$photo->photo_album_id.'/'.$photo->photo_name))){
            unlink(storage_path('app/public/photo_albums/'.$photo->photo_album_id.'/'.$photo->photo_name));
        }
        $photo->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了相片 id：".$photo->id." 名稱：".$photo->photo_name;
        logging('4',$event,get_ip());

        return redirect()->back();
    }

    public function delete(PhotoAlbum $photo_album)
    {
        del_folder(storage_path('app/public/photo_albums/'.$photo_album->id));
        Photo::where('photo_album_id',$photo_album->id)->delete();
        $photo_album->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        return redirect()->back();
    }
}
