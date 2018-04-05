<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryToken extends Model {
    protected $table = 'pub_token';
    public $timestamps = false;    

    function findLastToken($userId){
        $tokenObj = DB::table("pub_token")
                      ->where("user",$userId)
                      ->orderBy("id","desc")
                      ->first();
        if($tokenObj){
            return $tokenObj->token;
        }else{
            return null;
        }        
    }
}