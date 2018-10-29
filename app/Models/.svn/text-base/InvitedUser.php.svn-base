<?php
namespace App\Models;
use Eloquent;

class InvitedUser extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'invited_user';

	protected $fillable = array('host_uid','host_company','guest_uid','guest_company','invited_type', 'client_type', 'invite_status');
	protected $guarded = array('id');
	public $timestamps = true;

	public static function createInvitedUser($ary)
	{ 
		$affect = self::create($ary);
		return $affect;
	}

	public static function infoInvitedUser($where,$field='id')
	{
		$result = self::select($field)->where($where)->first();
		return $result;
	}

	public static function updateInvitedUser($where,$ary)
	{ 
		$result = self::where($where)->update($ary);
		return $result;
	}

}

?>