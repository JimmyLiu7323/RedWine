<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class SalesMixData extends Model
{
    protected $primaryKey="MixId";
    protected $keyType="varchar";
    protected $table="sales_mix";
    protected $fillable=['MixId','Image','ParentCase','Price','S_price','Stocks','MixName','MixNameEn','OrderNumber','Status','WeeklyReco','BriefDesc','Description','Delivery_Returns','BriefDescEn','DescriptionEn','Delivery_ReturnsEn','ActDate','EndDate','NoOffShelf','MetaDesc','MetaDescEn','MetaKeywords','MetaKeywordsEn','Maintainer','created_at','updated_at'];
}