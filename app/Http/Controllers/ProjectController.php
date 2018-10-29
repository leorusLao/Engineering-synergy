<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Support\Facades\Log;
use function implode;
use function Psy\debug;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyConfig;
use App\Models\UserCompany;
use App\Models\Customer;
use App\Models\WorkCalendar;
use App\Models\ProjectDetail;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\PartType;
use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Supplier;
use App\Models\WorkCalendarReal;
use App\Models\PlanTask;
use function strlen;
use function var_dump;

class ProjectController extends  Controller {
	protected $locale;

	public function __construct()
	{
		session_start();
		if(Session::has('locale')){
			$this->locale = Session::get('locale');
		}
		else if(isset($_COOKIE['locale'])){
			$this->locale = $_COOKIE['locale'];
		}
		else{
			$this->locale = config('app.locale');
		}

	}


    //创建项目(项目基础信息拉取)
    public function setupProject(Request $request, Response $response)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $companyid = Session::get('USERINFO')->companyId;
        if(!empty($companyid)){
            $result['project_number'] = json_decode(InitController::getAndInitProjectCoding())-> proj_code;//项目编号
            $result['part_number'] = json_decode(InitController::getAndInitPartCoding())-> part_code;//零件编号
            $result['project_coding'] = json_decode(InitController::getAndInitProductPlanCoding())-> plan_code;//项目计划编号
//            $result['mold_coding'] = json_decode(InitController::getAndInitMoldPlanCoding())-> plan_code;//模具编号
//            $result['jig_coding'] = json_decode(InitController::getAndInitGaugePlanCoding())-> plan_code;//检具编号
//            $result['gauge_coding'] = json_decode(InitController::getAndInitJigPlanCoding())-> plan_code;//夹具编号
            //根据计划类型获取各种类型初始编号（各种计划类型编号在initController中加工）
            //获取各种计划编号
//            $result['all_coding'] = json_decode(InitController::getAndInitAllPlanCoding());//所有计划编号

            $result['plan_type'] = PlanType::getPlanTypes($companyid);//计划类型
            $result['customer'] = Customer::getCustomerlistProject($companyid);//客户
            $result['company_user'] = User::infoCompanyUser($companyid);//公司成员
            $result['calendar'] = WorkCalendar::listCalendar($companyid);//项目日历
            $result['project_type'] = ProjectType::getProjectTypes($companyid);//项目类型
            $result['part_type'] = PartType::getPartTypes($companyid);//零件类型
        }
        //根据计划类型获取各种类型初始编号（各种计划类型编号在initController中加工）
        //获取计划的所有初始编号
        $all_coding = json_decode(InitController::getAndInitAllPlanCoding(),true);//所有计划编号

//		print '<pre>';
//		var_export($all_coding);
//		print '</pre>';
//		exit;
        Log::debug('获取各种计划编号');
        Log::debug($all_coding);

        $cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_setup');
        return view('dailywork.setup-project',array('cookieTrail' => $cookieTrail,  'result' => $result,
            'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale, 'all_coding' => $all_coding));
    }


	//单个项目数据
	public function dataProject($project_id)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;

		if(empty($project_id)){ 
			//return Redirect::to('/');
		}
		$field = '*';
		$nameArr = [];


		//项目信息
		$result_project = Project::infoProject(array('proj_id'=>$project_id,'company_id'=>$companyid),$field);
		if(empty($result_project)){ 
			//return Redirect::to('/'); 
		}
		if(!empty($companyid)){
			$result['customer'] = Customer::getCustomerlistProject($companyid);//客户
			$result['company_user'] = User::infoCompanyUser($companyid);//公司成员
			$result['calendar'] = WorkCalendar::listCalendar($companyid);//项目日历
			// 项目类别
			$tmp = ProjectType::where('company_id', $companyid)->get();
			foreach($tmp as $k => $tmpp) {
				$result['project_type'][$k] = [
					'type_id' => $tmpp->type_id,
					'name' => $tmpp->name,
				];
			}
		}
		//项目成员
		if(!empty($result_project->member_list)){
			$ary_member = explode(',',$result_project->member_list);
			foreach ($ary_member as $key => $value) {
				$user = User::infoUser(array('uid'=>$value),'fullname');
				$nameArr[$key] = [
					'uid' => $value,
					'fullname' => $user->fullname,
				];
			}
			$result_project->member_list_data = $nameArr;
		}
		// 项目状态
//		$approval_status_arr = [
//			1 => '待递交',
//			2 => '待审批',
//			3 => '审批同意',
//			4 => '审批拒绝',
//		];
//		$result_project->approval_status_name = $approval_status_arr[$result_project->approval_status];
		//项目日历
		if(!empty($result_project->calendar_id)){
			$res_cal = WorkCalendar::infoCalendar(array('cal_id'=>$result_project->calendar_id),'cal_name');
			$result_project->cal_name = $res_cal->cal_name;
		}
		// 项目类别
		if(!empty($result_project->proj_type)) {
			$result_project->proj_type_name = ProjectType::where('type_id', $result_project->proj_type)->value('name');
		}

		if(Storage::disk('company')->exists($companyid.'/project/'.$project_id)){//create project ... dir for $company_id if non-existance
			    $files = Storage::disk('company')->files($companyid.'/project/'.$project_id);
			    $files_path = $files;
        }

        //文件
		$result_project['public_path'] = url("/company").'/';
		$result_project['new_pic'] = [];
        if(!empty($files_path)){
			foreach ($files_path as $key => $value) {
				$new_pic[$key]['suffix'] = strtolower(mb_substr(strstr($value,'.'),1));
				$new_pic[$key]['name'] = mb_substr(strrchr($value,'/'),1);
				$new_pic[$key]['path'] = $value;
				$new_pic[$key]['size'] = byteTransGMKB(Storage::disk('company')->size($value));
				if(in_array($new_pic[$key]['suffix'],array('jpg','gif','jpeg','png'),true)){ 
					$new_pic[$key]['bool'] = true;
				}else{ 
					$new_pic[$key]['bool'] = false;
				}
			}
			$result_project['new_pic'] = $new_pic;
		}
		//文件
		/*if(!empty($result_project->work_file)){
			$ary_pic = explode(',',$result_project->work_file);
			foreach ($ary_pic as $key => $value) {
				$new_pic[$key]['suffix'] = strtolower(mb_substr(strstr($value,'.'),1));
				$new_pic[$key]['name'] = $value;
				if(in_array($new_pic[$key]['suffix'],array('jpg','gif','jpeg','png'),true)){ 
					$new_pic[$key]['bool'] = true;
				}else{ 
					$new_pic[$key]['bool'] = false;
				}
			}
			$result_project['public_path'] = 'http://localhost/uploads/common/';
			$result_project['new_pic'] = $new_pic;
		}*/
		//零件信息
		$result_detail = ProjectDetail::listDetail(array('proj_id'=>$project_id,'company_id'=>$companyid),$field);
		//计划信息
		$result_plan = Plan::listPlan(array('project_id'=>$project_id,'company_id'=>$companyid),$field);
		// 涉及用户
		$user_team = [];

		foreach($result_plan as $v){
			$user_team = array_merge($user_team, [$v['leader']],explode(',', $v['member']));
		}

		$user_arr = User::whereIn('uid', $user_team)->pluck('fullname', 'uid')->toArray();;

		$result['user_arr'] = $user_arr;
		$result['result_project']=$result_project;
		$result['result_detail']=$result_detail;
		$result['result_plan']=$result_plan;
		return $result;
	}

	//项目详情
	public function showProject(Request $request,$token, $project_id)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
		$uid = Session::get('userId');
		
		$salt = $companyid.$this->salt.$uid;
		$compToken = hash('sha256',$salt.$project_id);
        
		if($token !=  $compToken){
			return Redirect::back()->with('result', Lang::get('operation_disallowed'));
		}
	 	
		$result = self::dataProject($project_id);
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_view');
		
	
		
		return view('dailywork.cont-project',
			array(
			'cookieTrail' => $cookieTrail,
			'result_project' => $result['result_project'], 
			'result_detail' => $result['result_detail'], 
			'result_plan' => $result['result_plan'], 
			'pageTitle' => Lang::get('mowork.dashboard'),
			'locale' => $this->locale
			)
		);
	}

    //项目详情(新)
    public function showProjectNew(Request $request,$token, $project_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $companyid = Session::get('USERINFO')->companyId;
        $uid = Session::get('userId');

        $salt = $companyid.$this->salt.$uid;
        $compToken = hash('sha256',$salt.$project_id);

//		if($token !=  $compToken){
//			return Redirect::back()->with('result', Lang::get('operation_disallowed'));
//		}

        $result = self::dataProject($project_id);
        $cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_view');

        return view('dailywork.cont-projectNew',
            array(
                'cookieTrail' => $cookieTrail,
                'result_project' => $result['result_project'],
                'result_detail' => $result['result_detail'],
                'result_plan' => $result['result_plan'],
                'pageTitle' => Lang::get('mowork.dashboard'),
                'locale' => $this->locale
            )
        );
    }


	//编辑项目
	public function editProject(Request $request, $token, $project_id)
	{ 
		 
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.edit_project');
		
		$companyid = Session::get('USERINFO')->companyId;
		$uid = Session::get('userId');
		
		$salt = $companyid.$this->salt.$uid;
		$compToken = hash('sha256',$salt.$project_id);
		
		if($token !=  $compToken){
			return Redirect::back()->with('result', Lang::get('operation_disallowed'));
		}
		$result = self::dataProject($project_id);

		return view('dailywork.edit-project',
			array(
			'cookieTrail' => $cookieTrail,  
			'result' => $result, 
			'pageTitle' => Lang::get('mowork.dashboard'),
			'locale' => $this->locale
			)
		);
	}


    //编辑项目(新)
    public function editProjectNew(Request $request, $token, $project_id)
    {

        $cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.edit_project');

        $companyid = Session::get('USERINFO')->companyId;
        $uid = Session::get('userId');

        $salt = $companyid.$this->salt.$uid;
        $compToken = hash('sha256',$salt.$project_id);

//        if($token !=  $compToken){
//            return Redirect::back()->with('result', Lang::get('operation_disallowed'));
//        }
		$part_type = PartType::getPartTypes($companyid);//零件类型
		$part_number = json_decode(InitController::getAndInitPartCoding())-> part_code;//零件编号
		$plan_type = PlanType::getPlanTypes($companyid);//计划类型
		$company_user = User::infoCompanyUser($companyid);//公司成员
        $result = self::dataProject($project_id);
		$all_coding = json_decode(InitController::getAndInitAllPlanCoding(),true);//所有计划编号
//		dd($result);
        return view('dailywork.edit-projectNew',
            array_merge([
				'part_type' => $part_type,
				'part_number' => $part_number,
				'company_user' => $company_user,
				'plan_type' => $plan_type,
				'all_coding' => $all_coding,
                'cookieTrail' => $cookieTrail,
                'pageTitle' => Lang::get('mowork.dashboard'),
                'locale' => $this->locale],$result
            )
        );
    }

	//审批项目
	public function approvalProject(Request $request,Response $response)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;

		if(empty($request->get('intention')) || empty($request->get('projectid'))){
			//return Redirect::to('/');
		}

		$ary_approval = array(
			'approval_status' => $request->get('intention'),
			'approval_comment' => $request->get('cont_approval'),
			'approval_date' => date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),
			'approval_person' => Session::get('userId')
			);
		$affect = Project::updateProject($ary_approval,array('proj_id'=>$request->get('projectid'),'approval_status'=>0));
		if(!empty($affect)){ 
			return response()->json(array('code'=>1,'msg'=>Lang::get('mowork.approval_success')));
		}else{ 
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.approval_fail')));
		}

	}


    //保存项目
    public function saveProject(Request $request, Response $response)
    {
        //项目编号
        $project_id = 0;
        //零件编号
        $detail_id = 0;

        $companyid = Session::get('USERINFO')->companyId;

        /*
        //项目编号是否有重复
        $result = Project::exitProject($request->get('project_number'));
        if(!empty($result)){
            return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.project_number_exit')));
        }

        */
        //获取项目编号
        $ary_proj = InitController::getAndInitProjectCodingApi($companyid);

        //*****************************项目判断逻辑***********************************
        //项目性质不属于01(公开、私有)
		$pDataStr = $request->get('pData');
		$pData = json_decode($pDataStr, true);

		$project = $pData['project'];
		$part = $pData['part'];
		$plan = $pData['plan'];
		Log::debug($pData);
//        $category = $request->get('project_plan');
		$category = $project['property'];
        if(!in_array($category,[0,1])){
            return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.nature_notinscope')));
        }
        //项目经理在不在该公司中
//		$company_user = UserCompany::userincompany($request->get('proj_manager_uid'),$companyid);
		$company_user = UserCompany::userincompany($project['proj_manager_uid'],$companyid);
		if(empty($company_user)){
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.manager_notincompany')));
		}else{
//			$user = User::infoUser(['uid'=>$request->get('proj_manager_uid')],'fullname');
			$user = User::infoUser(['uid'=>$project['proj_manager_uid']],'fullname');
			$proj_manager = $user->fullname;
		}
		//项目成员在不在该公司中
//		$ary_member = $request->get('project_member_value');
		$ary_member = $project['member_list'];
        $temp_ary_member = explode(',',$ary_member);
        foreach ($temp_ary_member as $key => $value) {
			$company_user = UserCompany::userincompany($value,$companyid);
			if(empty($company_user)){
				return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.member_notincompany')));
			}
		}
        //判断公司与客户对不对应（客户名称）
//        $cust_name = Customer::customerName($companyid,$request->get('customer_number'));
        $cust_name = Customer::customerName($companyid,$project['customer_id']);
        if(empty($cust_name)){
            return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.company_notwithcustomer')));
        }
        //项目表
        if(!empty($companyid) && !empty($ary_proj))
        {
//            $ary_project = array(
//                'company_id'=>$companyid,//公司ID
//                'proj_name'=>$request->get('project_name'),//项目名称
//                'proj_type'=>$request->get('project_category'),//项目类别
//                'customer_id'=>$request->get('customer_number'),//客户编号
//                'customer_name'=>$cust_name->cust_company_name,//客户名称
//                'proj_manager_uid'=>$request->get('proj_manager_uid'),//项目经理
//                'proj_manager'=>$user->fullname,//项目经理全名
//                //'member_list'=>$request->get('project_member_value'),//项目成员列表
//                'member_list'=>$request->get('project_member_value'),//项目成员编号
//                'calendar_id'=>$request->get('project_calendar'),//项目日历未完善
//                'property'=>$request->get('project_plan'),//项目性质
//                'start_date'=>$request->get('start_date'),//接受日期
//                'end_date'=>$request->get('end_date'),//结束日期
//                'process_trail'=>$request->get('validation_date'),//工艺验证日期
//                'mold_sample'=>$request->get('sample_date'),//出模样件日期
//                'trail_production'=>$request->get('pilot_date'),//试产验证日期
//                'batch_production'=>$request->get('production_date'),//批量放产日期
//                'description'=>$request->get('project_desction'),//项目描述
//                'approval_status'=>0,//审批状态 0待批
//                'proj_status'=>0, //项目状态  0尚未审批
//            );
			$new_ary = array_merge($project, $ary_proj, [
				'company_id'=>$companyid, //公司ID
				'customer_name'=>$cust_name->cust_company_name, //客户名称
				'proj_manager'=>$user->fullname, //项目经理全名
				'approval_status'=> 1,//审批状态 待提交
			]);
            //项目编号+项目内容

//            $new_ary = $ary_proj + $ary_project;
            try{
                $project_id = Project::createProject($new_ary);
            }catch(Exception $e){
                return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
            }
            Log::debug('成功创建的条目'.$project_id);
        }
        //获取项目编号
        $proj_code = $ary_proj['proj_code'];
        Log::debug('项目编号'.$proj_code);
        //零件表
        $num_part = count($part);
        Log::debug('零件条数'.$num_part);
		Log::debug($part);
        if($num_part >= 1 && !empty($project_id)){
            for ($i=0; $i < $num_part; $i++) {
                $part_from = $part[$i]['part_from'];
                if($part_from = '自制'){
                    $part_from = 1;
                }else if($part_from = '外购'){
                    $part_from = 2;
                }else{
                    $part_from = 3;
                }
//                $ary_part = array(
//                    //项目编号
//                    'proj_id' => $project_id,
//                    //零件编号
//                    'proj_code' =>  $proj_code,
//                    'company_id'=> $companyid,//公司ID
//                    //缺少供应商的公司名称
//                    'supplier_name' => $request->get('part_size'),//指定供应商的公司名称
//                    'part_code' => $request->get('part_'.$i.'_2'),//零件编号
//                    'part_name' => $request->get('part_'.$i.'_3'),//零件名称
//                    'part_type' => $request->get('part_'.$i.'_4'),//零件类型
////                    'part_from' => $request->get('part_'.$i.'_5'),//来源
//                    'part_from' => $part_from,//来源
//                    'part_size' => $request->get('part_'.$i.'_10'),//零件尺寸
//                    'weight' => $request->get('part_'.$i.'_11'),//零件重量
//                    'material' => $request->get('part_'.$i.'_12'),//零件材料
//                    'mat_size' => $request->get('part_'.$i.'_13'),//材料规格
//                    'shrink' => $request->get('part_'.$i.'_14'),//缩水率
//                    'processing' => $request->get('part_'.$i.'_15'),//加工工艺
//                    'surface' => $request->get('part_'.$i.'_16'),//表面处理
//                    'note' => $request->get('part_'.$i.'_17')//备注
//                );
				$ary_part = array_merge($part[$i], [
					//项目编号
					'proj_id' 		=> $project_id,
					//零件编号
					'proj_code' 	=>  $proj_code,
					'company_id'	=> $companyid, //公司ID
					'part_from'		=> $part_from,
				]);
//                Log::debug($ary_part);
                //数量
//                if(!empty($request->get('part_'.$i.'_6'))){
//                    $ary_part['quantity'] = $request->get('part_'.$i.'_6');
////                    Log::debug('数量'.$ary_part['quantity']);
//                }else{
//                    $ary_part['quantity'] = 1;
//                }
				$ary_part['quantity'] || $ary_part['quantity'] = 1;
                //夹具
//                if(!empty($request->get('part_'.$i.'_7'))){
//                    $ary_part['jig'] = $request->get('part_'.$i.'_7');
//                }else{
//                    $ary_part['jig'] = 0;
//                }
				$ary_part['jig'] || $ary_part['jig'] = 0;
                //检具
//                if(!empty($request->get('part_'.$i.'_8'))){
//                    $ary_part['gauge'] = $request->get('part_'.$i.'_8');
////                    Log::debug('夹具'.$ary_part['gauge']);
//                }else{
//                    $ary_part['gauge'] = 0;
//                }
				$ary_part['gauge'] || $ary_part['gauge'] = 0;
                //模具
//                if(!empty($request->get('part_'.$i.'_9'))){
//                    $ary_part['mold'] = $request->get('part_'.$i.'_9');
//                }else{
//                    $ary_part['mold'] = 0;
//                }
				$ary_part['mold'] || $ary_part['mold'] = 0;
                //不清楚
//                if(!empty($request->get('part_size'))){
//                    $ary_part['assigned_supplier'] = $request->get('part_size');
//                }
//                Log::debug('不清楚'.$ary_part['assigned_supplier']);
                //新建零件
                try{
                    $detail_id = ProjectDetail::createDetail($ary_part);
                }catch(Exception $e){
                    return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
                }
//                Log::debug('新建零件'.$detail_id);
                if(!empty($detail_id)){
                    //零件ID
                    $newary_detail[$i]['id'] = $detail_id;
                    //零件编号
                    $newary_detail[$i]['part_code'] = $part[$i]['part_code'];
                }
            }
        }
        //计划表
//        $num_plan = $request->get('plan_num');
		$num_plan = count($plan);
        Log::debug('计划条数'.$num_plan);
//        Log::debug($newary_detail);
        if($num_plan >=1 && !empty($project_id)){
            for ($i=0; $i < $num_plan; $i++) {
                //var_dump($request->get('plan_'.$i.'_0'));
                foreach ($newary_detail as $key_detail => $value_detail) {
                    //判断零件信息的零件编号是不是等于计划信息中的零件编号
//                    if($request->get('plan_'.$i.'_2') == $value_detail['part_code']){
                    if($plan[$i]['part_code'] == $value_detail['part_code']){
//                        $ary_plan = array(
//                            'company_id'=>$companyid,
//                            'project_id'=>$project_id,//项目ID
//                            'project_detail_id'=>$value_detail['id'],//零件ID
//                            'part_code'=>$value_detail['part_code'],//零件编号
//                            'plan_code'=>$request->get('plan_'.$i.'_3'),//计划编号
//                            'plan_name'=>$request->get('plan_'.$i.'_4'),//计划名称
//                            'plan_type'=>$request->get('plan_'.$i.'_5'),//计划类型
//                            //计划负责人、计划成员、
//                            'description'=>$request->get('plan_'.$i.'_8')//计划描述
//                        );
						// leader member
						$tmpMemberArr = explode(',', $plan[$i]['member']);
						$tmpMemberArr1 = $tmpMemberArr;
						if(!in_array($plan[$i]['leader'], $tmpMemberArr)){
							$tmpMemberArr1[] = $plan[$i]['leader'];
						}
						$tmpArr = $user::whereIn('fullname', $tmpMemberArr1)->pluck('uid','fullname')->toArray();
						$plan[$i]['leader'] = $tmpArr[$plan[$i]['leader']];
						foreach($tmpMemberArr as $vv)
						{
							$tmpMember[] = $tmpArr[$vv];
						}
						$plan[$i]['member'] = implode(',', $tmpMember);

						$ary_plan = array_merge($plan[$i], [
							'company_id'=>$companyid,
							'project_id'=>$project_id,//项目ID
							'project_detail_id'=>$value_detail['id'],//零件ID
						]);
                        //开始日期
//                        if(!empty($request->get('plan_'.$i.'_9'))){
//                            $ary_plan['start_date'] = date('Y-m-d',strtotime($request->get('plan_'.$i.'_9')));
////                            $ary_plan['start_date'] = $request->get('plan_'.$i.'_9');
//                        }
						if(empty($ary_plan['start_date'])) {unset($ary_plan['start_date']);}
                        //结束日期
//                        if(!empty($request->get('plan_'.$i.'_10'))){
//                            $ary_plan['end_date'] = date('Y-m-d',strtotime($request->get('plan_'.$i.'_10')));
////                            $ary_plan['end_date'] = $request->get('plan_'.$i.'_10');
//                        }
						if(empty($ary_plan['end_date'])) {unset($ary_plan['end_date']);}
                        Log::debug($ary_plan);
                        try{
                            //新建计划（返回计划ID）
                            $plan_id = Plan::createPlan($ary_plan);
//                            Log::debug('新建计划测试');
                        }catch(Exception $e){
                            return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
                        }
                    }
                }
            }
        }

        if($project_id > 0 || $detail_id > 0){
            return response()->json(array('code'=>1, 'project_id'=>$project_id, 'msg'=>Lang::get('mowork.publish_success')));
        }else{
            return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
        }
    }


	//更新项目
	public function updateProject(Request $request, Response $response)
	{
		$project_id = 0;
		$detail_id = 0;
		$str_pic = '';

		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
//		$project_id = $request->get('project_id');

		$pData = $request->get('pData');
		$pData = json_decode($pData, true);
		Log::debug($pData);
		$project = $pData['project'];
		$part = $pData['part'];
		$plan = $pData['plan'];

		//项目id是否存在
		$result = Project::select('proj_id')->where(['proj_code'=>$project['proj_code']])->first();
		if(empty($result)){ 
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.project_number_notexit')));
		}
		/*
		//项目编号是否有重复
		$result = Project::exitProjectOther($request->get('project_number'),$project_id);
		if(!empty($result)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.project_number_exit')));
		}*/
		//项目类别是否是012
//		$category = $request->get('project_category');
//		if(!in_array($category,[1,2,3])){
//			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.category_notexit')));
//		}
		//项目性质不属于01
//		$category = $request->get('project_plan');
//		if(!in_array($category,[0,1])){
		if(!in_array($project['property'],[0,1])){
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.nature_notinscope')));
		}
		//项目经理不在公司中
//		$company_user = UserCompany::userincompany($request->get('proj_manager_uid'),$companyid);
		$company_user = UserCompany::userincompany($project['proj_manager_uid'],$companyid);
		if(empty($company_user)){
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.manager_notincompany')));
		}else{ 
//			$user = User::infoUser(['uid'=>$request->get('proj_manager_uid')],'fullname');
			$user = User::infoUser(['uid'=>$project['proj_manager_uid']],'fullname');
			$proj_manager = $user->fullname;
		}
		//有成员不在公司中
//		$ary_member = explode(',',$request->get('project_member_value'));
		$ary_member = explode(',',$project['member_list']);
		foreach ($ary_member as $key => $value) {
			$company_user = UserCompany::userincompany($value,$companyid);
			if(empty($company_user)){ 
				return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.member_notincompany')));
			}
		}
		//客户名称
		$cust_name = Customer::customerName($companyid,$project['customer_id']);
		if(empty($cust_name)){ 
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.company_notwithcustomer')));
		}

		//项目表
//		if(!empty($companyid) && !empty($request->get('project_number')))
		if(!empty($companyid) && !empty($project['proj_code']))
		{
//			$ary_project = array(
//				'proj_code'=>$request->get('project_number'),
//				'proj_unicode'=>'',
//				'customer_name'=>$cust_name->cust_company_name,//客户名称
//				'customer_id'=>$request->get('customer_number'),//客户编号
//				'company_id'=>$companyid,//公司ID
//				'proj_name'=>$request->get('project_name'),//项目名称
//				'description'=>$request->get('project_desction'),//项目描述
//				'proj_type'=>$request->get('project_category'),//项目类别
//				'start_date'=>$request->get('start_date'),//接受日期
//				'end_date'=>$request->get('end_date'),//结束日期
//				'approval_status'=>0,//审批状态 0待批
//				'proj_status'=>0, //项目状态  0尚未审批
//				'property'=>$request->get('project_plan'),//项目性质
//				'member_list'=>$request->get('project_member_value'),//项目成员编号
//				'process_trail'=>$request->get('validation_date'),//工艺验证日期
//				'mold_sample'=>$request->get('sample_date'),//出模样件日期
//				'trail_production'=>$request->get('pilot_date'),//试产验证日期
//				'batch_production'=>$request->get('production_date'),//批量放产日期
//				'proj_manager_uid'=>$request->get('proj_manager_uid'),//项目经理
//				'proj_manager'=>$user->fullname,//项目经理全名
//				//'member_list'=>$request->get('project_member_value'),//项目成员列表
//				'calendar_id'=>$request->get('project_calendar'),//项目日历未完善
//				);
				$ary_project = array_merge($project, [
					'customer_name' 	=> $cust_name->cust_company_name,
					'company_id' 		=> $companyid,//公司ID
					'approval_status'	=> 0,//审批状态 0待提交
					'proj_manager'		=> $proj_manager,//项目经理全名
				]);
				Log::debug($ary_project);
			try{
				$project_affect = Project::updateProject($ary_project,array('proj_code'=>$project['proj_code']));
			}catch(Exception $e){
				return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
			}
		}

		// 零件表
		if(!empty($part))
		{
			$part_code_old = ProjectDetail::where(['proj_code' => $project['proj_code'], 'company_id' => $companyid])->pluck('part_code')->toArray();
			$part_type = PartType::where('company_id', $companyid)->pluck('type_id', 'name')->toArray();//零件类型
			$part_from = [
				'自制' => 1,
				'外购' => 2,
				'客供' => 3,
			];
			$part_code_arr = [];
			$part_delete = $part_code_old;
			foreach($part as $k => $v){
				$v['part_type'] = is_numeric($v['part_type']) ? $v['part_type'] : $part_type[$v['part_type']];
				$v['part_from'] = $part_from[$v['part_from']];
				$v['quantity'] = $v['quantity'] ?  $v['quantity'] : 0;
				$v['jig'] = $v['jig'] ? $v['jig'] : 0;
				$v['gauge'] = $v['gauge'] ? $v['gauge'] : 0;
				$v['mold'] = $v['mold'] ? $v['mold'] : 0;
				$v['proj_id'] = $result['proj_id'];
				$v['company_id'] = $companyid;
				$v['proj_code'] = $project['proj_code'];

				$part_code_arr[] = $v['part_code'];
				if(in_array($v['part_code'], $part_code_old)) {
					// update
					ProjectDetail::where('part_code', $v['part_code'])->update($v);
				}else{
					// add
					ProjectDetail::create($v);
				}
				$part_delete = array_diff($part_delete, [$v['part_code']]);
			}
			if(!empty($part_delete)){
				ProjectDetail::whereIn('part_code', $part_delete)->delete();
				Plan::WhereIn('part_code', $part_delete)->delete();
			}
		}

		// 计划表
		if(!empty($part_code_arr) && !empty($plan)){
			$plan_old_arr = [];
			$plan_old = Plan::where('project_id', $result['proj_id'])->select('plan_type', 'part_code')->get()->toArray();
			foreach($plan_old as $k => $v)
			{
				$plan_old_arr[] = $v['part_code'].'-'.$v['plan_type'];
			}
			$plan_delete = $plan_old_arr;
			foreach($plan as $k => $v) {
				$tmpMemberArr = explode(',', $v['member']);
				$tmpMemberArr1 = $tmpMemberArr;
				if(!in_array($v['leader'], $tmpMemberArr)){
					$tmpMemberArr1[] = $v['leader'];
				}
				$tmpArr = $user::whereIn('fullname', $tmpMemberArr1)->pluck('uid','fullname')->toArray();
				$v['leader'] = $tmpArr[$v['leader']];
				foreach($tmpMemberArr as $vv)
				{
					$tmpMember[] = $tmpArr[$vv];
				}
				$v['member'] = implode(',', $tmpMember);
				$v['project_id'] = $result['proj_id'];
				$v['project_detail_id'] = ProjectDetail::where('part_code', $v['part_code'])->value('id');
				$v['company_id'] = $companyid;
				$plan[$k] = $v;
				$plan_arr[$k] = $v['part_code'].'-'.$v['plan_type'];
			}

			foreach($plan as $k => $v){
				if(in_array($plan_arr[$k], $plan_old_arr)){
					// update
					Plan::where(['part_code' => $v['part_code'], 'plan_type' => $v['plan_type'] ])->update($v);
				}else{
					// add
					Plan::create($v);
				}
				$plan_delete = array_diff($plan_delete, [$plan_arr[$k]]);
			}

			if(!empty($plan_delete)){
				foreach($plan_delete as $v)
				{
					$tmpArr = explode('-', $v);
					Plan::where(['part_code' => $tmpArr[0], 'plan_type' => $tmpArr[1]])->delete();
				}
			}
		}

		//零件表
//		$str_partcode = $request->get('part_delete');
//		if(!empty($str_partcode)){
//			$str_partcode = mb_substr($str_partcode,0,-1);
//			$ary_partcode = explode(',',$str_partcode);
//			ProjectDetail::deleteBypartcoded($ary_partcode);
//		}
//
//		$num_part = $request->get('num_lj');
//		if($num_part >= 1 && !empty($project_affect)){
//			for ($i=0; $i < $num_part; $i++) {
//				//有plan_id则为已有plan
//				$hasdetail_id = $request->get('part_'.$i.'_17');
//				$ary_part = array(
//				'part_code' => $request->get('part_'.$i.'_0'),
//				'part_name' => $request->get('part_'.$i.'_1'),
//				'part_type' => $request->get('part_'.$i.'_2'),
//				'note' => $request->get('part_'.$i.'_4'),
//				'processing' => $request->get('part_'.$i.'_8'),
//				'part_from' => $request->get('part_'.$i.'_9'),
//				'material' => $request->get('part_'.$i.'_10'),
//				'mat_size' => $request->get('part_'.$i.'_11'),
//				'shrink' => $request->get('part_'.$i.'_12'),
//				'surface' => $request->get('part_'.$i.'_13'),
//				'part_size' => $request->get('part_'.$i.'_14'),
//				'weight' => $request->get('part_'.$i.'_15'),
//				'proj_id' => $project_id,
//				'proj_code' => $request->get('project_number'),
//				'company_id'=> $companyid,//公司ID
//				'supplier_name' => $request->get('part_size'),//指定供应商的公司名称
//				);
//
//				if(!empty($request->get('part_'.$i.'_3'))){
//					$ary_part['quantity'] = $request->get('part_'.$i.'_3');
//				}
//				if(!empty($request->get('part_'.$i.'_5'))){
//					$ary_part['jig'] = $request->get('part_'.$i.'_5');
//				}
//				if(!empty($request->get('part_'.$i.'_6'))){
//					$ary_part['gauge'] = $request->get('part_'.$i.'_6');
//				}
//				if(!empty($request->get('part_'.$i.'_7'))){
//					$ary_part['mold'] = $request->get('part_'.$i.'_7');
//				}
//				if(!empty($request->get('part_size'))){
//					$ary_part['assigned_supplier'] = $request->get('part_size');//指定供应商的公司id
//				}
//
//				try{
//					if(!empty($hasdetail_id)){
//						$return = ProjectDetail::updateDetail($ary_part,array('id'=>$hasdetail_id));
//					}else{
//						$return = ProjectDetail::createDetail($ary_part);
//					}
//				}catch(Exception $e){
//					return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
//				}
//				if(!empty($return)){
//					$newary_detail[$i]['detail_id'] = $hasdetail_id;
//					$newary_detail[$i]['part_code'] = $request->get('part_'.$i.'_0');
//				}
//			}
//		}

		//计划表		
//		$str_plancode = $request->get('plan_delete');
//		if(!empty($str_plancode)){
//			$str_plancode = mb_substr($str_plancode,0,-1);
//			$ary_plancode = explode(',',$str_plancode);
//			Plan::deleteBypartcoded($ary_partcode);
//		}
//		$num_plan = $request->get('num_plan');
//		if($num_plan >= 1 && !empty($project_affect)){
//			for ($i=0; $i < $num_plan; $i++) {
//				foreach ($newary_detail as $key_detail => $value_detail) {
//					if($request->get('plan_'.$i.'_0') == $value_detail['part_code']){
//
//						$hasplan_id = $request->get('plan_'.$i.'_8');
//						$ary_part = array(
//						'project_id'=>$project_id,//项目ID
//						'project_detail_id'=>$value_detail['detail_id'],//零件编号
//						'plan_code'=>$request->get('plan_'.$i.'_1'),//计划编号
//						'plan_name'=>$request->get('plan_'.$i.'_2'),//计划名称
//						'plan_type'=>$request->get('plan_'.$i.'_3'),//计划类型
//						'description'=>$request->get('plan_'.$i.'_4'),//计划描述
//						'company_id'=>$companyid,
//						'part_code'=>$value_detail['part_code'],
//						);
//						if(!empty($request->get('plan_'.$i.'_5'))){
//							$ary_part['start_date'] = $request->get('plan_'.$i.'_5');
//						}
//						if(!empty($request->get('plan_'.$i.'_6'))){
//							$ary_part['end_date'] = $request->get('plan_'.$i.'_6');
//						}
//						try{
//							if(!empty($hasplan_id)){
//								$plan_id = Plan::updatePlan($ary_part,array('plan_id'=>$hasplan_id));
//							}else{
//								$plan_id = Plan::createPlan($ary_part);
//							}
//						}catch(Exception $e){
//							return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
//						}
//					}
//				}
//			}
//		}

		//文档上传
		$project_id = $request->get('project_id');
		$num_pic = $request->get('num_pic'); 
		if($num_pic >= 1 && !empty($project_id)){ 
			for ($i=0; $i < $num_pic; $i++) {
				$str_pic = $str_pic.$request->get('pic_'.$i).',';
			}			
			$ary_pic = array(
				'work_file'=>mb_substr($str_pic,0,-1)
			);
			try{
				$result_pic = Project::updateProject($ary_pic,array('proj_id'=>$project_id));
			}catch(Exception $e){ 
				return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
			}
		}

		if($project_affect > 0){
			return response()->json(array('code'=>1,'msg'=>Lang::get('mowork.publish_success')));
		}else{
			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.save_fail')));
		}

	}



	//审批列表
	public function approveProject(Request $request, Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('userId');
		$companyid = Session::get('USERINFO')->companyId;
		if(!empty($companyid)){
			// 审批
			if($request->has('submit')) {
				try{
					$proj_id = $request->get('proj_id');
					$approval_status = $request->get('approval_status');
					$approval_comment = $request->get('approval_comment');
					$approval_person = User::where('uid', $uid)->value('fullname');
					Project::where('proj_id', $proj_id)->update(compact('approval_status', 'approval_comment', 'approval_person'));
					$msg = $approval_status == 2 ? Lang::get('mowork.handin').Lang::get('mowork.success') : Lang::get('mowork.publish_success');
					$request->session()->flash('result', $msg);
				} catch(\Exception $e){
					Log::debug($e->getMessage());
					$msg = $approval_status == 2 ? Lang::get('mowork.handin').Lang::get('mowork.failure') : Lang::get('mowork.save_fail');
					$request->session()->flash('result', $msg);
				}
			}
			$result = Project::listApprovalProject($companyid,[1,2]);
		}else{
			$result = [];
		}
//		dd($result);
		$salt = $companyid.$this->salt.Session::has('userId');
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_approval');
		return view('dailywork.approve-project',array('salt' => $salt, 'cookieTrail' => $cookieTrail, 'result' => $result, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}


    //审批列表(新)
    public function approveProjectNew(Request $request, Response $response)
    {
        $uid = Session::get('userId');
        $companyid = Session::get('USERINFO')->companyId;

        if(!empty($companyid)){
            // 审批
            if($request->has('submit')) {
                try{
                    $proj_id = $request->get('proj_id');
                    $approval_status = $request->get('approval_status');
                    $approval_comment = $request->get('approval_comment');
                    $approval_person = User::where('uid', $uid)->value('fullname');
                    Project::where('proj_id', $proj_id)->update(compact('approval_status', 'approval_comment', 'approval_person'));
                    $request->session()->flash('result', Lang::get('mowork.publish_success'));
                } catch(\Exception $e){
                    Log::debug($e->getMessage());
                    $request->session()->flash('result', Lang::get('mowork.save_fail'));
                }
            }
            $result = Project::listApprovalProjectNew($companyid,0);
        }else{
            $result = [];
        }
        Log::debug('审批');
        Log::debug($result);
        $salt = $companyid.$this->salt.Session::has('userId');
        $cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_approval');

        if(empty($request->get('_search'))){
            return view('dailywork.approve-projectNew',array('salt' => $salt, 'cookieTrail' => $cookieTrail, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        }else{
            Log::debug("审批装载数据");
            $total = ceil($result->count()/$request->get('rows'));
            Log::debug($total);
            Log::debug($result->count());
            return '{"page":"'.$request->get('page').'","total":"'.$total.'","records":"'.$result->count().'","rows":'.$result.'}';
        }
    }
	
	//项目列表
	public function listProject(Request $request, Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
		$uid = Session::get('userId');
		if(!empty($companyid)){
			$result = Project::listProject($companyid);
		}else{ 
			$result = [];
		}

		$hasPartArr = [];
		// 项目有没有零件
		if(!empty($result)){
			$proj_id_arr = [];
			foreach($result as $key => $value){
				$proj_id_arr[] = $value->proj_id;
			}
			$hasPartArr = ProjectDetail::whereIn('proj_id', $proj_id_arr)->pluck('id', 'proj_id')->toArray();
		}

		$salt = $companyid.$this->salt.$uid;
        Log::debug($salt);
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_list');
		return view('dailywork.list-project',array('cookieTrail' => $cookieTrail, 'salt' => $salt, 'result' => $result,'hasPartArr' =>$hasPartArr,  'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}

    //项目列表(新)
    public function listProjectNew(Request $request, Response $response)
    {
        $companyid = Session::get('USERINFO')->companyId;
        $uid = Session::get('userId');

        if(!empty($companyid)){
//            $result = Project::listProject($companyid,$request->get('sidx'), $request->get('sort'))->get();
            $result = Project::listProjectNew($companyid);
            Log::debug($result);
        }else{
            $result = [];
        }
        $salt = $companyid.$this->salt.$uid;
        Log::debug('salt');
        Log::debug($salt);
        $cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_list');
        if(empty($request->get('_search'))){
            return view('dailywork.list-projectNew',array('cookieTrail' => $cookieTrail, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        }else{
            Log::debug("装载数据");
            $total = ceil($result->count()/$request->get('rows'));
            Log::debug($total);
            Log::debug($result->count());
            return '{"page":"'.$request->get('page').'","salt":"'.$salt.'","total":"'.$total.'","records":"'.$result->count().'","rows":'.$result.'}';
        }
    }

    public function listProjectData(Request $request, Response $response)
    {
        $all = ModelAccount::orderBy(Input::get("sidx"), Input::get("sort"))->get();
        $total = ceil($all->count()/Input::get("rows"));//*Input::get("rows") / 10;
        return '{"page":"'.Input::get("page").'","total":"'.$total.'","records":"'.$all->count().'","rows":'.$all.'}';
        //return $all;
    }

    /**
     * 获取项目总列表
     * @param
     * @return
     */
//    public function listProject(Request $request,Response $response)
//    {
//
//        //判断参数个数是否足够
//        $ary_params = array('company_id','uid','token','approval_status');
//        $return = CheckApi::check_format($request,$ary_params);
//        if($return !== true){ return $return;}
//
//        //company_id必须为数值
//        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}
//
//        //uid必须为数值
//        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
//
//        //用户是否存在
//        $return = CheckApi::check_userexit($request->get('uid'));
//        if($return !== true){ return $return;}
//
//        //token是否真实的/是否已过期
//        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
//        if($return !== true){ return $return;}
//
//        //执行sql的条件:
//        //1、用户是否在公司里面
//        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
//        if($return !== true){ return $return;}
//
//        //分页输入
//        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
//        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
//        if($page_size > 50){ $page_size = 50;} //限制查询条数
//
//        $result = Project::listProjectApi($request->get('company_id'),$request->get('approval_status'),
//            $page_size,$curr_page);
//        //SQL语句错误
//        if($result===10003){ return CheckApi::return_10003(); }
//
//        if(!empty($result)){
//            return CheckApi::return_success($result);
//        }else{
//            return CheckApi::return_46009();
//        }
//
//    }

	//删除项目
	public function deleteProject(Request $request,Response $response)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;

		$project_id = $request->get('id');
		if(!empty($project_id)){
			//$project_id = mb_substr($project_id,0,-1);
			$ary_id = explode(',',$project_id);
			//公司与项目是否对应
			foreach ($ary_id as $key => $value) {
				$result = Project::infoProject(array('company_id'=>$companyid,'proj_id'=>$value));
				if(empty($result)){ 
					return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.nopermission_delete')));
				}
			}
			// 项目中零件的不能删除
			$count = ProjectDetail::whereIn('id', $ary_id)->count();

			if($count != 0) {
				return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.do_not_delete_project')));
			}else {
				//删除项目
				$affect_project = Project::deleteProjectAry($ary_id);
				if($affect_project) {
					return response()->json(array('code'=>1,'msg'=>Lang::get('mowork.delete_success')));
				}
			}

//			//删除零件
//			$affect_detail = ProjectDetail::deleteByprojId($ary_id);

		}

		return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.delete_fail')));


//		if(!empty($affect_project)){
//			return response()->json(array('code'=>1,'msg'=>Lang::get('mowork.delete_success')));
//		}else{
//			return response()->json(array('code'=>2,'msg'=>Lang::get('mowork.delete_fail')));
//		}

	}

	//保存项目文件
	public function saveFiles(Request $request,Response $response)
	{	
		$length = $request->get('length');
		for ($i=0; $i < $length; $i++) { 
			$file = $request->file();
			$path = $file['file_'.$i]->getRealPath(); //文件临时路径
			$extension = $file['file_'.$i]->getClientOriginalExtension(); //文件后缀名
			$originalname = $file['file_'.$i]->getClientOriginalName();//原始文件名
			$originalname=iconv("UTF-8","gb2312", $originalname);
			//move_uploaded_file($path,public_path().'/uploads/common/'.time().rand(0,10000).'.'.$extension); 
			$result = move_uploaded_file($path,public_path().'/uploads/common/'.$originalname);
		}
	}

	//删除项目文件
	public function deleteFiles(Request $request,Response $response)
	{ 
		$filename = $request->get('filename');
		unlink(public_path().'/uploads/common/'.$filename);
	}

    
	public function distributeProject(Request $request,Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		 
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.assign_project');
		   	
		if($request->has('submit')) {
		   $project_detail_id = $request->get('proj_detail_id');
		   $sup_company_id = $request->get('cbx')[0];
	 
		   try {
		   	   $row = Supplier::where('sup_company_id', $sup_company_id)->first();
		   	   ProjectDetail::where('id',$project_detail_id)->update(array('assigned_supplier' => $sup_company_id, 'supplier_name' => $row->sup_company_name ));
		   	   return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		   } catch (\Exception $e) {
		   	   return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		   }
		}
		//only show 公开项目
		$rows = Project::join('project_detail','project_detail.proj_id','=','project.proj_id')
		->where(array('project.company_id' => $company_id,'property' => 1))
		->select('project.proj_code','project.proj_name','project.proj_manager','project_detail.*')->paginate(PAGEROWS);
		
		$suppliers = Supplier::join('company','company.company_id','=','supplier.sup_company_id')->
		leftJoin('city','city.city_id','=','company.city')->where('supplier.company_id',$company_id)->paginate(PAGEROWS, ['*'], 'supplierPage');
		$salt = $company_id.$this->salt.$uid;
	 	
		return view('dailywork.distribute-project',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'suppliers' => $suppliers, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
 
	/*
	public function acceptProject(Request $request,Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
			
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.accept_project');
	 
		if($request->has('submit')) {
			 
			$project_detail_id = $request->get('project_detail_id');
			$parent_proj_id = $request->get('proj_id');
			$customer_name = $request->get('upper_company_name');
			$customer_id = $request->get('upper_company_id');
			$proj_name = $request->get('part_name');
			$start_date = $request->get('start_date');
			$end_date = $request->get('end_date');
			$calendar_id = $request->get('calendar_id');
			DB::beginTransaction();
			try {
				//1. 更改项目接受标志
				ProjectDetail::where('id',$project_detail_id)->update(array('supplier_accepted' => 1 )); 
				//2. 为本公司创建一个项目和零件框架但不包括计划框架；创建时带上相关上游公司项目信息，以便打通上下游项目之间的联系
			    $json = InitController::getAndInitProjectCoding();
			    $proj = json_decode($json);
			    //calendar_id need to be set
				$proj_id = Project::create(array('proj_code' => $proj->proj_code, 'proj_unicode' => $proj->proj_unicode,
						'customer_name' => $customer_name, 'customer_id' => $customer_id, 'company_id' => $company_id,
						'proj_name' => $proj_name, 'parent_proj_id' => $parent_proj_id, 'parent_part_id' => $project_detail_id,
						'start_date' => $start_date, 'end_date' => $end_date, 'proj_status' => 0, 'calendar_id' => $calendar_id))->proj_id;
				
				$detail = ProjectDetail::where('id',$project_detail_id)->first();
				ProjectDetail::create(array( 'proj_id' => $proj_id,'proj_code' => $proj->proj_code, 'part_code' => $detail->part_code, 
						'part_name' => $detail->part_name, 'part_type' => $detail->part_type, 'quantity' => $detail->quantity, 'note' => $detail->note,
						'jig' => $detail->jig, 'gauge' => $detail->gauge, 'mold' => $detail->mold, 'processing' => $detail->processing, 
						'part_from' => $customer_name , 'material' => $detail->material, 'mat_size' => $detail->mat_size, 'shrink' => $detail->shrink,
						'surface' => $detail->surface, 'part_size' => $detail->part_size, 'weight' => $detail->weight, 'company_id' => $company_id));
				DB::commit();
				
				return json_encode(array('1' => "$project_detail_id"));
			} catch (\Exception $e) {
				DB::rollback();
				return json_encode(array('1' => '0'));
			}
		}
		//projects that other companies assigned to my company  
		$rows = ProjectDetail::join('project','project.proj_id','=','project_detail.proj_id')->join('company','company.company_id','=','project.company_id')
		        ->join('plan','plan.project_detail_id','=','project_detail.id')->where(array('assigned_supplier' => $company_id))
		        ->select('project.proj_code','project.proj_name','project.proj_manager','project_detail.*','company.company_name as upper_company',
		        'plan.company_id as upper_company_id', 'plan.start_date','plan.end_date')->paginate(PAGEROWS);
	      
		$calendars = WorkCalendarReal::where(array('company_id' => $company_id, 'cal_year' => date('Y')))->get(); 
		$salt = $company_id.$this->salt.$uid;
		 
		return view('dailywork.accept-project',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'calendars' => $calendars, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	*/
	
	public function acceptProject(Request $request,Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
			
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.accept_project');
	
		if($request->has('submit')) {
			$parent_proj_id = $request->get('proj_id');
			$project_detail_id = $request->get('project_detail_id');
			$plan_task_id = $request->get('plan_task_id');
			$customer_name = $request->get('upper_company_name');
			$customer_id = $request->get('upper_company_id');
			$proj_name = $request->get('part_name');
			$start_date = $request->get('start_date');
			$end_date = $request->get('end_date');
			 
			DB::beginTransaction();
			try {
				//1. 更改项目接受标志
				PlanTask::where('task_id',$plan_task_id)->update(array('supplier_accepted' => 1 ));
				//2. 为本公司创建一个项目和零件框架但不包括计划框架；创建时带上相关上游公司项目信息，以便打通上下游项目之间的联系
				$json = InitController::getAndInitProjectCoding();
				$proj = json_decode($json);
				//calendar_id need to be set; parent_proj_id, parent_part_id, parent_plan_task_id,用来做项目及节点进度之间的关联链接
				$proj_id = Project::create(array('proj_code' => $proj->proj_code, 'proj_unicode' => $proj->proj_unicode,
						'customer_name' => $customer_name, 'customer_id' => $customer_id, 'company_id' => $company_id,
						'proj_name' => $proj_name, 'parent_proj_id' => $parent_proj_id, 'parent_part_id' => $project_detail_id,
						'parent_plan_task_id' => $plan_task_id,	'start_date' => $start_date, 'end_date' => $end_date,
						'proj_status' => 0, 'calendar_id' => 0))->proj_id;
	
				$detail = ProjectDetail::where('id',$project_detail_id)->first();
				ProjectDetail::create(array( 'proj_id' => $proj_id,'proj_code' => $proj->proj_code, 'part_code' => $detail->part_code,
						'part_name' => $detail->part_name, 'part_type' => $detail->part_type, 'quantity' => $detail->quantity, 'note' => $detail->note,
						'jig' => $detail->jig, 'gauge' => $detail->gauge, 'mold' => $detail->mold, 'processing' => $detail->processing,
						'part_from' => $customer_name , 'material' => $detail->material, 'mat_size' => $detail->mat_size, 'shrink' => $detail->shrink,
						'surface' => $detail->surface, 'part_size' => $detail->part_size, 'weight' => $detail->weight, 'company_id' => $company_id));
				DB::commit();
	
				return json_encode(array('1' => "$plan_task_id"));
			} catch (\Exception $e) {
				DB::rollback();
				return json_encode(array('1' => '0'));
			}
		}
		//projects that other companies assigned to my company
		$rows = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')
		->join('project_detail','project_detail.id','=', 'plan.project_detail_id')
		->join('company','company.company_id','=','plan_task.company_id')->where(array('supplier_id' => $company_id))
		->select('plan_task.*','company.company_name as customer','project.proj_name','project.proj_manager',
				'project_detail.part_name','project_detail.quantity','plan.project_id','plan.project_detail_id')->paginate(PAGEROWS);
		 
		$calendars = WorkCalendarReal::where(array('company_id' => $company_id, 'cal_year' => date('Y')))->get();
		$salt = $company_id.$this->salt.$uid;
		 
		return view('dailywork.accept-project',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'calendars' => $calendars, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
}
