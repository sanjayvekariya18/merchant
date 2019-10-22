<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Menus;
use App\Module;
use App\Menus_database_manager;
use App\Database_manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use Redirect;

class MenuController extends PermissionsController
{

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Menus');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::all();
        $menus   = Menus::where("parent", 0)->orderBy('hierarchy', 'asc')->get();

        return view('menus.index', compact('menus', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $name = Input::get('name');
        $url  = Input::get('url');
        $icon = Input::get('icon');
        $type = Input::get('type');

        if ($type === "newmodule") {

            $modules                 = new Module();
            $modules->name           = $name;
            $modules->controller_url = $url;
            $modules->fa_icon        = $icon;
            $modules->save();
            return redirect('menus');

        } else {

            if ($type === "module") {
                $module_id = Input::get('module_id');
                $module    = Module::find($module_id);
                if (isset($module->id)) {
                    $name = $module->name;
                    $url  = $module->controller_url;
                    $icon = $module->fa_icon;
                } else {
                    return response()->json([
                        "status"  => "failure",
                        "message" => "Module does not exists",
                    ], 200);
                }
            }

            $menus         = new Menus();
            $menus->name   = $name;
            $menus->url    = $url;
            $menus->icon   = $icon;
            $menus->type   = $type;
            $menus->parent = 0;
            $menus->save();

            if ($type === "module") {
                return response()->json([
                    "status" => "success",
                ], 200);
            } else {
                return redirect('menus');
            }
        }
    }

    /**
     * Update Custom Menu
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $name = Input::get('name');
        $url  = Input::get('url');
        $icon = Input::get('icon');
        $type = Input::get('type');

        if ($type === "editmodule") {
            $module                 = Module::find($id);
            $module->name           = $name;
            $module->controller_url = $url;
            $module->fa_icon        = $icon;
            $module->save();

        } else {
            $menu       = Menus::find($id);
            $menu->name = $name;
            $menu->url  = $url;
            $menu->icon = $icon;
            $menu->save();
        }

        return redirect('menus');
    }

    public function get_posts_children($parent_id)
    {

        $children = array();
        // grab the posts children
        $parents = Menus::select('id')->where('parent', $parent_id)->get()->toArray();
        // now grab the grand children
        foreach ($parents as $child) {
            // recursion!! hurrah
            $gchildren = $this->get_posts_children($child['id']);
            // merge the grand children into the children array
            if (!empty($gchildren)) {
                $children = array_merge($children, $gchildren);
            }
        }
        // merge in the direct descendants we found earlier
        $children = array_merge($children, $parents);
        return $children;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_menu($id)
    {
        $menus       = array($id);
        $descendants = $this->get_posts_children($id);

        foreach ($descendants as $node) {
            $menus[] = $node['id'];
        }

        Menus::whereIn('id', $menus)->delete();
        return redirect('menus');
    }

    public function delete_module($id)
    {

        Module::where('id', $id)->delete();
        return redirect('menus');
    }

    /**
     * Update Menu Hierarchy
     *
     * @return \Illuminate\Http\Response
     */
    public function update_hierarchy()
    {
        $parents   = Input::get('jsonData');
        $parent_id = 0;

        for ($i = 0; $i < count($parents); $i++) {
            $this->apply_hierarchy($parents[$i], $i + 1, $parent_id);
        }

        return $parents;
    }

    public function apply_hierarchy($menuItem, $num, $parent_id)
    {

        $menu            = Menus::find($menuItem['id']);
        $menu->parent    = $parent_id;
        $menu->hierarchy = $num;
        $menu->save();

        $this->updateDatabaseManager($menuItem['id'],$parent_id);

        if (isset($menuItem['children'])) {
            for ($i = 0; $i < count($menuItem['children']); $i++) {
                $this->apply_hierarchy($menuItem['children'][$i], $i + 1, $menuItem['id']);
            }
        }
    }

    public function updateDatabaseManager($menuId,$parentId){

        $isExist = Menus_database_manager::where('menu_id',$menuId)->get()->count();

        if($isExist > 0 && $parentId != 0){
            // Delete assign database manager to the particular menu
            Menus_database_manager::where('menu_id',$menuId)->delete();
        }else if($isExist == 0 && $parentId == 0){
            // Assign database manager to the particular menu
            $databaseManagerValues = Database_manager::all();
            foreach ($databaseManagerValues as $databaseManager) {
                $databaseMenu = new Menus_database_manager();
                $databaseMenu->menu_id = $menuId;
                $databaseMenu->provider_id = $databaseManager['id'];
                $databaseMenu->priority = $databaseManager['id'];
                $databaseMenu->save();
            }
        }
    }
}
