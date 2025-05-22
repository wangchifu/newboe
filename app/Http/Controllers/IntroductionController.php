<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Introduction;
use App\Models\SectionPage;

class IntroductionController extends Controller
{
    public function organization()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->organization;
        }
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.organization',$data);
    }

    public function people()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.people',$data);
    }

    public function site()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->site;
        }

        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.site',$data);
    }

    public function store(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->back();
    }

    public function section_page_add()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();
        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.section_page_add',$data);
    }

    public function section_page_store(Request $request)
    {
        $att = $request->all();
        $section_page = SectionPage::create($att);
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());
        return redirect()->route('introductions.section_page',$section_page->id);
    }

    public function section_page(SectionPage $section_page)
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();
        $data = [
            'section_name'=>$section_name,
            'section_pages'=>$section_pages,
            'section_page'=>$section_page,
        ];
        return view('introductions.section_page',$data);
    }

    public function section_page_del(SectionPage $section_page)
    {
        if($section_page->section_id != auth()->user()->section_id){
            return redirect()->back();
        }
        $section_page->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.organization');
    }

    public function section_page_update(Request $request,SectionPage $section_page)
    {
        if($section_page->section_id != auth()->user()->section_id){
            return redirect()->back();
        }
        $att = $request->all();
        $section_page->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 編輯了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.section_page',$section_page->id);
    }
}
