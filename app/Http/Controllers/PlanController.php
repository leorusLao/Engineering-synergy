<?php

namespace App\Http\Controllers;
use App;
  
use Session;
use DB;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\Models\Task;
use App\Models\TaskLink;
use App\Models\Template;
use App\Models\Plan;
use App\Models\PlanTask;
use App\Models\PlanTaskLink;
use App\Models\PlanType;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\WorkCalendarBase;
use App\Models\WorkCalendarReal;
use App\Models\NodeCompany;
use App\Models\UserCompany;
use App\Models\PlanTaskEvent;
use App\Models\Department;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\Approver;
use App\Models\Supplier;
use App\Events\PlanMade;
use App\Events\PlanApproved;
use App\Events\TableRowChanged;
use App\Models\ProjectType;
use App\Models\User;
  
class PlanController extends Controller {
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
		session_cache_limiter(false); //let page no expiration after post data
	}

	public function projectPlan(Request $request)
	{
		//list project plans
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		if($request->has('submit')) {
			$project_detail_id = $request->get('proj_detail_id');
			$proj = ProjectDetail::where('id',$project_detail_id)->first();
			 
			$member_list = '';
			if($request->has('people')) {
			
				$people = $request->get ( 'people' );
				foreach ( $people as $user_id ) {
					$member_list .= $user_id . ',';
				}
				$member_list = rtrim ( $member_list, ',' );
			}
		 	 
			if($request->get('plan_id') > 0 ) {//update plan master
				Plan::where(array('plan_id' => $request->get('plan_id'), 'company_id' => $company_id))->update(
				array('plan_code' => $request->get('plan_code'),'plan_name' => $request->get('plan_name'),
					'plan_type' => $request->get('plan_type'), 'description' => $request->get('plan_des'),
					'project_id' => $proj->proj_id,'project_detail_id' => $project_detail_id,
					'start_date' => $request->get('start_date'), 'end_date' => $request->get('end_date'),
					'leader' => $request->get('leader'), 'member' => $member_list
				));
			} else { //create plan
				Plan::create(array('plan_code' => $request->get('plan_code'),'plan_name' => $request->get('plan_name'),
								'plan_type' => $request->get('plan_type'), 'description' => $request->get('plan_des'),
								'project_id' => $proj->proj_id,'project_detail_id' => $project_detail_id,
								'start_date' => $request->get('start_date'), 'end_date' => $request->get('end_date'),
								'leader' => $request->get('leader'), 'member' => $member_list, 'status' => 1 ));
			}
		} else if($request->has('search')) {
			//search button submitted
			$qtext = trim($request->get('qtext'));
			if ($qtext) {
				 
				$rows = ProjectDetail::join('project','project.proj_id','=','project_detail.proj_id')
				->leftJoin('plan','plan.project_detail_id','=','project_detail.id')
				->join('work_cal','work_cal.cal_id','=','project.calendar_id')
				->select('project.*','project_detail.*','work_cal.*','plan.plan_id','plan.plan_type','plan.plan_code','plan.plan_name',
						'plan.status', 'plan.leader')
						->where('plan.company_id',$company_id) 
						->whereRaw("(proj_name like '%$qtext%' OR  project.proj_code like '%$qtext%'
						   OR customer_name like '%$qtext%' OR part_name like '%$qtext%'
						   OR plan_name like '%$qtext%' OR plan_code like '%$qtext%')")
						->orderBy('project.proj_id','desc')
						->paginate(PAGEROWS);
			}
		}
		$cookieTrail = Lang::get('mowork.plan_list');
		
		if(!isset($rows)) {
			$rows = ProjectDetail::join('project','project.proj_id','=','project_detail.proj_id')
			->leftJoin('plan','plan.project_detail_id','=','project_detail.id')
			->join('work_cal','work_cal.cal_id','=','project.calendar_id')
			->select('project.*','project_detail.*','work_cal.*','plan.plan_id','plan.plan_type','plan.plan_code','plan.plan_name',
				'plan.status', 'plan.leader')
			->where('project_detail.company_id',$company_id)->orderBy('project.proj_id','desc')
	 		->paginate(PAGEROWS);
		}
		
		$salt = $company_id.$this->salt.$uid;
		$plantypes = PlanType::whereRaw('company_id = 0 OR company_id = '. $company_id )->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		
		return view('dailywork.project-plan',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 
				'plantypes' => $plantypes, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function editPlanMaster(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
 
		if($request->has('submit')) {
			$proj_detail_id = $request->get('proj_detail_id');
			$plan_id = $request->get('plan_id');
			//$row = Plan::where(array('plan.plan_id' => $plan_id, 'plan.company_id' => $company_id))->first();//Json error why?
			$row = ProjectDetail::where(array('id' => $proj_detail_id, 'project_detail.company_id' => $company_id,'plan.plan_id' =>$plan_id ))->join('plan',
					'plan.project_detail_id', 'project_detail.id')->select('plan.*')->first();
	        
			if($row) {
					
				$res = array('1' => $row->plan_code, '2' => $row->plan_name, '3' => $row->plan_type, '4' => $row->description,
						'5' => $row->leader? $row->leader:'', '6' => $row->member? $row->member: '',
						'7' => $row->start_date ? $row->start_date:'', '8' => $row->end_date?$row->end_date:'', '9' => $row->plan_id);
			}
			else {//had no plan master info yet
				 
				$planCoding = InitController::getAndInitProductPlanCoding();
				$planCoding = json_decode($planCoding);
				//create plan master first; then submit to update；创建计划主信息
				$projd = ProjectDetail::where(array('id' => $proj_detail_id))->first();
				$plan_id = Plan::create(array('plan_code' => $planCoding->plan_code, 'plan_unicode' => $planCoding->plan_unicode,
						'project_id' => $projd->proj_id, 'project_detail_id' => $proj_detail_id, 'company_id' => $company_id))->plan_id;
				 
				$res = array('1' => $planCoding->plan_code, '2' => "", '3' => "", '4' => "",
						'5' => "", '6' => "", '7' => "", '8' => "", '9' => "$plan_id");
			 
			}
			 
			return json_encode($res,JSON_UNESCAPED_UNICODE);
		}
	}
	
	public function projectView(Request $request, $token, $proj_id)
	{
		//view a project details
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$salt = $company_id.$this->salt.$uid.$proj_id;
		$cmpToken = hash('sha256',$salt);
		
		if($cmpToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		$row = Project::where(array('proj_id' => $proj_id, 'company_id' => $company_id))->first();
		$cookieTrail = Lang::get('mowork.plan_list') . ' &raquo; '.Lang::get('mowork.project_view');
		$parts = ProjectDetail::where('proj_id',$proj_id)->orderBy('id', 'asc')->get();
		return view('dailywork.project-view',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'parts' => $parts, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function makePlan(Request $request, $token, $plan_id)
	{
		//make gantt chart for a plan
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		$tmplPage = '';
		$planPage = '';
		if($request->has('tmplPage')){
		  $tmplPage = $request->get('tmplPage');	
		}
		if($request->has('planPage')){
			$tmplPage = $request->get('planPage');
		}
		
		if($request->has('submit')) {
			$ref_type = $request->get('reference');
			$basedate = $request->get('basedate');
			$year = intval(substr($basedate,0,4));
			$month = intval(substr($basedate, 5,2));
			$day = intval(substr($basedate, 8,2));
			if(! checkdate($month, $day, $year)){
				return Redirect::back()->with('result', Lang::get('mowork.basedate_error'));
			}
			
			$reference = $request->get('cbx')[0];
		 
			$theplan = Plan::where('plan_id', $plan_id)->first();
			if($theplan->status == 4 || $theplan->status == 5) {//以前同意或不同意的项目计划，重新进入待批
				Plan::where('plan_id', $plan_id)->update(array('status' => 3));
			} else {
				Plan::where('plan_id', $plan_id)->update(array('status' => 2));
			}
			if($ref_type == 1) {
				self::copyPlanTemplate($reference, $plan_id, $basedate);
			}
	  		 
		}
	   
		$row = Plan::where('plan_id', $plan_id)->join('project','project.proj_id','=','plan.project_id')->select('plan.*','project.calendar_id')->first();
		$tmplts = Template::whereRaw('company_id =' . $company_id . ' OR company_id = 0')->paginate(PAGEROWS, ['*'], 'tmplPage');//reference templates: 参考模板
		$refplans = Plan::where(array('company_id' => $company_id, 'status' => 4))->paginate(PAGEROWS, ['*'], 'planPage');//reference plans: 参考计划
		$refs = count($tmplts);
		$refps = count($refplans);
		
		$cookieTrail =  Lang::get('mowork.plan_control').' &raquo; '.Lang::get('mowork.make_plan');
		$task = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		$departments = Department::where('company_id',$company_id)->get();
		$nodetypes = NodeType::whereRaw('company_id='.$company_id)->get();
		$suppliers = Supplier::join('company','company.company_id','=','supplier.sup_company_id')
		->where('supplier.company_id',$company_id)->get();
		
		$cal_id = $row->calendar_id;
		//检查公司日历是否制作
		$cal = WorkCalendarReal::where(array('cal_year' => date('Y'), 'cal_id' => $cal_id, 'company_id' => $company_id))->first();
	 	 
		$cal_warning = Lang::get('mowork.cal_warning');

		return view('dailywork.make-plan',array('cookieTrail' => $cookieTrail,'row' => $row, 'suppliers' => $suppliers, 
				'departments' => $departments, 'employees' => $employees, 'nodetypes' => $nodetypes, 'tmplPage' => $tmplPage, 
				'planPage' => $planPage, 'tmplts' => $tmplts, 'refs' => $refs, 'refplans' => $refplans, 'refps' => $refps, 'task'  => $task, 'token' => $token, 'plan_id' => $plan_id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	 
	public function viewPlanChart(Request $request, $token, $plan_id)
	{
		//plan chart or gantt chart or plan nodes or plan details
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		$cookieTrail =  Lang::get('mowork.view_plan');
		$row = Plan::where('plan_id', $plan_id)->first();
		$task = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		return view('dailywork.view-plan-chart',array('cookieTrail' => $cookieTrail, 'row' => $row, 'task'  => $task, 'token' => $token, 'plan_id' => $plan_id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function appViewPlan(Request $request, $token, $company_id, $uid, $plan_id)
	{
		//plan chart or gantt chart or plan nodes or plan details
		$row = User::where('uid', $uid)->first();
	    
		if(!$row) {
			$header =<<<EOD
			!DOCTYPE html>
			<html lang="zh-CN">
			<head>
			<meta charset="UTF-8">
		
			<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
			<head>
EOD;
			die(Lang::get('mowork.user_noexist'));
		}
		$compToken = $row->api_token;
	 
		if($compToken != $token) {
			$header =<<<EOD
			!DOCTYPE html>
			<html lang="zh-CN">
			<head>
			<meta charset="UTF-8">
			
			<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
			<head>
EOD;
			die(Lang::get('mowork.faked_token'));
		}
	
		$cookieTrail =  Lang::get('mowork.view_plan');
		$row = Plan::where('plan_id', $plan_id)->first();
		$task = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		return view('dailywork.app-view-plan',array('cookieTrail' => $cookieTrail, 'row' => $row, 'task'  => $task, 'token' => $token, 'plan_id' => $plan_id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function viewTaskBar(Request $request, $token, $task_id)
	{
		//Ref planTaskEdit(Request $request, $token, $plan_id): here view only, disable editing
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','plan.project_id')->where('task_id', $task_id)->select('plan_task.*','project.calendar_id')->first();
			
		if (!$row) {
			die("Not found");
		} else {
			$start_date = $row->start_date;
			$end_date = $row->end_date;
			$cal_id = $row->calendar_id;
			$dayoffs = self::calculateDayoffs($start_date, $end_date, $cal_id, $company_id);
		}
			
		
		$isparent = PlanTask::where('parent_id',$task_id)->count();
		
		return view('dailywork.view-task-bar',array( 'row' => $row, 'token' => $token, 'id' => $task_id, 'isparent' => $isparent, 'dayoffs' => $dayoffs, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function pausePlan(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
			Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 6));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.db_error'));
		}
	}
	
	public function resumePlan(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 0));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.db_error'));
		}
	}
	
	public function completePlan(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 10));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.db_error'));
		}
	}
	
	public function antiCompletePlan(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 0));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.db_error'));
		}
	}
	
	public function planApproval(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_approval');
			
		$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('work_cal','work_cal.cal_id','=','project.calendar_id')
		->join('plan_type','plan_type.type_id','plan.plan_type')
		->join('project_type','project_type.type_id','project.proj_type')
		->select('project.proj_id','project.proj_code','project.customer_id','project.customer_name',
				'project.proj_name','project_type.name as proj_type','project.start_date',
				'plan.plan_id','plan_type.type_name as plan_type',
				'plan.plan_code','plan.plan_name','project.proj_manager','plan.status')
		->whereRaw('plan.company_id ='. $company_id .' AND (plan.status =  2 OR plan.status =  3  OR plan.status = 5)')->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-approval',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	  
	public function planApprovalHandin(Request $request, $token, $plan_id)
	{//handin plan for approval: change status of plan
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_approval');
		 
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		 
		if($compToken != $token) {
			return Redirect::to('/dashboard/plan-approval')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		 
		try {
		   Plan::where('plan_id',$plan_id)->update(array('status' => 3));
		   //fire event PlanMade to advise manager to approve plan(plan tasks)
		   $res = Plan::where('plan_id',$plan_id)->first();
		   //$approver = Approver::where('company_id', $company_id)->first();
		   
		   event(new PlanMade(array('0' => $uid), $plan_id, $source_type = 2, $res->plan_name, $subject = Lang::get('mowork.plan_made_subject'),
		   		$content = '',	$attachement = '',$status = 0 ,$site_message = 1, $small_routine = 1, $email = 1, $res->sms = 0, $company_id));
		 
		  	return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
	
	}
	
	public function planApprovalStamp(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_approval');
			
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/plan-approval')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')){
			 
	       if($request->get('submit') == Lang::get('mowork.agree')){
				$status = 1;//同意
			} else {
				$status = 2;//不同意
			}
		 
			$res = Plan::join('project','project.proj_id','=','plan.project_id')->where('plan_id',$plan_id)->first();
		    if($status ==1){
		    	//批准后：通知项目成员
		    	$people = $res->proj_manager_uid.','.$res->member_list;
		    	$people = explode(',',$people);
		    	$membertList = array_unique(array_filter($people)); 
		       	event(new PlanApproved($membertList, $res->plan_id, $res->plan_code, $res->plan_name ? $res->plan_name:$res->plan_code, 
		    			Lang::get('mowork.plan_approved'),1, 0, 1, 0, $company_id));
				
				Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 4, 
						'approval_comment' => $request->get('comment')?$request->get('comment'):'', 'approval_date' => date('Y-m-d')));
				//所有子节点设置为同意
				PlanTask::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 1));
			} else {//不批准：只通知项目经理
		    	$people = array(0 => $res->proj_manager_uid);
		    	$membertList = array_filter($people);
		    	event(new PlanApproved($membertList, $res->plan_id, $res->plan_code, $res->plan_name,
		    			Lang::get('mowork.plan_disapproved'),1, 0, 1, 0, $company_id));
		    	Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 5, 
		    			'approval_comment' => $request->get('comment')?$request->get('comment'):'', 'approval_date' => date('Y-m-d')));
		    	//所有子节点设置为不同意
		    	PlanTask::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 2));
		    }
		    return Redirect::back()->with('result',Lang::get('mowork.operaion_success'));
		}
		$departments = Department::where('company_id',$company_id)->get();
		$projtypes = ProjectType::whereRaw('company_id =0 OR company_id = '.$company_id)->get();
		$plantypes = PlanType::whereRaw('company_id =0 OR company_id = '.$company_id)->get();
		$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
		->where('plan.plan_id', $plan_id)->paginate(PAGEROWS);
		$binfo = plan::join('project','project.proj_id','=','plan.project_id')->where('plan.plan_id', $plan_id)->first();
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-approval-stamp',array('cookieTrail' => $cookieTrail,'plan_id' => $plan_id, 'rows' => $rows, 'token' => $token, 'binfo' => $binfo,
				'salt' => $salt, 'departments' => $departments, 'projtypes' => $projtypes, 'plantypes' => $plantypes, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	
	public function planTask(Request $request, $token, $plan_id)
	{   
		//if(!Session::has('userId')) return Redirect::to('/');取消为了app view gantt chart
		//得到一个计划的所有子节点-用来生成甘特图
		 
		$rows = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();
 		$result = $this->planTaskList($rows,$plan_id);
		header('Content-Type: application/json');
		 
		return response()->json($result);//echo json_encode($result);
	}
	
	public function planTaskList($items, $plan_id) 
	{
		//if(!Session::has('userId')) return Redirect::to('/');取消为了app view gantt chart
		 
		$result = array();
		
		foreach($items as $item) {
			$t = array();
			$r = (object) $t;
		
			// rows
			$r->id = "$item->task_id";
			//forcefully add qutation mark in order to taskLink can find this matched task id for its from_id and to_id
			$r->text = htmlspecialchars($item['name']);
			$r->start = $item['start_date'];
			$r->end = $item['end_date'];
			$r->complete = $item['complete'];
			if ($item['milestone']) {
				$r->type = 'Milestone';
			}
		
			$parent = $r->id;
		
			$children =  PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();
		
			if (!empty($children)) {
				$r->children = $this->planTaskList($children, $plan_id);
			}
		
			$result[] = $r;
		}
		 
		return $result;
	}
	
	public function planTaskDetail(Request $request, $token, $task_id)
	{
		//display plan task details
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$task_id;
		$cmpToken = hash('sha256',$salt);
		
		if($cmpToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member",
				"project.customer_id","project.customer_name",
				"plan.plan_code","plan.plan_name", "plan.approval_date",
				"plan.plan_type","plan_task.*")->first();
		$approver = Approver::join('user','user.uid','=','approver.plan_uid')->where('company_id',$company_id)->select('fullname')->first();
		$team = PlanTask::join('user','user.uid','=','plan_task.leader')->where('task_id',$task_id)->select('fullname')->first();
		$cookieTrail = Lang::get('mowork.task_view');
		return view('dailywork.plan-task-detail',array('cookieTrail' => $cookieTrail, 'row' => $row, 'approver' => $approver, 'team' => $team,
				'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function planTaskCreate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	 
	 	$plan_id = $request->get('plan_id');
		$ordinal = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->max('ordinal') + 1;
		$now = date('Y-m-d H:i:s');
		$duration = date_diff(date_create($request->get('start')), date_create($request->get('end')));
		$duration = $duration->format("%a");
		$end_date = date('Y-m-d H:i:s', strtotime($request->get('end'). ' - 1 second'));
		$start_date = $request->get('start');
		//initail start_date to the last task's start_date if any
		$row = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','desc')->first();
		if($row) {
			$start_date = substr($row->end_date,0,10) .' 00:00:00';
			$end_date = substr($row->end_date,0,10).' 23:59:59';
		}
		$task_id = PlanTask::create(array('name' => $request->get('name'), 'start_date' => $start_date, 'end_date' => $end_date,
			'duration' => $duration, 'ordinal' => $ordinal, 'ordinal_priority' => $now, 'plan_id' => $plan_id, 'company_id' => $company_id	
		));
	    
		//change plan approval_status from 0 or 4 to 1
		Plan::where(array('plan_id' => $plan_id, 'status' => 1))->orWhere('status', 5)->update(array('status' => 2));
	   
		$request = array();
		$response = (object) $request;
		$response->result = 'OK';
	 
		$response->id = $task_id;
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function planTaskEdit(Request $request, $token, $task_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	   	 
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','plan.project_id')->where('task_id', $task_id)->select('plan_task.*','project.calendar_id')->first();
		 
		if (!$row) {
			die("Not found");
		} else {
			$start_date = $row->start_date;
			$end_date = $row->end_date;
			$cal_id = $row->calendar_id;
			$dayoffs = self::calculateDayoffs($start_date, $end_date, $cal_id, $company_id);
		}
		 
		
	 	$isparent = PlanTask::where('parent_id',$task_id)->count();
	 	 
		return view('dailywork.plan-task-edit',array( 'row' => $row, 'token' => $token, 'id' => $task_id, 'isparent' => $isparent, 'dayoffs' => $dayoffs, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	 
	public function planTaskUpdate1(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$milestone = $request->has("milestone");
		$outsource = $request->has("outsource");
	    $supplier = 0;
	    if($outsource) {
	    	$supplier = $request->get('supplier');
	    }

	    $keynode = $request->has('keynode');
	    $condition =  '';
	    if($keynode) {
	    	$condition = $request->get('condition');
	    }
		//1:审批已经通过的可更改；该节点需要重新审批！TODO: 100%已完成的节点应禁止修改
		$task_id =  $request->get('task_id');
		$node_id = $request->get('node_id');
		$node = NodeCompany::where('node_id',$node_id)->first();

		$leader_list = '';
		if($request->has('people')) {

			$people = $request->get ( 'people' );
			foreach ( $people as $user_id ) {
				$leader_list .= $user_id . ',';
			}
			$leader_list = rtrim ( $leader_list, ',' );
		}

		PlanTask::where('task_id', $task_id)->update(array('name' => $node->name ? $node->name : '', 'node_id' => $node->node_id ? $node->node_id : '',
				'node_no' => $node->node_no ? $node->node_no :'', 'node_type' => $request->get('nodetype'),
				'start_date' => $request->get('start_date') ." 00:00:00", 'end_date' => $request->get('end_date')." 23:59:59", 'milestone' => $milestone,
				'outsource' => $outsource, 'supplier_id' => $supplier, 'key_node' => $keynode, 'key_condition' => $condition,
				'department' => $request->get('department'), 'leader' => $leader_list, 'status' => 0));
		Plan::where('plan_id',$request->get('plan_id'))->update(array('status' => 2));

	    /* for daypilot.js modal using
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);
		*/
		 return Redirect::back();

	}

	public function planTaskUpdate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$update_data = $request->get('update_data');
		$data = json_decode($update_data, true);

		foreach($data as $v)
		{
			$node = NodeCompany::where('node_id',$v['node_id'])->first();
			PlanTask::where('task_id', $v['task_id'])->update([
				'name' => $node->name ? $node->name : '',
				'node_id' => $v['node_id'],
				'node_no' => $node->node_no ? $node->node_no :'',
				'node_type' => $v['nodetype'],
				'start_date' => $v['start_date'] ." 00:00:00",
				'end_date' => $v['end_date']." 23:59:59",
				'milestone' => $v['milestone'],
				'outsource' => $v['outsource'],
				'supplier_id' => $v['supplier'] ? $v['supplier'] : null,
				'key_node' => $v['keynode'],
				'key_condition' => $v['condition'],
				'department' => $v['department'],
				'leader' => $v['people'],
				'status' => 0
			]);
			Plan::where('plan_id',$v['plan_id'])->update(array('status' => 2));
		}
		return Redirect::back();
	}
	
	public function planTaskDelete(Request $request)
	{//删除计划任务节点
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		//删除task_id之前，记录被删该节点的父节点号；如果task_id有子节点，则将task_id所有子节点的父节点设为task_id的父节点
		$row = PlanTask::where(array('task_id' => $request->get('id'), 'plan_id' => $request->get('plan_id')))->first();
	 	PlanTask::where(array('task_id' => $request->get('id'), 'plan_id' => $request->get('plan_id')))->delete();
	 	 
	 	//更改被删除节点的子节点的父节点号，以免悬空
	 	/*
	 	PlanTask::where(array('parent_id' => $row->task_id, 'plan_id' => $request->get('plan_id')))
	 	  ->update(array('parent_id' => $row->parent_id));
	 	*/ 
	 	//删除该节点有关的link指针plan_task_link 如果有的话
	 	PlanTaskLink::whereRaw('from_id = '.$row->task_id .' OR to_id = '.$row->task_id)->delete();
	 	//删除节点的所有子节点
	 	$rows = PlanTask::where(array('parent_id' => $row->task_id, 'plan_id' => $request->get('plan_id')))->get();
	 	PlanTask::where(array('parent_id' => $row->task_id, 'plan_id' => $request->get('plan_id')))->delete();
	 	//删除子节点有关的link指针plan_task_link 如果有的话
	 	foreach($rows as $row) {
	 	  PlanTaskLink::whereRaw('from_id = '.$row->task_id .' OR to_id = '.$row->task_id)->delete();
	 	}
	 	
	 	//检查是否还有任务节点，如果所有节点被删除，原来approval_status=1-已做，草稿，2-已递交，等待批;应该改为0
	 	$plantasks =  PlanTask::where(array('plan_id' => $request->get('plan_id')))->first();
	 	if(!$plantasks) {
	 		Plan::where(array('plan_id' => $request->get('plan_id'), 'status' => 2 ))->orWhere('status',3)
	 				->update(array('status' => 1));
	 	}
	 	//如果是‘未批准 ’,则重新审批
	 	Plan::where(array('plan_id' => $request->get('plan_id'), 'status' => 5 ))->update(array('status' => 2));
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
	 
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function planTaskMove(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		  
	    if($request->has('id')) {
	    	PlanTask::where('task_id', $request->get('id'))->update(array('start_date' => $request->get('start'), 'end_date' => $request->get('end')));
	        //check if this task id has parent task, yes: update parent id respectively
	        $parent = PlanTask::where('task_id', $request->get('id'))->pluck('parent_id')[0];
	        if($parent > 0) {//update parent task info
	        	$min_date = PlanTask::where('parent_id',$parent)->min('start_date');
	        	$max_date = Task::where('parent_id',$parent)->max('end_date');
	        	PlanTask::where('task_id', $parent)->update(array('start_date' => $min_date , 'end_date' => $max_date));
	        }
	    }
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
		
		header('Content-Type: application/json');
		echo json_encode($response);
 	}
	
	public function planTaskRowMove(Request $request)
	{    //Task name button on the left side 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		//file_put_contents('qqqq','task name move ==='. print_r($request->all(),true));
		$source = $request->get('source');
		$target = $request->get('target');
		$plan_id = $request->get('plan_id');
		
		$task_source = PlanTask::where('task_id',$source)->first();
		$task_target = PlanTask::where('task_id',$target)->first();
		
		$source_parent_id = $task_source? $task_source->parent_id : 0;
		$target_parent_id = $task_target? $task_target->parent_id : 0;
		$target_ordinal = $task_target->ordinal;
		$now = date('Y-m-d H:i:s');
		switch ($request->get('position')) {
			case "before":
				PlanTask::where('task_id', $task_source->task_id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal, 'ordinal_priority' => $now));
				break;
			case "after":
			 	PlanTask::where('task_id', $task_source->task_id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal + 1, 'ordinal_priority' => $now));
				break;
			case "child":
				echo "child:source/".$task_source->task_id. "/target/" . $task_target->task_id;
				//db_update_task_parent($source["id"], $target["id"], $max);
				PlanTask::where('id', $task_source->task_id)->update(array('parent_id' => $task_target->task_id, 'ordinal' => 10000, 'ordinal_priority' => $now));//max 10000 nodes or tasks for a certain level in a template
				$target_parent_id = $task_target->task_id;
				break;
			case "forbidden":
				break;
		}
		
		self::compactOrdinals($source_parent_id, $plan_id);
		 
		if ($source_parent_id != $target_parent_id) {
			self::compactOrdinals($target_parent_id, $plan_id);
		}
	 	
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
	
		header('Content-Type: application/json');
		echo json_encode($response);
	
		 
	}
	
	public function planTaskLink(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	    
		$result = array();
		$rows = PlanTaskLink::where('plan_id',$id)->get();
		
		foreach($rows as $item) {
			$t = array();
			$r = (object) $t;
			$r->id = $item->id;
			$r->from =  $item->from_id ;
			$r->to =   $item->to_id;
			$r->type = $item->type;
		
			$result[] = $r;
			//$result[] = array('id' => $item->id, 'from' => $item->from_id, 'type' => $item->type );
		}
		
	 
		header('Content-Type: application/json');
		return response()->json($result);
	     //echo json_encode($result) with problem;return response()->json($result);
	
	}
 	
	public function planTaskLinkCreate(Request $request)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	   
		$token = $request->get('token');
		$plan_id = $request->get('plan_id');
	 
		$link_id = PlanTaskLink::create(array('from_id' => $request->get('from'), 'to_id' => $request->get('to'), 'type' => $request->get('type'),
				 'plan_id' => $plan_id, 'company_id' => $company_id))->id;
		
		$result = array();
		
		$response = (object) $result;
		$response->result = 'OK';
		$response->message = 'Created with id: ' . $link_id;
		$response->id = $link_id;
		 
		header('Content-Type: application/json');
		echo json_encode($response);
		 	
	}
	
	public function planTaskLinkDelete(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	   
		$id = $request->get('id');
		$token = $request->get('token');
		$plan_id = $request->get('plan_id');
		
		PlanTaskLink::where(array('id' => $id, 'plan_id' => $plan_id))->delete();
	 
		$result = array();
		
		$response = (object) $result;
		$response->result = 'OK';
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function planNodeList(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.node_list');
		
		if($request->has('search')) {
			//search button submitted
			$qtext = trim($request->get('qtext'));
			if ($qtext) {
					
				$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
						->where(array('plan.company_id' => $company_id))->where('complete','<',100)
					 	->whereRaw("(proj_name like '%$qtext%' OR  project.proj_code like '%$qtext%'
								OR customer_name like '%$qtext%'
					 			OR plan_task.name like '%$qtext%'
					 			OR plan_task.node_no like '%$qtext%' 
								OR plan_name like '%$qtext%' 
					 			OR plan_code like '%$qtext%')")
								->orderBy('project.proj_id','desc')
								->paginate(PAGEROWS);
		     }
		}
		
		if(!isset($rows)) {
			$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
					->where(array('plan.company_id' => $company_id))->where('complete','<',100)->paginate(PAGEROWS);
		}
		
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-node-list',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
 	 
	public function completePlanNode(Request $request, $token, $task_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.node_list');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
		    PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('process_status' => 10, 'complete' => 100, 'real_end' => date('Y-m-d')));
		    return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_operation'));
		}
	}
	
	public function antiCompletePlanNode(Request $request, $token, $task_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.node_list');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		 
		try {
			PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('process_status' => 3, 'real_end' => null));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_operation'));
		}
	}
	
	public function progressFeedin(Request $request)
	{   //进度录入列表及进行进度完成情况录入
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.progress_input');
		 
		if($request->has('submit')) {
			$task_id = $request->get('task_id');
			$site_message = 0;
			$email = 0;
			$small_routine = 0;
			$sms = 0;
			$msg_via = $request->get('msg_via');
			foreach($msg_via as $via) {
			   if($via == 'site_message') $site_message = 1;
			   if($via == 'email') $email = 1;
			   if($via == 'small_routine') $small_routine = 1;
			   if($via == 'sms') $sms = 1;
			}
			$member_list = '';
			$people = $request->get('people');
			foreach ($people as $user_id) {
				$member_list .= $user_id .',';
			}
			$member_list = rtrim($member_list,',');
			
			if($task_id) {
				$binfo = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		          select("project.proj_id","project.proj_name","plan.plan_id","plan.plan_name",'plan_task.name')->first();
				try {
				     if($request->get('real_end')) {//task done
					
					    PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('member_list' => $member_list,
						'real_start' => $request->get('real_start'), 'real_end' => $request->get('real_end'), 'complete' => 100,
						'progress_remark' => $request->get('progress_remark'), 'process_status' => 10, 'site_message' => $site_message, 'small_routine' => $small_routine,
						'email' => $email, 'sms' => $sms	
					    ));
					 
					   //记录事件以便触发信息发送
					   PlanTaskEvent::create(array('project_id' => $binfo->proj_id, 'plan_id' => $binfo->plan_id, 'task_id' => $task_id,
						'title' => $binfo->name, 'msg_content' => $request->get('progress_remark'), 'member_list' => $member_list, 'site_message' => $site_message, 
						'small_routine' => $small_routine,	'email' => $email, 'sms' => $sms, 'complete' => 100, 'company_id' => $company_id
					   ));
				     } else {
					      PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('member_list' => $member_list,
							'real_start' => $request->get('real_start'), 'real_end' => $request->get('real_end'), 'complete' => $request->get('complete'),
							'progress_remark' => $request->get('progress_remark'), 'process_status' => 3, 'site_message' => $site_message, 'small_routine' => $small_routine,
							'email' => $email, 'sms' => $sms
					      ));
					
					      //记录事件以便触发信息发送
					      PlanTaskEvent::create(array('project_id' => $binfo->proj_id, 'plan_id' => $binfo->plan_id, 'task_id' => $task_id,
							'title' => $binfo->name, 'msg_content' => $request->get('progress_remark'), 'member_list' => $member_list, 'site_message' => $site_message,
							'small_routine' => $small_routine,	'email' => $email, 'sms' => $sms, 'complete' => $request->get('complete'), 'company_id' => $company_id
					     ));
				     }
				} catch (\Exception $e) {
					return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
				}
				
				return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
			}
		} else if($request->has('search')) {
			
			$qtext = trim($request->get('qtext'));
			if ($qtext) {
				$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
				->leftJoin('department','department.dep_id','=','plan_task.department')
				->where(array('plan.company_id' => $company_id))->where('complete','<',100)
				->where('plan.status', 4)
				->whereRaw("(proj_name like '%$qtext%'
						OR  project.proj_code like '%$qtext%'
						OR plan_task.name like '%$qtext%'
						OR plan_task.node_no like '%$qtext%'
						OR plan_name like '%$qtext%'
						OR plan_code like '%$qtext%')")
				->select('project.proj_code','project.proj_name','project.proj_manager',
					'project.member_list','plan.plan_code','plan.plan_name','plan.project_id',
					'plan_task.*','department.name as dep_name','plan.status as plan_status')->paginate(PAGEROWS);
			}
		}
		
		if(!isset($rows)) {
			$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
			->leftJoin('department','department.dep_id','=','plan_task.department')->where(array('plan.company_id' => $company_id))->where('plan.status', 4)
			->select('project.proj_code','project.proj_name','project.proj_manager',
				'project.member_list','plan.plan_code','plan.plan_name','plan.project_id',
				'plan_task.*','department.name as dep_name','plan.status as plan_status')->paginate(PAGEROWS);
		}
		
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
	 
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.progress-feed-in',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function getPlanTaskInfo2Edit(Request $request)
	{  
		//provide details of plan task for make-plan gantt chart bar 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$task_id = $request->get('task_id');
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member", "project.calendar_id", "plan.plan_code","plan.plan_name",
				"plan.plan_type","plan_task.*")->first();
		$res = array();
	 	 
		if($row) {
			//0-未处理，3-进行中，10-已完结 
			if($row->process_status == 0) {
				$task_status = Lang::get('mowork.unprocessed');
			} else if ($row->process_status == 3 ) {
				$task_status = Lang::get('mowork.processing');
			} else if ($row->process_status == 10) {
				$task_status = Lang::get('mowork.completed');
			}
			
			if($row->status == 0) {
				$status = Lang::get('mowork.pending');
			} else if($row->status == 1) {
				$status = Lang::get('mowork.agree');
			} else if($row->status == 2) {
				$status = Lang::get('mowork.disagree');
			}
			$duration = date_diff(date_create($row->start_date), date_create($row->end_date));
			$duration = $duration->format("%a") + 1;
			 
			$dayoffs = self::calculateDayoffs(substr($row->start_date,0,10), substr($row->end_date,0,10), $row->calendar_id, $company_id);
			$workdays = $duration - $dayoffs; 
			
			$notify_list = $row->member_list? $row->member_list : $row->proj_member;//IF wihtout member_list THEN using project member
			$nodetype = $row->node_type;
			 
			if($nodetype) {//找到所有属于该类型的节点
				$nodeList = Node::where('type_id', $nodetype)->select('node_id','node_no','name','name_en')->get();
			}
			 
			$res = array('3' => $row->node_id, '4' => $duration, '5' => $dayoffs, '6' => $workdays, '7' => $row->node_no? $row->node_no : '',
					'8' => $row->name ? $row->name : '', '9' => $row->node_type? $row->node_type: '', '11' => $row->department ? $row->department : '',
					'12' => $row->leader ? $row->leader : '','13' => substr($row->start_date,0,10), '14' => substr($row->end_date,0,10),
				    '15' => $row->supplier_id ? $row->supplier_id : '', '16' => $row->key_condition ? $row->key_condition : '',
					'20' => $row->milestone ? $row->milestone : '', '21' => $row->outsource ? $row->outsource :'' , 
					'22' => $row->key_node ? $row->key_node : ''
			);
			 
		}
	 
		return json_encode($res,JSON_UNESCAPED_UNICODE);
	}
	
	public function getNodeByType(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$nodetype = $request->get('nodetype');

		$node_name = [];
		$department = [];

		if(!$nodetype || !is_numeric($nodetype)) {
			return json_encode(compact('node_name', 'department'), JSON_UNESCAPED_UNICODE);
		}

		//$rows = Node::where('type_id',$nodetype)->orderBy('node_id','asc')->get();
		$rows = NodeCompany::where(array('type_id' => $nodetype, 'company_id' => $company_id))->orderBy('node_id','asc')->get();
		$ctrl_by_dep = NodeType::where(['type_id' => $nodetype, 'company_id' => $company_id])
			->value('ctrl_by_dep');
		$department = Department::whereIn('dep_id', explode(',', $ctrl_by_dep))
			->where('company_id', $company_id)->pluck('name', 'dep_id');

		foreach($rows as $row) {
			$node_name[$row->node_id] = $row->node_no .'-' .$row->name;
		}

		return json_encode(compact('node_name', 'department'), JSON_UNESCAPED_UNICODE);
	}
	
	public function getDepartmentByNodeType(Request $request)
	{
		//根据节点类型得到可操作该节点类型的部门
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$nodetype = $request->get('nodetype');
	
		$res = array();
		$row = NodeType::where(array('type_id' => $nodetype))->first();
		if($row) {
			$allowedDeps =  $row->ctrl_by_dep;
			$deps = Department::whereRaw("company_id = $company_id AND dep_id in ($allowedDeps)")->get();
		    foreach($deps as $dep) {
				$res[$dep->dep_id] = $dep->name;
		    }
		}
			
		return json_encode($res, JSON_UNESCAPED_UNICODE);
	}
	
	public function getPlanNodeInfo(Request $request)
	{
		//provide details form to fillup for function progressFeedin()
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$task_id = $request->get('task_id');
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member","plan.plan_code","plan.plan_name",
				"plan.plan_type","plan_task.*")->first();
		$res = array();
		if($row) {
			//0-未处理，3-进行中，10-已完结
			if($row->process_status == 0) {
				$task_status = Lang::get('mowork.unprocessed');
			} else if ($row->process_status == 3 ) {
				$task_status = Lang::get('mowork.processing');
			} else if ($row->process_status == 10) {
				$task_status = Lang::get('mowork.completed');
			}
				
			if($row->status == 0) {
				$status = Lang::get('mowork.pending');
			} else if($row->status == 1) {
				$status = Lang::get('mowork.agree');
			} else if($row->status == 2) {
				$status = Lang::get('mowork.disagree');
			}
			$duration = date_diff(date_create(substr($row->start_date,0,10)), date_create(substr($row->end_date,0,10)));
			$duration = $duration->format("%a") + 1;
			$notify_list = $row->member_list? $row->member_list : $row->proj_member;//IF wihtout member_list THEN using project member
				
			$res = array('1' => $row->proj_code, '2' => $row->proj_name, '3'=> $row->proj_manager, '4' => $row->member_list,
					'5' => $row->plan_code, '6' => $row->plan_name, '6' => $row->plan_type, '7' => $row->node_no, '8' => $row->name,
					'9' => $task_status, '10' => $status, '11' => $row->department, '12' => $row->leader,
					'13' => substr($row->start_date,0,10), '14' => substr($row->end_date,0,10),	'15' => $row->real_start,
					'16' => $row->real_end, '17' =>  $row->complete, '18' => $duration, '19' => $notify_list, '20' => $row->site_message,
					'21' => $row->small_routine, '22' => $row->sms, '23' => $row->email
			);
		}
		return json_encode($res,JSON_UNESCAPED_UNICODE);
	}
	
	static public function compactOrdinals($parent, $plan_id) 
	{
	 	 
		$children = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority','desc')->get();
		$size = count($children);
	 	 
		for ($i = 0; $i < $size; $i++) {
			$row = $children[$i];
			self::updateTaskOrdinal($row["id"], $i);
		}
	}
	
	static public function updateTaskOrdinal($task_id, $ordinal)
	{
		 
		$now = date('Y-m-d H:i:s');
		PlanTask::where('task_id', $task_id)->update(array('ordinal' => $ordinal, 'ordinal_priority' => $now));
		 
 	}
 	
 	static protected function copyPlanTemplate($template_id, $plan_id, $basedate)
 	{

 		if(!Session::has('userId')) return Redirect::to('/');
 		$company_id = Session::get('USERINFO')->companyId;
 		$uid = Session::get('USERINFO')->userId;
 		
 		$nodes = Task::where(array('template_id' => $template_id))->orderBy('id','asc')->get();
 		$links = TaskLink::where('template_id', $template_id)->get();
 		//第一 个节点起始日期与基点日期之间的天数差别-天数偏离
 		$min_date = Task::where(array('template_id' => $template_id))->min('start');
 		$days = date_diff(date_create($basedate), date_create(substr($min_date,0,10)));
 		$days = $days->format("%a");//天数偏离
 		 	
 		DB::beginTransaction();
 		$map = array();
 		//删除旧计划 如果存在
 		PlanTask::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->delete();
 		event(new TableRowChanged('plan_task', $plan_id, 'delete from plan_task where plan_id='.$plan_id, $uid, $company_id, Date('Y-m-d h:i:s')));
 		PlanTaskLink::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->delete();
 		event(new TableRowChanged('plan_task_link', $plan_id, 'delete from plan_task_link where plan_id='.$plan_id, $uid, $company_id, Date('Y-m-d h:i:s')));
 		
 		foreach ($nodes as $node) {
 			$parent_id = $node->parent_id;
 			if($parent_id != 0) $parent_id = $map[$node->parent_id];
 			$duration = date_diff(date_create($node->start), date_create($node->end));
 			$duration = $duration->format("%a");
 			$end_date = date('Y-m-d H:i:s', strtotime($node->end . ' - 1 second'));//这是计划甘特图与模板甘特图结束日期表达方式不同的地方：计划甘特图结束日期表达方式更符合惯例
 			//修改起始结束日期时：用模板日期的相对数+最早节点起始日期 			
 			$newId = PlanTask::create(array('plan_id' => $plan_id, 'name' => $node->name,
 					'node_id' => $node->node_id, 'node_no' => $node->node_no,'node_type' => $node->node_type,
 					'start_date' => date('Y-m-d',strtotime($node->start ." + $days days")) . " 00:00:00" , 
 					'end_date' => date('Y-m-d',strtotime($end_date . " + $days days")) . " 23:59:59" , 'parent_id' => $parent_id, 
 					'duration' => $duration, 'milestone' => $node->milestone, 'ordinal' => $node->ordinal, 'ordinal_priority' => $node->ordinal_priority, 'complete' => $node->complete,
					'department' => $node->department_id,
 					'company_id' => $company_id))->task_id;
 			$map[$node->id] = $newId;
 		}
 	 	 
 		foreach ($links as $link) {
 			$from = $link->from_id;
 			$to = $link->to_id;
 			$new_from = $map[$from];
 			$new_to = $map[$to];
 			PlanTaskLink::create(array('from_id' => $new_from, 'to_id' => $new_to, 'type' => $link->type, 'plan_id' => $plan_id, 'company_id' => $company_id ));
 		}
 		//update parents children relationship
 		DB::commit();
 	}
 	
 	static protected function calculateDayoffs($start_date, $end_date, $cal_id,  $company_id)
 	{
 		$year1 = substr($start_date,0,4);
 		$year2 = substr($end_date,0,4);
 		$day_no1 = date('z',strtotime($start_date));
 		 
 		
 		if($year1 == $year2) {//the same year 
 			//existed customerize calendar?
 			$row = WorkCalendarReal::where(array('cal_year' => $year1, 'cal_id' => $cal_id, 'company_id' => $company_id))->first();
 		 	// nth day of the year for $start_date;
 			 
 			$csv = '';
 			if($row) {
 				for($kk = 1; $kk < 13; $kk++) {
 					$month = 'month'.$kk;
 					$csv .= $row->{$month} .',';
 				}
 			}
 			$csv = rtrim($csv,',');
 			$days = explode(',', $csv);
 		 
 			$ones = 0;
 			$day_no2 = date('z',strtotime($end_date));
 			 
 			for($jj = $day_no1; $jj <= $day_no2; $jj++) {
 				$ones += $days[$jj];
 			}
 			
 			$dayoffs = $day_no2 - $day_no1 + 1 - $ones;
 			
 			return $dayoffs;
 			
 		} else {
 			//span over 2 years: for example from this December to next year January
 			//1. check if has made calendar for year2
 			$row2 = WorkCalendarReal::where(array('cal_year' => $year2, 'cal_id' => $cal_id))->first();
 			 
 			if(! $row2) {//no calendar for year2
 				return null;
 			}
 			 
 			//2. from $day_no1 to the end of year1
 			$row = WorkCalendarReal::where(array('cal_year' => $year1, 'cal_id' => $cal_id))->first();
 			// nth day of the year for $start_date;
 				
 			$csv = '';
 			if($row) {
 				for($kk = 1; $kk < 13; $kk++) {
 					$month = 'month'.$kk;
 					$csv .= $row->{$month} .',';
 				}
 			}
 			$csv = rtrim($csv,',');
 			$days = explode(',', $csv);
 			$day_no2 = date('z',strtotime($year1.'-12-31'));
 			
 			$ones = 0;
 			for($jj = $day_no1; $jj <= $day_no2; $jj++) {
 				$ones += $days[$jj];
 			}
 			
 			$dayoffs1 = $day_no2 - $day_no1 + 1 - $ones;
 		 
 			//2. from first_day of year2 to end_date
 			$end_month = substr($end_date,5,2);
 			$end_month = ltrim($end_month,'0');//remove the leading '0'
 			 		
 			$csv2 = '';
 			for($ii = 1; $ii <= $end_month; $ii++) {
 				$month = 'month'.$ii;
 				$csv2 .= $row2->{$month} .',';
 			}
 			$csv2 = rtrim($csv2,',');
 			$days2 = explode(',', $csv2);
 			
 			$ones2 = 0;
 			$day_no1 = 0;
 			$day_no2 = date('z',strtotime($end_date));
 			 
 			for($jj = $day_no1; $jj < $day_no2; $jj++) {
 				$ones2 += $days2[$jj];
 			}
 			
 			$daydiff = date_diff(date_create($year2.'-01-01'), date_create($end_date));
 			$daydiff = $daydiff->format("%a");
 			$dayoffs2 = $daydiff - $ones2;
 			return $dayoffs1 + $dayoffs2;
 			 
 		}
 	}
 	
}
