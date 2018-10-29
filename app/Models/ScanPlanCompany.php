<?php

namespace App\Models;

use Eloquent;

class ScanPlanCompany extends Eloquent {
	 
	protected $primaryKey = 'id';
	protected $table = 'scan_plan_company';
	protected $fillable = array (
		 'code', 'scan_name', 'scan_name_en', 'date_range', 'trigger_event',
		 'send_leader', 'people_list', 'detect_method', 'is_active', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
	 
	static public function isExistedCodeCompany($code, $company_id)
	{
		$row = self::where(array('code' => $code, 'company_id' => $company_id ))->first();

		if($row) return true;
		return false;
	}

    static public function customizeScanPlan($code, $scan_name, $date_range, $trigger, $people, $company_id )
    {
    	$existed = self::isExistedCodeCompany($code, $company_id);
    	
    	if($existed) {
    		return self::where(array('code' => $code, 'company_id' => $company_id))->update(array('scan_name' => $scan_name, 'date_range' => $date_range,
    				         'trigger_event' => $trigger, 'people_list' => $people, 'is_active' => 1));
    	}
    	    return self::create(array('code' => $code, 'scan_name' => $scan_name, 'date_range' => $date_range, 'trigger_event' => $trigger, 
    	    		'people_list' => $people, 'company_id' => $company_id
    	));
    }
    
    static public function disableAlert($code, $company_id) 
    {
    	$existed = self::isExistedCodeCompany($code, $company_id);
    	if($existed) {
    		return self::where(array('code' => $code, 'company_id' => $company_id))->update(array('is_active' => '0')); 
    	}
    }
}

?>
