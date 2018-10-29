<?php
namespace App\Models;
use Eloquent;


class UserResourceRole extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'user_resource_role';

	protected $fillable = array('role_id', 'resource_id', 'pread', 'padd', 'pdelete','pupdate', 'papproval','company_id'
	);
	protected $guarded = array('id');
	public $timestamps = true;

	 
}

?>
