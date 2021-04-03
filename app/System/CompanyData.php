<?php
namespace App\System;
use Illuminate\Database\Eloquent\Model;
class CompanyData extends Model
{
    protected $primaryKey="InfoKey";
    protected $keyType="var";
    protected $table="companyinfo";
    protected $fillable=['InfoKey','InfoKey_Zh','InfoValue','updated_at'];
    public $incrementing=false;
}