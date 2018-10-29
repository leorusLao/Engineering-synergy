<?php

namespace App\Models;

use Eloquent;

class Approver extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'approver';
	protected $fillable = array (
			'project_uid', 'plan_uid', 'issue_uid', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
}

?>
