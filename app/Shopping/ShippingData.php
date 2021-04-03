<?php
namespace App\Shopping;
use Illuminate\Database\Eloquent\Model;
class ShippingData extends Model
{
    protected $primaryKey="OrderId";
    protected $keyType="var";
    protected $table="order_shipping";
    protected $fillable=['OrderId','Type','Country','FirstName','MiddleName','LastName','Company','Address','Address2','City','Region','PostCode','Email','Telephone','created_at','updated_at'];
}