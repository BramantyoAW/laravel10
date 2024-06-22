<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    use HasFactory;
    protected $table = 'tenants';
    protected $fillable = ['sku','data','shop_product_id'];
}
