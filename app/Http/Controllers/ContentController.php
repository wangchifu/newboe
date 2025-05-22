<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Purifier;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::where('disable',null)->get();
        $sections = config('boe.sections');
        $data = [
            'contents'=>$contents,
            'sections'=>$sections,
        ];
        return view('contents.index',$data);
    }

    public function create()
    {
        return view('contents.create');
    }

    public function upload_image(Request $request){
        if ($request->hasFile('file')) {
            $filename = date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
            $path = $request->file('file')->storeAs('photos',$filename,'public');
            return response()->json(['location' => asset('storage/'.$path)]);        
        }
        
        return response()->json(['error' => '上傳失敗'], 400);        
    }




    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);
        
        $requestData = $request->all();
        $requestData['title'] = Purifier::clean($requestData['title'], array('AutoFormat.AutoParagraph'=>false));
        $requestData['content'] = Purifier::clean($requestData['content'], array('AutoFormat.AutoParagraph'=>false));

        $content = Content::create($requestData);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了內容管理 id：".$content->id." 名稱：".$content->title;
        logging('5',$event,get_ip());

        return redirect()->route('contents.index');
    }

    public function show(Content $content)
    {
        if($content->disable==1){
            abort(404);
        }
        return view('contents.show',compact('content'));
    }

    public function edit(Content $content)
    {
        return view('contents.edit',compact('content'));
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);
        
        $att= $request->all();
        $att['user_id'] = auth()->user()->id;
        $att['section_id'] = auth()->user()->section_id;
        
        $requestData = $att;
        $requestData['title'] = Purifier::clean($requestData['title'], array('AutoFormat.AutoParagraph'=>false));
        $requestData['content'] = Purifier::clean($requestData['content'], array('AutoFormat.AutoParagraph'=>false));

        $content->update($requestData);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了內容管理 id：".$content->id." 名稱：".$content->title;
        logging('5',$event,get_ip());

        return redirect()->route('contents.index');
    }

    public function destroy(Content $content)
    {
        $att['disable'] = 1;
        $att['user_id'] = auth()->user()->id;
        $att['section_id'] = auth()->user()->section_id;
        $content->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了內容管理 id：".$content->id." 名稱：".$content->title;
        logging('5',$event,get_ip());

        return redirect()->route('contents.index');
    }
}
