<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\post_schools_view;
use App\Models\PostSchool;
use App\Models\RegularReport;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use App\Models\UserPower;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Purifier;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    public function index(){
        
    }
    public function create()
    {
        //取 /config/boe.php下的categories
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $data = [
            'categories' => $categories,
            'sections' => $sections,
            'select_school' => '',
        ];
        return view('edus.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $att['user_id'] = auth()->user()->id;
        $att['category_id'] = $request->input('category_id');
        $att['title'] = Purifier::clean($request->input('title'), array('AutoFormat.AutoParagraph' => false));
        $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph' => false));
        //$att['content'] = $request->input('content');
        if(empty($att['content'])){
            return redirect()->back()->withErrors(['message' => ['公告內容不得為空白！']])->withInput();;
        }
        $att['type'] = $request->input('type');
        $att['another'] = $request->input('another');
        $att['url'] = transfer_url_http($request->input('url'));        
        if($request->input('form_action')=="送出審核不再修改"){
            $att['situation'] = "1";
        }elseif($request->input('form_action')=="暫存"){
            $att['situation'] = "-1";
        }                
        $att['section_id'] = auth()->user()->section_id;
        $att['views'] = "0";

        //行政公告的編號
        //if($att['category_id']=="5"){
        $cht_year = date('Y') - 1911;
        $last_post = Post::orderBy('id', 'DESC')
            ->where('post_no', 'like', $cht_year . "%")
            ->first();
        if ($last_post) {
            $att['post_no'] = $last_post->post_no + 1;
        } else {
            $att['post_no'] = $cht_year . '00001';
        }
        //}

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if (!empty($request->input('sel_school'))) {
            $school_set = checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_' . $key] = $value;
            }
        }

        //避免有人亂選後  再改為一般公告
        if($att['category_id'] <>"5"){
            $att['another'] = null;
            $att['school_set_0'] = 0;
            $att['school_set_1'] = 0;
            $att['school_set_2'] = 0;
            $att['school_set_3'] = 0;
            $att['school_set_4'] = 0;            
        }        

        $post = Post::create($att);

        //公務電話
        $user_att['telephone'] = $request->input('telephone');
        auth()->user()->update($user_att);

        //處理檔案上傳
        $allowed_extensions = ["ods", "odt", "zip", "png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg", "ods", "ODS"];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    //'size' => $file->getClientSize(),
                ];
                if ($info['extension'] && !in_array($info['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                
                $file->storeAs('public/post_files/' . $post->id, $safeName);
            }
        }

        //處理照片上傳
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                $info2 = [
                    'mime-type' => $photo->getMimeType(),
                    'original_filename' => $photo->getClientOriginalName(),
                    'extension' => $photo->getClientOriginalExtension(),
                    //'size' => $photo->getClientSize(),
                ];
                if ($info2['extension'] && !in_array($info2['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info2['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info2['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                      
                $photo->storeAs('public/post_photos/' . $post->id, $safeName);

                //縮圖
                
                $manager = new ImageManager(new GdDriver());
                $image = $manager->read($photo->getRealPath());
                $image->scale(width: 1000) // 保持比例縮放，指定寬度即可
                    ->save(storage_path('app/public/post_photos/' . $post->id . "/" . $safeName));

                //$img = Image::make($photo->getRealPath());
                //$img->resize(500, null, function ($constraint) {
                //    $constraint->aspectRatio();
                //})->save(storage_path('app/public/post_photos/' . $post->id . "/" . $info2['original_filename']));
            }
        }


        return redirect()->route('posts.reviewing');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post,$ps_id=null)
    {
        //沒通過不給看（發文者自己可以看）
        if ($post->situation != 3 and $post->situation != 4) {
            if (!auth()->check() || auth()->user()->id != $post->user_id) {
                abort('404','你沒有權限訪問這個頁面');
            }
        }
        //沒登入不給看
        if (!auth()->check() and $post->category_id == '5') {
            if ($post->another != 1) {
                abort('404','你沒有權限訪問這個頁面');
            }
        }
        //不是該校的行政公告，不給看（發文者自己可以看）
        if (auth()->check() and $post->category_id == '5' and $post->another != '1') {
            if (auth()->user()->id != $post->user_id) {
                $check_show = DB::table('post_schools_view')
                    ->where('code', 'like', "%" . auth()->user()->code . "%")
                    ->where('id', $post->id)
                    ->first();

                if (auth()->user()->other_code) {
                    $check_show2 = DB::table('post_schools_view')
                        ->where('code', 'like', "%" . auth()->user()->other_code . "%")
                        ->where('id', $post->id)
                        ->first();
                } else {
                    $check_show2 = false;
                }

                if (!$check_show and !$check_show2) {
                    abort(404,'別想偷看！');
                }
            }
        }

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');

        $post_key = 'post' . $post->id;
        if (session($post_key) != 1) {
            //更新views的值
            $att['views'] = $post->views + 1;
            $post->update($att);
        }

        session([$post_key => 1]);

        if(!is_null($ps_id)){
                        
            $user_power = DB::table('user_powers')->where([
                ['user_id', '=', auth()->user()->id],
                ['power_type', '=', 'B'],
            ])
            ->first();
            $post_school = PostSchool::find($ps_id);
            //不是該校的 post_school 不給看
            if(!strstr($post_school->code,auth()->user()->code)){
                abort('404','你想做什麼？');
            }
        }else{
            $user_power = null;
            $post_school = null;
        }


        $data = [
            //Key => 值
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'categories' => $categories,
            'sections' => $sections,
            'ps_id'=>$ps_id,
            'user_power'=>$user_power,
            'post_school'=>$post_school,
        ];
        return view('edus.posts.show', $data);
    }

    public function print(Post $post)
    {
        //沒通過不給看
        if ($post->situation != 3 and $post->situation != 4) {
            abort(404,'別想偷看！');
        }
        //沒登入不給看
        if (!auth()->check() and $post->category_id == '5') {
            if ($post->another != 1) {
                abort(404,'別想偷看！');
            }
        }

        //不是該校的行政公告，不給看
        if (auth()->check() and $post->category_id == '5' and $post->another != '1') {
            $check_show = DB::table('post_schools_view')
                ->where('code', 'like', "%" . auth()->user()->code . "%")
                ->where('id', $post->id)
                ->first();

            if (auth()->user()->other_code) {
                $check_show2 = DB::table('post_schools_view')
                    ->where('code', 'like', "%" . auth()->user()->other_code . "%")
                    ->where('id', $post->id)
                    ->first();
            } else {
                $check_show2 = false;
            }

            if (!$check_show and !$check_show2) {
                abort(404,'別想偷看！');
            }
        }

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');


        $data = [
            //Key => 值
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'categories' => $categories,
            'sections' => $sections,
        ];
        return view('edus.posts.print', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //這是自己的公告自己可以修改
    public function edit(Post $post)
    {

        //只能編輯自己的公告，這裡的update，是去讀取PostPolicy.php政策
        //$this->authorize('update', $post);
        if($post->situation==3 or $post->situation==4){
            abort(404,'都審核或廢除了，還想偷改？');
        }
        if($post->user_id != auth()->user()->id){
            abort(404,'你想做什麼壞事？');
        }

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');

        $select_school = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        $data = [
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            'select_school' => $select_school,
        ];

        return view('edus.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }
        if($post->situation==3 or $post->situation==4){
            header("refresh:5;url=".route('posts.passing'));
            print('失敗！！此公告已被審核通過，或已廢除！<br>五秒後自動跳轉。');
            dd();
        }

        //$this->authorize('update', $post);
        //DB::update('update post_schools set signed_user_id = ? , signed_at= ? where id= ? ',[auth()->user()->id,now(),$ps_id]);

        //return redirect()->route('posts.index');

        $att['category_id'] = $request->input('category_id');
        $att['title'] = Purifier::clean($request->input('title'), array('AutoFormat.AutoParagraph' => false));
        $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph' => false));
        //$att['content'] = $request->input('content');
        $att['type'] = $request->input('type');
        $att['another'] = $request->input('another');
        $att['url'] = transfer_url_http($request->input('url'));        
        //$att['situation'] = "3";
        if($request->input('form_action')=="送出審核不再修改"){
            $att['situation'] = "1";
        }elseif($request->input('form_action')=="暫存"){
            $att['situation'] = "-1";
        }

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if (!empty($request->input('sel_school'))) {
            $school_set = checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_' . $key] = $value;
            }
        }

        //避免有人亂選後  再改為一般公告
        if($att['category_id'] <>"5"){
            $att['another'] = null;
            $att['school_set_0'] = 0;
            $att['school_set_1'] = 0;
            $att['school_set_2'] = 0;
            $att['school_set_3'] = 0;
            $att['school_set_4'] = 0;            
        }   
        //dd($att);
        $post->update($att);
        //dd($post);
        //公務電話
        $user_att['telephone'] = $request->input('telephone');
        $post->user->update($user_att);
        //if($att['category_id'] != "5") {
        //$post->update($att);

        //處理檔案上傳
        $allowed_extensions = ["png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg", "ods", "ODS"];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    //'size' => $file->getClientSize(),
                ];
                if ($info['extension'] && !in_array($info['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                      
                $file->storeAs('public/post_files/' . $post->id, $safeName);
            }
        }

        //處理照片上傳
        $allowed_extensions = ["png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg"];
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                $info2 = [
                    'mime-type' => $photo->getMimeType(),
                    'original_filename' => $photo->getClientOriginalName(),
                    'extension' => $photo->getClientOriginalExtension(),
                    //'size' => $photo->getClientSize(),
                ];
                if ($info2['extension'] && !in_array($info2['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info2['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info2['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                      
                $photo->storeAs('public/post_photos/' . $post->id, $safeName);

                //縮圖
                $manager = new ImageManager(new GdDriver());
                $image = $manager->read($photo->getRealPath());
                $image->scale(width: 1000) // 保持比例縮放，指定寬度即可
                    ->save(storage_path('app/public/post_photos/' . $post->id . "/" . $safeName));                
            }
        }
        //}
        return redirect()->route('posts.reviewing');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //刪除公告
    public function destroy(Post $post)
    {
        if($post->situation==3 or $post->situation==4){
            return redirect()->back();
        }
        if ($post->user_id != auth()->user()->id) {   
            return back();
        }

        if ($post->situation == -1 or $post->situation == 0) {
            //刪除附檔案
            $folder = storage_path('app/public/post_files/' . $post->id);
            del_folder($folder);
            $folder = storage_path('app/public/post_photos/' . $post->id);
            del_folder($folder);
            $post->delete();
        }
        return redirect()->route('posts.reviewing');
    }

    //作廢公告
    public function obsolete(Post $post)
    {
        if ($post->user_id != auth()->user()->id) {
            return back();
        }
        /*$posts = Post::where('id',$id)->first();
         if(auth()->user()->id != $posts->user_id){
             $words = "你想做什麼！？";
             //return $words;
             return redirect()->route('posts.passing');
         }*/

        $att['situation'] = 4;
        $post->update($att);

        //已催收公告者，signed_quickly 改為 null
        $att2['signed_quickly'] = null;
        $post_schools = PostSchool::where('post_id', $post->id)->where('signed_quickly', '1')->get();
        foreach ($post_schools as $post_school) {
            $post_school->update($att2);
        }

        return redirect()->route('posts.passing');
    }

    //重新送審
    public function resend(Post $post)
    {
        if ($post->user_id != auth()->user()->id) {   
            return back();
        }
        $att['situation'] = 1;
        $post->update($att);
        return redirect()->route('posts.reviewing');
    }

    //檔案下載
    public function download($filename,$post_id)
    {
        $a = ['A','B','C','D','E','F','G','H','I','J'];
        if(auth()->check()){
            $user_power = UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();      
        }else{
            $user_power = null;
        }              
        $post = Post::findOrFail($post_id);
        if ($post->situation != 3 && $post->situation != 4) {       
            if(auth()->user()->id != $post->user_id && !$user_power){                    
                abort(404);
            }            
        }else{
            if ($post->category_id == '5' && $post->another != '1') {                
                if(auth()->user()->section_id != $post->user->section_id && !$user_power){
                    $check = DB::table('post_schools_view')
                        ->where('code', 'like', '%' . auth()->user()->code . '%')
                        ->where('id', $post->id)
                        ->first();
                    if (!$check) {
                        abort(404);
                    }
                }
            }
        }

        $file = storage_path('app/public/post_files/' . $post_id . '/' . $filename);        
        if (file_exists($file)) {
            return response()->download($file);
        }
    }
    //圖片下載
    public function downloadimage($filename,$post_id,)
    {
        $a = ['A','B','C','D','E','F','G','H','I','J'];
        if(auth()->check()){
            $user_power = UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();      
        }else{
            $user_power = null;
        }              
        $post = Post::findOrFail($post_id);
        if ($post->situation != 3 && $post->situation != 4) {       
            if(auth()->user()->id != $post->user_id && !$user_power){                    
                abort(404);
            }            
        }else{
            if ($post->category_id == '5' && $post->another != '1') {
                if(auth()->user()->id != $post->user_id && !$user_power){
                    $check = DB::table('post_schools_view')
                        ->where('code', 'like', '%' . auth()->user()->code . '%')
                        ->where('id', $post->id)
                        ->first();
                    if (!$check) {
                        abort(404);
                    }
                }
            }
        }

        $file = storage_path('app/public/post_photos/' . $post_id . '/' . $filename);
        return response()->download($file);
    }

    public function reviewing()
    {        
        $posts = Post::where('user_id', auth()->user()->id)
            //->where('section_id', auth()->user()->section_id)
            ->whereNotIn('situation', [3, 4])
            ->orderBy('id', 'DESC')
            ->simplePaginate('15');

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');
        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
        ];
        return view('edus.posts.reviewing', $data);
    }

    public function reading()
    {
        $posts = Post::where('user_id', auth()->user()->id)
            ->where('situation', '2')
            ->orderBy('id', 'DESC')
            ->get();

        $categories = config('boe.categories');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
        ];
        return view('posts.reading', $data);
    }

    public function backing()
    {
        $posts = Post::where('user_id', auth()->user()->id)
            ->where('situation', '0')
            ->orderBy('id', 'DESC')
            ->get();

        $categories = config('boe.categories');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
        ];
        return view('posts.backing', $data);
    }

    public function passing()
    {
        $posts = Post::where('user_id', auth()->user()->id)
            ->where(function ($q) {
                $q->where('situation', '3')
                    ->orWhere('situation', '4');
            })
            ->orderBy('id', 'DESC')
            ->paginate(20);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');
        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
        ];
        return view('edus.posts.passing', $data);
    }

    public function section_all()
    {
        $posts = Post::where('section_id', auth()->user()->section_id)
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('edus.posts.section_all', $data);
    }

    public function section_all2()
    {
        $posts = Post::where('section_id', auth()->user()->section_id)
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('edus.posts.section_all', $data);
    }

    public function all()
    {
        if (auth()->user()->code != "079999") {
            return back();
        }
        //不是處長、副處長、科長、督學即轉開
        if (auth()->user()->title != "處長" and auth()->user()->title != "副處長" and auth()->user()->title != "科長" and auth()->user()->title != "督學") {
            return back();
        }
        $posts = Post::orderBy('id', 'DESC')
            ->paginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('edus.posts.all', $data);
    }

    public function do_search(Request $request)
    {
        return redirect()->route('posts.all_search', $request->input('want'));
    }

    public function do_search_in_section(Request $request)
    {
        return redirect()->route('posts.all_search_in_section', $request->input('want'));
    }

    public function all_search($want)
    {

        $posts = Post::where(function ($q) use ($want) {
            $q->where('title', 'like', '%' . $want . '%')                
                ->orWhere('content', 'like', '%' . $want . '%')
                ->orWhereHas('user', function ($query) use ($want) {
                    $query->where('name', 'like', '%' . $want . '%');
                });
        })
            ->orderBy('id', 'DESC')
            ->paginate(20);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => $want,
        ];

        return view('edus.posts.all', $data);
    }

    public function all_search_in_section($want)
    {

        $posts = Post::where(function ($q) use ($want) {
            $q->where('title', 'like', '%' . $want . '%')
                ->orWhere('post_no', 'like', '%' . $want . '%')
                ->orWhere('content', 'like', '%' . $want . '%')
                ->orWhereHas('user', function ($query) use ($want) {
                    $query->where('name', 'like', '%' . $want . '%');
                });
        })->where('section_id', auth()->user()->section_id)
            ->orderBy('id', 'DESC')
            ->paginate(20);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => $want,
        ];

        return view('edus.posts.section_all', $data);
    }

    public function select_category(Request $request)
    {
        return redirect()->route('posts.all_category', $request->input('category'));
    }

    public function all_category($category)
    {

        $posts = Post::where('category_id', $category)
            ->orderBy('id', 'DESC')
            ->simplePaginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('posts.all', $data);
    }

    public function select_situation(Request $request)
    {
        return redirect()->route('posts.all_situation', $request->input('situation'));
    }

    public function all_situation($situation)
    {

        $posts = Post::where('situation', $situation)
            ->orderBy('id', 'DESC')
            ->simplePaginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('posts.all', $data);
    }

    public function all_user_id($user_id)
    {

        $posts = Post::where('user_id', $user_id)
            ->orderBy('id', 'DESC')
            ->simplePaginate(15);

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'want' => '',
        ];

        return view('posts.all', $data);
    }

    public function review()
    {
        //取得他管理的科室
        $user_power = UserPower::where('user_id', auth()->user()->id)
            ->where('power_type', 'A')
            ->whereIN('section_id',['A','B','C','D','E','F','G','H','I','J'])
            ->first();

        $posts = Post::where('section_id', $user_power->section_id)
            ->where(function ($q) {
                $q->where('situation', '1')
                ->orWhere('situation', '2');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $regular_reports = RegularReport::where('section_id',$user_power->section_id)
            ->where(function ($q) {
                $q->where('situation', '1')
                ->orWhere('situation', '2');
            })
            ->orderBy('id','DESC')
            ->get();

        $reports = Report::where('section_id', $user_power->section_id)
            ->where(function ($q) {
                $q->where('situation', '1')
                ->orWhere('situation', '2');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $categories = config('boe.categories');
        $situation = config('boe.situation');

        $uri = $_SERVER['REQUEST_URI'];
        $uri = strrchr($uri, "/");
        if (strpos($uri, "?")) {
            $uri_name = substr($uri, 1, strpos($uri, "?") - 1);
        } else {
            $uri_name = substr(strrchr($uri, "/"), 1);
        }

        $sections = config('boe.sections');

        $data = [
            'posts' => $posts,
            'regular_reports'=>$regular_reports,
            'reports' => $reports,
            'categories' => $categories,
            'situation' => $situation,
            'uri_name' => $uri_name,
            'sections' => $sections,
            'user_power'=>$user_power,
            'power_section_id' => $user_power->section_id,            
        ];

        return view('edus.posts.review', $data);
    }

    //秀出跑行程中的公告
    public function show_doing_post(Post $post)
    {
        //不是處長科長督學
        if ((auth()->user()->title != "處長" and auth()->user()->title != "副處長" and auth()->user()->title != "科長" and auth()->user()->title != "督學") or auth()->user()->code != "079999") {

            //非同科室的不得看
            //if($post->user->section_id != auth()->user()->section_id){
            //$post->user有可能是以前在教育處調府教師,歸鑑回學校後,它以前發的公告就會進入這邊了
            //所以再多檢查section_id=='' 時, 暫時防止不是教育處的人就好
            if ($post->user->section_id != '' && $post->user->section_id != auth()->user()->section_id) {
                //不同科室 也不是該科室的審核權
                $user_power = UserPower::where('section_id', $post->user->section_id)->where('user_id', auth()->user()->id)->first();

                if (!$user_power) {
                    abort('404','無法觀看別科室公告');
                }
            }
        }

        /**20260102移除
        if($post->user_id != auth()->user()->id){
            $a = ['A','B','C','D','E','F','G','H','I','J'];
            $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->whereIn('section_id',$a)
            ->first();
            if(!$user_power){
                abort(404,'你想做什麼壞事？');
            }            
        }
        */

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $situation = config('boe.situation');

        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_school = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        //由於Eloquent的whereIn方法只接收array，所以再將$select_school轉陣列
        $select_school = explode(", ", $select_school);
        //echo "<pre>";print_r($select_school);die();
        $schools = School::whereIn('id', $select_school)->get();
        //DB::table的寫法
        //$schools = DB::table('schools')->whereIn('id', $select_school)->get();

        //取出未簽收的學校代碼，再丟到School中篩選
        $quick_signed = PostSchool::where('post_id', $post->id)->whereNull('signed_user_id')->where('signed_quickly', '1')->first();

        $noSignedSchools = PostSchool::where('post_id', $post->id)->whereNull('signed_user_id')->get()->toArray();
        $noSignedSchoolsCode = array();
        foreach ($noSignedSchools as $noSignedSchool) {
            $noSignedSchoolsCode[] = $noSignedSchool['code'];
        }
        $noSignedSchools = School::whereIn('code_no', $noSignedSchoolsCode)->get();

        //取出已簽收的學校代碼，再丟到School中篩選 
        /**
        $signedSchools = PostSchool::where('post_id', $post->id)->whereNotNull('signed_user_id')->get()->toArray();
        $signedSchoolsCode = array();
        foreach ($signedSchools as $signedSchool) {
            $signedSchoolsCode[] = $signedSchool['code'];
        }
        $signedSchools = School::whereIn('code_no', $signedSchoolsCode)->get();
         */
        $signedSchools = PostSchool::where('post_id', $post->id)->whereNotNull('signed_user_id')->get();
        $data = [
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'categories' => $categories,
            'sections' => $sections,
            'situation' => $situation,
            'schools' => $schools,
            'noSignedSchools' => $noSignedSchools,
            'signedSchools' => $signedSchools,
            'quick_signed' => $quick_signed,
        ];
        return view('edus.posts.show_doing_post', $data);
    }

    //2025.07.29以下停用
    public function show_doing_post_print(Post $post)
    {
        //非同科室的不得看
        if ($post->user->section_id != auth()->user()->section_id) {
            //不同科室 也不是該科室的審核權
            $user_power = UserPower::where('section_id', $post->user->section_id)->where('user_id', auth()->user()->id)->first();

            if (!$user_power) {
                //dd('不要亂玩！');
            }
        }

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $situation = config('boe.situation');

        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_school = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        //由於Eloquent的whereIn方法只接收array，所以再將$select_school轉陣列
        $select_school = explode(", ", $select_school);
        //echo "<pre>";print_r($select_school);die();
        $schools = School::whereIn('id', $select_school)->get();
        //DB::table的寫法
        //$schools = DB::table('schools')->whereIn('id', $select_school)->get();

        //取出未簽收的學校代碼，再丟到School中篩選
        $quick_signed = PostSchool::where('post_id', $post->id)->whereNull('signed_user_id')->where('signed_quickly', '1')->first();

        $noSignedSchools = PostSchool::where('post_id', $post->id)->whereNull('signed_user_id')->get()->toArray();
        $noSignedSchoolsCode = array();
        foreach ($noSignedSchools as $noSignedSchool) {
            $noSignedSchoolsCode[] = $noSignedSchool['code'];
        }
        $noSignedSchools = School::whereIn('code_no', $noSignedSchoolsCode)->get();

        //取出已簽收的學校代碼，再丟到School中篩選
        /**
        $signedSchools = PostSchool::where('post_id', $post->id)->whereNotNull('signed_user_id')->get()->toArray();
        $signedSchoolsCode = array();
        foreach ($signedSchools as $signedSchool) {
        $signedSchoolsCode[] = $signedSchool['code'];
        }
        $signedSchools = School::whereIn('code_no', $signedSchoolsCode)->get();
         */
        $signedSchools = PostSchool::where('post_id', $post->id)->whereNotNull('signed_user_id')->get();
        $data = [
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'categories' => $categories,
            'sections' => $sections,
            'situation' => $situation,
            'schools' => $schools,
            'noSignedSchools' => $noSignedSchools,
            'signedSchools' => $signedSchools,
            'quick_signed' => $quick_signed,
        ];
        return view('edus.posts.show_doing_post_print', $data);
    }
    //以上停用

    //學校端顯示簽收公告
    public function showSigned()
    {        
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            $posts5_quickly = collect();
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                }                
            }            

            $reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            $regular_reports_not = \App\Models\RegularReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();

            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            session(['reports_not'=>$reports_not]);      
            session(['regular_reports_not'=>$regular_reports_not]);  

        //$post5 = DB::table('post_schools_view')
        //    ->where('code', 'like', "%" . auth()->user()->code . "%")
        //    ->whereRaw('not (situation = 4 and signed_user_id is null)')
        //    ->orderBy('passed_at', 'DESC')
        //    ->simplePaginate('20');        
        //$page = $post5->currentPage();
        
        $post_schools = PostSchool::where('code', 'like', "%" . auth()->user()->code . "%")
        ->orderBy('created_at', 'DESC')
        ->simplePaginate('20');         

        //$posts5_quickly = DB::table('post_schools_view')->where([
        //    ['code', 'like', "%" . auth()->user()->code . "%"],
        //    ['signed_quickly', '=', '1'],
        //])->get();

        //$user_power = DB::table('user_powers')->where([
        //    ['user_id', '=', auth()->user()->id],
        //    ['power_type', '=', 'B'],
        //])
        //    ->first();
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');
        //$schools = School::all()->pluck('school_name', 'code_no')->toArray();
        $schools = config('boe.schools_name'); 

        $data = [
            'post_schools' => $post_schools,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            //'user_power' => $user_power,
            'posts5_quickly' => session('posts5_quickly'),
            'schools' => $schools, 
            //'page' => $page,
        ];
        return view('schools.posts.show_signed', $data);
    }

    //學校端顯示簽收公告
    public function show_not_Signed()
    {        
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            $posts5_quickly = collect();
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                }                
            }            

            $reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            session(['reports_not'=>$reports_not]);   

        $post5 = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('signed_user_id', null)
            ->where('situation', '<>', 4)
            ->orderBy('passed_at', 'DESC')
            ->simplePaginate('20');

        $page = $post5->currentPage();        

        //$user_power = DB::table('user_powers')->where([
        //    ['user_id', '=', auth()->user()->id],
        //    ['power_type', '=', 'B'],
        //])
        //    ->first();
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');
        $schools = School::all()->pluck('school_name', 'code_no')->toArray();

        $data = [
            'post5' => $post5,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            //'user_power' => $user_power,
            'posts5_quickly' => session('posts5_quickly'),
            'schools' => $schools,
            'page' => $page,
        ];
        return view('schools.posts.show_not_signed', $data);
    }

    //學校端顯示簽收公告
    public function show_quick_Signed()
    {
       $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            $posts5_quickly = collect();
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                }                
            }            

            $reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            session(['reports_not'=>$reports_not]);   

        $post5 = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('signed_user_id', null)
            ->where('type', '1')
            ->where('situation', '<>', 4)
            ->orderBy('passed_at', 'DESC')
            ->simplePaginate('20');
        $page = $post5->currentPage();
    
        //$user_power = DB::table('user_powers')->where([
        //    ['user_id', '=', auth()->user()->id],
        //    ['power_type', '=', 'B'],
        //])
        //    ->first();
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');
        $schools = School::all()->pluck('school_name', 'code_no')->toArray();

        $data = [
            'post5' => $post5,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            //'user_power' => $user_power,
            'posts5_quickly' => session('posts5_quickly'),
            'schools' => $schools,
            'page' => $page,
        ];
        return view('schools.posts.show_quick_signed', $data);
    }


    //個人已簽收
    public function show_person_Signed()
    {
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            $posts5_quickly = collect();
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    $posts_not++;
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                }                
            }            

            $reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                ->where(function($q){
                    $q->where('situation','=',0)
                        ->orWhere('situation','=',1)
                        ->orWhere('situation','=',2)
                        ->orWhere('situation',null);
                })
                ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            session(['reports_not'=>$reports_not]);   
        $post5 = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('signed_user_id', auth()->user()->id)
            ->orderBy('passed_at', 'DESC')
            ->simplePaginate('20');        

        //$user_power = DB::table('user_powers')->where([
        //    ['user_id', '=', auth()->user()->id],
        //////    ['power_type', '=', 'B'],
        //])
        //    ->first();
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');
        $schools = School::all()->pluck('school_name', 'code_no')->toArray();

        $data = [
            'post5' => $post5,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            //'user_power' => $user_power,
            'posts5_quickly' => session('posts5_quickly'),
            'schools' => $schools,
        ];
        return view('schools.posts.show_person_signed', $data);
    }

    public function showSigned_other()
    {
        $post5 = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->other_code . "%")
            ->orderBy('passed_at', 'DESC')
            ->simplePaginate('20');
        $posts5_quickly = DB::table('post_schools_view')->where([
            ['code', '=', auth()->user()->other_code],
            ['signed_quickly', '=', '1'],
        ])->get();

        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $other_schools = config('boe.other_schools');
        $user_name = config('boe.user_name');

        $data = [
            'post5' => $post5,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            'posts5_quickly' => $posts5_quickly,
            'other_schools' => $other_schools,
        ];
        return view('schools.show_signed_other', $data);
    }

    public function showSigned_print(Post $post)
    {
        $posts = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('passed_at', '>=', $post->passed_at)
            ->where('situation', '<>', 4)
            ->orderBy('passed_at', 'DESC')
            ->get();
        $sections = config('boe.sections');
        $data = [
            'posts' => $posts,
            'sections' => $sections,
        ];
        return view('schools.posts.print', $data);
    }

    public function showSigned_print2(Post $post)
    {
        $posts = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('signed_user_id', null)
            ->where('passed_at', '>=', $post->passed_at)
            ->where('situation', '<>', 4)
            ->orderBy('passed_at', 'DESC')
            ->get();
        $sections = config('boe.sections');
        $data = [
            'posts' => $posts,
            'sections' => $sections,
        ];
        return view('schools.posts.print', $data);
    }

    public function showSigned_print3(Post $post)
    {
        $posts = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('signed_user_id', null)
            ->where('passed_at', '>=', $post->passed_at)
            ->where('type', '1')
            ->where('situation', '<>', 4)
            ->orderBy('passed_at', 'DESC')
            ->get();
        $sections = config('boe.sections');
        $data = [
            'posts' => $posts,
            'sections' => $sections,
        ];
        return view('schools.posts.print', $data);
    }

    public function search(Request $request)
    {
        $want = $request->input('want');
        /**
        $posts = Post::where('post_no',$want)
            ->orwhere('title','like','%'.$want.'%')
            ->orWhere('content','like','%'.$want.'%')
            ->orderBy('id','DESC')
            ->get();
        $post_data = [];
        foreach($posts as $post){
            $post_school = PostSchool::where('post_id',$post->id)
                ->where('code',auth()->user()->code)
                ->first();
            if($post_school){
                $post_data[$post->id]['post_no'] = $post->post_no;
                $post_data[$post->id]['title'] = $post->title;
                $post_data[$post->id]['section_id'] = $post->section_id;
                $post_data[$post->id]['user_id'] = $post->user_id;
                $post_data[$post->id]['updated_at'] = $post->updated_at;
                $post_data[$post->id]['signed_user_id'] = $post_school->signed_user_id;
                $post_data[$post->id]['signed_at'] = $post_school->signed_at;
                $post_data[$post->id]['post_school_id'] = $post_school->id;
            }
        }**/
        //$posts = DB::select('select a.id,a.post_no,a.title,a.content,a.section_id,a.user_id,a.updated_at,b.id as post_school_id,b.signed_at,b.signed_user_id from posts a left join post_schools b on a.id=b.post_id where b.code=? and (a.title like ? or a.content like ? or a.post_no=?)',[auth()->user()->code,'%'.$want.'%','%'.$want.'%',$want]);


        $posts = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where(function ($q) use ($want) {
                $q->where('title', 'like', '%' . $want . '%')
                    ->orWhere('content', 'like', '%' . $want . '%')
                    ->orWhere('post_no', 'like', '%' . $want . '%')
                    ->orWhere('name', 'like', '%' . $want . '%');
            })
            ->orderBy('id', 'DESC')
            ->simplePaginate('20');
        $user_power = DB::table('user_powers')->where([
            ['user_id', '=', auth()->user()->id],
            ['power_type', '=', 'B'],
        ])
            ->first();

        $sections = config('boe.sections');
        $user_name = config('boe.user_name');

        $data = [
            'want' => $want,
            'posts' => $posts,
            'user_name' => $user_name,
            'sections' => $sections,
            'user_power' => $user_power,
        ];

        return view('schools.posts.search', $data);
    }

    public function search_by_section($section_id)
    {

        $posts = DB::table('post_schools_view')
            ->where('code', 'like', "%" . auth()->user()->code . "%")
            ->where('section_id', $section_id)
            ->orderBy('id', 'DESC')
            ->simplePaginate('20');
        $user_power = DB::table('user_powers')->where([
            ['user_id', '=', auth()->user()->id],
            ['power_type', '=', 'B'],
        ])
            ->first();

        $sections = config('boe.sections');
        $user_name = config('boe.user_name');
        $sections = config('boe.sections');
        $data = [
            'section_id' => $section_id,
            'sections' => $sections,
            'posts' => $posts,
            'user_name' => $user_name,
            'sections' => $sections,
            'user_power' => $user_power,
        ];

        return view('schools.posts.search_by_section', $data);
    }

    //簽收公告 
    public function signed(Request $request, $ps_id)
    {
        //$page = $request->input('page');

        $post_school = PostSchool::find($ps_id);
        if (!strstr($post_school->code, auth()->user()->code)) {
            abort('404', '你想做什麼？');
        }
        if (empty($post_school->signed_user_id)) {
            DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);   

            //重算
            $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts5_quickly = collect();
            $posts_not = 0;
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                    $posts_not++;
                }
            }

            //$reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
            //    ->where(function($q){
            //        $q->where('situation','=',0)
            //            ->orWhere('situation','=',1)
            //           ->orWhere('situation','=',2)
            //            ->orWhere('situation',null);
            //    })
            //    ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            //session(['reports_not'=>$reports_not]);
            
            return redirect()->back();
        }else{
            return redirect()->back()->withErrors(['message' => ['已有人簽收了！']]);
        }          

        //return redirect('posts/showSigned?page=' . $page);
        
    }

    public function signed_at_show(Request $request, $ps_id){
        
        $post_school = PostSchool::find($ps_id);
        if (empty($post_school->signed_user_id)) {
            DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);

            
            //重算
            $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts5_quickly = collect();
            $posts_not = 0;
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                    $posts_not++;
                }
            }

            //$reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
            //    ->where(function($q){
            //        $q->where('situation','=',0)
            //            ->orWhere('situation','=',1)
            //            ->orWhere('situation','=',2)
            //            ->orWhere('situation',null);
            //    })
            //    ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
            //session(['reports_not'=>$reports_not]);                        
            //return redirect()->back();
        }else{
            //return redirect()->back()->withErrors(['message' => ['已有人簽收了！']]);
        }
        //$post_school = PostSchool::find($ps_id);
        //echo "<body onload=\"opener.location.reload();window.location.reload();\">";
        echo "
            <script>
            // 確保頁面加載完成後執行
            window.onload = function() {
                // 檢查父頁面是否存在且可以訪問 jQuery
                if (window.parent && window.parent.$) {
                    // 關閉 venobox 視窗
                    if (typeof window.parent.$.venobox !== 'undefined') {
                        window.parent.$.venobox.close();  // 關閉 venobox 視窗
                    }

                    // 可選：刷新父頁面，這樣可以讓父頁面顯示最新的內容
                    window.parent.location.reload();                
                }
            };
            </script>";
        
    }

    public function signed_more(Request $request)
    {
        $posts_id = explode(',', $request->input('posts_id'));
        foreach($posts_id as $post_id){
            $post_school = PostSchool::find($post_id);
            if(!empty($post_school->signed_user_id)) {
                $posts_id = array_diff($posts_id, [$post_id]);
            }
        }
        //檢查是不是自校的
        $post_schools = PostSchool::whereIn('id', $posts_id)->get();
        foreach($post_schools as $post_school){
            if (!strstr($post_school->code, auth()->user()->code)) {
                abort(403, '非本校公告');
            }   
        }        


        $att['signed_user_id'] = auth()->user()->id;
        $att['signed_at'] = now();
        $att['signed_quickly'] = 0;

        

        $post_schools = PostSchool::whereIn('id', $posts_id)->update($att);
        //dd($post_schools);
        //DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);

        //return redirect('posts/showSigned?page=' . $page);

        //echo json_encode($result);
        //return;
        //重算
        $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                ->where('signed_user_id',null)
            ->get();
            $posts_quick = 0;
            $posts_not = 0;
            $posts5_quickly = collect();
            foreach($posts_all_not as $post_all_not){
                if($post_all_not->post->situation === 3){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    if($post_all_not->signed_quickly == 1){
                     $posts5_quickly->push($post_all_not);
                    }
                    $posts_not++;
                }
            }

        //$reports_not = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
        //    ->where(function($q){
        //        $q->where('situation','=',0)
        //            ->orWhere('situation','=',1)
        //            ->orWhere('situation','=',2)
        //            ->orWhere('situation',null);
        //    })
        //    ->get()->count();
            session(['posts_not'=>$posts_not]);
            session(['posts_quick'=>$posts_quick]);
            session(['posts5_quickly'=>$posts5_quickly]);
        //    session(['reports_not'=>$reports_not]);

        return redirect()->back();
    }

    public function signed2(Request $request, $ps_id)
    {
        $post_school = PostSchool::find($ps_id);
        if (!strstr($post_school->code, auth()->user()->code)) {
            abort('404', '你想做什麼？');
        }
        $page = $request->input('page');
        DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);

        //return redirect('posts/show_not_Signed?page=' . $page);
        return redirect()->back();
    }

    public function signed3(Request $request, $ps_id)
    {
        $post_school = PostSchool::find($ps_id);
        if (!strstr($post_school->code, auth()->user()->code)) {
            abort('404', '你想做什麼？');
        }
        $page = $request->input('page');
        DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);

        //return redirect('posts/show_quick_Signed?page=' . $page);
        return redirect()->back();
    }

    //其他單位簽收公告
    public function signed_other($ps_id)
    {
        $post_school = PostSchool::find($ps_id);
        if ($post_school->code != auth()->user()->other_code) {
            abort('404', '你想做什麼？');
        }
        DB::update('update post_schools set signed_user_id = ? , signed_at= ?, signed_quickly= ? where id= ? ', [auth()->user()->id, now(), '0', $ps_id]);

        return redirect()->route('posts.showSigned_other');
    }

    //管理者退回公告
    public function return(Post $post)
    {
        $att['situation'] = '0';

        $post->update($att);
        return redirect()->route('posts.review');
    }

    //管理者審核通過公告
    public function approve(Post $post)
    {
        $att['situation'] = '3';
        $att['passed_at'] = substr(now(), 0, 19);
        $att['pass_user_id'] = auth()->user()->id;

        $post->update($att);
        return redirect()->route('posts.review');
    }

    //審核過的簽收公告寫到PostSchools
    public function addPostSchools(Post $post)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_schools = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        $select_schools = explode(", ", $select_schools);
        /*echo "<pre>";print_r($select_schools);die();*/
       
        $schools = School::whereIn('id', $select_schools)->get();

        // 1. 先找出資料庫裡針對這個 post_id 已經有哪些 code 了
        $existingCodes = PostSchool::where('post_id', $post->id)
                                    ->pluck('code')
                                    ->toArray();

        $postSchools = array();

        foreach ($schools as $school) {
            // 2. 檢查這次要寫入的 code，是否不在資料庫已有的名單內
            if (!in_array($school->code_no, $existingCodes)) {
                $postSchools[] = [
                    'post_id' => $post->id,
                    'code' => $school->code_no,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // 3. 重要：為了預防 $schools 裡面本身有重複資料，同步更新 $existingCodes
                $existingCodes[] = $school->code_no;
            }
        }

        // 4. 只寫入真正「新」的資料
        if (!empty($postSchools)) {
            // 配合你之前設定的 UNIQUE 索引，使用 insertOrIgnore 最安全
            PostSchool::insertOrIgnore($postSchools);
        }

        //DB::table的寫法
        //DB::table('post_schools')->insert($postSchools);

        /*foreach ($schools as $school) {
            DB::table('post_schools')->insert(
                array('post_id' => $post->id, 'code' => $school->code_no, 'created_at' => now(), 'updated_at' => now())
            );
        }*/

        return redirect()->route('posts.approve', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //管理者編輯公告
    public function eduadminedit($id)
    {
        $post = Post::where('id', $id)->first();
        
        //檢查是不是自己科室
        $user_power = UserPower::where('user_id', auth()->user()->id)
            ->where('power_type', 'A')
            ->where('section_id', $post->section_id)
            ->first();
        if (!$user_power) {
            abort(403, '無權編輯其他科室公告');
        }

        $files = get_files(storage_path('app/public/post_files/' . $post->id));
        $images = get_files(storage_path('app/public/post_photos/' . $post->id));
        $images_path = storage_path('app/public/post_photos/' . $post->id . '/');
        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');

        $select_school = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        $data = [
            'post' => $post,
            'files' => $files,
            'images' => $images,
            'images_path' => $images_path,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            'select_school' => $select_school,
        ];

        return view('edus.posts.eduadminedit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //管理者更新公告
    public function eduadminupdate(Request $request, $id)
    {

        $post = Post::where('id', $id)->first();
        //檢查是不是自己科室
        $user_power = UserPower::where('user_id', auth()->user()->id)
            ->where('power_type', 'A')
            ->where('section_id', $post->section_id)
            ->first();
        if (!$user_power) {
            abort(403, '無權編輯其他科室公告');
        }        
        $att['category_id'] = $request->input('category_id');
        $att['title']   = Purifier::clean($request->input('title'),
                        array('AutoFormat.AutoParagraph'=>false));
        $att['content'] = Purifier::clean($request->input('content'),
                        array('AutoFormat.AutoParagraph'=>false));                
        $att['type'] = $request->input('type');
        $att['another'] = $request->input('another');
        $att['url'] = transfer_url_http($request->input('url'));
        //$att['situation'] = "3";

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if (!empty($request->input('sel_school'))) {
            $school_set = checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_' . $key] = $value;
            }
        }

        $post->update($att);
        //dd($post);

        //if($att['category_id'] != "5") {
        //$post->update($att);

        //處理檔案上傳
        $allowed_extensions = ["png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg", "ods", "ODS"];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    //'size' => $file->getClientSize(),
                ];
                if ($info['extension'] && !in_array($info['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                      
                $file->storeAs('public/post_files/' . $post->id, $safeName);
            }
        }

        //處理照片上傳
        $allowed_extensions = ["png", "jpg", "pdf", "PDF", "JPG", "odt", "ODT", "csv", "txt", "zip", "jpeg"];
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            foreach ($photos as $photo) {
                $info2 = [
                    'mime-type' => $photo->getMimeType(),
                    'original_filename' => $photo->getClientOriginalName(),
                    'extension' => $photo->getClientOriginalExtension(),
                    //'size' => $photo->getClientSize(),
                ];
                if ($info2['extension'] && !in_array($info2['extension'], $allowed_extensions)) {
                    continue;
                }
                // 1. 取得副檔名 (odt)
                $extension = pathinfo($info2['original_filename'], PATHINFO_EXTENSION);

                // 2. 取得主檔名 (2023_{台北市}_中山國小)
                $mainName = pathinfo($info2['original_filename'], PATHINFO_FILENAME);

                // 3. 只清理主檔名的符號
                $safeMainName = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9_\-]/u', '_', $mainName);

                // 4. 處理連續底線並組合回副檔名
                $safeMainName = Str::of($safeMainName)->replaceMatches('/_+/', '_')->trim('_');
                $safeName = $safeMainName . '.' . $extension;                      
                $photo->storeAs('public/post_photos/' . $post->id, $safeName);

                //縮圖
                $manager = new ImageManager(new GdDriver());
                $image = $manager->read($photo->getRealPath());
                $image->scale(width: 1000) // 保持比例縮放，指定寬度即可
                    ->save(storage_path('app/public/post_photos/' . $post->id . "/" . $safeName));                
            }
        }
        //}
        return redirect()->route('posts.review');
    }
    //圖片呈現
    //public function getImg($file_path)
    //{
    //    $file_path = str_replace('&', '/', $file_path); //斜線不可以在URL中傳
    //    $file = File::get($file_path);
    //    $type = File::mimeType($file_path);
    //    return response($file)->header("Content-Type", $type);
    //}
    public function getImg($file_path)
    {
        $file_path = str_replace('&', '/', $file_path);

        // 限制只能存取 storage/app/public 目錄下的檔案
        $allowed_base = storage_path('app/public');
        $real_path = realpath($file_path);

        if ($real_path === false || !str_starts_with($real_path, $allowed_base)) {
            abort(404);
        }

        $file = File::get($real_path);
        $type = File::mimeType($real_path);
        return response($file)->header("Content-Type", $type);
    }    
    //刪除附件
    public function del_att($id, $filename)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }
        if ($post->situation == 3 || $post->situation == 4) {
            abort(403);
        }
        $file_path = storage_path('app/public/post_files/' . $id);
        $file = $file_path . '/' . $filename;
        File::delete($file);
        return back();
    }
    //刪除圖片
    public function del_img($id, $filename)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != auth()->user()->id) {
            abort(403);
        }
        if ($post->situation == 3 || $post->situation == 4) {
            abort(403);
        }        
        $file_path = storage_path('app/public/post_photos/' . $id);
        $file = $file_path . '/' . $filename;
        File::delete($file);
        return back();
    }

    //催收公告
    public function signedquickly($post)
    {
        if ($post->user_id != auth()->user()->id) {   
            return back();
        }
        PostSchool::where('post_id', $post)->whereNull('signed_at')->update(['signed_quickly' => '1']);

        return back()->with('message', '已送出催簽收訊息');
    }

    public function copy(Post $post)
    {
        if ($post->user_id != auth()->user()->id) {
            return back();
        };

        $categories = config('boe.categories');
        $sections = config('boe.sections');
        $user_name = config('boe.user_name');

        $select_school = checkbox_str_num(array($post->school_set_0, $post->school_set_1, $post->school_set_2, $post->school_set_3, $post->school_set_4));

        $data = [
            'post' => $post,
            'user_name' => $user_name,
            'sections' => $sections,
            'categories' => $categories,
            'select_school' => $select_school,
        ];

        return view('edus.posts.copy', $data);
    }

    public function people_other()
    {
        $other_schhols = config('boe.other_schools');
        $users = User::where('other_code', auth()->user()->other_code)->get();
        $data = [
            'other_schools' => $other_schhols,
            'users' => $users,
        ];
        return view('posts.people_other', $data);
    }

    public function people_add(Request $request)
    {
        $user = User::where('username', $request->input('username'))
            ->first();
        if ($user) {
            $att['other_code'] = auth()->user()->other_code;
            $user->update($att);
        } else {
            return back()->withErrors(['errors' => ['無此帳號！']]);
        }

        return redirect()->route('posts.people_other');
    }

    public function people_remove(User $user)
    {
        $att['other_code'] = null;
        $user->update($att);
        return redirect()->route('posts.people_other');
    }    
}
