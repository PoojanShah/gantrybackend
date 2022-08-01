<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Imagick;
use PhpParser\Node\Expr\Array_;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController as Admin;
use Illuminate\Support\Facades\Session;

class SliderController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function slider(Request $request)
    {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Slider';
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

        $slider = DB::table('slider')->orderBy('sort', 'asc')->paginate($per_page);

        $count = DB::table('slider')->count();
        $data['pagination'] = Admin::lara_pagination($count, $per_page);

        $data['slider'] = $slider;
        $data['count'] = $count;

        return view('admin.slider.slider', ['data' => $data]);
    }

    public function sliderDelete()
    {
        $id = $_GET['id'];
        DB::table('slider')->delete($id);
    }

    public function sliderEdit(Request $request) {

        $data['user'] = $request->user();

        $data['slider'] = DB::table('slider')->where('id', '=', $request->id)->first();

        $data['page_title'] = 'Edit Slide';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Slider', $href);

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['request'] = $request->session()->get('datas');

        if($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.slider.sliderEdit', ['data' => $data]);
    }

    public function sliderPostEdit(Request $request) {
        $data = $_POST;

        $db_link = DB::table('slider')->where('id', '=', $request->id)->first();
        $this_date = date('YmdHis');

        $image = $db_link->image;


        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            $this->image('uploadfiles', $this_date . $request->image->getClientOriginalName(), 1280, 500);
        }


        DB::table('slider')->where('id', $request->id)->update(
            [
                'image' => $image,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'subsubtitle' => $request->subsubtitle,
            ]
        );


        $request->session()->put('success', 'Successfully updated!');

        return redirect('/admin/slider/edit/'.$request->id)->with( ['datas' => $data] );
    }

    public function sliderAdd(Request $request) {

        $data['user'] = $request->user();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Slide';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = Admin::breadcrumbs('Slider', $href);

        $data['request'] = $request->session()->get('datas');

        if($request->session()->has('error')) {
            $data['error'] = $request->session()->get('error');
        }
        if($request->session()->has('error_link')) {
            $data['error_link'] = $request->session()->get('error_link');
        }

        if($request->session()->has('success')) {
            $data['success'] = $request->session()->get('success');
        }


        $request->session()->forget('error');
        $request->session()->forget('error_link');
        $request->session()->forget('success');
        $request->session()->forget('request');
        return view('admin.slider.sliderAdd', ['data' => $data]);
    }

    public function sliderPostAdd(Request $request) {
        $data = $_POST;

        $this_date = date('YmdHis');

        $image = '';
        $gif = '';

        $uploadedFile = $request->file('image');
        if (isset($uploadedFile)) {
            if ($uploadedFile->isValid()) {
                $uploadedFile->move('uploadfiles/', $this_date . $request->image->getClientOriginalName());
            }
            $image = '/uploadfiles/' . $this_date . $request->image->getClientOriginalName();
            $this->image('uploadfiles', $this_date . $request->image->getClientOriginalName(), 1280, 500);
        }

        DB::table('slider')->insert(
            [
                'image' => $image,
                'sort' => $request->sort ? $request->sort : 0,
                'status' => $request->status,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'subsubtitle' => $request->subsubtitle,
            ]
        );

        $request->session()->put('success', 'Successfully added!');
        return redirect('/admin/slider/')->with( ['datas' => $data] );

    }

    public function image($directory, $filename, $class_width, $class_height)
    {
        $img = $directory."/".$filename;

        $im = new Imagick($img);
        $imageprops = $im->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];

        $newWidth = $width;
        $newHeight = $height;

        $n_width = $width/$class_width;
        $im->adaptiveResizeImage($class_width, $height/$n_width);
        $newWidth = $class_width;
        $newHeight = $height/$n_width;


        $canvas = new Imagick();
        $finalWidth = $class_width;
        $finalHeight = $class_height;
        $canvas->newImage($finalWidth, $finalHeight, 'white', 'jpg');

        $offsetX = (int)($finalWidth / 2) - (int)($newWidth / 2);
        $offsetY = (int)($finalHeight / 2) - (int)($newHeight / 2);
        $canvas->compositeImage($im, imagick::COMPOSITE_OVER, $offsetX, $offsetY);

        if(!file_exists("uploadfiles/optimize/".$class_width."x".$class_height)) {
            mkdir("uploadfiles/optimize/".$class_width."x".$class_height);
        }

        $canvas->writeImage("uploadfiles/optimize/".$class_width."x".$class_height."/" . $filename);

    }
}
