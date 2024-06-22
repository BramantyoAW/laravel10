<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ShopifyController;
use App\Models\Shop;

class ShopController extends Controller
{
    const NGROK_URL = 'https://f3e6-149-113-4-155.ngrok-free.app';
    const API_KEY = '7544105ed75070f1507a150a0011c92d';
    const SECRET_KEY = 'b513bd67f20f274fd6e898ed919c3584';

    private $shopifyController;

    public function __construct(
        ShopifyController $shopifyController
    ){
        $this->shopifyController = $shopifyController;
    }


    public function index()
    {
        $shop_url = isset($_GET['shop']) ? $_GET['shop'] : '';
        $shop = Shop::where('shop_url', $shop_url)->first();

        if ($shop) {
            $this->shopifyController->setShopUrl($shop['shop_url']);
            $this->shopifyController->setAccessToken($shop['access_token']);
            
            $shopProduct = $this->shopifyController->restApi('/admin/api/2024-04/products.json', array(), 'GET');
            $shopProduct = json_decode($shopProduct['body'],true);
            if ($shopProduct) {
                return view('product', ['shopProduct' => $shopProduct]);
            }
        }else{
            return redirect('install?shop='.$shop_url);
        }

    }

    public function install()
    {
        $shop = $_GET['shop'];
        $scopes = 'read_products,write_products,read_orders,write_orders'; 
        $redirect_url = self::NGROK_URL . '/token';
        $nonce = bin2hex(random_bytes( 12 ));
        $access_mode = 'per-user';

        $oauth_url = 'https://' . $shop . '/admin/oauth/authorize?' .
        'client_id=' . self::API_KEY . 
        '&scope=' . $scopes . 
        '&redirect_uri=' . urlencode($redirect_url) . 
        '&state=' . $nonce . 
        '&grant_options[]=' . $access_mode;

        return redirect::away($oauth_url);
    }

    public function getToken()
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
                'shop_url' => $shop_url,
                'access_token' => $response['access_token'],
                'install_date' => NOW()
            ];
    
            \DB::table('shop')->updateOrInsert(
                ['shop_url' => $shop_url],
                $shopData
            );
            
            return redirect()->away('https://' . $shop_url . '/admin/apps');
        } else {
            return redirect()->away('https://' . $shop_url . '/admin/oauth/error');
        }
    }

    public function shop(Request $request)
    {
        $shop = new Shop;
        $shop->shop_url = $request->shop_url;
        $shop->access_token = $request->shop_url;
        $shop->install_date = $request->shop_url;
        $shop->save();

        return response()->json([
            "message" => "new shop addedd"
        ], 200);
    }

    public function showShop($id)
    {
        $shop = Shop::find($id);
        if (!empty($shop)) {
            return response()->json($shop);
        }else{
            return response()->json([
                "message" => "Shop not found"
            ], 200);
        }
    }
}
