<?php

namespace App\Models;

use Eloquent;

class Logging extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'logging';
	protected $fillable = array (
			'uid',
			'table_name',
			'record_id',
			'action',
		 	'company_id'
	);

	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
	
	public static function appendLog($uid, $table, $record_id, $action, $company_id) 
	{
		Logging::create(array('uid' => $uid, 'table_name' => $table, 'record_id' => $record_id, 'action' => $action, 'company_id' => $company_id));
	}

}