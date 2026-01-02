<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wrench;
use Purifier;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Mail;

class WrenchController extends Controller
{
    public function index()
    {
        $wrenches = Wrench::orderBy('id', 'DESC')->paginate('10');
                
        $data = [
            'wrenches' => $wrenches,
        ];
        return view('wrenches.index', $data);
    }

    public function store(Request $request)
    {
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        $att['content'] = Purifier::clean($att['content'], array('AutoFormat.AutoParagraph' => false));
        $att['email'] = Purifier::clean($att['email'], array('AutoFormat.AutoParagraph' => false));

        $wrench = Wrench::create($att);

        //處理檔案上傳
        $allowed_extensions = ["png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg"];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),                    
                ];

                if ($info['extension'] && !in_array($info['extension'], $allowed_extensions)) {
                    continue;
                }
                $file->storeAs('public/wrenches/' . $wrench->id, $info['original_filename']);
            }
        }


        $att2['email'] = $att['email'];
        $user = User::where('id',$att['user_id'])->first();
        $user->update($att2);

        $subject = env('APP_NAME') . '平台有人回報系統錯誤與建議';
        $body = $request->input('content');
                
        Mail::raw($body, function ($message) use ($subject){
            $message->to(env('ADMIN_MAIL'))
                    ->subject($subject);
        });        

        return redirect()->route('wrench.index');
    }

    public function reply(Request $request)
    {
        $wrench = Wrench::find($request->input('id'));
        $att = $request->all();
        $att['show'] = 1;

        $att['reply'] = Purifier::clean($att['reply'], array('AutoFormat.AutoParagraph' => false));
        
        $wrench->update($att);

        if ($wrench->user->email) {
            $subject = '回覆「' . env('APP_NAME') . '平台」中「系統錯誤與建議」';
            $body = "您問到：\r\n";
            $body .= $wrench->content . "\r\n";
            $body .= "\r\n系統管理員回覆：\r\n";
            $body .= $request->input('reply');
            $body .= "\r\n-----這是系統信件，請勿回信-----";
                                
            Mail::raw($body, function ($message) use ($subject){
                $message->to(env('ADMIN_MAIL'))
                        ->subject($subject);
            });            
        }

        return redirect()->route('wrench.index');
    }

    public function set_show(Wrench $wrench)
    {
        $att['show'] = 1;
        $wrench->update($att);
        return redirect()->route('wrench.index');
    }

    public function destroy(Wrench $wrench)
    {
        $folder = storage_path('app/public/wrenches/' . $wrench->id);
        del_folder($folder);
        $wrench->delete();
        return redirect()->route('wrench.index');
    }

    public function download($wrench_id, $filename)
    {
        $file = storage_path('app/public/wrenches/' . $wrench_id . '/' . $filename);
        return response()->download($file);
    }
}
