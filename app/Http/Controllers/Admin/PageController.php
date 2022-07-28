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

class PageController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Home';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['home'] = DB::table('page_home')->first();

        return view('admin.page.home', ['data' => $data]);
    }
    public function homeSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
			$db_link = DB::table('page_home')->first();
			$seo_image = $db_link->seo_image;
			$block1_image = $db_link->block1_image;
			$block2_image = $db_link->block2_image;
			$block3_image = $db_link->block3_image;
			$block4_image = $db_link->block4_image;
			
			$uploadedFile = $request->file('seo_image');
			$uploadedFile1 = $request->file('block1_image');
			$uploadedFile2 = $request->file('block2_image');
			$uploadedFile3 = $request->file('block3_image');
			$uploadedFile4 = $request->file('block4_image');
			
			
			$this_date = date('YmdHis');
			
			if (isset($uploadedFile)) {
				if ($uploadedFile->isValid()) {
					$uploadedFile->move('uploadfiles/', $this_date . $request->seo_image->getClientOriginalName());
				}
				$seo_image = '/uploadfiles/' . $this_date . $request->seo_image->getClientOriginalName();
			}
			if (isset($uploadedFile1)) {
				if ($uploadedFile1->isValid()) {
					$uploadedFile1->move('uploadfiles/', $this_date . $request->block1_image->getClientOriginalName());
				}
				$block1_image = '/uploadfiles/' . $this_date . $request->block1_image->getClientOriginalName();
			}
			if (isset($uploadedFile2)) {
				if ($uploadedFile2->isValid()) {
					$uploadedFile2->move('uploadfiles/', $this_date . $request->block2_image->getClientOriginalName());
				}
				$block2_image = '/uploadfiles/' . $this_date . $request->block2_image->getClientOriginalName();
			}
			if (isset($uploadedFile3)) {
				if ($uploadedFile3->isValid()) {
					$uploadedFile3->move('uploadfiles/', $this_date . $request->block3_image->getClientOriginalName());
				}
				$block3_image = '/uploadfiles/' . $this_date . $request->block3_image->getClientOriginalName();
			}
			if (isset($uploadedFile4)) {
				if ($uploadedFile4->isValid()) {
					$uploadedFile4->move('uploadfiles/', $this_date . $request->block4_image->getClientOriginalName());
				}
				$block4_image = '/uploadfiles/' . $this_date . $request->block4_image->getClientOriginalName();
			}
			
            DB::table('page_home')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                    'seo_zagolovok' => $request->seo_zagolovok ? $request->seo_zagolovok : '',
                    'seo_image' => $seo_image,
                    'seo_text' => $request->seo_text ? $request->seo_text : '',
					'block1_zagolovok' => $request->block1_zagolovok ? $request->block1_zagolovok : '',
					'block1_text' => $request->block1_text ? $request->block1_text : '',
					'block2_zagolovok' => $request->block2_zagolovok ? $request->block2_zagolovok : '',
					'block2_text' => $request->block2_text ? $request->block2_text : '',
					'block3_zagolovok' => $request->block3_zagolovok ? $request->block3_zagolovok : '',
					'block3_text' => $request->block3_text ? $request->block3_text : '',
					'block4_zagolovok' => $request->block4_zagolovok ? $request->block4_zagolovok : '',
					'block4_text' => $request->block4_text ? $request->block4_text : '',
					'block1_image' => $block1_image,
					'block2_image' => $block2_image,
					'block3_image' => $block3_image,
					'block4_image' => $block4_image,
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/home/' . $request->id)->with(['datas' => $data]);
    }

    public function blog(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Blog';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['blog'] = DB::table('page_blog')->first();

        return view('admin.page.blog', ['data' => $data]);
    }
    public function blogSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_blog')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/blog/' . $request->id)->with(['datas' => $data]);
    }

    public function team(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Team';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['team'] = DB::table('page_team')->first();

        return view('admin.page.team', ['data' => $data]);
    }
    public function teamSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_team')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/team/' . $request->id)->with(['datas' => $data]);
    }

    public function portfolio(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Portfolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['portfolio'] = DB::table('page_portfolio')->first();

        return view('admin.page.portfolio', ['data' => $data]);
    }
    public function portfolioSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_portfolio')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/portfolio/' . $request->id)->with(['datas' => $data]);
    }

    public function logofolio(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Logofolio';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['logofolio'] = DB::table('page_logofolio')->first();

        return view('admin.page.logofolio', ['data' => $data]);
    }
    public function logofolioSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_logofolio')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/logofolio/' . $request->id)->with(['datas' => $data]);
    }

    public function careers(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Careers';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['careers'] = DB::table('page_careers')->first();

        return view('admin.page.careers', ['data' => $data]);
    }
    public function careersSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_careers')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/careers/' . $request->id)->with(['datas' => $data]);
    }

    /**/
    public function joinTheTeam(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Join The Team';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['join_the_team'] = DB::table('page_join_the_team')->first();

        return view('admin.page.join_the_team', ['data' => $data]);
    }
    public function joinTheTeamSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_join_the_team')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/join_the_team/' . $request->id)->with(['datas' => $data]);
    }
    /**/

    public function contacts(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Contacts';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['contacts'] = DB::table('page_contacts')->first();

        return view('admin.page.contacts', ['data' => $data]);
    }
    public function contactsSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_contacts')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/contacts/' . $request->id)->with(['datas' => $data]);
    }

    public function showreel(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Showreel';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

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

        $data['breadcrumbs'] = Admin::breadcrumbs($data['page_title'], $href);

        $data['showreel'] = DB::table('page_showreel')->first();

        return view('admin.page.showreel', ['data' => $data]);
    }
    public function showreelSave(Request $request)
    {
        $data = $_POST;
        if (empty($request->seo_title)) {
            $request->session()->put('error', 'Fill in all the fields');
        } else {
            DB::table('page_showreel')->where('id', 1)->update(
                [
                    'seo_title' => $request->seo_title ? $request->seo_title : '',
                    'seo_description' => $request->seo_description ? $request->seo_description : '',
                    'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                ]
            );

            $request->session()->put('success', 'Successfully updated!');
        }
        return redirect('/admin/page/showreel/' . $request->id)->with(['datas' => $data]);
    }
}
