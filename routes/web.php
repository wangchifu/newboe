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


Route::get('/', [HomeController::class,'index'])->name('index');
Route::get('index', [HomeController::class,'index'])->name('index');
Route::get('glogin', [HomeController::class,'glogin'])->name('glogin');
Route::get('mlogin', [HomeController::class,'mlogin'])->name('mlogin');
Route::post('gauth', [HomeController::class,'gauth'])->name('gauth');
Route::post('mauth', [HomeController::class,'mauth'])->name('mauth');
Route::get('logout', [HomeController::class,'logout'])->name('logout');


//認證圖片
Route::get('pic', [HomeController::class,'pic'])->name('pic');

//內容頁面
Route::get('contents/{content}/show', [ContentController::class,'show'])->where('content', '[0-9]+')->name('contents.show');

//相簿
Route::get('photo_albums/guest', [PhotoAlbumController::class,'guest'])->name('photo_albums.guest');
Route::get('photo_albums/{photo_album}/guest_show', [PhotoAlbumController::class,'guest_show'])->name('photo_albums.guest_show');

//停用系統
Route::get('close', [AdminsController::class,'close'])->name('close');

//已註冊使用者可進入
Route::group(['middleware' => 'auth'],function(){
    //結束模擬
        Route::get('sims/impersonate_leave', [AdminsController::class,'impersonate_leave'])->name('sims.impersonate_leave');
    
        //下載資料填報附檔
        //Route::get('edu_report/{id}/{filename}/download', [EduReportController::class,'download'])->name('edu_report.download');
    
        //報錯
        //Route::get('wrench/index', [WrenchController::class,'index'])->name('wrench.index');
        //Route::post('wrench/store', [WrenchController::class,'store'])->name('wrench.store');
        //Route::get('wrench/download/{wrench_id}/{filename}', [WrenchController::class,'download'])->name('wrench.download');
    
        //常見問題集
        //Route::get('questions/index', [HomeController::class,'questions'])->name('questions.index');
        //Route::get('questions/about', [HomeController::class,'about'])->name('questions.about');
    
        //Route::get('user_reads/{no_read_sp}', [HomeController::class,'user_reads'])->name('user_reads');
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
    Route::get('admin/{group_id}/user_group' , [AdminsController::class,'user_group'])->name('admins.user_group');
    Route::match(['post','get'],'admin/user_search',[AdminsController::class,'user_search'])->name('admins.user_search');
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

    //其他連結
    Route::get('admin/other', [AdminsController::class,'other_index'])->name('admins.other_index');
    Route::get('admin/other/create', [AdminsController::class,'other_create'])->name('admins.other_create');
    Route::post('admin/other', [AdminsController::class,'other_store'])->name('admins.other_store');
    Route::delete('admin/other/{other}', [AdminsController::class,'other_destroy'])->name('admins.other_destroy');
    Route::get('admin/other/{other}/edit', [AdminsController::class,'other_edit'])->name('admins.other_edit');
    Route::patch('admin/other/{other}', [AdminsController::class,'other_update'])->name('admins.other_update');

    //log
    Route::get('logs',[AdminsController::class,'logs'])->name('logs');
    
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