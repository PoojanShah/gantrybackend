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

class SettingsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings(Request $request) {
        $data = Array();
        $data['page_title'] = 'Settings';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['settings'] = DB::table('settings')->get();

        return view('admin.settings.settings', ['data' => $data]);
    }

    public function settingsSave()
    {
        DB::table('settings')->delete();
        $data = $_POST;

        foreach ($data['settings_key'] as $key => $value) {
            if($data['settings_key'][$key][0] == '') {
                return 'Error';
            }
            else {
                DB::table('settings')->insert([
                    [
                        'settings_key' => $data['settings_key'][$key][0],
                        'settings_value' => $data['settings_value'][$key][0],
                        'settings_attr' => $data['settings_attr'][$key][0],
                    ]
                ]);
            }
        }
        return 'Added';
    }

    public function settingsDelete()
    {
        $id = $_GET['id'];
        DB::table('settings')->delete($id);
    }

}
