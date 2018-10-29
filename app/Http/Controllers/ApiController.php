<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use Illuminate\Routing\Router;

class ApiController extends BaseController
{   
    //不检测用户
    private $ary_except_user = ['api/workboard/list-plan-approval2'];

    //不检测权限
    private $ary_except_role = [
                                'api/workboard/list-planconfirm', //[M001]计划接收确认列表
                                'api/workboard/update-planconfirm', //[M013]更新计划确认
                                'api/workboard/list-nodeprogress', //[M005]计划节点进度录入列表
                                'api/workboard/list-plan',   //[M002]计划列表
                                'api/workboard/list-depart-plan',    //[M004]部门计划列表
                                'api/workboard/list-openissue',   //[M009]OPENISSUE列表
                                'api/workboard/list-openissue-progress'   //[M008]OPENISSUE进度录入列表
                               ];

    public function check_authority(Request $request,Response $response)
    { 
        $url = $request->path();
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 

        //用户检测  
        if(!in_array($url,$this->ary_except_user)){
            $return = CheckApi::check_userinfo($uid,$token,$company_id);
            if($return !== true){ return $return; }
        }

        //角色权限
        if(!in_array($url,$this->ary_except_role)){
            $return = CheckApi::check_role($uid,$company_id,$url);
            if($return !== true){ return $return; }
        }

        return true;

    }

}
