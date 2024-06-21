<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Shop::all();
        return response()->json($shop);
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
