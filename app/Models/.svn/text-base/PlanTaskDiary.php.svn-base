<?php

namespace App\Models;

use Eloquent;

class PlanTaskDiary extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'plan_task_diary';
	protected $fillable = array (
			'task_id', 'plan_id', 'name', 'node_id', 'node_no', 
			'progress_remark', 'complete', 'report_time', 'company_id'
	);
	
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
 
}

?>

