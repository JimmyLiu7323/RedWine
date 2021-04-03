<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class IndexFeaturedImagesData extends Model
{
    protected $primaryKey="SN";
    protected $keyType="int";
    protected $table="index_featured_images";
    protected $fillable=['SN','Image','ImageEn','LinkFile','LinkFileEn','BigTitle','BigTitleEn','Description','DescriptionEn','created_at','updated_at'];
}