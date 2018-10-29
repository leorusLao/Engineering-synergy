<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/2/8
 * Time: 17:38
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use DB;
use Session;
use Illuminate\Support\Facades\Cache;

class Permissions extends Model
{
    use SoftDeletes;
    protected $table ='permissions';
    protected $dates = ['deleted_at'];
    public function permissionsData($where)
    {
        $data = [];

        $data = $this->where($where)
            ->orderBy('sort', 'asc')
            ->get(['id', 'pid', 'sort', 'display_name', 'is_menu'])
            ->toArray();
        return $this->getLevel($data, 0, 0);
    }

    private function getLevel($arr,$pid,$step){
        global $tree;
        foreach($arr as $key=>$val) {
            if($val['pid'] == $pid) {
//                $flg = str_repeat('└―',$step);
//                $val['display_name'] = $flg.$val['display_name'];
                $val['flg'] = str_repeat('&emsp;&emsp;', $step);
                $val['_flg'] = str_repeat('└―',$step);
                $tree[] = $val;
                $this->getLevel($arr , $val['id'] ,$step+1);
            }
        }
        return $tree;
    }

    public function getTreeData($where)
    {
        $data = $this->where($where)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        foreach($data as $k => $v){
            if(!in_array($v['route_name'], array_merge(self::permission(), config('app.except')))){
                unset($data[$k]);
            }
        }
        $data = array_values($data);

        return $this->getTree($data, 0);
    }

    private function getTree($data, $pid)
    {
        $tree = [];
        foreach($data as $k => $v)
        {
            if($v['pid'] == $pid)
            {        //父亲找到儿子
                $v['children'] = $this->getTree($data, $v['id']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    public static function permission()
    {
        $data = Cache::get('PermissionData');
        if(!$data){
            $data = self::init();
            Cache::forever('PermissionData', $data);
        }
        try{
            $uid = Session::get('USERINFO')->userId;
            $role_id = UserCompany::where('uid', $uid)->value('role_id');
            if(isset($data[$role_id])){
                $res = $data[$role_id];
                foreach($res as $k => $v){
                    switch($v){
                        // 项目立项
                        case 'admin.project.setup':
                            $tmp = ModuleManager::where(['module' => 1, 'company_id' => Session::get('USERINFO')->companyId])->value('founder');
                            if(!in_array(Session::get('USERINFO')->userId, explode(',', $tmp))){
                                unset($res[$k]);
                            }
                            break;
                        // 项目审批
                        case 'admin.project.approve':
                            $tmp1 = ModuleManager::where(['module' => 1, 'company_id' => Session::get('USERINFO')->companyId])->value('verifyer');
                            $tmp2 = ModuleManager::where(['module' => 1, 'company_id' => Session::get('USERINFO')->companyId])->value('ratifyer');
                            if($tmp1 != 0 && $tmp2 != 0 && !in_array(Session::get('USERINFO')->userId, array_merge(explode(',', $tmp1),explode(',', $tmp2)))){
                                unset($res[$k]);
                            }
                            break;
                    }
                }
            }else{
                $res = [];
            }
            return $res;
        }catch(\Exception $e){
            return [];
        }

    }

    public static function init()
    {
        Cache::forget('PermissionData');
        $permissionArr = self::where('route_name','<>', '')->pluck('route_name', 'id')->toArray();
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