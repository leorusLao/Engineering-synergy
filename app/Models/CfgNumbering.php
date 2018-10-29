<?php

namespace App\Models;

use Eloquent;

class CfgNumbering extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'config_num';
	protected $fillable = array (
			'description', 'description_en', 'prefix', 'cycle', 'cycle_en', 'yyyy', 'mm', 'dd', 'serial_length', 'company_id'
	);
	protected $guarded = array (
			'id'
	);
	public $timestamps = true;
		
}

?>
