<?php

namespace App\Models;
use DB;
use Eloquent;

class UserRoleReal extends Eloquent
{
	 
	/**
	 * The attributes that are mass assignable.
	 * 
	 * a user may belong to mutiple companys
	 * 
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $table = 'user_role_real';

	protected $fillable = array('role_id', 'uid', 'role_name', 'role_description', 'role', 'company_id');
	protected $guarded = array('id');
	public $timestamps = true;
	
	public static function createUserRolereal($ary)
	{ 
		$affect = self::create($ary);
		return $affect;
	}	

	public static function infoUserRolereal($where,$field='id')
	{
		$result = self::select($field)->where($where)->first();
		return $result;
	}

	public static function updateUserRolereal($where,$ary)
	{ 
		$result = self::where($where)->update($ary);
		return $result;
	}

	
}
