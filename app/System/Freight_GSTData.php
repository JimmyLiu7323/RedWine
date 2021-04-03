<?php
namespace App\System;
use Illuminate\Database\Eloquent\Model;
class Freight_GSTData extends Model
{
    protected $primaryKey="CountryId";
    protected $keyType="char";
    protected $table="freight_gst";
    protected $fillable=['CountryId','GST','Freight_home','Freight_store','created_at','updated_at'];
}