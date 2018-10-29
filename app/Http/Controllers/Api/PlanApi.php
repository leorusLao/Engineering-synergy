<?php

namespace App\Http\Controllers\Api;
use App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Models\Plan;
use App\Models\PlanTask;
use App\Models\PlanType;


Class PlanApi extends App\Http\Controllers\Controller
{ 
    
    /**
    * 获取计划控制列表
    * @param
    * @return
    */

    public function listPlans(Request $request,Response $response)
    {
        //判断参数个数是否足够
        $ary_params = array('plan_status','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }     

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //枚举
        $plan_status = $request->get('plan_status');
        $ary_enu = array(0,1,2,3,5,6);
        if(!in_array($plan_status,$ary_enu)){ return CheckApi::return_46011();}

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $ary_search = array('plan_code'=>$request->get('plan_code'),
                            'plan_name'=>$request->get('plan_name'),
                            'proj_code'=>$request->get('proj_code'),
                            'proj_name'=>$request->get('proj_name')
                        );
        $result = plan::listPlansApi($company_id,$plan_status,$ary_search,$page_size,$curr_page);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }
    
    }



    /** 
    * 新增立项零件的计划头
    * @param 
    * @return 
    */ 
    public function createPartPlan(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','ary_member','detail_id','end_date','leader',
                            'plan_name','plan_type','start_date','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','detail_id','plan_type']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、零件是否属于公司
        $return = CheckApi::check_componypart($request->get('company_id'),$request->get('detail_id'));
        if($return !== true){ return $return;}

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('detail_id'));
        if($return !== true){ return $return; }
        
        //4、项目是否正常
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        $ary_member = $request->get('ary_member');
        $str_member_list = '';
        foreach ($ary_member as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if($key2=='id'){ 
                    $str_member_list = $str_member_list.$value2.',';
                }
            }
        }
        $str_member_list = substr($str_member_list,0,-1);
        $ary = array(
            'company_id'=>$request->get('company_id'),//公司ID    
            'description'=>$request->get('description'),//计划描述    
            'member'=>$str_member_list,//计划成员  
            'project_detail_id'=>$request->get('detail_id'),//零件ID 
            'end_date'=>$request->get('end_date'),//结束日期  
            'leader'=>$request->get('leader'),//责任人  
            'plan_name'=>$request->get('plan_name'),//计划名称 
            'plan_type'=>$request->get('plan_type'),//计划类型 
            'project_id'=>$request->get('proj_id'),//项目ID
            'start_date'=>$request->get('start_date'),//开始日期  
        );
        $result = Plan::createPartPlanApi($ary);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }




    /** 
    * 修改立项零件的计划头
    * @param 
    * @return 
    */ 
    public function updatePartPlan(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','ary_member_list','end_date','leader',
                            'plan_name','plan_type','start_date','plan_id','ary_leader_list');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','plan_id','plan_type']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //3、计划是否属于公司
        $return = CheckApi::check_userplan($request->get('company_id'),$request->get('plan_id'));
        if($return !== true){ return $return; }

        //人员
        $ary_member = $request->get('ary_member_list');
        $str_member_list = '';
        foreach ($ary_member as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if($key2=='id'){ 
                    $str_member_list = $str_member_list.$value2.',';
                }
            }
        }
        $str_member_list = substr($str_member_list,0,-1);

        //责任人
        $leader = $request->get('ary_leader_list');
        $str_leader_list = '';
        foreach ($leader as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if($key2=='id'){ 
                    $str_leader_list = $str_leader_list.$value2.',';
                }
            }
        }
        $str_leader_list = substr($str_leader_list,0,-1);

        $ary = array(
            'company_id'=>$request->get('company_id'),//公司ID    
            'description'=>$request->get('description'),//计划描述    
            'member'=>$str_member_list,//计划成员  
            'end_date'=>$request->get('end_date'),//结束日期  
            'leader'=>$str_leader_list,//责任人  
            'plan_name'=>$request->get('plan_name'),//计划名称 
            'plan_type'=>$request->get('plan_type'),//计划类型 
            'start_date'=>$request->get('start_date'),//开始日期  
        );
        $where = ['plan_id'=>$request->get('plan_id'),'status'=>0];
        $result = Plan::updatePartPlanApi($ary,$where);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }



    /** 
    * 获取项目已有计划信息列表
    * @param 
    * @return 
    */ 
    public function listPartPlan(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');

        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、项目是否正常
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、项目是否属于公司
        $return = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        $proj_id = $request->get('proj_id');       
        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $result = Plan::listPartPlanApi($proj_id,$page_size,$curr_page);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }



    /** 
    * 获取项目已有计划头信息
    * @param 
    * @return 
    */ 
    public function getPartPlan(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','plan_id');

        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','plan_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //3、计划是否属于公司
        $return = CheckApi::check_userplan($request->get('company_id'),$request->get('plan_id'));
        if($return !== true){ return $return; }

        $plan_id = $request->get('plan_id');   

        $result = Plan::getPartPlanApi($plan_id);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }



    /** 
    * 删除零件计划信息
    * @param 
    * @return 
    */ 
    public function deletePartPlan(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','plan_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','plan_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //3、计划是否属于公司
        $return = CheckApi::check_userplan($request->get('company_id'),$request->get('plan_id'));
        if($return !== true){ return $return; }

        $plan_id = $request->get('plan_id');
        $result = Plan::deletePartPlanApi($plan_id);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }



    /**
    * 改变计划控制状态
    * @param
    * @return
    */

    public function updateStatus(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('plan_status','plan_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','plan_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //计划检测
        $plan_id = $request->get('plan_id');
        $return = CheckApi::check_userplan($company_id,$plan_id);
        if($return !== true){ return $return; }

        //枚举
        $plan_status = $request->get('plan_status');
        $ary_enu = array(1,2,3,4,5);
        if(!in_array($plan_status,$ary_enu)){ return CheckApi::return_46011();}

        $result = plan::updateStatusApi($plan_id,$plan_status);
        
        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
           

    }
    
    /**
    * 获取单个计划基本信息 
    * @param
    * @return
    */

    public function getBaseInfo(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('plan_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','plan_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //计划检测
        $plan_id = $request->get('plan_id');
        $return = CheckApi::check_userplan($company_id,$plan_id);
        if($return !== true){ return $return; }
        
        $result = plan::getBaseInfoApi($plan_id);

        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }

    
    /**
    * 获取单个计划节点信息 
    * @param
    * @return
    */

    public function getNodeInfo(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('plan_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','plan_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //计划检测
        $plan_id = $request->get('plan_id');
        $return = CheckApi::check_userplan($company_id,$plan_id);
        if($return !== true){ return $return; }
        
        $result = plan::getNodeInfoApi($plan_id);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }


    
    /**
    * 获取单个计划节点详情 
    * @param
    * @return
    */

    public function getNodeCont(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('node_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','node_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //节点检测
        $node_id = $request->get('node_id');
        $return = CheckApi::check_usernode($company_id,$node_id);
        if($return !== true){ return $return; }
        
        $result = plantask::getNodoContApi($node_id);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }

    
    /**
    * 提交计划审批 
    * @param
    * @return
    */

    public function updateApproval(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('plan_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //计划检测
        $plan_id = $request->get('plan_id');
        $ary_planid = explode(',',$plan_id);
        foreach ($ary_planid as $key => $value) {
            $return = CheckApi::check_userplan($company_id,$plan_id);
            if($return !== true){ return $return; }
        }
        
        $result = plan::updateApprovalApi($ary_planid);
        
        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
           

    }

    
    /**
    * 提交计划审批结果
    * @param
    * @return
    */

    public function updateApprovalResult(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('plan_id','approval_status','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //计划检测
        $plan_id = $request->get('plan_id'); 
        $ary_planid = explode(',',$plan_id);
        foreach ($ary_planid as $key => $value) {
            $return = CheckApi::check_userplan($company_id,$value);
            if($return !== true){ return $return; }
        }

        $approval_comment = $request->get('approval_comment');
        $approval_status = $request->get('approval_status');
        $result = plan::updateApprovalResultApi($uid,$ary_planid,$approval_status,$approval_comment);
        
        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
           

    }


    /**
    * 获取节点列表
    * @param
    * @return
    */

    public function listNodes(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('plan_status','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //枚举
        $plan_status = $request->get('plan_status');
        $ary_enu = array(0,1,2,3,5,6);
        if(!in_array($plan_status,$ary_enu)){ return CheckApi::return_46011();}

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        $ary_search = array(
            'plan_code' => $request->get('plan_code'),
            'plan_name' => $request->get('plan_name'),
            'node_code' => $request->get('node_code'),
            'node_name' => $request->get('node_name')
        );

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        $result = plantask::listNodesApi($plan_status,$ary_search,$page_size,$curr_page);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        } 

    }


    
    /**
    * 获取单个节点的审批信息
    * @param
    * @return
    */

    public function getApprovalInfo(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('node_id','token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','node_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //节点检测
        $node_id = $request->get('node_id');
        $return = CheckApi::check_usernode($company_id,$node_id);
        if($return !== true){ return $return; }
        
        $result = plantask::getApprovalInfoApi($node_id);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }



    /**
    * 更改节点进展
    * @param
    * @return
    */

    public function updateProgress(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('node_id','token','uid','company_id','progress');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','node_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }

        //节点检测
        $node_id = $request->get('node_id');
        $return = CheckApi::check_usernode($company_id,$node_id);
        if($return !== true){ return $return; }
        
        $progress = $request->get('progress');
        $result = plantask::updateProgressApi($node_id,$progress);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }

    }


    /**
    * 获取计划类型列表
    * @param
    * @return
    */

    public function listPlanType(Request $request,Response $response)
    { 
        
        //判断参数个数是否足够
        $ary_params = array('token','uid','company_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户检测  
        $uid = $request->get('uid'); 
        $token = $request->get('token'); 
        $company_id = $request->get('company_id'); 
        $return = CheckApi::check_userinfo($uid,$token,$company_id); 
        if($return !== true){ return $return; }
        
        $result = PlanType::getPlanTypes($company_id);
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }



}


