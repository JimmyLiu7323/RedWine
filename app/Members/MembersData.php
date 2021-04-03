<?php
namespace App\Members;
use Illuminate\Database\Eloquent\Model;
class MembersData extends Model
{
    protected $primaryKey="MemberId";
    protected $keyType="varchar";
    protected $table="members";
    protected $fillable=['MemberId','Country','FirstName','MiddleName','LastName','Email','Password','FollowingNews','created_at','updated_at'];
}