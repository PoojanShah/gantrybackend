<?php

namespace App\Http\Controllers\Auth;

use App\Interfaces\OAuthClientInterface;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Exception;
use Illuminate\Http\Request;

class ZohoOAuthTempCodeController extends Controller
{
    public function processCode(OAuthClientInterface $client) {
// In the redirection page, check for the state you got before and that you should've stored
        if ($_SESSION['zoho_oauth_state'] !== $_GET['state']) {
            throw new \Exception('Someone is tampering with the oauth2 request');
        }

// Try to get an access token (using the authorization code grant)
        try {
            $client->setGrantCode($_GET['code']);

            // if you set the offline mode, you can also get the refresh token here (and store it)
            $refreshToken = $client->getRefreshToken();

            // get the access token (and store it probably)
            $token = $client->getAccessToken();
            var_dump($token);
            Redirect::action('admin');

        } catch (\Exception $e) {
            dd('Didnt process redirection ' , $e);
        }
    }
}
