<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class AdminData extends Model
{
    protected $primaryKey="AdminId";
    protected $keyType="int";
    protected $table="admins";
    protected $fillable=['AdminId','AdminAuthority','AdminAccount','AdminPassword','AdminName','Department','Email','Phone','PhoneExt','Status','created_at','updated_at'];
    protected static function login($acct,$password)
    {
        if($row=Self::where('AdminAccount',$acct)->where('Status',1)->take(1)->first()){
            if(password_verify($password,$row->AdminPassword))
                return $row;
        }
        return false;
    }
}