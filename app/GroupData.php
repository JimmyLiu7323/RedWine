<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GroupData extends Model
{
    protected $primaryKey="GroupId";
    protected $keyType="int";
    protected $table="groups";
    protected $fillable=['GroupId','GroupName','GroupDesc','Funcs','ChildFuncs','created_at','updated_at'];
}