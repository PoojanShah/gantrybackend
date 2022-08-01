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

class PortfolioController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function portfolio(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Portfolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

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

        $portfolio = DB::table('portfolio')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('portfolio')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['portfolio'] = $portfolio;
        $data['count'] = $count;

        return view('admin.portfolio.portfolio', ['data' => $data]);
    }

    public function portfolioDelete()
    {
        $id = $_GET['id'];
        DB::table('portfolio')->delete($id);
    }

    public function portfolioAdd(Request $request)
    {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Portfolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Portfolio', $href);

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
        return view('admin.portfolio.portfolioAdd', ['data' => $data]);
    }

    public function portfolioEdit(Request $request)
    {

        $data['user'] = $request->user();

        $data['portfolio'] = DB::table('portfolio')->where('id', '=', $request->id)->first();

        $data['contributions'] = DB::table('portfolio_contributions')->where('portfolio_id', '=', $request->id)->get();
        $data['links'] = DB::table('portfolio_links')->where('portfolio_id', '=', $request->id)->get();
        $data['flaglist'] = DB::table('portfolio_flaglist')->where('portfolio_id', '=', $request->id)->get();

        $data['page_title'] = 'Edit Portfolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Portfolio', $href);

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
        return view('admin.portfolio.portfolioEdit', ['data' => $data]);
    }

    public function portfolioPostEdit(Request $request)
    {
        $data = $_POST;

        if (empty($request->title) || empty($request->category)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {

            $db_link = DB::table('portfolio')->where('id', '=', $request->id)->first();
            $this_date = date('YmdHis');

            $image = $db_link->image;

            $uploadedFile = $request->file('image');
            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                }
                $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            }

            DB::table('portfolio')->where('id', $request->id)->update(
                [
                    'title' => $request->title,
                    'category' => $request->category,
                    'image' => $image,
                    'date' => $request->date ? $request->date : date('Y-m-d'),
                    'sort' => $request->sort ? $request->sort : 0,
                    'description' => $request->description ? $request->description : '',
                    'status' => $request->status ? $request->status : 1,
                ]
            );

            DB::table('portfolio_contributions')->where('portfolio_id', '=', $request->id)->delete();
            if(isset($request->contributions)) {
                foreach ($request->contributions as $contribution) {
                    DB::table('portfolio_contributions')->insert(
                        [
                            'value' => $contribution,
                            'portfolio_id' => $request->id
                        ]
                    );
                }
            }

            DB::table('portfolio_links')->where('portfolio_id', '=', $request->id)->delete();
            if(isset($request->links)) {
                foreach ($request->links['value'] as $key => $value) {
                    DB::table('portfolio_links')->insert(
                        [
                            'link' => $request->links['link'][$key],
                            'value' => $value,
                            'portfolio_id' => $request->id
                        ]
                    );
                }
            }

            DB::table('portfolio_flaglist')->where('portfolio_id', '=', $request->id)->delete();
            if(isset($request->flaglist)) {
                foreach ($request->flaglist['svg'] as $key => $value) {
                    DB::table('portfolio_flaglist')->insert(
                        [
                            'sort' => $request->flaglist['sort'][$key],
                            'svg' => $value,
                            'portfolio_id' => $request->id
                        ]
                    );
                }
            }


            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/portfolio/edit/' . $request->id)->with(['datas' => $data]);
    }

    public function portfolioPostAdd(Request $request)
    {
        $data = $_POST;
        if (empty($request->title) || empty($request->category)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/portfolio/add/')->with(['datas' => $data]);
        } else {
            $this_date = date('YmdHis');

            $image = '';

            $uploadedFile = $request->file('image');
            if (isset($uploadedFile)) {
                if ($uploadedFile->isValid()) {
                    $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
                }
                $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            }

            DB::table('portfolio')->insert(
                [
                    'title' => $request->title,
                    'category' => $request->category,
                    'image' => $image,
                    'date' => $request->date ? $request->date : date('Y-m-d'),
                    'sort' => $request->sort ? $request->sort : 0,
                    'description' => $request->description ? $request->description : '',
                    'status' => $request->status ? $request->status : 1,
                ]
            );

            $portfolio_id = DB::getPdo()->lastInsertId();

            if(isset($request->contributions)) {
                foreach ($request->contributions as $contribution) {
                    DB::table('portfolio_contributions')->insert(
                        [
                            'value' => $contribution,
                            'portfolio_id' => $portfolio_id
                        ]
                    );
                }
            }

            if(isset($request->links)) {
                foreach ($request->links['value'] as $key => $value) {
                    DB::table('portfolio_links')->insert(
                        [
                            'link' => $request->links['link'][$key],
                            'value' => $value,
                            'portfolio_id' => $portfolio_id
                        ]
                    );
                }
            }

            if(isset($request->flaglist)) {
                foreach ($request->flaglist['svg'] as $key => $value) {
                    DB::table('portfolio_flaglist')->insert(
                        [
                            'sort' => $request->flaglist['sort'][$key],
                            'svg' => $value,
                            'portfolio_id' => $portfolio_id
                        ]
                    );
                }
            }


            $request->session()->put('success', 'Successfully added!');
            return redirect('/admin/portfolio/')->with(['datas' => $data]);

        }
    }
}
