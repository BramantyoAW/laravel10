<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ShopifyController;
use App\Models\Tenants;


class RedirController extends Controller
{
    const API_KEY = '7544105ed75070f1507a150a0011c92d';
    const SECRET_KEY = 'b513bd67f20f274fd6e898ed919c3584';
    
    public function index()
    {
        $api_key = self::API_KEY;
        $secret_key = self::SECRET_KEY;
        $parameters = $_GET;
        $shop_url = isset($parameters['shop']) ? $parameters['shop'] : '';
        $hmac = isset($parameters['hmac']) ? $parameters['hmac'] : '';
        $parameters = array_diff($parameters, array('hmac' => ''));
        ksort($parameters);
        $new_hmac = hash_hmac('sha256',http_build_query($parameters), $secret_key);

        $access_token_endpoint = 'https://'. $shop_url .'/admin/oauth/access_token';

        $body = array(
            "client_id" => $api_key,
            "client_secret" => $secret_key,
            "code" => $parameters['code'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response,true);
        
        if (isset($response['access_token'])) {
            
            $shopData = [
                'domain' => $shop_url,
                'token' => $response['access_token'],
            ];
    
            \DB::table('tenants')->updateOrInsert(
                ['domain' => $shop_url],
                $shopData
            );
            
            return redirect()->away('https://' . $shop_url . '/admin/apps');
        } else {
            return redirect()->away('https://' . $shop_url . '/admin/oauth/error');
        }
    }
}
