<?php
namespace App\Members;
use Illuminate\Database\Eloquent\Model;
class AddressBookData extends Model
{
    protected $primaryKey="AddressId";
    protected $keyType="int";
    protected $table="address_book";
    protected $fillable=['AddressId','MemberId','Contact_first','Contact_middle','Contact_last','Company','Telephone','Fax','StreetAddr','StreetAddr2','City','Region','PostCode','Country','AddrType','DefaultChoose','created_at','updated_at'];
}