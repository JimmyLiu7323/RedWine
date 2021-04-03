<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class CasesData extends Model
{
    protected $primaryKey="CaseId";
    protected $keyType="int";
    protected $table="cases";
    protected $fillable=['CaseId','CasePic','CaseName','CaseNameEn','MetaDesc','MetaDescEn','MetaKeywords','MetaKeywordsEn','OrderNumber','Status','created_at','updated_at'];
}