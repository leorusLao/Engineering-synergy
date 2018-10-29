<?php

namespace App\Http\Controllers;
use App;
  
use Session;
use DB;
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
use App\Models\NodeCompany;
use App\Models\UserCompany;
use App\Models\PlanTaskEvent;
use App\Models\Department;
use App\Models\DepartmentTask;
use App\Models\DepartmentTaskLink;
use App\Models\WorkCalendarReal;
use App\Models\Node;
use App\Models\NodeType;
  
class DepartmentPlanController extends Controller {
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
	public function departmentPlan(Request $request) { 
		if (! Session::has ( 'userId' ))
			return Redirect::to ( '/' );
		$company_id = Session::get ( 'USERINFO' )->companyId;
		$uid = Session::get ( 'USERINFO' )->userId;
		$cookieTrail = Lang::get ( 'mowork.department_plan' );
		
		if ($request->has ( 'submit' )) {
			$task_id = $request->get ( 'task_id' );
			$site_message = 0;
			$email = 0;
			$small_routine = 0;
			$sms = 0;
			$msg_via = $request->get ( 'msg_via' );
			foreach ( $msg_via as $via ) {
				if ($via == 'site_message')
					$site_message = 1;
				if ($via == 'email')
					$email = 1;
				if ($via == 'small_routine')
					$small_routine = 1;
				if ($via == 'sms')
					$sms = 1;
			}
			$member_list = '';
			$people = $request->get ( 'people' );
			foreach ( $people as $user_id ) {
				$member_list .= $user_id . ',';
			}
			$member_list = rtrim ( $member_list, ',' );
			
			if ($task_id) {
				$binfo = PlanTask::join ( 'plan', 'plan.plan_id', '=', 'plan_task.plan_id' )->join ( 'project', 'project.proj_id', '=', 'plan.project_id' )->where ( 'task_id', $task_id )->select ( "project.proj_id", "project.proj_name", "plan.plan_id", "plan.plan_name", 'plan_task.name' )->first ();
				try {
					if ($request->get ( 'real_end' )) { // task done
						
						PlanTask::where ( array (
								'task_id' => $task_id,
								'company_id' => $company_id 
						) )->update ( array (
								'member_list' => $member_list,
								'real_start' => $request->get ( 'real_start' ),
								'real_end' => $request->get ( 'real_end' ),
								'complete' => 100,
								'progress_remark' => $request->get ( 'progress_remark' ),
								'process_status' => 10,
								'site_message' => $site_message,
								'small_routine' => $small_routine,
								'email' => $email,
								'sms' => $sms 
						) );
						
						// 记录事件以便触发信息发送
						PlanTaskEvent::create ( array (
								'project_id' => $binfo->proj_id,
								'plan_id' => $binfo->plan_id,
								'task_id' => $task_id,
								'title' => $binfo->name,
								'msg_content' => $request->get ( 'progress_remark' ),
								'member_list' => $member_list,
								'site_message' => $site_message,
								'small_routine' => $small_routine,
								'email' => $email,
								'sms' => $sms,
								'complete' => 100,
								'company_id' => $company_id 
						) );
					} else {
						PlanTask::where ( array (
								'task_id' => $task_id,
								'company_id' => $company_id 
						) )->update ( array (
								'member_list' => $member_list,
								'real_start' => $request->get ( 'real_start' ),
								'real_end' => $request->get ( 'real_end' ),
								'complete' => $request->get ( 'complete' ),
								'progress_remark' => $request->get ( 'progress_remark' ),
								'process_status' => 3,
								'site_message' => $site_message,
								'small_routine' => $small_routine,
								'email' => $email,
								'sms' => $sms 
						) );
						
						// 记录事件以便触发信息发送
						PlanTaskEvent::create ( array (
								'project_id' => $binfo->proj_id,
								'plan_id' => $binfo->plan_id,
								'task_id' => $task_id,
								'title' => $binfo->name,
								'msg_content' => $request->get ( 'progress_remark' ),
								'member_list' => $member_list,
								'site_message' => $site_message,
								'small_routine' => $small_routine,
								'email' => $email,
								'sms' => $sms,
								'complete' => $request->get ( 'complete' ),
								'company_id' => $company_id 
						) );
					}
				} catch ( \Exception $e ) {
					return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_failure' ) );
				}
				
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			}
		}
		//节点expendable才可做部门计划
		$rows = plan::join ( 'project', 'project.proj_id', '=', 'plan.project_id' )->join ( 'plan_task', 'plan_task.plan_id', '=', 'plan.plan_id' )
		->leftJoin('user','user.uid','=','plan_task.leader')->leftJoin ( 'department', 'department.dep_id', '=', 'plan_task.department' )->
		where ( array ('plan.company_id' => $company_id, 'plan_task.expandable' => 1 ) )->where ( 'complete', '<', 100 )->
		select ('project.proj_code', 'project.proj_name', 'project.proj_manager', 'project.member_list', 'plan.plan_code', 'plan.plan_name',
				'plan.project_id','plan_task.*', 'department.name as dep_name','department.dep_id', 'fullname')->paginate ( PAGEROWS );
		
		$employees = UserCompany::join ( 'user', 'user.uid', '=', 'user_company.uid' )->where ( array (
				'company_id' => $company_id,
				'user_company.status' => 1 
		) )->get ();
		
		$salt = $company_id . $this->salt . $uid;
		return view ( 'dailywork.department-plan', array (
				'cookieTrail' => $cookieTrail,
				'salt' => $salt,
				'rows' => $rows,
				'employees' => $employees,
				'pageTitle' => Lang::get ( 'mowork.dashboard' ),
				'locale' => $this->locale 
		) );
	}
	
	public function departmentPlanMake(Request $request, $token, $task_id)
	{
		//make gantt chart for a plan
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		$taksinfo = PlanTask::where('task_id',$task_id)->first();
		$plan_id = $taksinfo->plan_id;
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
			if($request->has('cbx')) {
				$reference = $request->get('cbx');
			} else {
				$reference = $request->get('cbx')[0];
			}
		
			if($ref_type == 1) {//TODO
				self::copyPlanTemplate($reference, $plan_id);
			}
				
		}
		
		$row = Plan::where('plan_id', $plan_id)->first();
	 	
		$tmplts = Template::whereRaw('company_id =' . $company_id . ' OR company_id = 0')->paginate(PAGEROWS, ['*'], 'tmplPage');//reference templates: 参考模板
		$refplans = Plan::where(array('company_id' => $company_id, 'status' => 3))->paginate(PAGEROWS, ['*'], 'planPage');//reference plans: 参考计划
		$refs = count($tmplts);
		$refps = count($refplans);
		$cookieTrail =  Lang::get('mowork.department_plan'). " &raquo; ". Lang::get('mowork.make_plan');
		//$task = PlanTask::where(array('plan_id' => $plan_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		$basinfo = PlanTask::where('task_id',$task_id)->join('department','department.dep_id','=','plan_task.department')
		          ->select('plan_task.*','department.name as dep_name')->first();
		$task = DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		$departments = Department::where('company_id',$company_id)->get();
		$nodetypes = NodeType::whereRaw('company_id = 0 OR company_id='.$company_id)->get();
		return view('dailywork.department-make-plan',array('cookieTrail' => $cookieTrail,'row' => $row, 'departments' => $departments, 'employees' => $employees, 
				'nodetypes' => $nodetypes, 'tmplPage' => $tmplPage, 'planPage' => $planPage, 'tmplts' => $tmplts, 'refs' => $refs, 'refplans' => $refplans, 'refps' => $refps,
				'basinfo' => $basinfo, 'task'  => $task, 'token' => $token, 'plan_id' => $plan_id, 'task_id' => $task_id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
			
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
			$dayoffs = self::calculateDayoffs($start_date, $end_date, $cal_id);
		}
			
		
		$isparent = PlanTask::where('parent_id',$task_id)->count();
		
		return view('dailywork.view-task-bar',array( 'row' => $row, 'token' => $token, 'id' => $task_id, 'isparent' => $isparent, 'dayoffs' => $dayoffs, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
 
	 
	public function planApproval(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_approval');
			
		$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('work_cal','work_cal.cal_id','=','project.calendar_id')
		->where(array('plan.company_id' => $company_id, 'status' => 1))->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-approval',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
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
			$tasks = $request->get('cbx');
			 
			if($request->get('submit') == Lang::get('mowork.agree')){
				$status = 1;
			} else {
				$status = 2;
			}
			
			foreach ($tasks as $task) {
				PlanTask::where(array('task_id' => $task, 'company_id' => $company_id))->update(array('status' => $status));
			}
			
			//check if all plan nodes /tasks have been approved, if so change status in table plan from 1 to 3
			$nodes = PlanTask::where(array('plan_id' => $plan_id, 'company_id' => $company_id, 'status' => 0))->first();
			if(! $nodes) {
				Plan::where(array('plan_id' => $plan_id, 'company_id' => $company_id))->update(array('status' => 3));
			}
		}
		$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
		->where('plan.plan_id', $plan_id)->paginate(PAGEROWS);
		$binfo = plan::join('project','project.proj_id','=','plan.project_id')->where('plan.plan_id', $plan_id)->first();
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-approval-stamp',array('cookieTrail' => $cookieTrail,'plan_id' => $plan_id, 'rows' => $rows, 'token' => $token, 'binfo' => $binfo
				,'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function departmentTask(Request $request, $token, $task_id)
	{   
		if(!Session::has('userId')) return Redirect::to('/');
	 	 
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	 
		$rows = DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => 0))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();
		 
		$result = $this->departmentTaskList($rows,$task_id);
		header('Content-Type: application/json');
		 
		return response()->json($result);//echo json_encode($result);
	}
	
	public function departmentTaskList($items, $task_id) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		 
		$result = array();
		
		foreach($items as $item) {
			$t = array();
			$r = (object) $t;
		
			// rows
			$r->id = "$item->id";
			//forcefully add qutation mark in order to taskLink can find this matched task id for its from_id and to_id
			$r->text = htmlspecialchars($item['name']);
			$r->start = $item['start_date'];
			$r->end = $item['end_date'];
			$r->complete = $item['complete'];
			if ($item['milestone']) {
				$r->type = 'Milestone';
			}
		
			$parent = $r->id;
		
			$children =  DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();
		
			if (!empty($children)) {
				$r->children = $this->departmentTaskList($children, $task_id);
			}
		
			$result[] = $r;
		}
		 
		return $result;
	}
	
	public function departmentTaskCreate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	 
		$plan_id = $request->get('plan_id');
	 	$task_id = $request->get('task_id');
		$ordinal = DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => 0))->max('ordinal') + 1;
		$now = date('Y-m-d H:i:s');
		$duration = date_diff(date_create($request->get('start')), date_create($request->get('end')));
		$duration = $duration->format("%d");
		$end_date = date('Y-m-d H:i:s', strtotime($request->get('end'). ' - 1 second'));
		$start_date = $request->get('start');
		//initail start_date to the last task's start_date if any
		$row = DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => 0))->orderBy('ordinal','desc')->first();
		 
		if($row) {
			$start_date = substr($row->end_date,0,10) .' 00:00:00';
			$end_date = substr($row->end_date,0,10).' 23:59:59';
		}
		$id = DepartmentTask::create(array('name' => $request->get('name'), 'start_date' => $start_date, 'end_date' => $end_date,
			'duration' => $duration, 'ordinal' => $ordinal, 'ordinal_priority' => $now, 'plan_id' => $plan_id, 'task_id' => $task_id, 'company_id' => $company_id	
		));
	   
		$request = array();
		$response = (object) $request;
		$response->result = 'OK';
	 
		$response->id = $id;
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	 	 
	public function departmentTaskUpdate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	 	
		$milestone = $request->has("milestone");
		$outsource = $request->has("outsource");
	    $supplier = '';
	    if($outsource) {
	    	$supplier = $request->get('supplier');
	    }
	    $keynode = $request->has('keynode');
	    $condition =  '';
	    if($keynode) {
	    	$condition = $request->get('condition');
	    }
	 
		$task_id =  $request->get('task_id');
		
		$leader_list = '';
		if($request->has('people')) {
			 
			$people = $request->get ( 'people' );
			foreach ( $people as $user_id ) {
				$leader_list .= $user_id . ',';
			}
			$leader_list = rtrim ( $leader_list, ',' );
		}
		DepartmentTask::where('id', $task_id)->update(array('name' => $request->get('nodename'), 'leader' => $leader_list,
				'start_date' => $request->get('start_date') ." 00:00:00", 'end_date' => $request->get('end_date')." 23:59:59",
				'milestone' => $milestone, 'key_node' => $keynode, 'key_condition' => $condition,
				'department' => $request->get('dep_id'), 'status' => 0));
	     
	    /* for daypilot.js modal using
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
		
		header('Content-Type: application/json');
		echo json_encode($response);
		*/
		 return Redirect::back();
	   	
	}
	
	public function departmentTaskDelete(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 
		DepartmentTask::where(array('id' => $request->get('id'), 'task_id' => $request->get('task_id')))->delete();
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	public function departmentTaskMove(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		  
	    if($request->has('id')) {
	    	$end_date = date('Y-m-d H:i:s', strtotime($request->get('end') . ' - 1 second'));
	    	DepartmentTask::where('id', $request->get('id'))->update(array('start_date' => $request->get('start'), 'end_date' => $request->get('end')));
	        //check if this task id has parent task, yes: update parent id respectively
	        $parent = DepartmentTask::where('id', $request->get('id'))->pluck('parent_id')[0];
	        if($parent > 0) {//update parent task info
	        	$min_date = DepartmentTask::where('parent_id',$parent)->min('start_date');
	        	$max_date = Task::where('parent_id',$parent)->max('end_date');
	        	DepartmentTask::where('task_id', $parent)->update(array('start_date' => $min_date , 'end_date' => $max_date));
	        }
	    }
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
		
		header('Content-Type: application/json');
		echo json_encode($response);
		
	    
	}
	
	public function departmentTaskRowMove(Request $request)
	{    //Task name button on the left side 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		//file_put_contents('qqqq','task name move ==='. print_r($request->all(),true));
		$source = $request->get('source');
		$target = $request->get('target');
		$task_id = $request->get('task_id');
		
		$task_source = DepartmentTask::where('id',$source)->first();
		$task_target = DepartmentTask::where('id',$target)->first();
		
		$source_parent_id = $task_source? $task_source->parent_id : 0;
		$target_parent_id = $task_target? $task_target->parent_id : 0;
		$target_ordinal = $task_target->ordinal;
		$now = date('Y-m-d H:i:s');
		switch ($request->get('position')) {
			case "before":
				DepartmentTask::where('id', $task_source->id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal, 'ordinal_priority' => $now));
				break;
			case "after":
				DepartmentTask::where('id', $task_source->id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal + 1, 'ordinal_priority' => $now));
				break;
			case "child":
				echo "child:source/".$task_source->id. "/target/" . $task_target->id;
				//db_update_task_parent($source["id"], $target["id"], $max);
				DepartmentTask::where('id', $task_source->id)->update(array('parent_id' => $task_target->id, 'ordinal' => 10000, 'ordinal_priority' => $now));//max 10000 nodes or tasks for a certain level in a template
				$target_parent_id = $task_target->id;
				break;
			case "forbidden":
				break;
		}
		
		self::compactOrdinals($source_parent_id, $task_id);
		 
		if ($source_parent_id != $target_parent_id) {
			self::compactOrdinals($target_parent_id, $task_id);
		}
	 	
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';
	
		header('Content-Type: application/json');
		echo json_encode($response);
	
		 
	}
	
	public function departmentTaskLink(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	    
		$result = array();
		$rows = DepartmentTaskLink::where('task_id',$id)->get();
		
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
		
		//file_put_contents('qqqq', 'task-link=='.print_r($result,true));
		header('Content-Type: application/json');
		echo json_encode($result); //echo json_encode($result);return response()->json($result);
	
	}
 	
	public function departmentTaskLinkCreate(Request $request)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	   
		$token = $request->get('token');
		$task_id = $request->get('task_id');
	 
		$link_id = DepartmentTaskLink::create(array('from_id' => $request->get('from'), 'to_id' => $request->get('to'), 'type' => $request->get('type'),
				 'task_id' => $task_id, 'company_id' => $company_id))->id;
		
		$result = array();
		
		$response = (object) $result;
		$response->result = 'OK';
		$response->message = 'Created with id: ' . $link_id;
		$response->id = $link_id;
		 
		header('Content-Type: application/json');
		echo json_encode($response);
		 	
	}
	
	public function departmentTaskLinkDelete(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	   
		$id = $request->get('id');
		$token = $request->get('token');
		$task_id = $request->get('plan_id');
		
		DepartmentTaskLink::where(array('id' => $id, 'task_id' => $task_id))->delete();
	 
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
			
		$rows = plan::join('project','project.proj_id','=','plan.project_id')->join('plan_task','plan_task.plan_id','=','plan.plan_id')
		->where(array('plan.company_id' => $company_id))->where('complete','<',100)->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-node-list',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
 	 
	public function getDepartmentTaskInfo2Edit(Request $request)
	{  
		//provide details of plan task for make-plan gantt chart bar 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		file_get_contents('qqqq','get Taskinfo 2Edit');
		$task_id = $request->get('task_id');
		$row = DepartmentTask::where('id',$task_id)->join('plan','plan.plan_id','=','department_task.plan_id')->
		    join('project','project.proj_id','=','plan.project_id')->select('department_task.*','project.calendar_id')->first();
	 	 
		$res = array();
		if($row) {
		   
			$duration = date_diff(date_create($row->start_date), date_create($row->end_date));
			$duration = $duration->format("%d") + 1;
			 
			$dayoffs = self::calculateDayoffs(substr($row->start_date,0,10), substr($row->end_date,0,10), $row->calendar_id);
			$workdays = $duration - $dayoffs; 
			
			$notify_list = $row->member_list? $row->member_list : $row->leader;//IF wihtout member_list THEN using project member
	 		
			$res = array( '4' => $duration, '5' => $dayoffs, '6' => $workdays, '8' => $row->name,
			    '12' => $row->leader?$row->leader:'', '13' => substr($row->start_date,0,10), '14' => substr($row->end_date,0,10),
			    '16' => $row->key_condition,'20' => $row->milestone, '21' => $row->outsource, '22' => $row->key_node
				 
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
		
		$rows = Node::where('type_id',$nodetype)->orderBy('node_id','asc')->get();
		$res = array();
		foreach($rows as $row) {
			$res[$row->node_id] = $row->node_no .'-' .$row->name;
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
			$duration = $duration->format("%d") + 1;
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
	
	static public function compactOrdinals($parent, $task_id) 
	{
	 	 
		$children = DepartmentTask::where(array('task_id' => $task_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority','desc')->get();
		$size = count($children);
	 	 
		for ($i = 0; $i < $size; $i++) {
			$row = $children[$i];
			self::updateTaskOrdinal($row["id"], $i);
		}
	}
	
	static public function updateTaskOrdinal($id, $ordinal)
	{
		 
		$now = date('Y-m-d H:i:s');
		DepartmentTask::where('id', $id)->update(array('ordinal' => $ordinal, 'ordinal_priority' => $now));
		 
 	}
 	
 	static protected function copyPlanTemplate($template_id, $plan_id)
 	{//TODO

 		if(!Session::has('userId')) return Redirect::to('/');
 		$company_id = Session::get('USERINFO')->companyId;
 		$uid = Session::get('USERINFO')->userId;
 		
 		$nodes = Task::where(array('template_id' => $template_id))->orderBy('id','asc')->get();
 		$links = TaskLink::where('template_id', $template_id)->get();
 		
 		DB::beginTransaction();
 		$map = array();
 		
 		foreach ($nodes as $node) {
 			$parent_id = $node->parent_id;
 			if($parent_id != 0) $parent_id = $map[$node->parent_id];
 			$duration = date_diff(date_create($node->start), date_create($node->end));
 			$duration = $duration->format("%d");
 			$end_date = date('Y-m-d H:i:s', strtotime($node->end . ' - 1 second'));//这是计划甘特图与模板甘特图结束日期表达方式不同的地方：计划甘特图结束日期表达方式更符合惯例
 			$newId = PlanTask::create(array('plan_id' => $plan_id, 'name' => $node->name, 'start_date' => $node->start, 'end_date' => $end_date, 'parent_id' => $parent_id, 
 					'duration' => $duration, 'milestone' => $node->milestone, 'ordinal' => $node->ordinal, 'ordinal_priority' => $node->ordinal_priority, 'complete' => $node->complete,
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
 	
 	static protected function calculateDayoffs($start_date, $end_date, $cal_id)
 	{//TODO
 		$year1 = substr($start_date,0,4);
 		$year2 = substr($end_date,0,4);
 		$day_no1 = date('z',strtotime($start_date));
 		 
 		
 		if($year1 == $year2) {//the same year 
 			//existed customerize calendar?
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
 			$daydiff = $daydiff->format("%d");
 			$dayoffs2 = $daydiff - $ones2;
 			return $dayoffs1 + $dayoffs2;
 			 
 		}
 	}
 	
}