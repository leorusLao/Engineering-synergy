<?php

namespace App\Models;

use Eloquent;

class PlanTaskEvent extends Eloquent {//计划节点触发事件信息发送表
	protected $primaryKey = 'id';
	protected $table = 'plan_event';
	protected $fillable = array (
			'project_id', 'plan_id', 'task_id', 'title', 'msg_content', 'member_list', 'site_message', 'small_routine', 'email', 'sms',
			'complete', 'message_sent', 'sent_start', 'sent_end', 'company_id'
	);

	protected $guarded = array (
			'id'
	);

	public $timestamps = true;


}

?>
