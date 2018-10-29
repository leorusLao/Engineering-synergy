<?php

namespace App\Models;

use Eloquent;

class ScanPlan extends Eloquent {
	 
	protected $primaryKey = 'id';
	protected $table = 'scan_plan';
	protected $fillable = array (  
    	 'code', 'scan_name', 'scan_name_en', 'date_range', 'trigger_event', 
		 'send_leader', 'people_list', 'detect_method', 'is_active', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
   
	static public function isExistedNode($nodeNo, $companyId)
	{
		$row = self::where('node_no',$nodeNo)->whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->first();

		if($row) return true;
		return false;
	}

	 
}

?>
