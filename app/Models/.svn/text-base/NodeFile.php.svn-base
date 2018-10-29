<?php

namespace App\Models;

use Eloquent;

class NodeFile extends Eloquent {
	//documents atteched to a specifed node
	protected $primaryKey = 'id';
	protected $table = 'node_file';
	protected $fillable = array (  
    	'node_id', 'node_no','fullpath','filename', 'fsize', 'version', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

}

?>