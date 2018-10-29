<?php
namespace App\Models;
use Eloquent;


class UserRole extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'user_role';

	protected $fillable = array('role_id', 'role_code', 'role_name', 'english', 'role_description', 'company_id');
	protected $guarded = array('id');
    
	static function companyUserRoleList() 
	{
	 
		$rows = UserRole::where('role_id', '>=',  '20')->orderBy('id','asc')->get();
		foreach($rows as $row){
			$list[$row->role_id] = $row->role_name;
		}
		return $list;
	}
	
	static function isExistedRoleCode($role_code, $company_id) 
	{
		$row = self::whereRaw("role_code = '" .$role_code . "' AND (company_id = ". $company_id. " OR company_id = 0)")->first();
		if($row) {
			return true;
		} else {
			return false;
		}
	}
	
	static function isExistedRoleName($role_name, $company_id)
	{
		$row = self::whereRaw("role_name = '" .$role_name . "' AND (company_id = ". $company_id. " OR company_id = 0)")->first();
		if($row) {
			return true;
		} else {
			return false;
		}
	}
	
	static function addUserRole($role_id, $role_code, $role_name, $description, $company_id)
	{
		self::create(array('role_id' => $role_id, 'role_code' => $role_code, 'role_name' => $role_name, 
				'role_description' => $description, 'company_id' => $company_id));
	}
}

?>