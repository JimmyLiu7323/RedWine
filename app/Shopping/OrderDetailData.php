<?php
namespace App\Shopping;
use Illuminate\Database\Eloquent\Model;
class OrderDetailData extends Model
{
    protected $primaryKey="DetailSN";
    protected $keyType="int";
    protected $table="order_detail";
    protected $fillable=['DetailSN','OrderId','ProductType','Product','Price','Quantity','Subtotal'];
}