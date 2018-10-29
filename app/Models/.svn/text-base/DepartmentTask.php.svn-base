<?php

namespace App\Models;

use Eloquent;

class DepartmentTask extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'department_task';
	protected $fillable = array (
			'task_id', 'plan_id', 'name', 'node_id', 'node_no', 'node_type', 'parent_id', 'start_date', 'end_date', 'duration', 'milestone', 
			'ordinal', 'ordinal_priority', 'department', 'leader', 'member_list', 'outsource', 'outsource_supplier', 'key_node', 'key_condition', 
			'real_start', 'real_end', 'complete', 'progress_remark', 'status', 'process_status', 'site_message', 'small_routine',
			'email', 'sms', 'company_id'
	);
	
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;


}

?>
