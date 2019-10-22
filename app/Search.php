<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Search extends Model
{
    public $timestamps = false;
    
    protected $table = 'search';
    protected $primaryKey = 'search_id';

    public static function queryDBSearchUrlList() {
       return $searchUrlList=Search::get();
    }

    public static function deleteDBSearchUrl($searchId) {
        Search::where('search_id','=', $searchId)->delete();
    }
    
    public static function insertDBSearchUrl($searchUrl, $searchPriority) {
        $searchId=Search::where('search_url', $searchUrl)->first();
        if (empty($searchId)) {
            Search::insert(array('search_url'=>$searchUrl,'search_priority'=>$searchPriority));
        }
    }

    public static function updateDBSearchUrl($searchUrlId, $searchUrl, $searchPriority) {
        Search::where('search_id','=', $searchUrlId)->update(array('search_url'=>$searchUrl,'search_priority'=>$searchPriority));
    }
}
