<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the services.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {
        //var_dump(Hash::make('adminadmin'));
        //$2y$10$rAKMWnxhvSyhiORLYgfnvuHcR/9HyxZg9cgZUgQUp74UK56weC0jG
        $old_user = User::first();

        $success = '';
        $error = '';
        if(isset($request->email) || isset($request->password)) {
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                if(empty($request->password)) {
                    User::where('id', '=', 1)->update([
                        'new_email' => $request->email,
                    ]);

                    $to = $old_user->email;

                    $headers = "Content-type: text/plain; charset=UTF-8\r\n";
                    $headers .= "From: Laravel <info@".$_SERVER['HTTP_HOST'].">\r\n";

                    $message = "Запрошено обновление Вашего Email администратора в панели администратора\n";
                    $message .= "Старый Email: ".$old_user->email."\n";
                    $message .= "Новый Email: ".$request->email."\n";
                    $message .= "Подтвердите смену Email, перейдя по ссылке: https://".$_SERVER['HTTP_HOST']."/admin/change_profile?new_email=".$request->email;

                    mail($to, 'Смена Email в панели администратора', $message, $headers);

                    $success = 'Подтвердите обновление, перейдя по ссылке в Email '.$old_user->email;
                }
                else {
                    if (strlen($request->password) < 5) {
                        $error = 'Длина пароля должна быть не менее 5 символов!';
                    } else {
                        $token_for_password = Hash::make(date("Y-m-d H:i:s"));

                        User::where('id', '=', 1)->update([
                            'new_email' => $request->email,
                            'new_password' => Hash::make($request->password),
                            'token_for_password' => $token_for_password,
                        ]);
                        $to = $old_user->email;

                        $headers = "Content-type: text/plain; charset=UTF-8\r\n";
                        $headers .= "From: Laravel <info@".$_SERVER['HTTP_HOST'].">\r\n";

                        $message = "Запрошено обновление Вашего Email/Пароля администратора в панели администратора\n";
                        $message .= "Старый Email: ".$old_user->email."\n";
                        $message .= "Новый Email: ".$request->email."\n";
                        $message .= "Подтвердите смену Email/Пароля, перейдя по ссылке: https://".$_SERVER['HTTP_HOST']."/admin/change_profile?new_email=".$request->email."&token_for_password=".$token_for_password;

                        mail($to, 'Смена Email/Пароля в панели администратора', $message, $headers);

                        $success = 'Подтвердите обновление, перейдя по ссылке в Email '.$old_user->email;
                    }
                }
            }
            else {
                $error = 'Введите правильный Email!';
            }
        }
        $user = User::first();

        $data = Array();
        $data['page_title'] = 'Dashboard';
        $data['user'] = $request->user();

        return view('profile', ['data' => $data, 'user' => $user, 'success' => $success, 'error' => $error]);
    }

    public function change(Request $request) {
        if(!isset($request->token_for_password)) {
            $user = User::first();
            if (!empty($user->new_email)) {
                if ($request->new_email == $user->new_email) {
                    User::where('id', '=', 1)->update([
                        'email' => $user->new_email,
                        'new_email' => ''
                    ]);
                    echo "Успешно обновлено!";
                }
                else {
                    echo "Error!";
                }
            } else {
                echo "Error!";
            }
        }
        else {
            $user = User::first();
            if (!empty($user->new_email)) {
                if ($request->new_email == $user->new_email) {
                    if(!empty($user->new_password)) {
                        if ($request->token_for_password == $user->token_for_password) {
                            User::where('id', '=', 1)->update([
                                'email' => $user->new_email,
                                'new_email' => '',
                                'password' => $user->new_password,
                                'new_password' => '',
                                'token_for_password' => ''
                            ]);
                            echo "Успешно обновлено!";
                        }
                        else {
                            echo "Error!";
                        }
                    }
                    else {
                        echo "Error!";
                    }
                }
                else {
                    echo "Error!";
                }
            } else {
                echo "Error!";
            }
        }

    }

    public function edit() {
       /* return User::update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ])->where('id', '=', 1);*/
    }

}
