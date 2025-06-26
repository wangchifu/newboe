<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marquee;
use Purifier;

class MarqueeController extends Controller
{
    public function index()
    {
        $marquees = Marquee::orderBy('stop_date','DESC')
            ->paginate('20');
        $data = [
            'marquees'=>$marquees,
        ];
        return view('marquees.index',$data);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:50',
            'start_date' => 'required',
            'stop_date' => 'required',
        ]);

        $att['title'] = Purifier::clean($request->input('title'), array('AutoFormat.AutoParagraph'=>false));
        $att['start_date'] = $request->input('start_date');
        $att['stop_date'] = $request->input('stop_date');
        $att['user_id'] = auth()->user()->id;
        Marquee::create($att);
        return redirect()->route('marquees.index');
    }        

    public function destroy(Marquee $marquee)
    {
        if($marquee->user_id != auth()->user()->id){
            return back();
        }
        $marquee->delete();
        return redirect()->route('marquees.index');
    }
}
