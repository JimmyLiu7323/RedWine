<?php
namespace App\Products;
use Illuminate\Database\Eloquent\Model;
class WinesData extends Model
{
    protected $primaryKey="WineId";
    protected $keyType="varchar";
    protected $table="wines";
    protected $fillable=['WineId','IntroductionPDF','Image','Image2','Image3','Name','Price','S_price','Volume','Alcohol','WineCatg','WineStyle','WineVariety','WineColour','WineCountry','WineRegion','WineClosure','WineCaterings','Maturity_Start','Maturity_End','Stocks','ActDate','EndDate','NoOffShelf','OrderNumber','Status','WeeklyReco','Flagship','BriefDesc','Description','Delivery_Returns','Maintainer','created_at','updated_at'];
}