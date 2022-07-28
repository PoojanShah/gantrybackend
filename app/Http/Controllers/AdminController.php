<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Imagick;
use PhpParser\Node\Expr\Array_;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customers(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Dashboard';

        $data['user'] = $request->user();

        $data['customers'] = DB::table('customers')->orderBy('id', 'desc')->get();
        return view('admin.customers', ['data' => $data]);
    }

    public function index(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Dashboard';

        $data['user'] = $request->user();

        if(!empty($request->get('status'))) {
            $data['orders'] = DB::table('orders')->where(['status' => $request->get('status')])->orderBy('order_id', 'desc')->get();
        }
        else {
            $data['orders'] = DB::table('orders')->orderBy('order_id', 'desc')->get();
        }

        /*$data['sends'] = DB::table('sends')->orderBy('date', 'desc')->limit(10)->get();
        $data['resumes'] = DB::table('resumes')->orderBy('date', 'desc')->limit(10)->get();*/


        //$data['count_products'] = DB::table('products')->count();

        //$data['count_messages'] = DB::table('messages')->count();
        //$data['count_messages'] = 0;

        //$subscribers = DB::table('subscribe');

        //$data['count_subscribers'] = $subscribers->count();

        //$data['subscribers'] = $subscribers->limit(8)->orderBy('id', 'desc')->get();

        return view('admin.index', ['data' => $data]);
    }

    /* **************************** ----- **************************************** */


    public function promocodes(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Promocodes';

        $data['user'] = $request->user();

        $data['promocodes'] = DB::table('promocodes')->where('type', '!=', 'date')->orderBy('id', 'desc')->get();
        $data['date_promocodes'] = DB::table('promocodes')->where('type', '=', 'date')->orderBy('id', 'desc')->get();


        return view('admin.promocodes', ['data' => $data]);
    }

    public function promocodesDelete(Request $request)
    {
        $promocode = DB::table('promocodes')->where('id', '=', $request->get('id'));
        if(!empty($promocode->first())) {
            $promocode->delete();
            return redirect('/admin/promocodes/');
        }
    }

    public function promocodesAdd(Request $request)
    {

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Add Promocode';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs('Promocodes', $href);

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
        return view('admin.promocodesAdd', ['data' => $data]);
    }

    public function promocodesPostAdd(Request $request)
    {
        $data = $_POST;

        if(!empty($request->promocode)) {
            if(!empty($request->costs)) {
                DB::table('promocodes')->insert(
                    [
                        'promocode' => $request->promocode,
                        'costs' => $request->costs,
                        'percent' => 0,
                        'status' => 'Оплачен',
                        'type' => 'date',
                        'date_start' => $request->date_start,
                        'date_end' => $request->date_end,
                    ]
                );
            }
            if(!empty($request->percent)) {
                DB::table('promocodes')->insert(
                    [
                        'promocode' => $request->promocode,
                        'costs' => 0,
                        'percent' => $request->percent,
                        'status' => 'Оплачен',
                        'type' => 'date',
                        'date_start' => $request->date_start,
                        'date_end' => $request->date_end,
                    ]
                );
            }
            

        }
        else {

            if(!empty($request->costs)) {
                DB::table('promocodes')->insert(
                    [
                        'promocode' => $this->generatePromo(),
                        'costs' => $request->costs,
                        'percent' => 0,
                        'status' => 'Оплачен',
                        'type' => 'date',
                        'date_start' => $request->date_start,
                        'date_end' => $request->date_end,
                    ]
                );
            }
            if(!empty($request->percent)) {
                DB::table('promocodes')->insert(
                    [
                        'promocode' => $this->generatePromo(),
                        'costs' => 0,
                        'percent' => $request->percent,
                        'status' => 'Оплачен',
                        'type' => 'date',
                        'date_start' => $request->date_start,
                        'date_end' => $request->date_end,
                    ]
                );
            }


        }

        $request->session()->put('success', 'Successfully added!');
        return redirect('/admin/promocodes/')->with(['datas' => $data]);
    }

    public function generatePromo()
    {
        $chars = '1ABC2DEF3GHI4JKL5MNO6PQR7STU8VQX9YZ0';
        $hashpromo = '';
        for($ichars = 0; $ichars <= 12; ++$ichars) {
            $random = str_shuffle($chars);
            $hashpromo .= $random[0];
        }
        return $hashpromo;
    }


    public function subscribers(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Subscribers';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = $this->breadcrumbs($data['page_title'], $href);

        $subscribers = DB::table('subscribe');

        $per_page = 15;
        $count = $subscribers->count();

        $data['per_page'] = $per_page;
        $data['pagination'] = $this->lara_pagination($count, $per_page);
        $data['subscribers'] = $subscribers->orderBy('id', 'desc')->paginate($per_page);

        return view('admin.subscribers', ['data' => $data]);
    }


    public function subscribersDelete()
    {
        $id = $_GET['id'];
        DB::table('subscribe')->delete($id);
    }

	public function changeStatus(Request $request)
	{
		if(!empty($request->get('value'))) {
			DB::table('orders')->where(['order_id' => $request->get('order_id')])->update(['status' => $request->get('value')]);
			return true;
		}
	}

    public function categories(Request $request) {
        $data = Array();
        $data['page_title'] = 'Категории';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = $this->breadcrumbs($data['page_title'], $href);

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

        $data['categories'] = DB::table('categories')->orderBy('sort', 'asc')->get();

        return view('admin.categories', ['data' => $data]);
    }

    public function categoriesAdd(Request $request) {

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['categories'] = DB::table('categories')->where('status', '=', 1)->where(['parent_id' => 0])->get();

        $data['page_title'] = 'Добавление категории';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs('Категории', $href);

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
        return view('admin.categoriesAdd', ['data' => $data]);
    }

    public function categoriesPostAdd(Request $request) {
        $data = $_POST;
        if(empty($request->title)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/categories/add/')->with( ['datas' => $data] );
        }
        else {
            $db_link = DB::table('categories')->where('link', '=', $request->link)->count();
            if($db_link > 0) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
                return redirect('/admin/categories/add/')->with( ['datas' => $data] );
            }
            else {

                $this_date = date('YmdHis');

                $image = '';
                $big_image = '';

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('storage/files/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/storage/files/' . $this_date . $request->image->getClientOriginalName();
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 256, 290);
                }

                $uploadedFileBig = $request->file('big_image');
                if (isset($uploadedFileBig)) {
                    if ($uploadedFileBig->isValid()) {
                        $uploadedFileBig->move('storage/files/', $this_date . $request->big_image->getClientOriginalName());
                    }
                    $big_image = '/storage/files/' . $this_date . $request->big_image->getClientOriginalName();
                }



                DB::table('categories')->insert(
                    [
                        'title' => $request->title,
                        'link' => $request->link ? $request->link : strtolower($this->rus2translit($request->title)),
                        'parent_id' => $request->category,
                        'short_description' => $request->short_description ? $request->short_description : '',
                        'description' => $request->description ? $request->description : '',
                        'seo_title' => $request->seo_title ? $request->seo_title : '',
                        'seo_description' => $request->seo_description ? $request->seo_description : '',
                        'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                        'status' => $request->status,
                        'sort' => $request->sort ? $request->sort : 0,
                        'image' => $image,
                        'big_image' => $big_image,
                    ]
                );


                $request->session()->put('success', 'Successfully added!');
                return redirect('/admin/categories/')->with( ['datas' => $data] );
            }
        }

    }

    public function categoriesDelete()
    {
        $id = $_GET['id'];
        DB::table('categories')->delete($id);
    }

    public function categoriesEdit(Request $request) {

        $data['category'] = DB::table('categories')->where('id', '=', $request->id)->first();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['categories'] = DB::table('categories')->where('status', '=', 1)->where(['parent_id' => 0])->get();
        $data['current_category'] = $data['category']->parent_id;

        $data['page_title'] = 'Редактирование категории - '.$data['category']->title.' ';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs('Категории', $href);

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
        return view('admin.categoriesEdit', ['data' => $data]);
    }

    public function categoriesPostEdit(Request $request) {
        $data = $_POST;
        if(empty($request->title)) {
            $request->session()->put('error', 'Fill in all the fields');
        }
        else {
            $db_link = DB::table('categories')->where('link', '=', $request->link)->first();
            if($db_link->id != $request->id) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
            }
            else {

                $this_date = date('YmdHis');

                $image = $db_link->image;
                $big_image = $db_link->big_image;

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('storage/files/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/storage/files/' . $this_date . $request->image->getClientOriginalName();
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 256, 290);
                }

                $uploadedFileBig = $request->file('big_image');
                if (isset($uploadedFileBig)) {
                    if ($uploadedFileBig->isValid()) {
                        $uploadedFileBig->move('storage/files/', $this_date . $request->big_image->getClientOriginalName());
                }
                    $big_image = '/storage/files/' . $this_date . $request->big_image->getClientOriginalName();
                }

                DB::table('categories')->where('id', $request->id)->update(
                    [
                        'title' => $request->title,
                        'link' => $request->link ? $request->link : strtolower($this->rus2translit($request->title)),
                        'short_description' => $request->short_description ? $request->short_description : '',
						'description' => $request->description ? $request->description : '',
                        'seo_title' => $request->seo_title ? $request->seo_title : '',
                        'seo_description' => $request->seo_description ? $request->seo_description : '',
                        'seo_keywords' => $request->seo_keywords ? $request->seo_keywords : '',
                        'status' => $request->status,
                        'sort' => $request->sort ? $request->sort : 0,
                        'image' => $image,
                        'big_image' => $big_image,
                    ]
                );


                $request->session()->put('success', 'Successfully updated!');
            }
        }
        return redirect('/admin/categories/edit/'.$request->id)->with( ['datas' => $data] );
    }


    public function products(Request $request) {
        $data = Array();

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['user'] = $request->user();

        $data['page_title'] = 'Products';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs($data['page_title'], $href);

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

        $per_page = 300;


		if(!empty($request->get('category_id'))) {
			$products = DB::table('products')->select(['products.*'])
                        ->join('product_to_category', 'products.id', '=', 'product_to_category.product_id')
						->where(['product_to_category.category_id' => $request->get('category_id')])->orderBy('products.id', 'asc')->paginate($per_page);
			$count = DB::table('products')->join('product_to_category', 'products.id', '=', 'product_to_category.product_id')
						->where(['product_to_category.category_id' => $request->get('category_id')])->count();
			$data['pagination'] = $this->lara_pagination($count, $per_page);
		}
		else {
			$products = DB::table('products')->orderBy('id', 'asc')->paginate($per_page);
			$count = DB::table('products')->count();
			$data['pagination'] = $this->lara_pagination($count, $per_page);
		}

        if(!empty($request->get('search'))) {
            $products = DB::table('products')->where('title', 'like', '%'.$request->get('search').'%')->orderBy('id', 'asc')->paginate($per_page);
			$count = DB::table('products')->where('title', 'like', '%'.$request->get('search').'%')->count();
			$data['pagination'] = $this->lara_pagination($count, $per_page);
        }



        $data['products'] = $products;
        $data['count'] = $count;

		$data['categories'] = DB::table('categories')->get();

        return view('admin.products', ['data' => $data]);
    }

    public function productsDelete()
    {
        $id = $_GET['id'];
        DB::table('products')->delete($id);
    }

    public function productsAdd(Request $request) {

        $data['user'] = $request->user();

        $data['categories'] = DB::table('categories')->where('parent_id', '!=', '0')->where('status', '=', 1)->get();
        $attributes = DB::table('attributes')->orderBy('sort', 'asc')->get();
        foreach ($attributes as $attribute) {
            $product_attributes = DB::table('product_attributes')->where('product_id', '=', $request->id)->where('attribute_id', '=', $attribute->id)->first();
            if(!empty($product_attributes)) {
                $data['attributes'][] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'value' => $product_attributes->value,
                ];
            }
            else {
                $data['attributes'][] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'value' => '',
                ];
            }
        }

        $data['error'] = '';
        $data['error_link'] = '';
        $data['success'] = '';

        $data['page_title'] = 'Add Product';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs('Products', $href);

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
        return view('admin.productsAdd', ['data' => $data]);
    }

    public function productsPostAdd(Request $request) {
        $data = $_POST;
        if(empty($request->title)) {
            $request->session()->put('error', 'Fill in all the fields');
            return redirect('/admin/products/add/')->with( ['datas' => $data] );
        }
        else {
            $db_link = DB::table('products')->where('link', '=', $request->link)->count();
            if($db_link > 0) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
                return redirect('/admin/products/add/')->with( ['datas' => $data] );
            }
            else {

                $this_date = date('YmdHis');

                $image = '';

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('storage/files/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/storage/files/' . $this_date . $request->image->getClientOriginalName();
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 150, 150);
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 107, 107);
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 500, 500);
                }

                DB::table('products')->insert(
                    [
                        'title' => $request->title,
                        'link' => $request->link ? $request->link : strtolower($this->rus2translit($request->title)),
                        'short_description' => $request->short_description ? $request->short_description : '',
                        'description' => $request->description ? $request->description : '',
                        'big_description' => $request->big_description ? $request->big_description : '',
                        'status' => $request->status,
                        'image' => (!empty($image)) ? $image : "/images/noimage.svg",
						'top' => $request->top,
						'stock' => $request->stock,
						'home' => $request->home,
						'prod' => $request->prod,
						'price' => $request->price,
						'price_sale' => $request->price_sale ? $request->price_sale : 0,
						'article' => $request->article,
                        'icon1' => $request->icon1,
                        'icon2' => $request->icon2,
                        'icon3' => $request->icon3,
                        'icon4' => $request->icon4,
                        'icon5' => $request->icon5,
                        'icon6' => $request->icon6,
                    ]
                );

                $product_id = DB::getPdo()->lastInsertId();

                if(isset($request->category)) {
                    DB::table('product_to_category')->insert(
                        [
                            'category_id' => $request->category,
                            'product_id' => $product_id,
                        ]
                    );
                }

                if(isset($request->images)) {
                    foreach ($request->images as $images) {
                        if(is_string($images[0])) {
                            DB::table('product_images')->insert(
                                [
                                    'product_id' => $product_id,
                                    'image' => $images[0],
                                    'sort' => $images[1],
                                ]
                            );
                        }
                        else {
                            if($images[0]->isValid()) {
                                $images[0]->move('storage/files/', $this_date . $images[0]->getClientOriginalName());
                                $image = '/storage/files/' . $this_date . $images[0]->getClientOriginalName();

                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 150, 150);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 107, 107);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 500, 500);

                                DB::table('product_images')->insert(
                                    [
                                        'product_id' => $product_id,
                                        'image' => $image,
                                        'sort' => $images[1],
                                    ]
                                );
                            }
                        }
                    }
                }

                if(isset($request->colors)) {
                    foreach ($request->colors as $images) {
                        if(is_string($images[0])) {
                            DB::table('product_colors')->insert(
                                [
                                    'product_id' => $product_id,
                                    'image' => $images[0],
                                    'name' => $images[1],
                                ]
                            );
                        }
                        else {
                            if($images[0]->isValid()) {
                                $images[0]->move('storage/files/', $this_date . $images[0]->getClientOriginalName());
                                $image = '/storage/files/' . $this_date . $images[0]->getClientOriginalName();

                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 150, 150);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 107, 107);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 500, 500);

                                DB::table('product_colors')->insert(
                                    [
                                        'product_id' => $product_id,
                                        'image' => $image,
                                        'name' => $images[1],
                                    ]
                                );
                            }
                        }
                    }
                }

                if(isset($request->attribute)) {
                    foreach ($request->attribute as $key => $value) {
                        if($value[0] != NULL) {
                            DB::table('product_attributes')->insert(
                                [
                                    'product_id' => $product_id,
                                    'attribute_id' => $key,
                                    'value' => $value[0],
                                ]
                            );
                        }
                    }
                }


                $request->session()->put('success', 'Successfully added!');
                return redirect('/admin/products/edit/'.$product_id)->with( ['datas' => $data] );
            }

        }

    }

    public function productsEdit(Request $request) {

        $data['user'] = $request->user();

        $data['product'] = DB::table('products')->where('id', '=', $request->id)->first();
        if(empty($data['product'])) { abort(404); }
        $data['categories'] = DB::table('categories')->where('parent_id', '!=', '0')->where('status', '=', 1)->get();
        $data['current_category'] = DB::table('product_to_category')->where('product_id', '=', $request->id)->first();
        $data['product_images'] = DB::table('product_images')->where('product_id', '=', $request->id)->orderBy('sort')->get();
        $data['product_colors'] = DB::table('product_colors')->where('product_id', '=', $request->id)->orderBy('id')->get();
        $attributes = DB::table('attributes')->orderBy('sort', 'asc')->get();
        foreach ($attributes as $attribute) {
            $product_attributes = DB::table('product_attributes')->where('product_id', '=', $request->id)->where('attribute_id', '=', $attribute->id)->first();
            if($data['current_category']->category_id == $attribute->category_id) {
                if(!empty($product_attributes)) {
                    $data['attributes'][] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value' => $product_attributes->value,
                    ];
                }
                else {
                    $data['attributes'][] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value' => '',
                    ];
                }
            }
            else {
                $data['attributes'][] = [];
            }
        }

        //$data['product_attributes'] = DB::table('product_attributes')->where('product_id', '=', $request->id)->get();

        $data['page_title'] = 'Edit Product';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['breadcrumbs'] = $this->breadcrumbs('Products', $href);

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
        return view('admin.productsEdit', ['data' => $data]);
    }

    public function productsPostEdit(Request $request) {
        $data = $_POST;
        if(empty($request->title)) {
            $request->session()->put('error', 'Fill in all the fields');
        }
        else {
            $db_link = DB::table('products')->where('link', '=', $request->link)->first();

            if($db_link != NULL && $request->id != $db_link->id) {
                $request->session()->put('error_link', 'This SEO_URL is already in use!');
            }
            else {

                $this_date = date('YmdHis');

                $uploadedFile = $request->file('image');
                if (isset($uploadedFile)) {
                    if ($uploadedFile->isValid()) {
                        $uploadedFile->move('storage/files/', $this_date . $request->image->getClientOriginalName());
                    }
                    $image = '/storage/files/' . $this_date . $request->image->getClientOriginalName();
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 150, 150);
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 107, 107);
                    $this->image('storage/files', $this_date . $request->image->getClientOriginalName(), 500, 500);
                }
                else {
                    if(isset($request->image)) {
                        $image = $request->image;
                    }
                    else {
                        $image = '';
                    }
                }

                if($image == '') {
                    DB::table('products')->where('id', $request->id)->update(
                        [
                            'title' => $request->title,
                            'link' => $request->link ? $request->link : strtolower($this->rus2translit($request->title)),
                            'short_description' => $request->short_description ? $request->short_description : '',
                            'description' => $request->description ? $request->description : '',
                            'big_description' => $request->big_description ? $request->big_description : '',
                            'status' => $request->status,
							'top' => $request->top,
							'stock' => $request->stock,
						'home' => $request->home,
						'price' => $request->price,
						'price_sale' => $request->price_sale ? $request->price_sale : 0,
						'article' => $request->article,
						'prod' => $request->prod,
                            'icon1' => $request->icon1,
                            'icon2' => $request->icon2,
                            'icon3' => $request->icon3,
                            'icon4' => $request->icon4,
                            'icon5' => $request->icon5,
                            'icon6' => $request->icon6,
                            /*'image' => $image,*/
                        ]
                    );
                }
                else {
                    DB::table('products')->where('id', $request->id)->update(
                        [
                            'title' => $request->title,
                            'link' => $request->link ? $request->link : strtolower($this->rus2translit($request->title)),
                            'short_description' => $request->short_description ? $request->short_description : '',
                            'description' => $request->description ? $request->description : '',
                            'big_description' => $request->big_description ? $request->big_description : '',
                            'status' => $request->status,
                            'image' => $image,
							'top' => $request->top,
							'stock' => $request->stock,
						'home' => $request->home,
						'price' => $request->price,
						'price_sale' => $request->price_sale ? $request->price_sale : 0,
						'article' => $request->article,
						'prod' => $request->prod,
                            'icon1' => $request->icon1,
                            'icon2' => $request->icon2,
                            'icon3' => $request->icon3,
                            'icon4' => $request->icon4,
                            'icon5' => $request->icon5,
                            'icon6' => $request->icon6,
                        ]
                    );
                }


                if(isset($request->category)) {
                    DB::table('product_to_category')->where('product_id', '=', $request->id)->delete();
                    DB::table('product_to_category')->insert(
                        [
                            'category_id' => $request->category,
                            'product_id' => $request->id,
                        ]
                    );
                }

                if(isset($request->images)) {
                    DB::table('product_images')->where('product_id', '=', $request->id)->delete();
                    foreach ($request->images as $images) {
                        if(is_string($images[0])) {
                            DB::table('product_images')->insert(
                                [
                                    'product_id' => $request->id,
                                    'image' => $images[0],
                                    'sort' => $images[1],
                                ]
                            );
                        }
                        else {
                            if($images[0]->isValid()) {
                                $images[0]->move('storage/files/', $this_date . $images[0]->getClientOriginalName());
                                $image = '/storage/files/' . $this_date . $images[0]->getClientOriginalName();

                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 150, 150);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 107, 107);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 500, 500);

                                DB::table('product_images')->insert(
                                    [
                                        'product_id' => $request->id,
                                        'image' => $image,
                                        'sort' => $images[1],
                                    ]
                                );
                            }
                        }
                    }
                }

                if(isset($request->colors)) {
                    DB::table('product_colors')->where('product_id', '=', $request->id)->delete();
                    foreach ($request->colors as $images) {
                        if(is_string($images[0])) {
                            DB::table('product_colors')->insert(
                                [
                                    'product_id' => $request->id,
                                    'image' => $images[0],
                                    'name' => $images[1],
                                ]
                            );
                        }
                        else {
                            if($images[0]->isValid()) {
                                $images[0]->move('storage/files/', $this_date . $images[0]->getClientOriginalName());
                                $image = '/storage/files/' . $this_date . $images[0]->getClientOriginalName();

                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 150, 150);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 107, 107);
                                $this->image('storage/files', $this_date . $images[0]->getClientOriginalName(), 500, 500);

                                DB::table('product_colors')->insert(
                                    [
                                        'product_id' => $request->id,
                                        'image' => $image,
                                        'name' => $images[1],
                                    ]
                                );
                            }
                        }
                    }
                }

                if(isset($request->attribute)) {
                    DB::table('product_attributes')->where('product_id', '=', $request->id)->delete();
                    foreach ($request->attribute as $key => $value) {
                        if($value[0] != NULL) {
                            DB::table('product_attributes')->insert(
                                [
                                    'product_id' => $request->id,
                                    'attribute_id' => $key,
                                    'value' => $value[0],
                                ]
                            );
                        }
                    }
                }

                $request->session()->put('success', 'Successfully updated!');
            }

        }
        return redirect('/admin/products/edit/'.$request->id)->with( ['datas' => $data] );

    }

    public function productsCopy($product_id)
    {

        $product = DB::table('products')->where('id', '=', $product_id)->first();
        //$product->link = 'enter_link_here';

        DB::table('products')->insert(
            [
                'title' => $product->title,
                'link' => $product->link.'-'.rand(1000,9999),
                'short_description' => $product->short_description ? $product->short_description : '',
                'description' => $product->description ? $product->description : '',
                'big_description' => $product->big_description ? $product->big_description : '',
                'status' => $product->status,
                'article' => $product->article,
                'image' => $product->image,
                'top' => $product->top,
                'stock' => $product->stock,
                'home' => $product->home,
                'prod' => $product->prod,
                'price' => $product->price,
                'price_sale' => $product->price_sale ? $product->price_sale : 0,
                'icon1' => $product->icon1,
                'icon2' => $product->icon2,
                'icon3' => $product->icon3,
                'icon4' => $product->icon4,
                'icon5' => $product->icon5,
                'icon6' => $product->icon6,
            ]
        );

        $new_product_id = DB::getPdo()->lastInsertId();

        $product_to_category = DB::table('product_to_category')->where('product_id', '=', $product_id)->first();
        $product_to_category->product_id = $new_product_id;

        DB::table('product_to_category')->insert(
            [
                'product_id' => $new_product_id,
                'category_id' => $product_to_category->category_id,
            ]
        );

        $attributes = DB::table('product_attributes')->where('product_id', '=', $product_id)->get();
        foreach ($attributes as $attr) {
            DB::table('product_attributes')->insert(
                [
                    'product_id' => $new_product_id,
                    'attribute_id' => $attr->attribute_id,
                    'value' => $attr->value,
                ]
            );
        }


        return redirect('/admin/products/edit/'.$new_product_id);

    }

    public function attributes(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Attributes';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = $this->breadcrumbs($data['page_title'], $href);

        $data['attributes'] = DB::table('attributes')->orderBy('sort', 'asc')->get();
        $data['categories'] = DB::table('categories')->get();

            //->select(['product_category.id', 'product_category.guid', 'product_category.url', 'product_category.name'])
            //->where(['product_category_parent.parent_guid' => $guid])->orderBy('sort_order', 'asc')->get()->toArray();

        return view('admin.attributes', ['data' => $data]);
    }

    public function attributesSave()
    {
        DB::table('attributes')->delete();
        $data = $_POST;

        foreach ($data['attribute'] as $key => $value) {
            DB::table('attributes')->insert([
                [
                    'id' => $key,
                    'name' => $value[0],
                    'category_id' => $data['category'][$key][0],
                    'sort' => $data['sort'][$key][0]
                ]
            ]);
        }
        return 'Added';
    }

    public function attributesDelete()
    {
        $id = $_GET['id'];
        DB::table('attributes')->delete($id);
    }

    public function slider(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Slider';
        $href = $_SERVER['REQUEST_URI'];
        $href = explode('/', $href);
        $href = $href[2];

        $data['user'] = $request->user();

        $data['breadcrumbs'] = $this->breadcrumbs($data['page_title'], $href);

        $data['slider'] = DB::table('home_slider')->get();

        return view('admin.slider', ['data' => $data]);
    }

    public function sliderSave(Request $request)
    {
        //DB::table('home_slider')->delete();
        $data = $_POST;

        $this_date = date('YmdHis');
        $uploadedFiles = $request->file('image');



        foreach ($uploadedFiles as $uploadedFile) {
            if (isset($uploadedFile[0])) {
                if ($uploadedFile[0]->isValid()) {
                    $uploadedFile[0]->move('storage/files/', $this_date . $uploadedFile[0]->getClientOriginalName());
                }
                $image = '/storage/files/' . $this_date . $uploadedFile[0]->getClientOriginalName();
            }
        }

        var_dump($uploadedFiles);
        exit();


        foreach ($data['title'] as $key => $value) {
            DB::table('home_slider')->insert([
                [
                    'title' => $value,
                    'description' => $data['description'][$key],
                    'status' => $data['status'][$key],
                ]
            ]);
        }
        return 'Added';
    }





































    static function lara_pagination($count, $per_page)
    {
        $paginate = '';

        if(isset($_GET['page'])) {
            $current_page = (int)$_GET['page'];
        }
        else {
            $current_page = 1;
        }

        if($count > $per_page ){
            $paged = $current_page;
            $num_pages = ceil($count / $per_page);

            $paginate = '<ul class="pagination-numbers">';

            if(isset($_GET['search'])) {
                $search = '&search='.$_GET['search'];
            }
			elseif(isset($_GET['category_id'])) {
                $search = '&category_id='.$_GET['category_id'];
            }
            else {
                $search = '';
            }

            if($paged > 1){
                $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link" href="'.explode('?',$_SERVER['REQUEST_URI'])[0].'?page='.($paged-1).$search.'"><i class="pagination-numbers__arrow biticon-left"></i></a></li>';
            }else{
                $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link"><i class="pagination-numbers__arrow biticon-left"></i></a></li>';
            }



            for($p = 1; $p <= $num_pages; $p++){
                if($p==1) {
                    if ($paged == $p) {
                        $paginate .= '<li class="pagination-numbers__number pagination-numbers__number_current"><a class="pagination-numbers__link">' . $p . '</a></li>';
                    } else {
                        $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link" href="' . explode('?',$_SERVER['REQUEST_URI'])[0] . '?page=' . $p . $search.'">' . $p . '</a></li>';
                    }
                    if($paged > 4) {
                        $paginate .= '<li class="pagination-numbers__number"><span class="pagination-numbers__link break"> ... </span></li>';
                    }
                }
                else {
                    if ($paged >= $p - 2 && $paged <= $p + 2 && $p!=$num_pages) {
                        if ($paged == $p) {
                            $paginate .= '<li class="pagination-numbers__number pagination-numbers__number_current"><a class="pagination-numbers__link">' . $p . '</a></li>';
                        } else {
                            $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link" href="' . explode('?',$_SERVER['REQUEST_URI'])[0] . '?page=' . $p . $search.'">' . $p . '</a></li>';
                        }
                    }
                }
                if($p==$num_pages) {
                    if($paged < $num_pages-3) {
                        $paginate .= '<li class="pagination-numbers__number"><span class="pagination-numbers__link break"> ... </span></li>';
                    }
                    if ($paged == $p) {
                        $paginate .= '<li class="pagination-numbers__number pagination-numbers__number_current"><a class="pagination-numbers__link">' . $p . '</a></li>';
                    } else {
                        $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link" href="' . explode('?',$_SERVER['REQUEST_URI'])[0] . '?page=' . $p . $search.'">' . $p . '</a></li>';
                    }
                }

            }



            if($paged < $num_pages){
                $paginate .= '<li class="pagination-numbers__number"><a href="'.explode('?',$_SERVER['REQUEST_URI'])[0].'?page='.($paged+1).$search.'" class="pagination-number__link"><i class="pagination-numbers__arrow biticon-right"></i></a></li>';
            }else{
                $paginate .= '<li class="pagination-numbers__number"><a class="pagination-numbers__link"><i class="pagination-numbers__arrow biticon-right"></i></a></li>';
            }

            $paginate .= '</ul>';
        }
        return $paginate;
    }

    static function breadcrumbs($title, $href) {
        $breadcrumbs[] = [
            'title' => 'Главная',
            'href' => '/admin/',
        ];

        $breadcrumbs[] = [
            'title' => $title,
            'href' => '/admin/'.$href.'/',
        ];

        return $breadcrumbs;
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

    public function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'y',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            ' ' => '-',
            ',' => '-',
            '.' => '-',
            '/' => '-',
            '?' => '-',
            '"' => '-',
            ';' => '-',
            ':' => '-',
            '(' => '-',
            ')' => '-',
            '&' => '-',
            'ї' => 'i',
            'і' => 'i',
            'є' => 'e',
        );
        return strtr($string, $converter);
    }

}
