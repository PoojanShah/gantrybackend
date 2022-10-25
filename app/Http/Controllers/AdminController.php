<?php

namespace App\Http\Controllers;

use App\Interfaces\OAuthClientInterface;
use App\Models\Settings;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

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

    public function zohoTokensManagement(Request $request, OAuthClientInterface $authClient)
    {
        // TODO Accessible only for superadmin

        $errors = [];
        $data = [];
        $settings = Settings::where('type', '=', Settings::GLOBAL_TYPE)->first();

        if ($request->has('code')) {

            //this code might be taken from  https://api-console.zoho.com/  - Self Client - generate code with scope ZohoSubscriptions.fullaccess.all
            $code = $_REQUEST['code'];

            try {
                $settings = $settings ?? new Settings();
                $decodedSettings = $settings->settings ? json_decode($settings->settings, true) : [];
                $authClient->setGrantCode($code);
                $data['refreshToken'] = $decodedSettings['refreshToken'] = $authClient->getRefreshToken();
                $data['accessToken'] = $authClient->getAccessToken();
                $settings->settings = json_encode($decodedSettings);
                $settings->type = Settings::GLOBAL_TYPE;
                $settings->save();
            } catch (\Weble\ZohoClient\Exception\ApiError $e) {
                $errors[] = $e->getMessage();
            }

        } else {
            $data['refreshToken'] = json_decode($settings->settings, true)['refreshToken'];

//            $authClient->promptForConsent(false);
//            $url = $authClient->getAuthorizationUrl();
//            $_SESSION['zoho_oauth_state'] = $client->getState();// Get the state for security, and save it (usually in session)
//            redirect($url); // Do your redirection as you prefer

        }

        $data['user'] = $request->user();
        $data['page_title'] = 'Zoho tokens management';

        return view('admin.zohoTokens', ['data' => $data])->withErrors($errors);
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
