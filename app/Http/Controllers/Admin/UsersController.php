<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Array_;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController as Admin;
use Illuminate\Support\Facades\Session;

class UsersController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users(Request $request)
    {
        if($request->user()->superadmin != 1) {
            abort(403);
        }

        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs(__('Users'), $href);

        $data['request'] = $request->session()->get('datas');

        if ($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if ($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if ($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');

        $per_page = 10;

        $users = DB::table('users')->orderBy('id', 'asc')->paginate($per_page);

        $count = DB::table('users')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['users'] = $users;
        $data['count'] = $count;

        return view('admin.users.users', ['data' => $data]);
    }

    public function usersDelete()
    {
        $id = $_GET['id'];
        DB::table('users')->delete($id);
    }

    public function usersAdd(Request $request)
    {
        if($request->user()->superadmin != 1) {
            abort(403);
        }
        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('User', $href);

        $data['request'] = $request->session()->get('datas');

        if ($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if ($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if ($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.users.usersAdd', ['data' => $data]);
    }

    public function usersEdit(Request $request)
    {
        if($request->user()->superadmin != 1) {
            abort(403);
        }
        $data['user'] = $request->user();

        $data['user'] = DB::table('users')->where('id', '=', $request->id)->first();

        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('User', $href);

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['request'] = $request->session()->get('datas');

        if ($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if ($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if ($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.users.usersEdit', ['data' => $data]);
    }

    public function usersPostEdit(Request $request)
    {
        if($request->user()->superadmin != 1) {
            abort(403);
        }
        $data = $_POST;
        if (empty($request->email)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {

            $db_link = DB::table('users')->where('email', '=', $request->email)->first();

            if ($db_link != NULL && $request->id != $db_link->id) {
                $request->session()->put('error_link', 'This EMAIL is already in use!');
            } else {
                $db_link = DB::table('users')->where('id', '=', $request->id)->first();

                if(!empty($request->post('password'))) {
                    $password = Hash::make($request->post('password'));
                    DB::table('users')->where('id', $request->id)->update([
                        'name' => $request->post('name'),
                        'email' => $request->post('email'),
                        'password' => $password,
                        'superadmin' => $request->post('superadmin'),
                    ]);
                }
                else {
                    DB::table('users')->where('id', $request->id)->update([
                        'name' => $request->post('name'),
                        'email' => $request->post('email'),
                        'superadmin' => $request->post('superadmin'),
                    ]);
                }


                $request->session()->put('success', 'Successfully updated!');
            }
        }
        return redirect('/admin/users/edit/' . $request->id)->with(['datas' => $data]);
    }

    public function usersPostAdd(Request $request)
    {
        if($request->user()->superadmin != 1) {
            abort(403);
        }
        $data = $_POST;
        if (empty($request->email)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/users/add/')->with(['datas' => $data]);
        } else {
            $db_link = DB::table('users')->where('email', '=', $request->email)->count();
            if ($db_link > 0) {
                $request->session()->put('error_link', 'This EMAIL is already in use!');
                return redirect('/admin/users/add/')->with(['datas' => $data]);
            } else {

                $password = Hash::make($request->post('password'));
                DB::table('users')->insert([
                    'name' => $request->post('name'),
                    'email' => $request->post('email'),
                    'password' => $password,
                    'superadmin' => $request->post('superadmin'),
                    'new_email' => '',
                    'new_password' => '',
                    'token_for_password' => '',
                ]);

                $request->session()->put('success', 'Successfully added!');
                return redirect('/admin/users/')->with(['datas' => $data]);
            }

        }
    }
}
