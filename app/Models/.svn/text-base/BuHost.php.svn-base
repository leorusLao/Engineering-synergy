<?php

namespace App\Models;
use \Exception;
use Eloquent;
use DB;

class Buhost extends Eloquent {
    protected $primaryKey = 'bu_id';//
    protected $table = 'buhost';
    protected $fillable = array (
            'bu_id', 'bu_name', 'bu_site', 'verify_code', 'is_master', 'monpoly', 'description'
    );
    protected $guarded = array (
            'bu_id'
    );

    public $timestamps = true;

    public static function listBuApi()
    { 
        try{
            $result['buList'] = Buhost::where('bu_id', '<>', 1)->select('bu_id as id','bu_name as short_name')->get();
            return $result;

        }catch(Exception $e){ return 10003; } 
    }


}

?>
