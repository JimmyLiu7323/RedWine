<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ExhibitionData extends Model
{
    protected $primaryKey="EventId";
    protected $keyType="varchar";
    protected $table="exhibitions";
    protected $fillable=['EventId','EventType','ImpactImage','TicketImage','EventName','EventNameEn','BriefDesc','BriefDescEn','Description','DescriptionEn','EventDateTime_Start','EventDateTime_End','AllCapacity','StandingCapacity','SeatingCapacity','EventOnDateTime_Start','EventOnDateTime_End','Free','Price','Status','created_at','updated_at'];

    protected static function newEvent($eventData){
    	if(Self::insert($eventData)){
    		return true;
    	}
    	return false;
    }
}