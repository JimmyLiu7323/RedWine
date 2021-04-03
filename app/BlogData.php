<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class BlogData extends Model
{
    protected $primaryKey="ArticleId";
    protected $keyType="var";
    protected $table="blogs";
    protected $fillable=['ArticleId','Image','Title','TitleEn','BriefDesc','BriefDescEn','Category','Tags','TagsEn','OnDate','OffDate','Content','ContentEn','OrderNumber','Status','OnTop','Flagship','Author','created_at','updated_at'];
}