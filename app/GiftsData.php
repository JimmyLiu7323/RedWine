<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GiftsData extends Model
{
    protected $primaryKey="GiftId";
    protected $keyType="varchar";
    protected $table="gifts";
    protected $fillable=['GiftId','Image','Name','NameEn','Price','S_price','Volume','Alcohol','Category','Stocks','ActDate','EndDate','OrderNumber','Status','BriefDesc','Description','Delivery_Returns','BriefDescEn','DescriptioEn','Delivery_ReturnsEn','created_at','updated_at'];
}