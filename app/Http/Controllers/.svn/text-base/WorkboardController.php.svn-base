<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
 
use App\Events\TableRowChanged; 
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
use App\Models\UserCompany;
use App\Models\MessageEvent;
use App\Models\PlanTask;
use App\Models\PlanTaskDiary;
use App\Models\OpenIssueDetail;
use App\Models\Approver;
use App\Models\NodeFile;
use App\Models\Department;
use App\Models\Node;
use function foo\func;

class WorkboardController extends  Controller {
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

	public function workboard(Request $request, Response $response)
	{  
		 
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		
		//1. all plan tasks whose leader=$uid 与我有关的任务节点已批准但尚未处理的
		$numTasks = PlanTask::where(array('leader' => $uid, 'status' => 1, 'process_status' => 0))->count();
		$tasks = PlanTask::where(array('leader' => $uid, 'status' => 1, 'process_status' => 0))->orderBy('task_id','desc')->limit(6)->get();
		 
		//2. all plan tasks which approved and leader=$uid与我有关的批准过的任务节点
		$numAllTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))->count();
		$allTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))->orderBy('task_id','desc')->limit(6)->get();
		 
		//3. all plan tasks  whose leader=$uid and has not completed 
		$numProgressTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))
		                 ->whereRaw('process_status != 2 AND process_status != 10')->count();
		
		$progressTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))
		                 ->whereRaw('process_status != 2 AND process_status != 10')->orderBy('task_id','desc')->limit(6)->get();
		 
		//4. 列出责任人用户的拖期计划
		$numDelayedTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))
		                 ->whereRaw('process_status != 10 AND CURRENT_DATE() > end_date')->count();
		                 
		$delayedTasks = PlanTask::where(array('leader' => $uid, 'status' => 1))
		                 ->whereRaw('process_status != 10 AND CURRENT_DATE() > end_date')->orderBy('task_id','desc')->limit(6)->get();
		                 
		//5. 列出用户所在部门的计划节点
		$dep = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id))->first();
		$dep_id = $dep->dep_id;
		$numDepTasks = PlanTask::where(array('status' => 1, 'department' => $dep_id))->count();
		$depTasks = PlanTask::where(array('status' => 1, 'department' => $dep_id))->orderBy('task_id','desc')->limit(6)->get();
				
		//6. 列出责任人用户 的delayed openissue
		$numDelayedIssues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				   'status' => 0, 'is_completed' => 0))
		          ->whereRaw('CURRENT_DATE() > plan_complete_date ')->count();
		
	    $delayedIssues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				  'status' => 0, 'is_completed' => 0, ))
		          ->whereRaw('CURRENT_DATE() > plan_complete_date ')->orderBy('updated_at','desc')->limit(6)->get();
		 
		//7. 列出责任人用户 的openissue录入
		$numProgressIssues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				 'status' => 0, 'is_completed' => 0))->count();
		       
		$progressIssues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id,  'is_approved' => 1,
				'status' => 0, 'is_completed' => 0))->orderBy('id','desc')->limit(6)->get();
		 
		//8. 列出责任人用户 的openissue
		$numIssues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				'status' => 0))->count();
	 
		$issues = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				'status' => 0))->orderBy('id','desc')->limit(6)->get();
					 
	 	
		//站内事件消息
		//get all unread event notitifications
		$pieces = MessageEvent::where(array('uid' => $uid, 'status' => 0))->count();
		$toConfirms = MessageEvent::where(array('uid' => $uid, 'status' => 0))->orderBy('id','desc')->limit(6)->get();
		Session::put('NOTICOUNTS',$pieces);
		Session::put('Notifications',$toConfirms);
	    
		$cookieTrail = Lang::get('mowork.workboard');
		$salt = $company_id.$this->salt.$uid;
		
		return view('dailywork.workboard',array('cookieTrail' => $cookieTrail,'confirmTasks' => $tasks, 'numTasks' => $numTasks,
				'numAllTasks' => $numAllTasks, 'allTasks' => $allTasks, 'numProgressTasks' => $numProgressTasks, 
				'progressTasks' => $progressTasks, 'numDelayedTasks' => $numDelayedTasks, 'delayedTasks' => $delayedTasks,
				'numDepTasks' => $numDepTasks, 'depTasks' => $depTasks, 'numDelayedIssues' => $numDelayedIssues,
				'delayedIssues' => $delayedIssues, 'numProgressIssues' => $numProgressIssues, 'progressIssues' => $progressIssues,
				'numIssues' => $numIssues, 'issues' => $issues, 'salt' => $salt,
				'pageTitle' => Lang::get('mowork.dashboard'), 'locale' => $this->locale));
		
	}
 
	public function planTaskConfirm(Request $request, $token, $task_id)
	{ 
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('accept')) {
			PlanTask::where(array('task_id' => $task_id ,'company_id' => $company_id))->update(array('process_status' => 1, 'progress_remark' => $request->get('reason') ));
		    return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} else if ( $request->has('unaccept')) {
			PlanTask::where(array('task_id' => $task_id ,'company_id' => $company_id))->update(array('process_status' => 2, 'progress_remark' => $request->get('reason') ));
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member",
				"project.customer_id","project.customer_name",
				"plan.plan_code","plan.plan_name", "plan.approval_date",
				"plan.plan_type","plan_task.*")->first();
		$approver = Approver::join('user','user.uid','=','approver.plan_uid')->where('company_id',$company_id)->select('fullname')->first();
		$team = PlanTask::join('user','user.uid','=','plan_task.leader')->where('task_id',$task_id)->select('fullname')->first();
		$docs = Node::where('node_id', $task_id)->get();
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '.Lang::get('mowork.plan_confirm');
		return view('dailywork.plan-task-confirm',array('cookieTrail' => $cookieTrail, 'row' => $row, 'approver' => $approver, 'team' => $team,
			'docs' => $docs, 'token' => $token, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function planTaskConfirmList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		//1. all plan tasks whose leader=$uid 与我有关的任务节点已批准但尚未处理的
	 
		$rows = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')
		 ->where(array('plan_task.leader' => $uid, 'plan_task.status' => 1, 'process_status' => 0))->orderBy('task_id','desc')->paginate(PAGEROWS);
		 
		if($request->has('accept') || $request->has('unaccept')) {
			$task_ids = $request->get('pstatus');
			$reason = $request->get('reason');
			if($request->has('accept')){
				$process_status = 1;
			} else if ($request->has('unaccept')) {
				$process_status = 2;
			}
		    
			foreach($task_ids as $task_id) {
				PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))
				->update(array('process_status' => $process_status, 'reason' => $reason));
			}
			
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		$cookieTrail =  Lang::get('mowork.workboard').' &raquo; '. Lang::get('mowork.unaccepted_task');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		return view('dailywork.plan-task-confirm-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
				'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function planTaskList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		//2. all plan tasks which approved and leader=$uid与我有关的批准过的任务节点
		 
		$rows = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')
		->where(array('plan_task.leader' => $uid, 'plan_task.status' => 1))->orderBy('task_id','desc')->paginate(PAGEROWS);
		
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '. Lang::get('mowork.plan_list');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		return view('dailywork.plan-task-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
				'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function planTaskView(Request $request, $token, $task_id)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
	
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member",
				"project.customer_id","project.customer_name",
				"plan.plan_code","plan.plan_name", "plan.approval_date",
				"plan.plan_type","plan_task.*")->first();
		$approver = Approver::join('user','user.uid','=','approver.plan_uid')->where('company_id',$company_id)->select('fullname')->first();
		$team = PlanTask::join('user','user.uid','=','plan_task.leader')->where('task_id',$task_id)->select('fullname')->first();
		$docs = NodeFile::where('node_id', isset($row->node_id)?$row->node_id:0)->get();
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '.Lang::get('mowork.task_detail');
		return view('dailywork.plan-task-view',array('cookieTrail' => $cookieTrail, 'row' => $row, 'approver' => $approver, 'team' => $team,
				'docs' => $docs, 'token' => $token, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function taskProgressInput(Request $request, $token, $task_id)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
	
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$task_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-plan')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
			$res = PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->first();
			if($res->complete || $res->start_date) {
				//转最后进度记录到历史表中
				PlanTaskDiary::create(array('task_id' => $res->task_id, 'plan_id' => $res->plan_id, 'name' => $res->name, 
						'node_id' => $res->node_id, 'node_no' => $res->node_no, 'progress_remark' => $res->progress_remark,
						'complete' => $res->complete, 'report_time' => $res->updated_at, 'company_id' => $company_id));
			}
			$progress = $request->get('progress');
			$real_start= $request->get('real_start');
			$real_end = $request->get('real_end');
			$progress_remark = $request->get('progress_remark');
			
			if($progress) {
				PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('complete' => $progress));
				if($progress == 100) {
					PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('complete' => $progress,'process_status' => 10,
							'real_end' => $real_end? $real_end: date('Y-m-d')));
				}
			}
			if($real_start) {
				PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('real_start' => $real_start));
			}
			if($real_end) {
				PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('real_end' => $real_end, 'complete' => 100, 'process_status' => 10));
			}
			if($progress_remark) {
					PlanTask::where(array('task_id' => $task_id, 'company_id' => $company_id))->update(array('progress_remark' => $progress_remark));
			}
		}
	
		$row = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')->where('task_id',$task_id)->
		select("project.proj_code","project.proj_name","project.proj_manager","project.member_list as proj_member",
				"project.customer_id","project.customer_name",
				"plan.plan_code","plan.plan_name", "plan.approval_date",
				"plan.plan_type","plan_task.*")->first();
		$approver = Approver::join('user','user.uid','=','approver.plan_uid')->where('company_id',$company_id)->select('fullname')->first();
		$team = PlanTask::join('user','user.uid','=','plan_task.leader')->where('task_id',$task_id)->select('fullname')->first();
		$docs = NodeFile::where('node_id', $row->node_id)->get();
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '.Lang::get('mowork.progress_input');
		return view('dailywork.plan-task-progress-input',array('cookieTrail' => $cookieTrail, 'row' => $row, 'approver' => $approver, 'team' => $team,
				'docs' => $docs, 'token' => $token, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function taskProgressInputList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		//2. all plan tasks which approved and leader=$uid与我有关的批准过的任务节点
			
		$rows = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')
		->where(array('plan_task.leader' => $uid, 'plan_task.status' => 1))
		->whereRaw(' (process_status = 0 OR process_status = 1 OR process_status = 3)')->orderBy('task_id','desc')->paginate(PAGEROWS);
	
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '. Lang::get('mowork.progress_input');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		return view('dailywork.plan-task-progress-input-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
				'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function taskDelayedList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		//4. 列出责任人用户的拖期计划
	 	$rows = PlanTask::where(array('leader' => $uid, 'status' => 1))
		->whereRaw('process_status != 10 AND CURRENT_DATE() > end_date')->orderBy('task_id','desc')->paginate(PAGEROWS);
	 
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '. Lang::get('mowork.delayed_plan');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		return view('dailywork.plan-task-dealyed-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
				'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
  	
	public function myDepartmentTaskList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		//5. 列出用户所在部门的计划节点
		$dep = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id))->first();
		$dep_id = $dep->dep_id;
		 
		$rows = PlanTask::join('plan','plan.plan_id','=','plan_task.plan_id')->join('project','project.proj_id','=','plan.project_id')
		       ->where(array('plan_task.status' => 1, 'plan_task.department' => $dep_id))->orderBy('plan_task.task_id','desc')->paginate(PAGEROWS);
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; '. Lang::get('mowork.department_plan');
		$departments = Department::where('company_id',$company_id)->get();
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.my-department-task-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows,
				    'departments' => $departments, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function openissueDelayedList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		
		//6. 列出责任人用户 的delayed openissue
		$rows = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				'status' => 0, 'is_completed' => 0, ))
				->whereRaw('CURRENT_DATE() > plan_complete_date ')->orderBy('updated_at','desc')->paginate(PAGEROWS);
							
	
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; ' .Lang::get('mowork.delayed_openissue');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		return view('dailywork.my-openissue-dealyed-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
				'departments' => $departments, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function openissueList(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
	
		//6. 列出责任人用户 的delayed openissue
		$rows = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id, 'is_approved' => 1,
				'status' => 0, 'is_completed' => 0, ))
				->orderBy('updated_at','desc')->paginate(PAGEROWS);
					
	
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; ' .Lang::get('mowork.delayed_openissue');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		return view('dailywork.my-openissue-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
						'departments' => $departments, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function openissueView(Request $request, $token, $id)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		//
		$row = OpenIssueDetail::where('id', $id)->first();
		//找出属于这条openissue同一片问题清单
		$rows = OpenIssueDetail::where(array('issue_id' => $row->issue_id, 'source_id' => $row->source_id))->get();
		
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; ' .Lang::get('mowork.openissue');
		
		$departments = Department::where('company_id',$company_id)->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		return view('dailywork.my-openissue-view',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'source_id' => $row->source_id,
				'departments' => $departments, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function openissueProgressList(Request $request) 
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		
		//7. 列出责任人用户 的progress-able openissue
	 	$rows = OpenIssueDetail::where(array('leader' => $uid, 'company_id' => $company_id,  'is_approved' => 1,
						'status' => 0, 'is_completed' => 0))->orderBy('updated_at','desc')->paginate(PAGEROWS);
		
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; Open Issue ' .Lang::get('mowork.progress_input');
		$salt = $company_id.$this->salt.$uid;
		$departments = Department::where('company_id',$company_id)->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		return view('dailywork.my-openissue-progress-list',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt,
						'departments' => $departments, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function openissueProgressInput(Request $request, $token, $id)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
	 
		$row = OpenIssueDetail::where('id', $id)->first();
		//找出属于这条openissue同源的问题清单
		$rows = OpenIssueDetail::where(array('issue_id' => $row->issue_id, 'source_id' => $row->source_id))->get();
		
		$cookieTrail = Lang::get('mowork.workboard').' &raquo; Open Issue ' .Lang::get('mowork.progress_input');
		
		$departments = Department::where('company_id',$company_id)->get();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		return view('dailywork.my-openissue-progress-input',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'source_id' => $row->source_id,
				'departments' => $departments, 'employees' => $employees, 'token' => $token,
				'id' => $id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function openissueProgressPost(Request $request) 
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		$company_id = Session::get('USERINFO')->companyId;
		  
		if($request->has('end_date')) {
			OpenIssueDetail::where(array('id' => $request->get('id'), 'company_id' => $company_id))->update(array('real_complete_date' => $request->get('end_date')));
		}
		echo 'success';
	}
}
