<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Videos
Route::get('/admin/video/', '\App\Http\Controllers\Admin\VideoController@video');
Route::get('/admin/video/delete/', '\App\Http\Controllers\Admin\VideoController@videoDelete');
Route::get('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoEdit');
Route::post('/admin/video/edit/{id?}', '\App\Http\Controllers\Admin\VideoController@videoPostEdit');
Route::get('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoAdd');
Route::post('/admin/video/add/', '\App\Http\Controllers\Admin\VideoController@videoPostAdd');


//Users
Route::get('/admin/users/', '\App\Http\Controllers\Admin\UsersController@users');
Route::get('/admin/users/delete/', '\App\Http\Controllers\Admin\UsersController@usersDelete');
Route::get('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersAdd');
Route::post('/admin/users/add/', '\App\Http\Controllers\Admin\UsersController@usersPostAdd');
Route::get('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersEdit');
Route::post('/admin/users/edit/{id?}', '\App\Http\Controllers\Admin\UsersController@usersPostEdit');


Route::get('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
Route::post('/admin/profile', '\App\Http\Controllers\Admin\ProfileController@index')->name('profile');
Route::get('/admin/change_profile', '\App\Http\Controllers\Admin\ProfileController@change');
Route::get('/admin/change-status', 'AdminController@changeStatus');
Route::get('/admin/image', 'AdminController@image');

Route::get('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
Route::post('/forgot', '\App\Http\Controllers\Auth\ForgotController@index')->name('forgot');
Route::get('/change_password', '\App\Http\Controllers\Auth\ForgotController@change');

//Dashboard
Route::get('/admin/', [AdminController::class, 'index'])->name('admin');
Route::get('/admin/customers/', 'AdminController@customers');
Route::get('/home/', function () {
    return redirect('/admin/');
});

Route::get('/admin/promocodes/', 'AdminController@promocodes');
Route::get('/admin/promocodes/delete', 'AdminController@promocodesDelete');
Route::get('/admin/promocodes/add', 'AdminController@promocodesAdd');
Route::post('/admin/promocodes/add', 'AdminController@promocodesPostAdd');

//text pages
Route::get('/admin/pages/', 'Admin\PagesController@pages');
Route::get('/admin/pages/add/', 'Admin\PagesController@pagesAdd');
Route::post('/admin/pages/add/', 'Admin\PagesController@pagesPostAdd');
Route::get('/admin/pages/edit/{id?}/', 'Admin\PagesController@pagesEdit');
Route::post('/admin/pages/edit/{id?}/', 'Admin\PagesController@pagesPostEdit');
Route::get('/admin/pages/delete/', 'Admin\PagesController@pagesDelete');

//menu
Route::get('/admin/menu/', 'Admin\MenuController@menu');
Route::post('/admin/menu/save/', 'Admin\MenuController@menuSave');
Route::get('/admin/menu/delete/', 'Admin\MenuController@menuDelete');

//mobile menu
Route::get('/admin/menu_mobile/', 'Admin\MenuController@menuMobile');
Route::post('/admin/menu_mobile/save/', 'Admin\MenuController@menuMobileSave');
Route::get('/admin/menu_mobile/delete/', 'Admin\MenuController@menuMobileDelete');

//footer menu
Route::get('/admin/menu_footer/', 'Admin\MenuController@menuFooter');
Route::post('/admin/menu_footer/save/', 'Admin\MenuController@menuFooterSave');
Route::get('/admin/menu_footer/delete/', 'Admin\MenuController@menuFooterDelete');

//settings
Route::get('/admin/settings/', 'Admin\SettingsController@settings');
Route::get('/admin/settings/save/', 'Admin\SettingsController@settingsSave');
Route::post('/admin/settings/save/', 'Admin\SettingsController@settingsSave');
Route::get('/admin/settings/delete/', 'Admin\SettingsController@settingsDelete');


//Blog
Route::get('/admin/blog/', 'Admin\BlogController@blog');
Route::get('/admin/blog/delete/', 'Admin\BlogController@blogDelete');
Route::get('/admin/blog/add/', 'Admin\BlogController@blogAdd');
Route::post('/admin/blog/add/', 'Admin\BlogController@blogPostAdd');
Route::get('/admin/blog/edit/{id?}', 'Admin\BlogController@blogEdit');
Route::post('/admin/blog/edit/{id?}', 'Admin\BlogController@blogPostEdit');

//Team
Route::get('/admin/team/', 'Admin\TeamController@team');
Route::get('/admin/team/delete/', 'Admin\TeamController@teamDelete');
Route::get('/admin/team/edit/{id?}', 'Admin\TeamController@teamEdit');
Route::post('/admin/team/edit/{id?}', 'Admin\TeamController@teamPostEdit');
Route::get('/admin/team/add/', 'Admin\TeamController@teamAdd');
Route::post('/admin/team/add/', 'Admin\TeamController@teamPostAdd');

//Career
Route::get('/admin/vacancies/', 'Admin\VacanciesController@vacancies');
Route::get('/admin/vacancies/delete/', 'Admin\VacanciesController@vacanciesDelete');
Route::get('/admin/vacancies/edit/{id?}', 'Admin\VacanciesController@vacanciesEdit');
Route::post('/admin/vacancies/edit/{id?}', 'Admin\VacanciesController@vacanciesPostEdit');
Route::get('/admin/vacancies/add/', 'Admin\VacanciesController@vacanciesAdd');
Route::post('/admin/vacancies/add/', 'Admin\VacanciesController@vacanciesPostAdd');

//Portfolio
Route::get('/admin/portfolio/', 'Admin\PortfolioController@portfolio');
Route::get('/admin/portfolio/delete/', 'Admin\PortfolioController@portfolioDelete');
Route::get('/admin/portfolio/edit/{id?}', 'Admin\PortfolioController@portfolioEdit');
Route::post('/admin/portfolio/edit/{id?}', 'Admin\PortfolioController@portfolioPostEdit');
Route::get('/admin/portfolio/add/', 'Admin\PortfolioController@portfolioAdd');
Route::post('/admin/portfolio/add/', 'Admin\PortfolioController@portfolioPostAdd');

//Slider
Route::get('/admin/slider/', 'Admin\SliderController@slider');
Route::get('/admin/slider/delete/', 'Admin\SliderController@sliderDelete');
Route::get('/admin/slider/edit/{id?}', 'Admin\SliderController@sliderEdit');
Route::post('/admin/slider/edit/{id?}', 'Admin\SliderController@sliderPostEdit');
Route::get('/admin/slider/add/', 'Admin\SliderController@sliderAdd');
Route::post('/admin/slider/add/', 'Admin\SliderController@sliderPostAdd');


//ckEditor UploadImaged
Route::post('/admin/upload/', 'SiteController@upload');
Route::get('/admin/upload/', 'SiteController@upload');

//Static content for pages
Route::get('/admin/page/home/', 'Admin\PageController@home');
Route::post('/admin/page/home/save/', 'Admin\PageController@homeSave');

Route::get('/admin/page/blog/', 'Admin\PageController@blog');
Route::post('/admin/page/blog/save/', 'Admin\PageController@blogSave');

Route::get('/admin/page/team/', 'Admin\PageController@team');
Route::post('/admin/page/team/save/', 'Admin\PageController@teamSave');

Route::get('/admin/page/portfolio/', 'Admin\PageController@portfolio');
Route::post('/admin/page/portfolio/save/', 'Admin\PageController@portfolioSave');

Route::get('/admin/page/logofolio/', 'Admin\PageController@logofolio');
Route::post('/admin/page/logofolio/save/', 'Admin\PageController@logofolioSave');

Route::get('/admin/page/careers/', 'Admin\PageController@careers');
Route::post('/admin/page/careers/save/', 'Admin\PageController@careersSave');

Route::get('/admin/page/join_the_team/', 'Admin\PageController@joinTheTeam');
Route::post('/admin/page/join_the_team/save/', 'Admin\PageController@joinTheTeamSave');

Route::get('/admin/page/contacts/', 'Admin\PageController@contacts');
Route::post('/admin/page/contacts/save/', 'Admin\PageController@contactsSave');

Route::get('/admin/page/showreel/', 'Admin\PageController@showreel');
Route::post('/admin/page/showreel/save/', 'Admin\PageController@showreelSave');






/* ******************************* NEXT WILL BE DELETE ************************************* */

Route::get('/admin/categories/', 'AdminController@categories');
Route::get('/admin/categories/delete/', 'AdminController@categoriesDelete');
Route::get('/admin/categories/add/', 'AdminController@categoriesAdd');
Route::post('/admin/categories/add/', 'AdminController@categoriesPostAdd');
Route::get('/admin/categories/edit/{id?}/', 'AdminController@categoriesEdit');
Route::post('/admin/categories/edit/{id?}/', 'AdminController@categoriesPostEdit');

Route::get('/admin/products/', 'AdminController@products');
Route::get('/admin/products/delete/', 'AdminController@productsDelete');
Route::get('/admin/products/add/', 'AdminController@productsAdd');
Route::post('/admin/products/add/', 'AdminController@productsPostAdd');
Route::get('/admin/products/edit/{id?}', 'AdminController@productsEdit');
Route::get('/admin/products/copy/{id?}', 'AdminController@productsCopy');
Route::post('/admin/products/edit/{id?}', 'AdminController@productsPostEdit');

Route::get('/admin/attributes/', 'AdminController@attributes');
Route::post('/admin/attributes/save/', 'AdminController@attributesSave');
Route::get('/admin/attributes/delete/', 'AdminController@attributesDelete');




Route::get('/admin/login/', [
    'as' => 'login',
    'uses' => '\App\Http\Controllers\Auth\LoginController@showLoginForm'
]);
Route::get('/login/', [
    'as' => 'login',
    'uses' => '\App\Http\Controllers\Auth\LoginController@showLoginForm'
]);
Route::post('/admin/login/', [
    'as' => '',
    'uses' => '\App\Http\Controllers\Auth\LoginController@login'
]);
Route::post('/admin/logout/', [
    'as' => 'logout',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
]);
Route::get('/admin/logout/', [
    'as' => 'logout',
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
]);
