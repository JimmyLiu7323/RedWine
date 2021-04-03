<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class BannersData extends Model
{
    protected $primaryKey="BannerId";
    protected $keyType="int";
    protected $table="banners";
    protected $fillable=['BannerId','BannerImage','BannerLink','BannerTitle','BannerSubtitle','BannerOnStatus','BannerOnDate','BannerOnTime','BannerOffSTatus','BannerOffDate','BannerOffTime','BannerOrder','created_at','updated_at'];
}