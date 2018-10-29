<?php

namespace App\Http\Controllers\Api;
use App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Http\Controllers\ControllerApi;
use App\Models\Project;
use App\Models\Plan;
use App\Models\OpenIssueDetail;
use App\Models\PlanTask;

Class WorkboardApi extends App\Http\Controllers\ApiController
{ 

    /** 
    * 计划确认列表
    * @param 
    * @return 
    */    
    public function listPlanConfirm(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }
    
        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = PlanTask::listPlanConfirm($request->get('uid'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

        
    }

    /** 
    * 更新计划确认
    * @param 
    * @return 
    */    
    public function updatePlanConfirm(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','node_id','status');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //枚举
        $status = $request->get('status');
        $ary_enu = array(1,2);
        if(!in_array($status,$ary_enu)){ return CheckApi::return_46011();}

        //节点检测
        $node_id = $request->get('node_id');
        $return = CheckApi::check_usernode($request->get('company_id'),$node_id);
        if($return !== true){ return $return; }

        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }
    
        $result = PlanTask::updatePlanConfirm($request->get('uid'),$node_id,$status);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

        
    }



    /** 
    * 项目审批列表
    * @param 
    * @return 
    */    
    public function listProjectApproval(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }
    
       //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = Project::listPendingApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }


    /** 
    * 计划节点进度列表
    * @param 
    * @return 
    */    
    public function listNodeProgress(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }
    
       //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = PlanTask::listNodeProgressApi($request->get('uid'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }



    /** 
    * 计划列表
    * @param 
    * @return 
    */    
    public function listBoardPlan(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }
    
        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $result = Plan::listBoardPlanApi($uid,$company_id,$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }




    /** 
    * 部门计划列表
    * @param 
    * @return 
    */    
    public function listDepartmentPlan(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','dep_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }

        //部门名是否在已存在公司里面
        $company_id = $request->get('company_id');
        $dep_id = $request->get('dep_id');
        $return = CheckApi::check_depnameincomp($company_id,$dep_id);
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $result = Plan::listDepartmentPlanApi($uid,$company_id,$dep_id,$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }



    /** 
    * OPENISSUE列表
    * @param 
    * @return 
    */    
    public function listBoardOpenissue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','dep_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }

        //部门名是否在已存在公司里面
        $company_id = $request->get('company_id');
        $dep_id = $request->get('dep_id');
        $return = CheckApi::check_depnameincomp($company_id,$dep_id);
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $result = OpenIssueDetail::lisBoardOpenissueApi($uid,$company_id,$dep_id,$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }

    /** 
    * OPENISSUE进度录入列表
    * @param 
    * @return 
    */    
    public function listOpenissueProgress(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $result = OpenIssueDetail::listOpenissueProgressApi($uid,$company_id,$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }



    /** 
    * 计划审批列表
    * @param 
    * @return 
    */    
    public function listPlanApproval(Request $request,Response $response)
    {
        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $plan_status = 2; //待审批
        $ary_search = [];
        $result = plan::listPlansApi($request->get('company_id'),2,$ary_search,$page_size,$curr_page);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }

    /** 
    * OPENISSUE审批列表
    * @param 
    * @return 
    */    
    public function listOpenissueApproval(Request $request,Response $response)
    {
        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }
        
        //用户角色检测
        $return = parent::check_authority($request,$response);
        if($return !== true){ return $return; }

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = OpenIssueDetail::listPendingApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result['list_result'][0])){
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }



}


