<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class ProductOptionSetData extends Model
{
    protected $primaryKey="SetId";
    protected $keyType="int";
    protected $table="product_option_set";
    protected $fillable=['SetId','OptionId','ProductType','ProductId'];
}