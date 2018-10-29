<?php
namespace App\Models;
use Eloquent;

class Sysconfig extends Eloquent {

protected $primaryKey = 'cfg_id';
protected $table = 'sysconfig';
 
protected $fillable = array('cfg_name','cfg_value','data_type','comment');
protected $guarded = array('cfg_id');
public $timestamps = true;

 	 static public function UidIncrement() 
 	 {
 	 	Sysconfig::where('cfg_name','uid_current_id')->increment('cfg_value',1);
 	 }
 	 
 	 static public function getCurrentUid()
 	 {
 	 	$cur = Sysconfig::where('cfg_name','uid_current_id')->first();
 	 	return $cur->cfg_value;
 	 }
}

?>