<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TitleImage;

class TitleImageController extends Controller
{
    public function index()
    {
        $title_images = TitleImage::where('disable', null)->get();
        $path = storage_path('app/public/title_image');
        if (!is_dir($path)) mkdir($path);

        $data = [
            'title_images' => $title_images,
        ];
        return view('title_images.index', $data);
    }

    public function add(Request $request)
    {
        $att = $request->all();

        $path = storage_path('app/public/title_image');

        if (!is_dir($path)) mkdir($path);

        //處理檔案上傳
        if ($request->hasFile('pic')) {
            $pic = $request->file('pic');
            if(check_php($pic)) return back()->withErrors(['errors'=>['不合規定的檔案類型']]);

            $info = [
                'original_filename' => $pic->getClientOriginalName(),
                'extension' => $pic->getClientOriginalExtension(),
            ];
            $name = date('YmdHis');
            $pic->storeAs('public/title_image', $name . '.' . $info['extension']);
            $att['photo_name'] = $name . '.' . $info['extension'];

            $title_image = TitleImage::create($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 新增了標題圖片 id：" . $title_image->id;
            logging('5', $event, get_ip());
        }
        return redirect()->route('title_image_index');
    }

    public function edit(TitleImage $title_image)
    {
        $data = [
            'title_image' => $title_image,
        ];
        return view('title_images.edit', $data);
    }

    public function update(Request $request, TitleImage $title_image)
    {
        $att = $request->all();
        $title_image->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 修改了標題圖片 id：" . $title_image->id;
        logging('5', $event, get_ip());
        return redirect()->route('title_image_index');
    }

    public function delete(TitleImage $title_image)
    {
        $att['disable'] = 1;
        $title_image->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了標題圖片 id：" . $title_image->id;
        logging('5', $event, get_ip());

        return redirect()->route('title_image_index');
    }
}
