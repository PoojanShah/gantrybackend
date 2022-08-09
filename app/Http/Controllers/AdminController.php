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

    public function index(Request $request)
    {
        $data = Array();
        $data['page_title'] = 'Dashboard';

        $data['user'] = $request->user();

        return view('admin.index', ['data' => $data]);
    }

    /* **************************** ----- **************************************** */

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
            'title' => 'Home',
            'href' => '/admin/',
        ];

        $breadcrumbs[] = [
            'title' => $title,
            'href' => '/admin/'.$href.'/',
        ];

        return $breadcrumbs;
    }
}
