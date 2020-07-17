<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
    protected $table = "user";
    protected $primaryKey = "user_id";
    public $timestamps = false;
    protected $fillable=["user_id","user_name","user_pwd"];
}
