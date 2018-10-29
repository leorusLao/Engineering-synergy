<?php

namespace App\Models;

use Eloquent;

class Task extends Eloquent {
	 
	protected $primaryKey = 'id';
	protected $table = 'task';
	protected $fillable = array (  
    	 'name', 'name_en', 'node_id', 'node_no', 'node_type', 'department_id', 'expandable','duration', 'start', 'end', 'parent_id', 'milestone', 'ordinal', 'ordinal_priority', 'complete', 
		 'template_id', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
}

?>