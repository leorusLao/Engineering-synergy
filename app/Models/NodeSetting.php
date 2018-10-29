<?php

namespace App\Models;

use Eloquent;

class NodeSetting extends Eloquent {
	//Node means plan node, node type menas plan-node-type
	protected $primaryKey = 'id';
	protected $table = 'node_setting';
	protected $fillable = array (  
    	 'completion_date', 'percent_done', 'cover_children', 'parent_auto', 'task_advise_header', 'task_advise_pmanager', 'task_advise_pmember', 
			'done_advise_supervisor', 'done_advise_header', 'done_advise_pmanager', 'done_advise_pmember', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
   
	static public function isExistedNodeSetting($companyId)
	{
		return self::where('company_id',$companyId)->first();
  
	}

	static public function addSetting($completion_date, $percent_done, $cover_children, $parent_auto, $task_advise_header, $task_advise_pmanager, $task_advise_pmember,
			$done_advise_supervisor, $done_advise_header, $done_advise_pmanager, $done_advise_pmember, $company_id)
	{
		return self::create(array('completion_date' => $completion_date, 'percent_done' => $percent_done, 'cover_children' => $cover_children, 'parent_auto' => $parent_auto,
				'task_advise_header' => $task_advise_header, 'task_advise_pmanager' => $task_advise_pmanager, 'task_advise_pmember' => $task_advise_pmember, 'done_advise_supervisor' => $done_advise_supervisor,
				'done_advise_header' => $done_advise_header, 'done_advise_pmanager' => $done_advise_pmanager, 'done_advise_pmember' => $done_advise_pmember, 'company_id' => $company_id));
	}

 
	static public function updateSetting($completion_date, $percent_done, $cover_children, $parent_auto, $task_advise_header, $task_advise_pmanager, $task_advise_pmember,
			$done_advise_supervisor, $done_advise_header, $done_advise_pmanager, $done_advise_pmember, $company_id)
	{
		return self::where(array('company_id' => $company_id))->update(array('completion_date' => $completion_date, 'percent_done' => $percent_done, 'cover_children' => $cover_children, 'parent_auto' => $parent_auto,
				'task_advise_header' => $task_advise_header, 'task_advise_pmanager' => $task_advise_pmanager, 'task_advise_pmember' => $task_advise_pmember, 'done_advise_supervisor' => $done_advise_supervisor,
				'done_advise_header' => $done_advise_header, 'done_advise_pmanager' => $done_advise_pmanager, 'done_advise_pmember' => $done_advise_pmember));
	}
}

?>
