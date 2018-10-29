<?php

namespace App\Models;

use Eloquent;

class BOM extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'bom';
	protected $fillable = array (
			'level','part_no', 'part_name', 'revision', 'quantity', 'unit', 'procurement',
			'reference', 'note', 'company_id', 'parent_company' 
	);
	
	protected $guarded = array (
			'id' 
	);
	
	public $timestamps = true;
 	 
}

?>