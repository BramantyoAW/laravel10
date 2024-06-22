<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Shop;

class ShopifyController extends Controller
{
    private $shop_url;
    private $access_token;

    // Getter and Setter for shop_url
    public function setShopUrl($url)
    {
        $this->shop_url = $url;
    }

    public function getShopUrl()
    {
        return $this->shop_url;
    }

    // Getter and Setter for access_token
    public function setAccessToken($token)
    {
        $this->access_token = $token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function restApi($api_endpoint, $query = [], $method = 'GET')
    {
        $url = 'https://' . $this->getShopUrl() . $api_endpoint;

        // Build the full URL for GET and DELETE requests
        if (in_array($method, ['GET', 'DELETE']) && !empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        // Setup the request headers
        $headers = [];
        if (!is_null($this->getAccessToken())) {
            $headers['X-Shopify-Access-Token'] = $this->getAccessToken();
        }

        // Make the request using Laravel's HTTP client
        $response = Http::withHeaders($headers)->$method($url, $query);

        // Check for errors
        if ($response->failed()) {
            return $response->body(); // or you can handle errors as you see fit
        }

        // Return the response as an array with headers and body
        return [
            'headers' => $response->headers(),
            'body' => $response->body()
        ];
    }
}
