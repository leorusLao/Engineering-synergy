<?php
namespace App\Models;
use Eloquent;


class UserRoleConfig extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'user_role_config';

	protected $fillable = array('start_role_id', 'current_role_id', 'company_id');
	protected $guarded = array('id');
    
    
	
}

?>