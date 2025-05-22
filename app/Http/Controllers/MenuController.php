<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Purifier;
class MenuController extends Controller
{
    public function index($id=null)
    {        
        $id=(empty($id))?0:$id;
        $menus = Menu::where('belong', $id)->orderBy('order_by')->get();
        $all_menus = Menu::all();
        foreach($all_menus as $all_menu){
            $menu2name[$all_menu->id] = $all_menu->name;
        }
        $path0 = "<nav aria-label='breadcrumb'><ol class='breadcrumb'><li class='breadcrumb-item'><a href='".route('menu_index')."'>最上層</a></li>";
        $path1 = "";
        $path9 = "</ol></nav>";
        if($id != 0){                    
            $this_menu = Menu::find($id);
            $this_menu_name = $this_menu->name;
            $this_menu_id = $this_menu->id;
            $this_menu_type = $this_menu->type;
            //取路徑的 id            
            $path_array = array_filter(explode(">",$this_menu->path.$id), fn($value) => $value !== "" && $value !== null);
            foreach($path_array as $k=>$v){
                if($v != 0){
                    $path1 = $path1."<li class='breadcrumb-item' aria-current='page'><a href='".route('menu_index',['id'=>$v])."'>".$menu2name[$v]."</a></li>";
                }                
            }            
        }else{
            $this_menu_name = "最上層";
            $this_menu_id = 0;
            $this_menu_type = 1;
        }
        
        $path = $path0.$path1.$path9;
        $data = [
            'menus' => $menus,
            'this_menu_name'=>$this_menu_name,
            'this_menu_id'=>$this_menu_id,
            'this_menu_type'=>$this_menu_type,
            'path'=>$path,
        ];
        return view('menus.index', $data);
    }    

    public function add(Request $request)
    {
        $att = $request->all();
        if ($att['belong'] == 0) {
            $att['path'] = ">0>";
        } else {
            $belong_menu = Menu::find($att['belong']);
            $att['path'] = $belong_menu->path . $belong_menu->id . ">";
        }
        if (!isset($att['order_by'])) {
            $att['order_by'] = 999;
        }

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph' => false));
        $att['link'] = Purifier::clean($att['link'], array('AutoFormat.AutoParagraph' => false));
        
        $menu = Menu::create($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 新增了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->route('menu_index');
    }

    public function delete(Menu $menu)
    {
        $son_menus = Menu::where('path', 'like', '%>' . $menu->id . '>%')->get();
        foreach ($son_menus as $son_menu) {
            $son_menu->delete();
        }
        $menu->delete();

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->back();
    }

    public function edit(Menu $menu)
    {
        $all_menus = Menu::all();
        foreach($all_menus as $all_menu){
            $menu2name[$all_menu->id] = $all_menu->name;
        }
        $path0 = "<nav aria-label='breadcrumb'><ol class='breadcrumb'><li class='breadcrumb-item'><a href='".route('menu_index')."'>最上層</a></li>";
        $path1 = "";
        $path9 = "</ol></nav>";        
        if($menu->belong != 0){                    
            $this_menu = Menu::find($menu->belong);
            $this_menu_name = $this_menu->name;
            $this_menu_id = $this_menu->id;
            //取路徑的 id            
            $path_array = array_filter(explode(">",$this_menu->path.$menu->belong), fn($value) => $value !== "" && $value !== null);
            foreach($path_array as $k=>$v){
                if($v != 0){
                    $path1 = $path1."<li class='breadcrumb-item' aria-current='page'><a href='".route('menu_index',['id'=>$v])."'>".$menu2name[$v]."</a></li>";
                }                
            }            
        }else{
            $this_menu_name = "最上層";
            $this_menu_id = 0;
        }
        
        $path = $path0.$path1.$path9;
        $data = [
            'menu' => $menu,
            'this_menu_name' => $this_menu_name,
            'this_menu_id' => $this_menu_id,
            'path' => $path,
        ];
        return view('menus.edit', $data);
    }

    public function update(Request $request, Menu $menu)
    {
        $att = $request->all();
        if ($att['belong'] == 0) {
            $att['path'] = ">0>";
        } else {
            $belong_menu = Menu::find($att['belong']);
            $att['path'] = $belong_menu->path . $belong_menu->id . ">";
        }
        if (!isset($att['order_by'])) {
            $att['order_by'] = 999;
        }

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph' => false));
        $att['link'] = Purifier::clean($att['link'], array('AutoFormat.AutoParagraph' => false));
        $menu->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 編輯了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->route('menu_index',$att['belong']);
    }    
}
