<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GiftBannersData extends Model
{
    protected $primaryKey="BannerId";
    protected $keyType="int";
    protected $table="gift_banners";
    protected $fillable=['BannerId','BannerImage','BannerLink','BannerOnStatus','BannerOnDate','BannerOffDate','BannerOrder','created_at','updated_at'];
}