<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
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
use App\Models\Sysconfig;
use App\Models\UserCompany;
use App\Models\Project;
use App\Models\Customer;
use App\Models\WorkCalendar;
use App\Models\ProjectDetail;
use App\Models\Plan;
use App\Models\Supplier;
 
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
    
    //创建项目
	public function setupProject(Request $request, Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');

		$companyid = Session::get('USERINFO')->companyId;
		if(!empty($companyid)){
			$result['customer'] = Customer::getCustomerlistProject($companyid);//客户
			$result['company_user'] = User::info_companyuser($companyid);//公司成员
			$result['calendar'] = WorkCalendar::list_calendar($companyid);//项目日历
		}
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_setup');
		return view('dailywork.setup-project',array('cookieTrail' => $cookieTrail,  'result' => $result, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

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
		$str_name = '';
		$ary_pic = array();

		//项目信息
		$result_project = Project::info_project(array('proj_id'=>$project_id,'company_id'=>$companyid),$field);
		if(empty($result_project)){ 
			//return Redirect::to('/'); 
		}
		if(!empty($companyid)){
			$result['customer'] = Customer::getCustomerlistProject($companyid);//客户
			$result['company_user'] = User::info_companyuser($companyid);//公司成员
			$result['calendar'] = WorkCalendar::list_calendar($companyid);//项目日历
		}
		//项目成员
		if(!empty($result_project->member_list)){
			$ary_member = explode(',',$result_project->member_list);
			foreach ($ary_member as $key => $value) {
				$user = User::info_user(array('uid'=>$value),'fullname');
				$str_name = $str_name.$user->fullname.' ';
			}
			$result_project->member_list_ary = $ary_member;
			$result_project->member_list_name = mb_substr($str_name,0,-1);
		}
		//项目日历
		if(!empty($result_project->calendar_id)){
			$res_cal = WorkCalendar::info_calendar(array('cal_id'=>$result_project->calendar_id),'cal_name');
			$result_project->cal_name = $res_cal->cal_name;
		}
		//文件
		if(!empty($result_project->work_file)){
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
		}
		//零件信息
		$result_detail = ProjectDetail::list_detail(array('proj_id'=>$project_id,'company_id'=>$companyid),$field);
		//计划信息
		$result_plan = Plan::list_plan(array('project_id'=>$project_id,'company_id'=>$companyid),$field);
		$result['result_project']=$result_project;
		$result['result_detail']=$result_detail;
		$result['result_plan']=$result_plan;
		return $result;
	}

	//项目详情
	public function showProject(Request $request,Response $response)
	{ 
		$result = self::dataProject($request->get('project_id'));
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


	//编辑项目
	public function editProject(Request $request,Response $response)
	{ 
		$result = self::dataProject($request->get('project_id'));
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.edit_project');
		return view('dailywork.edit-project',
			array(
			'cookieTrail' => $cookieTrail,  
			'result' => $result, 
			'pageTitle' => Lang::get('mowork.dashboard'),
			'locale' => $this->locale
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
		$affect = Project::update_project($ary_approval,array('proj_id'=>$request->get('projectid'),'approval_status'=>0));
		if(!empty($affect)){ 
			return response()->json(array('code'=>1,'msg'=>LANG::get('mowork.approval_success')));
		}else{ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.approval_fail')));
		}

	}

	//保存项目
	public function saveProject(Request $request, Response $response)
	{ 
		$project_id = 0;
		$detail_id = 0;
		$str_pic = '';

		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
		//项目编号是否有重复
		$result = Project::exit_project($request->get('project_number'));
		if(!empty($result)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.project_number_exit')));
		}
		//项目类别是否是012
		$category = $request->get('project_category');
		if(!in_array($category,[1,2,3])){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.category_notexit')));
		}
		//项目性质不属于01
		$category = $request->get('project_plan');
		if(!in_array($category,[0,1])){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.nature_notinscope')));
		}
		//项目经理不在公司中
		$company_user = UserCompany::userincompany($request->get('proj_manager_uid'),$companyid);
		if(empty($company_user)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.manager_notincompany')));
		}else{ 
			$user = User::info_user(['uid'=>$request->get('proj_manager_uid')],'fullname');
			$proj_manager = $user->fullname;
		}
		//有成员不在公司中
		$ary_member = explode(',',$request->get('project_member_value'));
		foreach ($ary_member as $key => $value) {
			$company_user = UserCompany::userincompany($value,$companyid);
			if(empty($company_user)){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.member_notincompany')));
			}
		}
		//客户名称
		$cust_name = Customer::customerName($companyid,$request->get('customer_number'));
		if(empty($company_user)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.company_notwithcustomer')));
		}

		//项目表
		if(!empty($companyid) && !empty($request->get('project_number')))
		{	
			$ary_project = array(
				'proj_code'=>$request->get('project_number'),
				'proj_unicode'=>'',
				'customer_name'=>$cust_name->cust_company_name,//客户名称
				'customer_id'=>$request->get('customer_number'),//客户编号
				'company_id'=>$companyid,//公司ID
				'proj_name'=>$request->get('project_name'),//项目名称
				'description'=>$request->get('project_desction'),//项目描述
				'proj_type'=>$request->get('project_category'),//项目类别
				'start_date'=>$request->get('start_date'),//接受日期
				'end_date'=>$request->get('end_date'),//结束日期
				'approval_status'=>0,//审批状态 0待批
				'proj_status'=>0, //项目状态  0尚未审批
				'property'=>$request->get('project_plan'),//项目性质
				'member_list'=>$request->get('project_member_value'),//项目成员编号
				'process_trail'=>$request->get('validation_date'),//工艺验证日期
				'mold_sample'=>$request->get('sample_date'),//出模样件日期
				'trail_production'=>$request->get('pilot_date'),//试产验证日期
				'batch_production'=>$request->get('production_date'),//批量放产日期
				'proj_manager_uid'=>$request->get('proj_manager_uid'),//项目经理
				'proj_manager'=>$user->fullname,//项目经理全名
				//'member_list'=>$request->get('project_member_value'),//项目成员列表
				'calendar_id'=>$request->get('project_calendar'),//项目日历未完善
				);
			try{
				$project_id = Project::create_project($ary_project);
			}catch(Exception $e){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
			}
		}

		//零件表
		$num_part = $request->get('num_lj');
		if($num_part >= 1 && !empty($project_id)){ 
			for ($i=0; $i < $num_part; $i++) { 
				$ary_part = array(
				'part_code' => $request->get('linjian_'.$i.'_0'),
				'part_name' => $request->get('linjian_'.$i.'_1'),
				'part_type' => $request->get('linjian_'.$i.'_2'),
				'quantity' => $request->get('linjian_'.$i.'_3'),
				'note' => $request->get('linjian_'.$i.'_4'),
				'jig' => $request->get('linjian_'.$i.'_5'),
				'gauge' => $request->get('linjian_'.$i.'_6'),
				'mold' => $request->get('linjian_'.$i.'_7'),
				'processing' => $request->get('linjian_'.$i.'_8'),
				'part_from' => $request->get('linjian_'.$i.'_9'),
				'material' => $request->get('linjian_'.$i.'_10'),
				'mat_size' => $request->get('linjian_'.$i.'_11'),
				'shrink' => $request->get('linjian_'.$i.'_12'),
				'surface' => $request->get('linjian_'.$i.'_13'),
				'part_size' => $request->get('linjian_'.$i.'_14'),
				'weight' => $request->get('linjian_'.$i.'_15'),		
				'proj_id' => $project_id,			
				'proj_code' => $project_id,		
				'company_id'=> $companyid,//公司ID
				'assigned_supplier' => $request->get('part_size'),//指定供应商的公司id
				'supplier_name' => $request->get('part_size'),//指定供应商的公司名称
				);
				try{
					$detail_id = ProjectDetail::create_detail($ary_part);
				}catch(Exception $e){ 
					return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
				}
				if(!empty($detail_id)){ 
					$newary_detail[$i]['detail_id'] = $detail_id;
					$newary_detail[$i]['part_code'] = $request->get('linjian_'.$i.'_0');
				}
			}
		}

		//计划表
		$num_plan = $request->get('num_plan');
		if($num_plan >= 1 && !empty($project_id)){ 
			for ($i=0; $i < $num_plan; $i++) { 
				foreach ($newary_detail as $key_detail => $value_detail) {
					if($request->get('plan_'.$i.'_0') == $value_detail['part_code']){ 
						$ary_part = array(
						'project_id'=>$project_id,//项目ID
						'project_detail_id'=>$value_detail['detail_id'],//零件编号
						'plan_code'=>$request->get('plan_'.$i.'_1'),//计划编号
						'plan_name'=>$request->get('plan_'.$i.'_2'),//计划名称
						'plan_type'=>$request->get('plan_'.$i.'_3'),//计划类型
						'description'=>$request->get('plan_'.$i.'_4'),//计划描述
						'company_id'=>$companyid,
						'start_date'=>$request->get('plan_'.$i.'_5'),
						'end_date'=>$request->get('plan_'.$i.'_6'),
						'part_code'=>$value_detail['part_code'],
						);
						try{
							$plan_id = Plan::create_plan($ary_part);
						}catch(Exception $e){ 
							return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
						}
					}
				}
			}
		}

		//文档上传
		$num_pic = $request->get('num_pic'); 
		if($num_pic >= 1 && !empty($project_id)){ 
			for ($i=0; $i < $num_pic; $i++) {
				$str_pic = $str_pic.$request->get('pic_'.$i).',';
			}			
			$ary_pic = array(
				'work_file'=>mb_substr($str_pic,0,-1)
			);
			try{
				$result_pic = Project::update_project($ary_pic,array('proj_id'=>$project_id));
			}catch(Exception $e){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
			}
		}


		if($project_id > 0 && $detail_id > 0){
			return response()->json(array('code'=>1,'msg'=>LANG::get('mowork.save_success')));
		}else{
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
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
		$project_id = $request->get('project_id');
		//项目id是否存在
		$result = Project::exit_projectid($project_id);
		if(empty($result)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.project_number_notexit')));
		}
		//项目编号是否有重复
		$result = Project::exit_project_other($request->get('project_number'),$project_id);
		if(!empty($result)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.project_number_exit')));
		}
		//项目类别是否是012
		$category = $request->get('project_category');
		if(!in_array($category,[1,2,3])){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.category_notexit')));
		}
		//项目性质不属于01
		$category = $request->get('project_plan');
		if(!in_array($category,[0,1])){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.nature_notinscope')));
		}
		//项目经理不在公司中
		$company_user = UserCompany::userincompany($request->get('proj_manager_uid'),$companyid);
		if(empty($company_user)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.manager_notincompany')));
		}else{ 
			$user = User::info_user(['uid'=>$request->get('proj_manager_uid')],'fullname');
			$proj_manager = $user->fullname;
		}
		//有成员不在公司中
		$ary_member = explode(',',$request->get('project_member_value'));
		foreach ($ary_member as $key => $value) {
			$company_user = UserCompany::userincompany($value,$companyid);
			if(empty($company_user)){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.member_notincompany')));
			}
		}
		//客户名称
		$cust_name = Customer::customerName($companyid,$request->get('customer_number'));
		if(empty($company_user)){ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.company_notwithcustomer')));
		}

		//项目表
		if(!empty($companyid) && !empty($request->get('project_number')))
		{	
			$ary_project = array(
				'proj_code'=>$request->get('project_number'),
				'proj_unicode'=>'',
				'customer_name'=>$cust_name->cust_company_name,//客户名称
				'customer_id'=>$request->get('customer_number'),//客户编号
				'company_id'=>$companyid,//公司ID
				'proj_name'=>$request->get('project_name'),//项目名称
				'description'=>$request->get('project_desction'),//项目描述
				'proj_type'=>$request->get('project_category'),//项目类别
				'start_date'=>$request->get('start_date'),//接受日期
				'end_date'=>$request->get('end_date'),//结束日期
				'approval_status'=>0,//审批状态 0待批
				'proj_status'=>0, //项目状态  0尚未审批
				'property'=>$request->get('project_plan'),//项目性质
				'member_list'=>$request->get('project_member_value'),//项目成员编号
				'process_trail'=>$request->get('validation_date'),//工艺验证日期
				'mold_sample'=>$request->get('sample_date'),//出模样件日期
				'trail_production'=>$request->get('pilot_date'),//试产验证日期
				'batch_production'=>$request->get('production_date'),//批量放产日期
				'proj_manager_uid'=>$request->get('proj_manager_uid'),//项目经理
				'proj_manager'=>$user->fullname,//项目经理全名
				//'member_list'=>$request->get('project_member_value'),//项目成员列表
				'calendar_id'=>$request->get('project_calendar'),//项目日历未完善
				);
			try{
				$project_affect = Project::update_project($ary_project,array('proj_id'=>$request->get('project_id')));
			}catch(Exception $e){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
			}
		}

		//零件表
		$str_partcode = $request->get('part_delete');
		if(!empty($str_partcode)){ 
			$str_partcode = mb_substr($str_partcode,0,-1);
			$ary_partcode = explode(',',$str_partcode);
			ProjectDetail::delete_bypartcoded($ary_partcode);
		}

		$num_part = $request->get('num_lj');
		if($num_part >= 1 && !empty($project_affect)){ 
			for ($i=0; $i < $num_part; $i++) { 
				//有plan_id则为已有plan
				$hasdetail_id = $request->get('linjian_'.$i.'_17');
				$ary_part = array(
				'part_code' => $request->get('linjian_'.$i.'_0'),
				'part_name' => $request->get('linjian_'.$i.'_1'),
				'part_type' => $request->get('linjian_'.$i.'_2'),
				'quantity' => $request->get('linjian_'.$i.'_3'),
				'note' => $request->get('linjian_'.$i.'_4'),
				'jig' => $request->get('linjian_'.$i.'_5'),
				'gauge' => $request->get('linjian_'.$i.'_6'),
				'mold' => $request->get('linjian_'.$i.'_7'),
				'processing' => $request->get('linjian_'.$i.'_8'),
				'part_from' => $request->get('linjian_'.$i.'_9'),
				'material' => $request->get('linjian_'.$i.'_10'),
				'mat_size' => $request->get('linjian_'.$i.'_11'),
				'shrink' => $request->get('linjian_'.$i.'_12'),
				'surface' => $request->get('linjian_'.$i.'_13'),
				'part_size' => $request->get('linjian_'.$i.'_14'),
				'weight' => $request->get('linjian_'.$i.'_15'),		
				'proj_id' => $project_id,			
				'proj_code' => $project_id,		
				'company_id'=> $companyid,//公司ID
				'assigned_supplier' => $request->get('part_size'),//指定供应商的公司id
				'supplier_name' => $request->get('part_size'),//指定供应商的公司名称
				);
				try{
					if(!empty($hasdetail_id)){ 
						$detail_id = ProjectDetail::update_detail($ary_part,array('id'=>$hasdetail_id));
					}else{
						$detail_id = ProjectDetail::create_detail($ary_part);
					}
				}catch(Exception $e){ 
					return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
				}
				if(!empty($detail_id)){ 
					$newary_detail[$i]['detail_id'] = $detail_id;
					$newary_detail[$i]['part_code'] = $request->get('linjian_'.$i.'_0');
				}
			}
		}

		//计划表		
		$str_plancode = $request->get('plan_delete');
		if(!empty($str_plancode)){ 
			$str_plancode = mb_substr($str_plancode,0,-1);
			$ary_plancode = explode(',',$str_plancode);
			Plan::delete_bypartcoded($ary_partcode);
		}
		$num_plan = $request->get('num_plan');
		if($num_plan >= 1 && !empty($project_affect)){ 
			for ($i=0; $i < $num_plan; $i++) { 
				foreach ($newary_detail as $key_detail => $value_detail) {
					if($request->get('plan_'.$i.'_0') == $value_detail['part_code']){ 

						$hasplan_id = $request->get('plan_'.$i.'_8');
						$ary_part = array(
						'project_id'=>$project_id,//项目ID
						'project_detail_id'=>$value_detail['detail_id'],//零件编号
						'plan_code'=>$request->get('plan_'.$i.'_1'),//计划编号
						'plan_name'=>$request->get('plan_'.$i.'_2'),//计划名称
						'plan_type'=>$request->get('plan_'.$i.'_3'),//计划类型
						'description'=>$request->get('plan_'.$i.'_4'),//计划描述
						'company_id'=>$companyid,
						'start_date'=>$request->get('plan_'.$i.'_5'),
						'end_date'=>$request->get('plan_'.$i.'_6'),
						'part_code'=>$value_detail['part_code'],
						);
						try{
							if(!empty($hasplan_id)){
								$plan_id = Plan::update_plan($ary_part,array('plan_id'=>$hasplan_id));
							}else{ 
								$plan_id = Plan::create_plan($ary_part);								
							}
						}catch(Exception $e){ 
							return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
						}
					}
				}
			}
		}

		//文档上传
		$num_pic = $request->get('num_pic'); 
		if($num_pic >= 1 && !empty($project_id)){ 
			for ($i=0; $i < $num_pic; $i++) {
				$str_pic = $str_pic.$request->get('pic_'.$i).',';
			}			
			$ary_pic = array(
				'work_file'=>mb_substr($str_pic,0,-1)
			);
			try{
				$result_pic = Project::update_project($ary_pic,array('proj_id'=>$project_id));
			}catch(Exception $e){ 
				return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
			}
		}

		if($project_affect > 0){
			return response()->json(array('code'=>1,'msg'=>LANG::get('mowork.save_success')));
		}else{
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.save_fail')));
		}

	}


	
	//审批列表
	public function approveProject(Request $request, Response $response)
	{
	
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
		if(!empty($companyid)){
			$result = Project::list_approvalproject($companyid,0);
		}else{ 
			$result = '';
		}
		if(!empty($result)){ 
			$res_cal = '';
			foreach ($result as $key => $value) {
				$res_cal = WorkCalendar::info_calendar(array('cal_id'=>$value->calendar_id),'cal_name');
				$result[$key]['cal_name'] = $res_cal->cal_name;
			}
		}

		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.project_approval');
		return view('dailywork.approve-project',array('cookieTrail' => $cookieTrail, 'result' => $result, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	//项目列表
	public function listProject(Request $request, Response $response)
	{
	
		$companyid = Session::get('USERINFO')->companyId;
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;
		if(!empty($companyid)){
			$result = Project::list_project($companyid);
		}else{ 
			$result = '';
		}
		if(!empty($result)){ 
			$res_cal = '';
			foreach ($result as $key => $value) {
				$res_cal = WorkCalendar::info_calendar(array('cal_id'=>$value->calendar_id),'cal_name');
				$result[$key]['cal_name'] = $res_cal->cal_name;
			}
		}
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_list');
		return view('dailywork.list-project',array('cookieTrail' => $cookieTrail, 'result' => $result, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}

	//删除项目
	public function deleteProject(Request $request,Response $response)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$companyid = Session::get('USERINFO')->companyId;

		$project_id = $request->get('id');
		if(!empty($project_id)){
			$project_id = mb_substr($project_id,0,-1);
			$ary_id = explode(',',$project_id);
			//公司与项目是否对应
			foreach ($ary_id as $key => $value) {
				$result = Project::info_project(array('company_id'=>$companyid,'proj_id'=>$value));
				if(empty($result)){ 
					return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.nopermission_delete')));
				}
			}
			//删除项目
			$affect_project = Project::delete_project_ary($ary_id);
			//删除零件
			$affect_detail = ProjectDetail::delete_detail_ary($ary_id);

		}else{ 
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.delete_fail')));
		}

		if(!empty($affect_project)){
			return response()->json(array('code'=>1,'msg'=>LANG::get('mowork.delete_success')));
		}else{
			return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.delete_fail')));
		}

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
		$rows = project::join('project_detail','project_detail.proj_id','=','project.proj_id')
		->where(array('project.company_id' => $company_id,'property' => 1))
		->select('project.proj_code','project.proj_name','project.proj_manager','project_detail.*')->paginate(PAGEROWS);
		
		$suppliers = Supplier::join('company','company.company_id','=','supplier.sup_company_id')->
		leftJoin('city','city.city_id','=','company.city')->where('supplier.company_id',$company_id)->paginate(PAGEROWS, ['*'], 'supplierPage');
		$salt = $company_id.$this->salt.$uid;
	 	
		return view('dailywork.distribute-project',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'suppliers' => $suppliers, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
 
	public function acceptProject(Request $request,Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
			
		$cookieTrail = Lang::get('mowork.project_management').' &raquo; '.Lang::get('mowork.accept_project');
	
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
		$rows = project::join('project_detail','project_detail.proj_id','=','project.proj_id')
		->where(array('project.company_id' => $company_id,'property' => 1))
		->select('project.proj_code','project.proj_name','project.proj_manager','project_detail.*')->paginate(PAGEROWS);
	
		$suppliers = Supplier::join('company','company.company_id','=','supplier.sup_company_id')->
		leftJoin('city','city.city_id','=','company.city')->where('supplier.company_id',$company_id)->paginate(PAGEROWS, ['*'], 'supplierPage');
		$salt = $company_id.$this->salt.$uid;
		 
		return view('dailywork.accept-project',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'suppliers' => $suppliers, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
}
