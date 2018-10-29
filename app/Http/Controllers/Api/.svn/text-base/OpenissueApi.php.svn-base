<?php

namespace App\Http\Controllers\Api;
use App;
use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Models\Project;
use App\Models\Company;
use App\Models\ProjectDetail;
use App\Models\ProjectRelation;
use App\Models\WorkCalendar;
use App\Models\ProjectType;
use App\Models\Department;
use App\Http\Controllers\InitController;
use App\Models\Customer;
use App\Models\IssueSource;
use App\Models\Plan;
use App\Models\OpenIssueDetail;
use App\Models\IssueClass;


class OpenissueApi extends App\Http\Controllers\Controller
{

    /** 
    * 获取OPENISSUE来源列表
    * @param 
    * @return 
    */    
    public function listSource(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        $result = IssueSource::listIssueSourceApi($request->get('company_id'));

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result->count())){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

	}


    /** 
    * 获取OPENISSUE类型列表
    * @param 
    * @return 
    */    
    public function listClass(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        $result = IssueClass::listIssueClassApi($request->get('company_id'));

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result->count())){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }


    /** 
    * 获取OPENISSUE计划列表
    * @param 
    * @return 
    */    
    public function listPlanIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = Plan::listPlanIssueApi($request->get('company_id'),$page_size,$curr_page);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result['result']->count())){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

	}


    /** 
    * 获取OPENISSUE项目列表
    * @param 
    * @return 
    */    
    public function listProjectIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = Project::listProjectApi($request->get('company_id'),'all',$page_size,$curr_page);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result['result']->count())){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }


    /** 
    * 新增OPENISSUE
    * @param 
    * @return 
    */    
    public function createOpenIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','description','issue_class','issuer','leader',
                            'plan_complete_date','source_id','title','issue_id','issue_date','source_code');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','source_id','issue_id']);
        if($return !== true){ return $return; }

        //source_code为project  plan  other
        $status = $request->get('source_code');
        $ary_enu = array('Project','Plan','Other');
        if(!in_array($status,$ary_enu)){ return CheckApi::return_46011();}

        //issuer是否与公司对应
        $return = CheckApi::check_issuerincompany($request->get('issuer'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //leader责任人是否与公司对应
        $return = CheckApi::check_leaderincompany($request->get('leader'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

/*        //2、source_id是否属于这个公司
        $return = CheckApi::check_issuesource_company($request->get('source_id'),$request->get('company_id'));
        if($return !== true){ return $return;}   */   

        //3、issue_id是否合法
        $return = CheckApi::check_issue_id($request->get('issue_id'),$request->get('source_code'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //4、issue_class是否属于这个公司
        $return = CheckApi::check_issueclass_company($request->get('issue_class'),$request->get('company_id'));
        if($return !== true){ return $return;}
        $ary = array(
            'description'=>$request->get('description'),//描述
            'issue_class'=>$request->get('issue_class'),//问题类型
            'issuer'=>$request->get('issuer'),//提出人
            'leader'=>$request->get('leader'),//责任人
            'plan_complete_date'=>$request->get('plan_complete_date'),//计划完成时间
            'source_id'=>$request->get('source_id'),//来源ID
            'issue_date'=>$request->get('issue_date'),//提出时间
            'issue_id'=>$request->get('issue_id'),//issue_id
            'title'=>$request->get('title'),//标题
            'company_id'=>$request->get('company_id'),//公司
        );
        if(!empty($request->get('comment'))){
            $ary['comment'] = $request->get('comment');//备注
        }
        if(!empty($request->get('solution'))){
            $ary['solution'] = $request->get('solution');//解决方案
        }

        $result = OpenIssueDetail::createOpenIssueApi($ary);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
	}


    /** 
    * 修改OPENISSUE
    * @param 
    * @return 
    */    
    public function updateOpenIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','description','issue_class','issuer','leader','issue_detail_id',
                            'plan_complete_date','source_id','title','issue_id','issue_date','source_code');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //source_id必须为数值
        if(!is_numeric($request->get('source_id'))){ return CheckApi::return_46011();}

        //issue_id必须为数值
        if(!is_numeric($request->get('issue_id'))){ return CheckApi::return_46011();}

        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}

        //source_code为project  plan  other
        if($request->get('source_code') != 'Project' && $request->get('source_code') != 'Plan' 
            && $request->get('source_code') != 'Other'){ 
            return CheckApi::return_46011();
        }

        //issuer是否与公司对应
        $return = CheckApi::check_issuerincompany($request->get('issuer'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //leader责任人是否与公司对应
        $return = CheckApi::check_leaderincompany($request->get('leader'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

/*        //2、source_id是否属于这个公司
        $return = CheckApi::check_issuesource_company($request->get('source_id'),$request->get('company_id'));
        if($return !== true){ return $return;}  */    

        //3、issue_id是否合法
        $return = CheckApi::check_issue_id($request->get('issue_id'),$request->get('source_code'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //4、issue_class是否属于这个公司
        $return = CheckApi::check_issueclass_company($request->get('issue_class'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $condition = array(
            'company_id'=>$request->get('company_id'),//公司
            'id'=>$request->get('issue_detail_id'),//id
            'issue_id'=>$request->get('issue_id'),//issue_id
            'source_id'=>$request->get('source_id'),//来源ID
            'status'=>0//状态
        );

        $ary = array(
            'description'=>$request->get('description'),//描述
            'issue_class'=>$request->get('issue_class'),//问题类型
            'issuer'=>$request->get('issuer'),//提出人
            'leader'=>$request->get('leader'),//责任人
            'plan_complete_date'=>$request->get('plan_complete_date'),//计划完成时间
            'issue_date'=>$request->get('issue_date'),//提出时间
            'title'=>$request->get('title'),//标题
        );
        if(!empty($request->get('comment'))){
            $ary['comment'] = $request->get('comment');//备注
        }
        if(!empty($request->get('solution'))){
            $ary['solution'] = $request->get('solution');//解决方案
        }

        $result = OpenIssueDetail::updateOpenIssueApi($condition,$ary);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

	}


    /** 
    * 获取待审批OPENISSUE列表
    * @param 
    * @return 
    */    
    public function listPending(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //用户是否有审批权限        
        $return = CheckApi::check_approval($request->get('company_id'),$request->get('uid'),'openissue');
        if($return !== true){ return $return;}

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


    /** 
    * 获取已审批OPENISSUE列表
    * @param 
    * @return 
    */    
    public function listApproved(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        $result = OpenIssueDetail::listApprovedApi($request->get('uid'),$request->get('company_id'),$page_size,$curr_page);

        if(!empty($result['list_result'][0])){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }


	}


    /** 
    * 获取OPENISSUE详情
    * @param 
    * @return 
    */    
    public function getOpenIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','issue_detail_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、公司与openissue是否对应
        $return = CheckApi::check_issuedetail($request->get('company_id'),$request->get('issue_detail_id'));
        if($return !== true){ return $return;}
    
        $result = OpenIssueDetail::getOpenissueApi($request->get('issue_detail_id'));

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

	}


    /** 
    * 提交OPENISSUE审批
    * @param 
    * @return 
    */    
    public function updateApproval(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','issue_detail_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、公司与openissue是否对应
        $return = CheckApi::check_issuedetail($request->get('company_id'),$request->get('issue_detail_id'));
        if($return !== true){ return $return;}
    
        $result = OpenIssueDetail::updateApprovalApi($request->get('issue_detail_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


	}


    /** 
    * 提交OPENISSUE审批结果
    * @param 
    * @return 
    */    
    public function updateApprovalResult(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','issue_detail_id','is_approved');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}
            
        //is_approved为1或2
        if($request->get('is_approved') != 1 && $request->get('is_approved') != 2){ 
            return CheckApi::return_46011();
        }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、公司与openissue是否对应
        $return = CheckApi::check_issuedetail($request->get('company_id'),$request->get('issue_detail_id'));
        if($return !== true){ return $return;}
        
        $time = date('Y-m-d',time());
        $result = OpenIssueDetail::updateApprovalResultApi($request->get('uid'),$request->get('issue_detail_id'),
                    $request->get('is_approved'),$request->get('approval_comment'),$time);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


	}


    /** 
    * 获取(某项目、计划下)OPENISSUE列表(可能不需要)
    * @param 
    * @return 
    */    
    public function listOpenIssue(Request $request,Response $response)
    {

	}


    /** 
    * 获取公司下OPENISSUE列表
    * @param 
    * @return 
    */    
    public function listOpenIssueAll(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','approval_status');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //approval_status必须为数值
        if($request->get('approval_status') != 0 && $request->get('approval_status') != 1 
            && $request->get('approval_status') != 2 && $request->get('approval_status') != 3 
            && $request->get('approval_status') != 4){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
    
        if($request->get('approval_status')==0){ 
            //0：未提交审批 
            $result = OpenIssueDetail::list_issue_unsubmitapi($request->get('company_id'),$page_size,$curr_page);
        }else if($request->get('approval_status')==1){ 
            //1：待审批
            $result = OpenIssueDetail::list_issue_submitapi($request->get('company_id'),$page_size,$curr_page);
        }else if($request->get('approval_status')==2){ 
            //2：审批通过
            $result = OpenIssueDetail::list_issue_passapi($request->get('company_id'),$page_size,$curr_page);
        }else if($request->get('approval_status')==3){ 
            //3：审批拒绝
            $result = OpenIssueDetail::list_issue_refuseapi($request->get('company_id'),$page_size,$curr_page);
        }else if($request->get('approval_status')==4){
            //4：全部
            $result = OpenIssueDetail::listIssueApi($request->get('company_id'),$page_size,$curr_page);
        }

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result['list_result'])){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

	}


    /** 
    * 删除OPENISSUE
    * @param 
    * @return 
    */    
    public function deleteOpenIssue(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','issue_detail_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $condition = array(
            'company_id'=>$request->get('company_id'),//公司
            'id'=>$request->get('issue_detail_id'),//id
            'status'=>0//状态
        );

        $result = OpenIssueDetail::deleteOpenissueApi($condition);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
	}


    /** 
    * 更改OPENISSUE进度
    * @param 
    * @return 
    */    
    public function updateCompleteStatus(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','issue_detail_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //issue_detail_id必须为数值
        if(!is_numeric($request->get('issue_detail_id'))){ return CheckApi::return_46011();}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $condition = array(
            'company_id'=>$request->get('company_id'),//公司
            'id'=>$request->get('issue_detail_id'),//id
            'status'=>0,//状态
            'is_completed'=>0//进度
        );

        $result = OpenIssueDetail::updateCompleteApi($condition);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


	}




}
