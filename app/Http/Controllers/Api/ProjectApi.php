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

class ProjectApi extends App\Http\Controllers\Controller
{

    /** 
    * 获取未完成立项（零件空缺）列表
    * @param 
    * @return 
    */    
    public function listVacancy(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
    
        $result = Project::listVacancyApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }



    /** 
    * 确认立项已经完成
    * @param 
    * @return 
    */    
    public function updateProjectCompleted(Request $request,Response $response)
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
        
        $result = Project::updateProjectCompletedApi($request->get('proj_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }



    /** 
    * 获取项目总列表
    * @param 
    * @return 
    */    
    public function listProject(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','approval_status');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
        
        $result = Project::listProjectApi($request->get('company_id'),$request->get('approval_status'),
                                            $page_size,$curr_page);
        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }

    /** 
    * 获取项目待审批列表
    * @param 
    * @return 
    */     
    public function listPending(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
       //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

       //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
    
        $result = Project::listPendingApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


    }

    /** 
    * 获取项目已审批列表
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
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
    
        $result = Project::listApprovedApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


    }


    /** 
    * 获取项目分发列表
    * @param 
    * @return 
    */     
    public function listSend(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
    
        $result = Project::listSendApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    /** 
    * 获取项目关联列表
    * @param 
    * @return 
    */     
    public function listRelation(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
    
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

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
    
        $data = Project::listRelationApi($request->get('company_id'),$page_size,$curr_page);

        if(!empty($data)){ 
            foreach($data['result'] as $key=>$value)
            {
                $aa = Company::infoCompany($value->assigned_distribute);
                $part_id = $value->part_id;
                $proj_id = $value->proj_id;
                $data['result'][$key]->distribute_name = Company::infoCompany($value->assigned_distribute)->company_name;
                $data['result'][$key]->part_name = ProjectDetail::infoDetail(array('id'=>$part_id),'part_name')->part_name;
                $data['result'][$key]->proj_name = Project::infoProject(array('proj_id'=>$proj_id),'proj_name')->proj_name;
            }
            return CheckApi::return_success($data);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    /** 
    * 删除项目
    * @param 
    * @return 
    */    
    public function deleteProject(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        $return = Project::deleteProjectApi($request->get('proj_id'),$request->get('company_id'));

        if(!empty($return)){ 
            return CheckApi::return_success($return);
        }else{ 
            return CheckApi::return_10000();    
        }


    }

    /** 
    * 获取立项基础信息
    * @param 
    * @return 
    */ 
    public function getBaseInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        $result = Project::getBaseinfoApi($request->get('proj_id'),$request->get('company_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }



    }

    /** 
    * 获取分发关联项目基础信息
    * @param 
    * @return 
    */ 
    public function getSendInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','part_id','relation_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //part_id必须为数值
        if(!is_numeric($request->get('part_id'))){ return CheckApi::return_46011();}

        //relation_id必须为数值
        if(!is_numeric($request->get('relation_id'))){ return CheckApi::return_46011();}
    
        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('part_id'));
        if($return !== true){ return $return; }
        
        $result = ProjectRelation::getSendinfoApi($request->get('relation_id'),$request->get('part_id'));

        if(!empty($result)){  
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    // /** 
    // * 新增分发关联项目基础信息
    // * @param 
    // * @return 
    // */ 
    // public function create_sendprojectinfo(Request $request,Response $response)
    // { 

    // }

    // /** 
    // * 修改分发关联项目基础信息
    // * @param 
    // * @return 
    // */ 
    // public function update_sendprojectinfo(Request $request,Response $response)
    // { 

    // }


    /** 
    * 新增分发关联状态
    * @param 
    * @return 
    */ 
    public function createSendStatus(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','part_id','relation');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //part_id必须为数值
        if(!is_numeric($request->get('part_id'))){ return CheckApi::return_46011();}

        //relation必须为数值
        if($request->get('relation') != 1 && $request->get('relation') != 2){ 
            return CheckApi::return_46011();
        }
        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('part_id'));
        if($return !== true){ return $return; }

        $result = ProjectRelation::createSendStatusApi($request->get('part_id'),$request->get('relation'),$request->get('remark'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }



    }



    /** 
    * 修改立项基础信息
    * @param 
    * @return 
    */ 
    public function updateBaseInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('customer_name','uid','token','end_date','proj_id',
                        'proj_name','proj_type','start_date','company_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        $proj_id = $request->get('proj_id');//项目ID
        $company_id = $request->get('company_id');//公司ID

        //数值检测
        $return = CheckApi::check_numeric($request,['uid','proj_id','company_id']);
        if($return !== true){ return $return; }

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //proj_type范围是1 2 3 
        if($request->get('proj_type') != 1 && $request->get('proj_type') != 2 && $request->get('proj_type') != 3)
        { return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //传入的人员是否在公司里面
        $ary_member_list = $request->get('ary_member_list');
        $str_member_list = '';    

        foreach ($ary_member_list as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if($key2=='id'){ 
                    $str_member_list = $str_member_list.$value2.',';
                }
            }
        }
        $str_member_list = substr($str_member_list,0,-1);

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        $ary = array(
            'customer_name' => $request->get('customer_name'),//客户名称
            'end_date' => $request->get('end_date'),//结束日期
            'proj_name' => $request->get('proj_name'),//项目名称
            'proj_type' => $request->get('proj_type'),//项目类型
            'start_date' => $request->get('start_date'),//接受日期
            'batch_production' => $request->get('batch_production'),//批量放产日期
            'calendar_id' => $request->get('calendar_id'),//项目日历ID
            'description' => $request->get('description'),//项目描述
            //'member_list' => $request->get('memberid_list'),//项目成员ID
            'member_list' => $str_member_list,//项目成员ID

            'mold_sample' => $request->get('mold_sample'),//出模样件日期
            'process_trail' => $request->get('process_trail'),//工艺验证日期
            'proj_manager' => $request->get('proj_manager'),//项目经理名称
            'proj_manager_uid' => $request->get('proj_manager_uid'),//项目经理ID
            'property' => $request->get('property'),//项目性质
            'trail_production' => $request->get('trail_production')//试产验证日期
        );
        $result = Project::updateBaseinfoApi($proj_id,$company_id,$ary);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

        
    }

    /** 
    * 项目提交审批
    * @param 
    * @return 
    */ 
    public function updateApproval(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }
        
        $result = Project::updateApprovalApi($request->get('proj_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


    }

    /** 
    * 提交项目审批结果
    * @param 
    * @return 
    */ 
    public function updateApprovalResult(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','approval_status');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id']);
        if($return !== true){ return $return; }

        //proj_status范围是3  4 
        if($request->get('approval_status') !=3  && $request->get('approval_status') != 4)
        { return CheckApi::return_46011();}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }
        
        $result = Project::updateApprovalResultApi(
            $request->get('proj_id'),
            $request->get('approval_status'),
            $request->get('approval_comment'),
            $request->get('uid'),
            date('Y-m-d',$_SERVER['REQUEST_TIME'])
        );

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }

    /** 
    * 新增立项基础信息
    * @param 
    * @return 
    */ 
    public function createBaseInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('customer_id','company_id','uid','token','end_date','proj_name','proj_type','start_date');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}

        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //proj_type范围是1 2 3
        $status = $request->get('proj_type');
        $ary_enu = array(1,2,3);
        if(!in_array($status,$ary_enu)){ return CheckApi::return_46011();}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、公司与客户是否对应
        $return = CheckApi::check_custom_company($request->get('company_id'),$request->get('customer_id'));
        if($return !== true){ return $return;}
    
        //客户公司名称
        $ary_custname = Customer::getCustomerName($request->get('customer_id'));

        $company_id = $request->get('company_id');
        //传入的人员是否在公司里面
        $ary_member_list = $request->get('ary_member_list');
        //print_r($ary_member_list);
        $str_member_list = '';
        foreach ($ary_member_list as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                if($key2=='id'){ 
                    $str_member_list = $str_member_list.$value2.',';
                }
            }
        }
        $str_member_list = substr($str_member_list,0,-1);
        
        //新建proj_code和proj_unicode
        $proj = InitController::getAndInitProjectCodingApi($request->get('company_id'));
        $ary = array(
            'company_id' => $company_id,//公司ID
            'customer_name' => $ary_custname->customer_name,//客户名称
            'customer_id' => $request->get('customer_id'),//客户公司ID
            'end_date' => $request->get('end_date'),//结束日期
            'proj_id' => $request->get('proj_id'),//项目ID
            'proj_name' => $request->get('proj_name'),//项目名称
            'proj_type' => $request->get('proj_type'),//项目类型
            'start_date' => $request->get('start_date'),//接受日期
            'batch_production' => $request->get('batch_production'),//批量放产日期
            'calendar_id' => $request->get('calendar_id'),//项目日历ID
            'description' => $request->get('description'),//项目描述
            //'member_list' => $request->get('memberid_list'),//项目成员ID
            'member_list' => $str_member_list,//项目成员ID

            'mold_sample' => $request->get('mold_sample'),//出模样件日期
            'process_trail' => $request->get('process_trail'),//工艺验证日期
            'proj_manager' => $request->get('proj_manager'),//项目经理名称
            'proj_manager_uid' => $request->get('proj_manager_uid'),//项目经理ID
            'property' => $request->get('property'),//项目性质
            'trail_production' => $request->get('trail_production')//试产验证日期
        );
        
        $newary = $proj + $ary;
        $result = Project::createBaseInfo($newary);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }
     

    }


    // /** 
    // * 获取当前立项项目编号
    // * @param 
    // * @return 
    // */ 
    // public function get_projectnumber(Request $request,Response $response)
    // { 

    // }

    // /** 
    // * 获取当前立项零件编号(同时附带项目编号)
    // * @param 
    // * @return 
    // */ 
    // public function get_partnumber(Request $request,Response $response)
    // { 

    // }

    /** 
    * 获取项目下零件信息列表
    * @param 
    * @return 
    */ 
    public function listPartInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}
    
        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }
        
        $result = ProjectDetail::listPartinfoApi($request->get('proj_id'));

        if(!$result->isEmpty()){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    /** 
    * 获取零件信息详情
    * @param 
    * @return 
    */ 
    public function getPartInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','part_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id','part_id']);
        if($return !== true){ return $return; }

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //1-2、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('part_id'));
        if($return !== true){ return $return; }


        $result = ProjectDetail::getPartinfoApi($request->get('part_id'));

        if(!empty($result)){     
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }



    }

    /** 
    * 删除单个零件
    * @param 
    * @return 
    */ 
    public function deletePartInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','part_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id','part_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //1-2、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('part_id'));
        if($return !== true){ return $return; }

        $result = ProjectDetail::deletePartinfoApi($request->get('part_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }

    /** 
    * 修改立项零件信息
    * @param 
    * @return 
    */ 
    public function updatePartInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','part_id','end_time','start_time','part_name');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id','part_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //1-2、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        //3、零件是否属于项目
        $return = CheckApi::check_projectpart($request->get('proj_id'),$request->get('part_id'));
        if($return !== true){ return $return; }

        if(!empty($request->get('gauge'))){ 
            if(!is_numeric($request->get('gauge'))){ return CheckApi::return_46011();}
            $ary['gauge'] = $request->get('gauge');//检具
        }
        if(!empty($request->get('jig'))){ 
            if(!is_numeric($request->get('jig'))){ return CheckApi::return_46011();}
            $ary['jig'] = $request->get('jig');//夹具
        }
        if(!empty($request->get('mat_size'))){ 
            $ary['mat_size'] = $request->get('mat_size');//材料规格
        }
        if(!empty($request->get('material'))){ 
            $ary['material'] = $request->get('material');//零件材料
        }
        if(!empty($request->get('mold'))){ 
            if(!is_numeric($request->get('mold'))){ return CheckApi::return_46011();}
            $ary['mold'] = $request->get('mold');//模具
        }
        if(!empty($request->get('note'))){ 
            $ary['note'] = $request->get('note');//零件备注
        }
        if(!empty($request->get('part_from'))){ 
            $ary['part_from'] = $request->get('part_from');//来源
        }
        if(!empty($request->get('part_size'))){ 
            $ary['part_size'] = $request->get('part_size');//零件尺寸
        }
        if(!empty($request->get('part_type'))){ 
            $ary['part_type'] = $request->get('part_type');//零件类型
        }
        if(!empty($request->get('processing'))){ 
            $ary['processing'] = $request->get('processing');//加工工艺
        }
        if(!empty($request->get('quantity'))){ 
            if(!is_numeric($request->get('quantity'))){ return CheckApi::return_46011();}
            $ary['quantity'] = $request->get('quantity');//数量
        }
        if(!empty($request->get('shrink'))){ 
            $ary['shrink'] = $request->get('shrink');//缩水率
        }
        if(!empty($request->get('surface'))){ 
            $ary['surface'] = $request->get('surface');//表面处理
        }
        if(!empty($request->get('weight'))){ 
            $ary['weight'] = $request->get('weight');//零件重量
        }
        $ary['end_time'] = $request->get('end_time'); //结束时间
        $ary['part_name'] = $request->get('part_name'); //零件名
        $ary['start_time'] = $request->get('start_time'); //开始时间
        
        $result = ProjectDetail::updatePartinfoApi($request->get('part_id'),$ary);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }

    }

    /** 
    * 新增立项零件信息
    * @param 
    * @return 
    */ 
    public function createPartInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id','end_time','start_time','part_name');
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

        //1-2、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        if(!empty($request->get('gauge'))){ 
            if(!is_numeric($request->get('gauge'))){ return CheckApi::return_46011();}
        }
        if(!empty($request->get('jig'))){ 
            if(!is_numeric($request->get('jig'))){ return CheckApi::return_46011();}
        }
        if(!empty($request->get('mold'))){ 
            if(!is_numeric($request->get('mold'))){ return CheckApi::return_46011();}
        }
        if(!empty($request->get('quantity'))){ 
            if(!is_numeric($request->get('quantity'))){ return CheckApi::return_46011();}
        }

        $ary = array(
            'proj_id'=>$request->get('proj_id'),//项目ID
            'proj_code'=>$request->get('proj_id'),//项目ID
            'company_id'=>$request->get('company_id'),//公司ID    
            'end_time'=>$request->get('end_time'),//结束时间
            'gauge'=>$request->get('gauge'),//检具
            'jig'=>$request->get('jia'),//夹具
            'mat_size'=>$request->get('mat_size'),//材料规格
            'material'=>$request->get('material'),//零件材料
            'mold'=>$request->get('mold'),//模具
            'note'=>$request->get('note'),//零件备注
            'part_from'=>$request->get('part_from'),//来源
            'part_name'=>$request->get('part_name'),//零件名
            'part_size'=>$request->get('part_size'),//零件尺寸
            'part_type'=>$request->get('part_type'),//零件类型
            'processing'=>$request->get('processing'),//加工工艺
            'quantity'=>$request->get('quantity'),//数量
            'shrink'=>$request->get('shrink'),//缩水率
            'start_time'=>$request->get('start_time'),//开始时间
            'surface'=>$request->get('surface'),//表面处理
            'weight'=>$request->get('weight') //零件重量
        );
        
        $result = ProjectDetail::createPartinfoApi($ary);

        //SQL语句错误
        if($result===10003){ return CheckApi::return_10003(); }

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();    
        }


    }






    // /** 
    // * 修改文档
    // * @param 
    // * @return 
    // */ 
    // public function update_document(Request $request,Response $response)
    // { 

    // }

    /** 
    * 新增项目(零件)文档
    * @param 
    * @return 
    */ 
    public function createDocument(Request $request,Response $response)
    { 

    }

    /** 
    * 删除项目(零件)文档
    * @param 
    * @return 
    */ 
    public function deleteDocument(Request $request,Response $response)
    { 

    }

    /** 
    * 获取项目(零件)文档列表
    * @param 
    * @return 
    */ 
    public function listDocument(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token','proj_id');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //proj_id必须为数值
        if(!is_numeric($request->get('proj_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
            
        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id','proj_id']);
        if($return !== true){ return $return; }

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //1-2、项目不存在或已删除
        $return = CheckApi::check_projectstatus($request->get('proj_id'));
        if($return !== true){ return $return; }

        //2、项目与公司是否属于公司
        $ruturn = CheckApi::check_projectincomp($request->get('company_id'),$request->get('proj_id'));
        if($return !== true){ return $return; }

        $result = Project::listDocumentApi($request->get('proj_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }

    }

    /** 
    * 获取项目(零件)文档信息
    * @param 
    * @return 
    */ 
    public function getDocument(Request $request,Response $response)
    { 

    }

    /** 
    * 获取公司部门列表(已有？)
    * @param 
    * @return 
    */ 
    public function listDepartment(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $result = Department::listDepartlistApi($request->get('company_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    /** 
    * 获取公司部门下人员(已有？)
    * @param 
    * @return 
    */ 
    public function listDepartmentMembers(Request $request,Response $response)
    { 

    }

    /** 
    * 获取公司日历列表
    * @param 
    * @return 
    */ 
    public function listCalendar(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $result = WorkCalendar::listCalendarApi($request->get('company_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }


    }

    /** 
    * 获取项目类型列表
    * @param 
    * @return 
    */ 
    public function listProperty(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
        
        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
    
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $result = ProjectType::getProjectTypes($request->get('company_id'));

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();    
        }



    }

    /** 
    * 获取供应商列表
    * @param 
    * @return 
    */ 
    public function listSupplier(Request $request,Response $response)
    { 

    }

    /** 
    * 给供应商发送消息
    * @param 
    * @return 
    */ 
    public function createSupplierMessage(Request $request,Response $response)
    { 

    }



}
