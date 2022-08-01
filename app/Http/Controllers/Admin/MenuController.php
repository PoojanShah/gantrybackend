<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Array_;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController as Admin;
use Illuminate\Support\Facades\Session;

class MenuController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function menu(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Menu';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['menu'] = DB::table('menu')->orderBy('sort', 'asc')->get();

        return view('admin.menu.menu', ['data' => $data]);
    }

    public function menuSave()
    {
        DB::table('menu')->delete();
        $data = $_POST;

        foreach ($data['sort'] as $key => $value) {
            if(isset($data['status'][$key][0]) && $data['status'][$key][0] == 'on') {
                $status = 1;
            }
            else {
                $status = 0;
            }
            if($data['value'][$key][0] == '') {
                return 'Error';
            }
            else {
                DB::table('menu')->insert([
                    [
                        'value' => $data['value'][$key][0],
                        'menu_key' => $data['menu_key'][$key][0],
                        'sort' => $value[0],
                        'status' => $status
                    ]
                ]);
            }
        }
        return 'Added';
    }

    public function menuDelete()
    {
        $id = $_GET['id'];
        DB::table('menu')->delete($id);
    }

    public function menuMobile(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Mobile Menu';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['menu'] = DB::table('menu_mobile')->orderBy('sort', 'asc')->get();

        return view('admin.menu.menu_mobile', ['data' => $data]);
    }

    public function menuMobileSave()
    {
        DB::table('menu_mobile')->delete();
        $data = $_POST;

        foreach ($data['sort'] as $key => $value) {
            if(isset($data['status'][$key][0]) && $data['status'][$key][0] == 'on') {
                $status = 1;
            }
            else {
                $status = 0;
            }
            if($data['value'][$key][0] == '') {
                return 'Error';
            }
            else {
                DB::table('menu_mobile')->insert([
                    [
                        'value' => $data['value'][$key][0],
                        'menu_key' => $data['menu_key'][$key][0],
                        'sort' => $value[0],
                        'status' => $status
                    ]
                ]);
            }
        }
        return 'Added';
    }

    public function menuMobileDelete()
    {
        $id = $_GET['id'];
        DB::table('menu_mobile')->delete($id);
    }

    public function menuFooter(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Footer Menu';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['menu'] = DB::table('menu_footer')->orderBy('sort', 'asc')->get();

        return view('admin.menu.menu_footer', ['data' => $data]);
    }

    public function menuFooterSave()
    {
        DB::table('menu_footer')->delete();
        $data = $_POST;

        foreach ($data['sort'] as $key => $value) {
            if(isset($data['status'][$key][0]) && $data['status'][$key][0] == 'on') {
                $status = 1;
            }
            else {
                $status = 0;
            }
            if($data['value'][$key][0] == '') {
                return 'Error';
            }
            else {
                DB::table('menu_footer')->insert([
                    [
                        'value' => $data['value'][$key][0],
                        'menu_key' => $data['menu_key'][$key][0],
                        'sort' => $value[0],
                        'status' => $status
                    ]
                ]);
            }
        }
        return 'Added';
    }

    public function menuFooterDelete()
    {
        $id = $_GET['id'];
        DB::table('menu_footer')->delete($id);
    }
}
