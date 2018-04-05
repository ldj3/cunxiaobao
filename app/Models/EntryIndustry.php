<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class EntryIndustry extends Model {
    protected $table = 'pub_industry';
    public $timestamps = false;    
      
    

    public static function getIndustry($industryId){
      $industry = DB::table("pub_industry")
                  ->where("id",$industryId)
                  ->first();
      return $industry;
    }
}