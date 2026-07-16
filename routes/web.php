<?php
//如果關閉網站
//if($_SERVER['REQUEST_URI'] != "/close"){
//    close_system();
//};

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TitleImageController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PhotoAlbumController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\MySectionController;
use App\Http\Controllers\IntroductionController;
use App\Http\Controllers\WrenchController;
use App\Http\Controllers\MarqueeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\OpenIDLoginController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\EduReportController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolReportController;
use App\Http\Controllers\RegularReportController;
use App\Http\Controllers\DB2Controller;


Route::get('/', [HomeController::class,'index'])->name('index');
Route::get('index', [HomeController::class,'index'])->name('index');
//Route::get('glogin', [HomeController::class,'glogin'])->name('glogin');
//Route::post('gauth', [HomeController::class,'gauth'])->name('gauth');
Route::get('logins', [HomeController::class,'logins'])->name('logins');
Route::get('login', [HomeController::class,'login'])->name('login');
Route::get('mlogin', [HomeController::class,'mlogin'])->name('mlogin');
Route::post('mauth', [HomeController::class,'mauth'])->name('mauth');
Route::post('logout', [HomeController::class,'logout'])->name('logout');


//認證圖片
Route::get('pic', [HomeController::class,'pic'])->name('pic');

//openid登入
Route::get('sso', [OpenIDLoginController::class,'sso'])->name('sso');
Route::get('auth/callback', [OpenIDLoginController::class,'callback'])->name('callback');

//rss
Route::get('rss', [HomeController::class,'rss'])->name('rss');

//內容頁面
Route::get('contents/{content}/show', [ContentController::class,'show'])->where('content', '[0-9]+')->name('contents.show');

//相簿
Route::get('photo_albums/guest', [PhotoAlbumController::class,'guest'])->name('photo_albums.guest');
Route::get('photo_albums/{photo_album}/guest_show', [PhotoAlbumController::class,'guest_show'])->name('photo_albums.guest_show');

//檔案下載
Route::get('upload/show_download/{path?}', [UploadController::class,'show_download'])->name('uploads.show_download');
Route::get('upload/download/{path}', [UploadController::class,'download'])->name('uploads.download');

//各科室介紹
Route::get('introduction/{type}/show/{section_id}', [IntroductionController::class,'show'])->name('introductions.show');
Route::get('introduction/{type}/show2/{section_id}', [IntroductionController::class,'show2'])->name('introductions.show2');
Route::get('introduction/{section_id}/section_page_show/{section_page}', [IntroductionController::class,'section_page_show'])->name('introductions.section_page_show');

//學校介紹
Route::get('school/school_map', [SchoolController::class,'school_map'])->name('introductions.school_map');
Route::get('school/school_list', [SchoolController::class,'school_list'])->name('introductions.school_list');
Route::get('school/{code_no}/school_show', [SchoolController::class,'school_show'])->name('introductions.school_show');


//停用系統
Route::get('close', [AdminsController::class,'close'])->name('close');

Route::get('search', [HomeController::class,'search'])->name('search');

//秀出公告類別的公告
Route::get('bulletin/{category}', [HomeController::class,'bulletin'])->name('bulletin.show');
Route::post('bulletin_search', [HomeController::class,'bulletin_search'])->name('bulletin_search');
Route::get('bulletin_search_result/{category_id}/{want}/result', [HomeController::class,'bulletin_search_result'])->name('bulletin_search_result');

//秀出指定的公告
Route::get('posts_show/{post}/{ps_id?}', [PostsController::class,'show'])->name('posts.show');
Route::get('posts_print/{post}', [PostsController::class,'print'])->name('posts.print');

//顯示最新公告列表，暫時沒用到先註解掉
//Route::get('posts', [PostsController::class,'index'])->name('posts.index');

//下載檔案
Route::get('download/{filename}/{id}', [PostsController::class,'download'])->name('posts.download');

//顯示上傳的圖片
Route::get('img/{file_path}', [PostsController::class,'getImg'])->name('posts.img');
//下載圖片
Route::get('downloadimage/{filename}/{id}/', [PostsController::class,'downloadimage'])->name('posts.downloadimage');


//已註冊使用者可進入
Route::group(['middleware' => 'auth'],function(){    
    Route::get('user_reads/{no_read_sp}',[HomeController::class,'user_reads'])->name('user_reads');
    Route::get('qanda', [HomeController::class,'qanda'])->name('qanda');
    Route::get('about', [HomeController::class,'about'])->name('about');
    //結束模擬
    Route::get('sims/impersonate_leave', [AdminsController::class,'impersonate_leave'])->name('sims.impersonate_leave');

    //下載資料填報附檔
    Route::get('edu_report/{id}/{filename}/download', [EduReportController::class,'download'])->name('edu_report.download');
    Route::get('edu_regular_report/{id}/{filename}/download', [RegularReportController::class,'download'])->name('edu_regular_report.download');
    //報錯
    Route::get('wrench/index', [WrenchController::class,'index'])->name('wrench.index');
    Route::post('wrench/store', [WrenchController::class,'store'])->name('wrench.store');
    Route::get('wrench/download/{wrench_id}/{filename}', [WrenchController::class,'download'])->name('wrench.download');        

    Route::get('edit_title',[HomeController::class,'edit_title'])->name('edit_title');
    Route::patch('update_title',[HomeController::class,'update_title'])->name('update_title');
            
});

//教育處科員可用
Route::group(['middleware' => 'edu'],function(){
    //跑馬燈
    Route::get('marquees' , [MarqueeController::class,'index'])->name('marquees.index');    
    Route::post('marquees' , [MarqueeController::class,'store'])->name('marquees.store');    
    Route::get('marquees/{marquee}/destroy' , [MarqueeController::class,'destroy'])->name('marquees.destroy');    

    //掛載檔案
    Route::get('upload/index/{path?}', [UploadController::class,'upload'])->name('uploads.index');    
    Route::post('upload/create_folder' , [UploadController::class,'create_folder'])->name('uploads.create_folder');
    Route::post('upload/upload_file' , [UploadController::class,'upload_file'])->name('uploads.upload_file');
    Route::get('upload/delete/{path}' , [UploadController::class,'delete'])->name('uploads.delete');
    Route::post('upload/create_url' , [UploadController::class,'create_url'])->name('uploads.create_url');
    //修改名稱
    Route::get('upload/{upload}/{path}/edit', [UploadController::class,'edit'])->name('uploads.edit');
    Route::post('upload/store_name', [UploadController::class,'store_name'])->name('uploads.store_name');    
    
    //申請科室
    Route::get('apply_section', [MySectionController::class,'apply_section'])->name('apply_section');
    Route::patch('apply_section/{user}', [MySectionController::class,'section_update'])->name('apply_section.update');
    Route::get('apply_section/{user}/delete', [MySectionController::class,'section_delete'])->name('apply_section.delete');

//出現新增公告的表單
    Route::get('posts/create', [PostsController::class,'create'])->name('posts.create');
//實際post儲存公告資料
    Route::post('posts/store', [PostsController::class,'store'])->name('posts.store');
//出現要修改的指定公告
    Route::get('posts/{post}/edit', [PostsController::class,'edit'])->name('posts.edit');
//送出要修改的指定公告內容
    Route::patch('posts/{post}/update', [PostsController::class,'update'])->name('posts.update');
//刪除指定的公告
    Route::delete('posts/{post}/delete', [PostsController::class,'destroy'])->name('posts.destroy');
//作廢指定的公告
    Route::patch('posts/{post}/obsolete', [PostsController::class,'obsolete'])->name('posts.obsolete');
//再次送審
    Route::patch('posts/{post}/resend', [PostsController::class,'resend'])->name('posts.resend');
//催收公告
    Route::patch('posts/{post}/signedquickly', [PostsController::class,'signedquickly'])->name('posts.signedquickly');


//審核中
    Route::get('posts/reviewing', [PostsController::class,'reviewing'])->name('posts.reviewing');
//已讀未審
    Route::get('posts/reading', [PostsController::class,'reading'])->name('posts.reading');
//顯示通過的公告
    Route::get('posts/passing', [PostsController::class,'passing'])->name('posts.passing');
//秀行程中的公告
    Route::get('posts/show_doing_post/{post}', [PostsController::class,'show_doing_post'])->name('posts.show_doing_post');
    Route::get('posts/show_doing_post_print/{post}', [PostsController::class,'show_doing_post_print'])->name('posts.show_doing_post_print');

//顯示退回的公告
    Route::get('posts/backing', [PostsController::class,'backing'])->name('posts.backing');

    //刪除指定公告的附件
    Route::get('posts/{id}/{filename}/del_att', [PostsController::class,'del_att'])->name('posts.del_att');
//刪除指定公告的圖片
    Route::get('posts/{id}/{filename}/del_img', [PostsController::class,'del_img'])->name('posts.del_img');

    //複製公告
    Route::get('posts/{post}/copy', [PostsController::class,'copy'])->name('posts.copy');  
    
    //看同科室的所有公告
    Route::get('posts/section_all2', [PostsController::class,'section_all2'])->name('posts.section_all2');
    Route::post('posts/do_search_in_section', [PostsController::class,'do_search_in_section'])->name('posts.do_search_in_section');
    Route::get('posts/{want}/all_search_in_section', [PostsController::class,'all_search_in_section'])->name('posts.all_search_in_section');
    
        //資料填報
    Route::get('edu_report', [EduReportController::class,'index'])->name('edu_report.index');
    Route::get('edu_report/create', [EduReportController::class,'create'])->name('edu_report.create');
    //Route::post('edu_report/add_one', [EduReportController::class,'add_one'])->name('edu_report.add_one');
    //Route::post('edu_report/add_one_school', [EduReportController::class,'add_one_school'])->name('edu_report.add_one_school');
    Route::post('edu_report/store', [EduReportController::class,'store'])->name('edu_report.store');
    Route::get('edu_report/{report}/edit', [EduReportController::class,'edit'])->name('edu_report.edit');
    Route::get('edu_report/{id}/{filename}/delete_file', [EduReportController::class,'delete_file'])->name('edu_report.delete_file');
    Route::get('edu_report/{report}/show', [EduReportController::class,'show'])->name('edu_report.show');
    Route::get('edu_report/{report}/print', [EduReportController::class,'print'])->name('edu_report.print');
    Route::get('edu_report/{report}/date_late', [EduReportController::class,'date_late'])->name('edu_report.date_late');
    Route::patch('edu_report/{report}/save_date_late', [EduReportController::class,'save_date_late'])->name('edu_report.save_date_late');
    Route::get('edu_report/{report}/result', [EduReportController::class,'result'])->name('edu_report.result');
    Route::get('edu_report/{report}/result2', [EduReportController::class,'result2'])->name('edu_report.result2');
    Route::patch('edu_report/{report}/update', [EduReportController::class,'update'])->name('edu_report.update');
    //增加附件
    //Route::post('edu_report/add_file', [EduReportController::class,'add_file'])->name('edu_report.add_file');
    //刪除附件
    Route::get('edu_report/{id}/{file}/del_file', [EduReportController::class,'del_file'])->name('edu_report.del_file');
    Route::delete('edu_report/{report}/destroy', [EduReportController::class,'destroy'])->name('edu_report.destroy');
    //Route::get('edu_report/{question}/question_destroy', [EduReportController::class,'question_destroy'])->name('edu_report.question_destroy');
    //Route::get('edu_report/{report}/{school_id}/school_destroy', [EduReportController::class,'school_destroy'])->name('edu_report.school_destroy');
    Route::get('edu_report/passing', [EduReportController::class,'passing'])->name('edu_report.passing');
    //再次送審
    Route::patch('edu_report/{report}/resend', [EduReportController::class,'resend'])->name('edu_report.resend');

    Route::patch('regular_report/{regular_report}/resend', [RegularReportController::class,'resend'])->name('regular_report.resend');

    //下載excel
    Route::get('edu_report/{report}/export', [EduReportController::class,'export'])->name('edu_report.export');

    //作廢
    Route::get('edu_report/{report}/obsolete', [EduReportController::class,'obsolete'])->name('edu_report.obsolete');
    Route::get('edu_report/{report}/copy', [EduReportController::class,'copy'])->name('edu_report.copy');

    //催促公告
    Route::post('edu_report/post', [EduReportController::class,'post'])->name('edu_report.post');

    //退回學校的填報
    Route::get('edu_report/{report_school}/set_back', [EduReportController::class,'set_back'])->name('edu_report.set_back');
    Route::get('edu_report/{report_school}/set_null', [EduReportController::class,'set_null'])->name('edu_report.set_null');

    //定期填報    
    Route::get('edu_regular_report', [RegularReportController::class,'index'])->name('edu_regular_report.index');
    Route::get('edu_regular_report/passing', [RegularReportController::class,'passing'])->name('edu_regular_report.passing');
    Route::get('edu_regular_report/create', [RegularReportController::class,'create'])->name('edu_regular_report.create');
    Route::get('edu_regular_report/show/{regular_report}', [RegularReportController::class,'show'])->name('edu_regular_report.show');
    Route::get('edu_regular_report/show_sample/{regular_sample}', [RegularReportController::class,'show_sample'])->name('edu_regular_report.show_sample');
    Route::get('edu_regular_report/create_by_sample/{regular_sample}', [RegularReportController::class,'create_by_sample'])->name('edu_regular_report.create_by_sample');
    Route::post('edu_regular_report/store_by_sample', [RegularReportController::class,'store_by_sample'])->name('edu_regular_report.store_by_sample');
    Route::delete('edu_regular_report/{regular_report}/delete_by_sample', [RegularReportController::class,'delete_by_sample'])->name('edu_regular_report.delete_by_sample');
    Route::get('edu_regular_report/{regular_report}/edit', [RegularReportController::class,'edit_by_sample'])->name('edu_regular_report.edit_by_sample');
    Route::patch('edu_regular_report/{regular_report}/update', [RegularReportController::class,'update_by_sample'])->name('edu_regular_report.update_by_sample');
    Route::get('edu_regular_report/{id}/{file}/del_file', [RegularReportController::class,'del_file'])->name('edu_regular_report.del_file');
    Route::get('edu_regular_report/{regular_report}/date_late', [RegularReportController::class,'date_late'])->name('edu_regular_report.date_late');
    Route::patch('edu_regular_report/{regular_report}/save_date_late', [RegularReportController::class,'save_date_late'])->name('edu_regular_report.save_date_late');
    Route::get('edu_regular_report/{regular_report}/obsolete', [RegularReportController::class,'obsolete'])->name('edu_regular_report.obsolete');
    Route::get('edu_regular_report/{regular_report}/result', [RegularReportController::class,'result'])->name('edu_regular_report.result');
    Route::get('edu_regular_report/{regular_report}/result2', [RegularReportController::class,'result2'])->name('edu_regular_report.result2');
    //下載excel
    Route::get('edu_regular_report/{regular_report}/export', [RegularReportController::class,'export'])->name('edu_regular_report.export');

    //退回學校的定期填報
    Route::get('edu_regular_report/{regular_report_school}/set_back', [RegularReportController::class,'set_back'])->name('edu_regular_report.set_back');
    Route::get('edu_regular_report/{regular_report_school}/set_null', [RegularReportController::class,'set_null'])->name('edu_regular_report.set_null');

});
//系統管理者、科室管理者
Route::group(['middleware' => 'all_admin'],function(){
    //更改密碼
    Route::get('edit_password',[HomeController::class,'edit_password'])->name('edit_password');
    Route::patch('update_password',[HomeController::class,'update_password'])->name('update_password');

    //橫幅廣告
    Route::get('title_image_index',[TitleImageController::class,'index'])->name('title_image_index');
    Route::post('title_image_add',[TitleImageController::class,'add'])->name('title_image_add');
    Route::get('title_image_delete/{title_image}',[TitleImageController::class,'delete'])->name('title_image_delete');
    Route::get('title_image_edit/{title_image}',[TitleImageController::class,'edit'])->name('title_image_edit');
    Route::post('title_image_update/{title_image}',[TitleImageController::class,'update'])->name('title_image_update');

    //選單連結
    Route::get('menu_index/{id?}',[MenuController::class,'index'])->name('menu_index');    
    Route::post('menu_add',[MenuController::class,'add'])->name('menu_add');
    Route::get('menu_edit/{menu}',[MenuController::class,'edit'])->name('menu_edit');
    Route::post('menu_update/{menu}',[MenuController::class,'update'])->name('menu_update');
    Route::get('menu_delete/{menu}',[MenuController::class,'delete'])->name('menu_delete');

    //內容管理
    Route::get('contents/index', [ContentController::class,'index'])->name('contents.index');
    Route::get('contents/create', [ContentController::class,'create'])->name('contents.create');
    Route::post('contents/upload_image', [ContentController::class,'upload_image'])->name('contents.upload_image');
    Route::post('contents/store', [ContentController::class,'store'])->name('contents.store');
    Route::post('contents/destroy/{content}', [ContentController::class,'destroy'])->name('contents.destroy');
    Route::get('contents/edit/{content}', [ContentController::class,'edit'])->name('contents.edit');
    Route::post('contents/update/{content}', [ContentController::class,'update'])->name('contents.update');    

    //album
    Route::get('photo_albums/index', [PhotoAlbumController::class,'index'])->name('photo_albums.index');
    //Route::get('photo_albums/create', [PhotoAlbumController::class,'create'])->name('photo_albums.create');
    Route::post('photo_albums/store', [PhotoAlbumController::class,'store'])->name('photo_albums.store');
    Route::get('photo_albums/{photo_album}/show', [PhotoAlbumController::class,'show'])->name('photo_albums.show');
    Route::post('photo_albums/{photo_album}/store_photo', [PhotoAlbumController::class,'store_photo'])->name('photo_albums.store_photo');
    Route::get('photo_albums/{photo_album}/delete', [PhotoAlbumController::class,'delete'])->name('photo_albums.delete');
    Route::get('photo_albums/{photo_album}/edit', [PhotoAlbumController::class,'edit'])->name('photo_albums.edit');
    Route::post('photo_albums/{photo_album}/update', [PhotoAlbumController::class,'update'])->name('photo_albums.update');    
    Route::get('photo_albums/{photo}/delete_photo', [PhotoAlbumController::class,'delete_photo'])->name('photo_albums.delete_photo');    
});

//最高管理者可用
Route::group(['middleware' => 'admin'],function(){
    //模擬登入
    Route::get('admin/{user}/impersonate', [AdminsController::class,'impersonate'])->name('admins.impersonate');

    //帳號管理
    Route::get('admin/user_index' , [AdminsController::class,'user_index'])->name('admins.user_index');
    Route::get('admin/user_check' , [AdminsController::class,'user_check'])->name('admins.user_check');
    Route::get('admin/user_db2' , [DB2Controller::class,'user_db2'])->name('admins.user_db2');
    Route::get('admin/user_db2_create' , [DB2Controller::class,'user_db2_create'])->name('admins.user_db2_create');
    //Route::get('admin/{id}/user_db2_delete' , [DB2Controller::class,'user_db2_delete'])->name('admins.user_db2_delete');
    Route::post('admin/user_db2_search' , [DB2Controller::class,'user_db2_search'])->name('admins.user_db2_search');
    Route::post('admin/user_db2_store' , [DB2Controller::class,'user_db2_store'])->name('admins.user_db2_store');
    Route::post('admin/user_db2_store2' , [DB2Controller::class,'user_db2_store2'])->name('admins.user_db2_store2');
    Route::get('admin/{id}/user_db2_out' , [DB2Controller::class,'user_db2_out'])->name('admins.user_db2_out');
    Route::get('admin/{id}/user_db2_in' , [DB2Controller::class,'user_db2_in'])->name('admins.user_db2_in');
    Route::post('admin/{id}/user_db2_change' , [DB2Controller::class,'user_db2_change'])->name('admins.user_db2_change');
    Route::post('admin/{id}/user_db2_change2' , [DB2Controller::class,'user_db2_change2'])->name('admins.user_db2_change2');
    Route::get('admin/{group_id}/user_group' , [AdminsController::class,'user_group'])->name('admins.user_group');
    Route::match(['post','get'],'admin/user_search/{want?}',[AdminsController::class,'user_search'])->name('admins.user_search');
    Route::get('admin/user', [AdminsController::class,'user'])->name('admins.user');
    Route::get('admin/user/{user}/edit', [AdminsController::class,'user_edit'])->name('admins.user_edit');
    Route::post('admin/user/{user}/update', [AdminsController::class,'user_update'])->name('admins.user_update');
    Route::delete('admin/user/{user}/destroy',[AdminsController::class,'user_destroy'])->name('admins.user_destroy');
    Route::get('admin/user/{user}/reback',[AdminsController::class,'user_reback'])->name('admins.user_reback');
    //變更local使用者密碼
    Route::get('admin/reback_password/{user}',[AdminsController::class,'reback_password'])->name('reback_password');

    //教育處介紹
    Route::get('admin/introduction/index', [AdminsController::class,'introduction_index'])->name('admins.introduction_index');
    Route::get('admin/introduction/{type}/organization', [AdminsController::class,'introduction_organization'])->name('admins.introduction_organization');
    Route::get('admin/introduction/{type}/people', [AdminsController::class,'introduction_people'])->name('admins.introduction_people');
    Route::get('admin/introduction/{type}/people2', [AdminsController::class,'introduction_people2'])->name('admins.introduction_people2');
    Route::get('admin/introduction/{type}/site', [AdminsController::class,'introduction_site'])->name('admins.introduction_site');
    Route::post('admin/introduction/store', [AdminsController::class,'introduction_store'])->name('admins.introduction_store');
    Route::post('admin/introduction/store2', [AdminsController::class,'introduction_store2'])->name('admins.introduction_store2');

    //相關連結    
    Route::get('admin/link/create', [AdminsController::class,'link_create'])->name('admins.link_create');
    Route::post('admin/link', [AdminsController::class,'link_store'])->name('admins.link_store');
    Route::delete('admin/link/{link}', [AdminsController::class,'link_destroy'])->name('admins.link_destroy');
    Route::get('admin/link/{link}/edit', [AdminsController::class,'link_edit'])->name('admins.link_edit');
    Route::patch('admin/link/{link}', [AdminsController::class,'link_update'])->name('admins.link_update');


    //其他連結
    Route::get('admin/other', [AdminsController::class,'other_index'])->name('admins.other_index');
    Route::get('admin/other/create', [AdminsController::class,'other_create'])->name('admins.other_create');
    Route::post('admin/other', [AdminsController::class,'other_store'])->name('admins.other_store');
    Route::delete('admin/other/{other}', [AdminsController::class,'other_destroy'])->name('admins.other_destroy');
    Route::get('admin/other/{other}/edit', [AdminsController::class,'other_edit'])->name('admins.other_edit');
    Route::patch('admin/other/{other}', [AdminsController::class,'other_update'])->name('admins.other_update');

    //系統公告
    Route::get('admin/sys_post', [AdminsController::class,'sys_post_index'])->name('admins.sys_post_index');    
    Route::post('admin/sys_post_store',[AdminsController::class,'sys_post_store'])->name('admins.sys_post_store');    
    Route::get('admin/ys_post_destroy/{system_post}',[AdminsController::class,'sys_post_destroy'])->name('admins.sys_post_destroy');    

    //清理資料
    Route::get('admin/clean_index', [AdminsController::class,'clean_index'])->name('admins.clean_index');
    Route::post('admin/clean_do_post', [AdminsController::class,'clean_do_post'])->name('admins.clean_do_post');
    Route::post('admin/clean_do_report', [AdminsController::class,'clean_do_report'])->name('admins.clean_do_report');

    Route::get('admin/special', [AdminsController::class,'special'])->name('admins.special');
    Route::post('admin/special_post', [AdminsController::class,'special_post'])->name('admins.special_post');
    Route::post('admin/special_post_delete', [AdminsController::class,'special_post_delete'])->name('admins.special_post_delete');
    Route::post('admin/special_report', [AdminsController::class,'special_report'])->name('admins.special_report');
    Route::post('admin/special_report_delete', [AdminsController::class,'special_report_delete'])->name('admins.special_report_delete');


    //log
    Route::get('logs',[AdminsController::class,'logs'])->name('logs');

    //管理員回覆
    Route::post('wrench/reply', [WrenchController::class,'reply'])->name('wrench.reply');
    Route::get('wrench/set_show/{wrench}', [WrenchController::class,'set_show'])->name('wrench.set_show');
    Route::get('wrench/destroy/{wrench}', [WrenchController::class,'destroy'])->name('wrench.destroy');
    
    //關閉系統
    Route::get('close_system',[AdminsController::class,'close_system'])->name('close_system');

});

//admin1~admin9及有教育處科內一級管理A才可進入
//科室管理者及admin1~admin9
Route::group(['middleware' => 'section_admin'],function(){
    //科室頁面介紹
    Route::get('introduction/organization', [IntroductionController::class,'organization'])->name('introductions.organization');
    Route::get('introduction/people', [IntroductionController::class,'people'])->name('introductions.people');
    Route::get('introduction/site', [IntroductionController::class,'site'])->name('introductions.site');
    Route::post('introduction', [IntroductionController::class,'store'])->name('introductions.store');
    Route::get('introduction/section_page_add', [IntroductionController::class,'section_page_add'])->name('introductions.section_page_add');
    Route::post('introduction/section_page_store', [IntroductionController::class,'section_page_store'])->name('introductions.section_page_store');
    Route::get('introduction/section_page/{section_page}', [IntroductionController::class,'section_page'])->name('introductions.section_page');
    Route::get('introduction/section_page_del/{section_page}', [IntroductionController::class,'section_page_del'])->name('introductions.section_page_del');
    Route::post('introduction/section_page_update/{section_page}', [IntroductionController::class,'section_page_update'])->name('introductions.section_page_update');

    //成員管理
    Route::get('my_section/admin', [MySectionController::class,'admin'])->name('my_section.admin');
    Route::get('my_section/admin_db2', [DB2Controller::class,'admin_db2'])->name('my_section.admin_db2');
    Route::get('my_section/{id}/admin_db2_out' , [DB2Controller::class,'admin_db2_out'])->name('admins.db2_out');
    Route::get('my_section/{id}/admin_db2_in' , [DB2Controller::class,'admin_db2_in'])->name('admins.db2_in');
    Route::get('my_section/admin_db2_create' , [DB2Controller::class,'admin_db2_create'])->name('admins.db2_create');
    Route::post('my_section/admin_db2_store' , [DB2Controller::class,'admin_db2_store'])->name('admins.db2_store');
    Route::get('my_section/{user}/agree', [MySectionController::class,'agree'])->name('my_section.agree');
    Route::get('my_section/{user}/disagree', [MySectionController::class,'disagree'])->name('my_section.disagree');
    Route::get('my_section/{user}/remove', [MySectionController::class,'remove'])->name('my_section.remove');
    Route::get('my_section/power', [MySectionController::class,'power'])->name('my_section.power');
    Route::post('my_section/power_update1', [MySectionController::class,'power_update1'])->name('my_section.power_update1');
    Route::post('my_section/power_update2', [MySectionController::class,'power_update2'])->name('my_section.power_update2');
    Route::get('my_section/{id}/power_remove', [MySectionController::class,'power_remove'])->name('my_section.power_remove');
    Route::get('my_section/member', [MySectionController::class,'member'])->name('my_section.member');
    Route::post('my_section/update', [MySectionController::class,'member_update'])->name('my_section.member_update');
    Route::post('my_section/update2', [MySectionController::class,'member_update2'])->name('my_section.member_update2');

    //刪除跑馬燈
    Route::get('marquees/{marquee}/delete' , [MarqueeController::class,'delete'])->name('marquees.delete');
});

//教育處科室內的人，且是一級管理A身分才可進入
//教育處科室長官可用
Route::group(['middleware' => 'edu_admin'],function(){
    //公告審查
    Route::get('posts/review', [PostsController::class,'review'])->name('posts.review');
    //退回指定的公告內容
    Route::patch('posts/{post}/return', [PostsController::class,'return'])->name('posts.return');
    //核准指定的公告內容
    Route::get('posts/{post}/approve', [PostsController::class,'approve'])->name('posts.approve');
    //將核准的公告寫到Post_schools資料表
    Route::post('posts/{post}/addPostSchools', [PostsController::class,'addPostSchools'])->name('posts.addPostSchools');

    //修改的指定公告
    Route::get('posts/{id}/eduadminedit', [PostsController::class,'eduadminedit'])->name('posts.eduadminedit');
    //實際儲存修改好的公告資料
    Route::patch('posts/{id}/eduadminupdate', [PostsController::class,'eduadminupdate'])->name('posts.eduadminupdate');

        //資料填報審查
    Route::get('reports/review', [EduReportController::class,'review'])->name('reports.review');
    //退回指定的填報內容
    Route::patch('reports/{report}/return', [EduReportController::class,'return'])->name('reports.return');
    //核准指定的填報內容
    Route::patch('reports/{report}/approve', [EduReportController::class,'approve'])->name('reports.approve');

    //退回指定的定期填報內容
    Route::patch('regular_reports/{regular_report}/return', [RegularReportController::class,'return'])->name('regular_reports.return');
    //核准指定的定期填報內容
    Route::patch('regular_reports/{regular_report}/approve', [RegularReportController::class,'approve'])->name('regular_reports.approve');

    //顯示本科室內的全數公告
    Route::get('posts/section_all', [PostsController::class,'section_all'])->name('posts.section_all');        
    Route::get('posts/all', [PostsController::class,'all'])->name('posts.all');
    Route::post('posts/do_search', [PostsController::class,'do_search'])->name('posts.do_search');
    Route::get('posts/{want}/all_search', [PostsController::class,'all_search'])->name('posts.all_search');
    Route::post('posts/select_category', [PostsController::class,'select_category'])->name('posts.select_category');
    Route::get('posts/{category}/all_category', [PostsController::class,'all_category'])->name('posts.all_category');
    Route::post('posts/select_situation', [PostsController::class,'select_situation'])->name('posts.select_situation');
    Route::get('posts/{situation}/all_situation', [PostsController::class,'all_situation'])->name('posts.all_situation');
    Route::get('posts/{user_id}/all_user_id', [PostsController::class,'all_user_id'])->name('posts.all_user_id');

    //審核者可看
    Route::get('reports/section_all', [EduReportController::class,'section_all'])->name('reports.section_all');
    Route::post('reports/do_search_in_section', [EduReportController::class,'do_search_in_section'])->name('reports.do_search_in_section');
    Route::get('reports/{want}/do_search', [EduReportController::class,'do_search'])->name('reports.do_search');    

    //定期填報審核者可看
    Route::get('regular_reports/section_all', [RegularReportController::class,'section_all'])->name('regular_reports.section_all');    
    
});

//學校一級管理A才可進入
//學校管理可用
Route::group(['middleware' => 'school_admin'],function(){
    //列出學校帳號
    Route::get('school_acc', [SchoolController::class,'index'])->name('school_acc.index');
    //列出所有管理學校的權限名單
    Route::get('school_acc/list', [SchoolController::class,'list'])->name('school_acc.list');
    Route::get('school_acc/{id}/power_remove', [SchoolController::class,'power_remove'])->name('school_acc.power_remove');
    //修改帳號
    Route::get('school_acc/{user}/edit', [SchoolController::class,'edit'])->name('school_acc.edit');
    Route::post('school_acc/{user}/update', [SchoolController::class,'update'])->name('school_acc.update');
    Route::get('school_acc/{user}/destroy', [SchoolController::class,'destroy'])->name('school_acc.destroy');
    Route::get('school_acc/{user}/reback', [SchoolController::class,'reback'])->name('school_acc.reback');

    Route::patch('school_report/{report_school}/back', [SchoolReportController::class,'back'])->name('school_report.back');
    Route::patch('school_report/{report_school}/delay', [SchoolReportController::class,'delay'])->name('school_report.delay');
    Route::patch('school_report/{report_school}/cancel', [SchoolReportController::class,'cancel'])->name('school_report.cancel');
    Route::patch('school_report/{report_school}/passing', [SchoolReportController::class,'passing'])->name('school_report.passing');

    Route::patch('school_regular_report/{regular_report_school}/back', [RegularReportController::class,'school_back'])->name('school_regular_report.back');
    Route::patch('school_regular_report/{regular_report_school}/delay', [RegularReportController::class,'school_delay'])->name('school_regular_report.delay');
    Route::patch('school_regular_report/{regular_report_school}/cancel', [RegularReportController::class,'school_cancel'])->name('school_regular_report.cancel');
    Route::patch('school_regular_report/{regular_report_school}/passing', [RegularReportController::class,'school_passing'])->name('school_regular_report.passing');

    Route::post('school_acc/other', [SchoolController::class,'other'])->name('school_acc.other');
    Route::post('school_acc/store_other', [SchoolController::class,'store_other'])->name('school_acc.store_other');

    //學校簡介
    Route::get('school_introduction', [SchoolController::class,'school_introduction'])->name('school_introduction.index');
    Route::post('school_introduction_store', [SchoolController::class,'school_introduction_store'])->name('school_introduction.store');
});

//學校一級簽收B才可進入
//學校簽收填報者可用
Route::group(['middleware' => 'school_sign'], function () {
    //顯示簽收公告
    Route::get('posts/showSigned', [PostsController::class,'showSigned'])->name('posts.showSigned');
    Route::get('posts/show_not_Signed', [PostsController::class,'show_not_Signed'])->name('posts.show_not_Signed');
    Route::get('posts/show_quick_Signed', [PostsController::class,'show_quick_Signed'])->name('posts.show_quick_Signed');
    Route::get('posts/show_person_Signed', [PostsController::class,'show_person_Signed'])->name('posts.show_person_Signed');
    //列印簽收公告
    Route::get('posts/{post}/showSigned_print', [PostsController::class,'showSigned_print'])->name('posts.showSigned_print');
    Route::get('posts/{post}/showSigned_print2', [PostsController::class,'showSigned_print2'])->name('posts.showSigned_print2');
    Route::get('posts/{post}/showSigned_print3', [PostsController::class,'showSigned_print3'])->name('posts.showSigned_print3');

    //簽收公告路由
    Route::patch('posts/{ps_id}/signed', [PostsController::class,'signed'])->name('posts.signed');
    Route::patch('posts/{ps_id}/signed_at_show', [PostsController::class,'signed_at_show'])->name('posts.signed_at_show');
    Route::post('posts/signed_more', [PostsController::class,'signed_more'])->name('posts.signed_more');
    Route::patch('posts/{ps_id}/signed2', [PostsController::class,'signed2'])->name('posts.signed2');
    Route::patch('posts/{ps_id}/signed3', [PostsController::class,'signed3'])->name('posts.signed3');

    //搜尋公告編號主旨、內文、公告人
    Route::match(['post', 'get'], 'posts/search', [PostsController::class,'search'])->name('posts.search');
    Route::get('posts/search_by_section/{section_id}', [PostsController::class,'search_by_section'])->name('posts.search_by_section');

    //資料填報
    Route::get('school_report', [SchoolReportController::class,'index'])->name('school_report.index');
    Route::get('school_report_not', [SchoolReportController::class,'not_index'])->name('school_report_not.index');
    Route::get('show_person_Signed', [SchoolReportController::class,'show_person_Signed'])->name('school_report.show_person_Signed');
    //搜尋填報編號主旨、內文、公告人
    Route::match(['post', 'get'], 'school_report/search', [SchoolReportController::class,'search'])->name('school_report.search');
    Route::get('school_report/{report_school}/create', [SchoolReportController::class,'create'])->name('school_report.create');
    //20230815取消這功能
    //Route::get('school_report/{report_school}/no_report', [SchoolReportController::class,'no_report'])->name('school_report.no_report');
    Route::post('school_report/store', [SchoolReportController::class,'store'])->name('school_report.store');
    Route::get('school_report/{report_school}/show', [SchoolReportController::class,'show'])->name('school_report.show');
    Route::get('school_report/{report_school}/edit', [SchoolReportController::class,'edit'])->name('school_report.edit');
    Route::patch('school_report/update', [SchoolReportController::class,'update'])->name('school_report.update');
    Route::post('school_report/save_temp', [SchoolReportController::class,'save_temp'])->name('school_report.save_temp');
    Route::post('school_report/pull_temp/{report_id}', [SchoolReportController::class,'pull_temp'])->name('school_report.pull_temp');

    //列印資料填報列表
    Route::get('school_report/{report_school}/print', [SchoolReportController::class,'print'])->name('school_report.print');
    //列印單一資料填報
    Route::get('school_report/{report_school}/print2', [SchoolReportController::class,'print2'])->name('school_report.print2');

    //定期資料填報
    Route::get('school_regular_report', [RegularReportController::class,'school_index'])->name('school_regular_report.index');
    Route::get('school_regular_report_not', [RegularReportController::class,'not_index'])->name('school_regular_report_not.index');
    Route::get('regular_show_person_Signed', [RegularReportController::class,'show_person_Signed'])->name('school_regular_report.show_person_Signed');
    Route::get('school_regular_report/{regular_report_school}/show', [RegularReportController::class,'school_show'])->name('school_regular_report.show');
    Route::get('school_regular_report/{regular_report_school}/create', [RegularReportController::class,'school_create'])->name('school_regular_report.create');
    Route::post('school_regular_report/store', [RegularReportController::class,'school_store'])->name('school_regular_report.store');
    Route::get('school_regular_report/{regular_report_school}/edit', [RegularReportController::class,'school_edit'])->name('school_regular_report.edit');
    Route::patch('school_regular_report/{regular_report_school}/update', [RegularReportController::class,'school_update'])->name('school_regular_report.update');
    Route::post('school_regular_report/save_temp', [RegularReportController::class,'school_save_temp'])->name('school_regular_report.save_temp');
    Route::post('school_regular_report/pull_temp/{regular_report_id}', [RegularReportController::class,'school_pull_temp'])->name('school_regular_report.pull_temp');

    //列印資料填報列表
    Route::get('school_regular_report/{regular_report_school}/print', [RegularReportController::class,'school_print'])->name('school_regular_report.print');
    //列印單一資料填報
    Route::get('school_regular_report/{regular_report_school}/print2', [RegularReportController::class,'school_print2'])->name('school_regular_report.print2');
});

//其他類學校的單位
Route::group(['middleware' => 'school_sign_other'], function () {
    //顯示簽收公告
    Route::get('posts/showSigned_other', [PostsController::class,'showSigned_other'])->name('posts.showSigned_other');
    //簽收公告路由
    Route::patch('posts/{ps_id}/signed_other', [PostsController::class,'signed_other'])->name('posts.signed_other');
    //其他單位人員管理
    Route::get('posts/people_other', [PostsController::class,'people_other'])->name('posts.people_other');
    Route::post('posts/people_add', [PostsController::class,'people_add'])->name('posts.people_add');
    Route::get('posts/{user}/people_remove', [PostsController::class,'people_remove'])->name('posts.people_remove');
});