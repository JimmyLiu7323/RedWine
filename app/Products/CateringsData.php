<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class CateringsData extends Model
{
    protected $primaryKey="CateringId";
    protected $keyType="int";
    protected $table="wine_caterings";
    protected $fillable=['CateringId','Catering','CateringPic','Status','Memo','Maintainer','created_at','updated_at'];
}