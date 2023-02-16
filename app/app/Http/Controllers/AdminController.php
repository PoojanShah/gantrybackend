<?php

namespace App\Http\Controllers;

use App\Interfaces\OAuthClientInterface;
use App\Models\Settings;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function index(Request $request)
    {
        $data = [];

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
            $data['refreshToken'] = $settings
                ? json_decode($settings->settings, true)['refreshToken']
                : 'No token!';

//            $authClient->promptForConsent(false);
//            $url = $authClient->getAuthorizationUrl();
//            $_SESSION['zoho_oauth_state'] = $client->getState();// Get the state for security, and save it (usually in session)
//            redirect($url); // Do your redirection as you prefer

        }

        $data['user'] = $request->user();

        return view('admin.zohoTokens', ['data' => $data])->withErrors($errors);
    }
}
