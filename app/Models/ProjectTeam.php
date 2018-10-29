<?php

namespace App\Models;

use Eloquent;

class ProjectTeam extends Eloquent {
	protected $primaryKey = 'team_id';
	protected $table = 'project_team';
	protected $fillable = array (
			'project_id', 'name_list', 'company_id' 
	);
	protected $guarded = array (
			'team_id'
	);

	public $timestamps = true;

	 
}

?>