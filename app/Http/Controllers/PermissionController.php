<?php

namespace App\Http\Controllers;
use App;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Input;
use Auth;
use Session;
use App\Models\UserCompany;
use App\Models\Permissions;

class PermissionController extends Controller
{
	public static function permission()
	{
		$data = Cache::get('PermissionData');
		if(!$data){
			$data = self::init();
			Cache::forever('PermissionData', $data);
		}
		$uid = Session::get('uid');
		$role_id = UserCompany::where('uid', $uid)->value('role_id');

		return isset($data[$role_id]) ? $data[$role_id] : [];
	}

	public static function init()
	{
		Cache::forget('PermissionData');
		$permissionArr = Permissions::where('route_name','<>', '')->pluck('route_name', 'id')->toArray();
		$res = DB::select('select `role_id`, `permission_id` from `role_has_permissions`');
		$permissionData = [];
		foreach($res as $k => $v){
			$tmp = explode(',', $v->permission_id);
			foreach($tmp as $vv){
				if(isset($permissionArr[$vv])){
					$permissionData[$v->role_id][] = $permissionArr[$vv];
				}
			}
		}
		Log::debug('$permissionData');
		return $permissionData;
	}
}