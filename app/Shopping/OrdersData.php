<?php
namespace App\Shopping;
use Illuminate\Database\Eloquent\Model;
class OrdersData extends Model
{
    protected $primaryKey="OrderId";
    protected $keyType="var";
    protected $table="orders";
    protected $fillable=['OrderId','MemberId','Paymethod','Subtotal','GST','DeliveryCost','Total','MemberDiscount','Notes','Status','PayDate','CardHolder','CardName','CardNumber','QRCode','QRCodePic','PaymentTransNo','created_at','updated_at'];
}