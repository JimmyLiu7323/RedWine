<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class ExhibitionAttendeeData extends Model
{
    protected $primaryKey="AttendeeId";
    protected $keyType="int";
    protected $table="exhibition_attendees";
    protected $fillable=['AttendeeId','MemberId','EventId','Quantity','Paymethod','Status','QRCode','QRCodePic','PaymentTransNo','created_at','updated_at'];
}