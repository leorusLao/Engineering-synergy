<?php

namespace App\Models;

use Eloquent;

class PlanTaskLink extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'plan_task_link';
	protected $fillable = array (
			'from_id', 'to_id', 'type', 'plan_id', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
}

?>
