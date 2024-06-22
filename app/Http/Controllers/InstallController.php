<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ShopifyController;
use App\Models\Tenants;

class InstallController extends Controller
{
    const NGROK_URL = 'https://f3e6-149-113-4-155.ngrok-free.app';
    const API_KEY = '7544105ed75070f1507a150a0011c92d';
    const SECRET_KEY = 'b513bd67f20f274fd6e898ed919c3584';
    const SCOPE = 'read_products,write_products';

    private $shopifyController;

    public function __construct(
        ShopifyController $shopifyController
    ){
        $this->shopifyController = $shopifyController;
    }


    public function index()
    {
        $shop_url = isset($_GET['shop']) ? $_GET['shop'] : '';
        $shop = Tenants::where('domain', $shop_url)->first();

        if ($shop) {
            return redirect('/');
        }else{
            $shop = $_GET['shop'];
            $scopes = self::SCOPE; 
            $redirect_url = self::NGROK_URL . '/redir';
            $nonce = bin2hex(random_bytes( 12 ));
            $access_mode = 'offline';

            $oauth_url = 'https://' . $shop . '/admin/oauth/authorize?' .
            'client_id=' . self::API_KEY . 
            '&scope=' . $scopes . 
            '&redirect_uri=' . urlencode($redirect_url) . 
            '&state=' . $nonce . 
            '&grant_options[]=' . $access_mode;

            return redirect::away($oauth_url);
        }

    }
}
