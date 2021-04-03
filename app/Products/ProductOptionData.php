<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class ProductOptionData extends Model
{
    protected $primaryKey="OptionId";
    protected $keyType="int";
    protected $table="product_options";
    protected $fillable=['OptionId','OptionRule','Price','PurchaseAmount','created_at','updated_at'];
}