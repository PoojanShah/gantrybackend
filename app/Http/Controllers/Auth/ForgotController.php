<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Exception;
use Illuminate\Http\Request;

class ForgotController extends Controller
{
    public function index(Request $request) {

        $success = '';
        $error = '';

        $old_user = User::first();

        if(isset($request->email)) {
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                if($request->email == $old_user->email) {
                    $token_for_password = Hash::make(date("Y-m-d H:i:s"));

                    User::where('id', '=', 1)->update([
                        'token_for_password' => $token_for_password,
                    ]);
                    $to = $old_user->email;

                    $headers = "Content-type: text/plain; charset=UTF-8\r\n";
                    $headers .= "From: Laravel <info@" . $_SERVER['HTTP_HOST'] . ">\r\n";

                    $message = "Восстановление пароля в панели администратора Maincode\n";
                    $message .= "Подтвердите смену пароля, перейдя по ссылке: https://" . $_SERVER['HTTP_HOST'] . "/change_password?token_for_password=" . $token_for_password;

                    mail($to, 'Восстановление пароля в панели администратора Maincode', $message, $headers);

                    $success = 'Подтвердите обновление, перейдя по ссылке в Email ' . $old_user->email;
                }
                else {
                    $error = "Такого Email не существует!";
                }
            }
        }

        $user = User::first();

        return view('forgot', ['data' => $user, 'success' => $success, 'error' => $error]);

    }

    public function change(Request $request) {
        if(!isset($request->token_for_password)) {
            echo "Error!";
        }
        else {
            $user = User::first();
            if ($request->token_for_password == $user->token_for_password) {
                $new_password = rand(10000, 99999);
                $password = Hash::make($new_password);
                User::where('id', '=', 1)->update([
                    'password' => $password,
                    'token_for_password' => ''
                ]);
                $to = $user->email;

                $headers = "Content-type: text/plain; charset=UTF-8\r\n";
                $headers .= "From: Laravel <info@" . $_SERVER['HTTP_HOST'] . ">\r\n";

                $message = "Новый пароль от панели администратора Maincode\n";
                $message .= $new_password."\n\n";
                $message .= "Обязательно измените пароль после авторизации!";

                mail($to, 'Восстановление пароля в панели администратора Maincode', $message, $headers);

                echo "Новый пароль отправлен на Email администратора. Обязательно измените пароль после авторизации!";
            }
        }
    }
}
