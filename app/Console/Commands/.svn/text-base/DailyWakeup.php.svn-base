<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use App\Models\User;
use App\Models\Company;
use App\Models\ScanPlan;
use App\Models\ScanPlanCompany;
use App\Models\ScanIssue;
use App\Models\ScanIssueCompany;
use App\Models\PlanTask;
use App\Models\MessageEvent;
use DB;
use App\Http\Controllers\EmailController;

class DailyWakeup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:wakeup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据计划控制扫描配置，Issue扫描配置发送站内信，邮件，短信，或小程序';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	 //$this->scanPlan();
    	 $this->scanIssue();
    }
    
    public function scanPlan() 
    {
    	$this->departmentPlanDealyWarning();//部门计划拖期
    	$this->planStartWarning();//计划开始
    	$this->planFinishWarning();//计划完成
    	$this->planStartPreWarning('ALERTSTART1');//预警计划开始一级
    	$this->planStartPreWarning('ALERTSTART2');//预警计划开始二级
    	$this->planStartPreWarning('ALERTSTART3');//预警计划开始三级
    	$this->planFinishPreWarning('ALERTCOMPLETION1');//预警计划完成一级
    	$this->planFinishPreWarning('ALERTCOMPLETION2');//预警计划完成二级
    	$this->planFinishPreWarning('ALERTCOMPLETION3');//预警计划完成三级
    }
    
    public function scanIssue()
    {
     	$this->issueFinishPreWarning('ALERT1');//Issue预警完成
     	$this->issueFinishPreWarning('ALERT2');//Issue预警完成
     	$this->issueFinishPreWarning('ALERT3');//Issue预警完成
    }
    
    public function departmentPlanDealyWarning()
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划拖期的扫描设置
    		$depRow = ScanPlanCompany::where(array('company_id' => $com->company_id,
    				 'code' => 'DEPDELAY'))->first();
    		if(!$depRow) {//否则使用平台设置
    			$depRow = ScanPlan::where('code','DEPDELAY')->first();
    		}
    		
    		$dayToWarn = $depRow->date_range;//例如提前7天警告
    		$methodToWarn = $depRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $depRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $depRow->people_list; //收到警告的成员列表
    		
    		//2. 得到所有应该在当前日期+dayToWarn天应该完成的节点
    		$endDate = date('Y-m-d', strtotime(date('Y-m-d') . " + $dayToWarn day"));
    		$planTasks = DB::select(DB::raw("SELECT * FROM plan_task JOIN plan ON plan.plan_id = plan_task.plan_id
    				WHERE plan_task.company_id = $com->company_id  
		               AND substring(plan_task.end_date,1,10) = '$endDate' AND (complete <100 OR process_status != 10)" ));
    		foreach ($planTasks as $task) {
    			$leader = $task->leader;
    			$member1 = $task->member_list;
    			$member1 = explode(',', $member1);
    			$member2 = explode(',', $peopleToWarn);
    			$member = array_merge($member1,$member2);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			//提示内容： 某计划某节点将在某天后拖期
    			$subject = $endDate.Lang::get('mowork.plan_duedate');
    			$warningContent = $task->plan_name." ".$task->name." ".$dayToWarn.Lang::get('mowork.days')
    			                 .Lang::get('mowork.become').Lang::get('mowork.delayed_plan');
    			//对member
    			$mail = new EmailController();
    			foreach ($member as $uid){
    				//对每个成员用户发站内信，email,等
    				//3.1 站内信
    				if(empty($uid)){
    					continue;
    				}
    				 MessageEvent::create(array('uid' => $uid, 'source_id' => $task->task_id, 'source_type' => '3',
    				 		'event_name' => $task->name, 'subject' => $subject,
    				 		'content' => $warningContent
    				 ));
    				//3.2 Email
    				$user = User::where('uid',$uid)->first(); 
    				if($user) {
    					if(!empty($user->email)) {
    						$mail->wakeupMessage($user->email, $subject, $warningContent);
    					}
    				}
    			}
    		}
    	}
    }
    
    public function planStartWarning()
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划开始扫描设置
    		$scanRow = ScanPlanCompany::where(array('company_id' => $com->company_id,
    				'code' => 'PLANSTART'))->first();
    		if(!$scanRow) {//否则使用平台设置
    			$scanRow = ScanPlan::where('code','PLANSTART')->first();
    		}
    	
    		$dayToWarn = $scanRow->date_range;//例如提前7天警告
    		$methodToWarn = $scanRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $scanRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $scanRow->people_list; //收到警告的成员列表
    	   
    		//2. 得到所有应该在当前日期+dayToWarn天应该开始的项目计划
    		$startDate = date('Y-m-d', strtotime(date('Y-m-d') . " + $dayToWarn day"));
    		 
    		$plans = DB::select(DB::raw("SELECT * FROM plan
    				WHERE plan.company_id = $com->company_id
    				AND plan.start_date = '$startDate'"));
    		foreach ($plans as $plan) {
    			$leader = $plan->leader;
    			$member1 = $plan->member;
    			$member1 = explode(',', $member1);
    			$member2 = explode(',', $peopleToWarn);
    			$member = array_merge($member1,$member2);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			//提示内容： 某项目计划将在某天后开始
    			$subject = Lang::get('mowork.project').Lang::get('mowork.plan_start').Lang::get('mowork.remind');
    			$warningContent = $plan->plan_name. ",".$startDate. "(".Lang::get('mowork.day_left') .': '.$dayToWarn.Lang::get('mowork.days').")"
    			 .Lang::get('mowork.is'). Lang::get('mowork.plan_start').Lang::get('mowork.date');
    			//对member
    			$mail = new EmailController();
    			foreach ($member as $uid){
    				//对每个成员用户发站内信，email,等
    				//3.1 站内信
    				if(empty($uid)){
    					continue;
    				}
    				MessageEvent::create(array('uid' => $uid, 'source_id' => $plan->plan_id, 'source_type' => '2',
    						'event_name' => $plan->plan_name, 'subject' => $subject,
    						'content' => $warningContent
    				));
    				//3.2 Email
    				$user = User::where('uid',$uid)->first();
    				if($user) {
    					if(!empty($user->email)) {
    						$mail->wakeupMessage($user->email,$user->fullname, $subject, $warningContent);
    					}
    				}
    			}
    		}
    	}
    }
    
    public function planFinishWarning()
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划完成扫描设置
    		$scanRow = ScanPlanCompany::where(array('company_id' => $com->company_id,
    				'code' => 'PLANCOMPLETION'))->first();
    		if(!$scanRow) {//否则使用平台设置
    			$scanRow = ScanPlan::where('code','PLANCOMPLETION')->first();
    		}
    		 
    		$dayToWarn = $scanRow->date_range;//例如提前7天警告
    		$methodToWarn = $scanRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $scanRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $scanRow->people_list; //收到警告的成员列表
    	
    		//2. 得到所有应该在当前日期+dayToWarn天应该完成的项目计划
    		$endDate = date('Y-m-d', strtotime(date('Y-m-d') . " + $dayToWarn day"));
    		$plans = DB::select(DB::raw("SELECT * FROM plan
    				WHERE plan.company_id = $com->company_id
    				AND plan.end_date = '$endDate'"));
    		foreach ($plans as $plan) {
    			$leader = $plan->leader;
    			$member1 = $plan->member;
    			$member1 = explode(',', $member1);
    			$member2 = explode(',', $peopleToWarn);
    			$member = array_merge($member1,$member2);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			//提示内容： 某项目计划将在某天后完成
    			$subject = Lang::get('mowork.project').Lang::get('mowork.plan_completion').Lang::get('mowork.remind');
    			$warningContent = $plan->plan_name. ",".$endDate. "(".Lang::get('mowork.day_left') .': '.$dayToWarn.Lang::get('mowork.days').")"
    					.Lang::get('mowork.is'). Lang::get('mowork.plan_completion').Lang::get('mowork.date');
    					//对member
    					$mail = new EmailController();
    					foreach ($member as $uid){
    						//对每个成员用户发站内信，email,等
    						//3.1 站内信
    						if(empty($uid)){
    							continue;
    						}
    						MessageEvent::create(array('uid' => $uid, 'source_id' => $plan->plan_id, 'source_type' => '2',
    								'event_name' => $plan->plan_name, 'subject' => $subject,
    								'content' => $warningContent
    						));
    						//3.2 Email
    						$user = User::where('uid',$uid)->first();
    						if($user) {
    							if(!empty($user->email)) {
    								$mail->wakeupMessage($user->email,$user->fullname, $subject, $warningContent);
    							}
    						}
    					}
    		}
    	}
    }
    
    public function planStartPreWarning($alertLevel)
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划开始扫描设置
    		$scanRow = ScanPlanCompany::where(array('company_id' => $com->company_id,
    				'code' => $alertLevel))->first();
    		if(!$scanRow) {//否则使用平台设置
    			$scanRow = ScanPlan::where('code', $alertLevel)->first();
    		}
    		
    		if($scanRow->is_active != 1) continue; //该级报警没有被启用
    		
    		$dayToWarn = $scanRow->date_range;//例如提前7天警告
    		$methodToWarn = $scanRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $scanRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $scanRow->people_list; //收到警告的成员列表
    	
    		//2. 得到所有应该在当前日期+dayToWarn天应该开始的项目计划
    		$startDate = date('Y-m-d', strtotime(date('Y-m-d') . "  $dayToWarn day"));
    		echo 'start date ==='.$startDate.";daytoWarn===".$dayToWarn."\n";
    		$plans = DB::select(DB::raw("SELECT * FROM plan
    				WHERE plan.company_id = $com->company_id
    				AND plan.start_date = '$startDate'"));
    		
    		if($alertLevel == 'ALERTSTART1') {
    			$level = Lang::get('mowork.first_grade');
    		} else if($alertLevel == 'ALERTSTART2') {
    			$level = Lang::get('mowork.second_grade');
    		} else {
    			$level = Lang::get('mowork.third_grade');
    		}
    		
    		foreach ($plans as $plan) {
    			$leader = $plan->leader;
    			$member1 = $plan->member;
    			$member1 = explode(',', $member1);
    			$member2 = explode(',', $peopleToWarn);
    			$member = array_merge($member1,$member2);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			//提示内容： 某项目计划将在某天后开始
    			$subject = Lang::get('mowork.project').Lang::get('mowork.plan_start').Lang::get('mowork.alert').$level;
    			$warningContent = $plan->plan_name. ",".$startDate. "(".Lang::get('mowork.day_left') .': '.$dayToWarn.Lang::get('mowork.days').")"
    					.Lang::get('mowork.is'). Lang::get('mowork.plan_start').Lang::get('mowork.date');
    					//对member
    					$mail = new EmailController();
    					foreach ($member as $uid){
    						//对每个成员用户发站内信，email,等
    						//3.1 站内信
    						if(empty($uid)){
    							continue;
    						}
    						MessageEvent::create(array('uid' => $uid, 'source_id' => $plan->plan_id, 'source_type' => '2',
    								'event_name' => $plan->plan_name, 'subject' => $subject,
    								'content' => $warningContent
    						));
    						//3.2 Email
    						$user = User::where('uid',$uid)->first();
    						if($user) {
    							if(!empty($user->email)) {
    								$mail->wakeupMessage($user->email,$user->fullname, $subject, $warningContent);
    							}
    						}
    					}
    		}
    	}
    }
    
    public function planFinishPreWarning($alertLevel)
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划完成扫描设置
    		$scanRow = ScanPlanCompany::where(array('company_id' => $com->company_id,
    				'code' => $alertLevel))->first();
    		if(!$scanRow) {//否则使用平台设置
    			$scanRow = ScanPlan::where('code', $alertLevel)->first();
    		}
    
    		if($scanRow->is_active != 1) continue; //该级报警没有被启用
    
    		$dayToWarn = $scanRow->date_range;//例如提前7天警告
    		$methodToWarn = $scanRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $scanRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $scanRow->people_list; //收到警告的成员列表
    		 
    		//2. 得到所有应该在当前日期+dayToWarn天应该开始的项目计划
    		$endDate = date('Y-m-d', strtotime(date('Y-m-d') . "  $dayToWarn day"));
    		echo 'start date ==='.$endDate.";daytoWarn===".$dayToWarn."\n";
    		$plans = DB::select(DB::raw("SELECT * FROM plan
    				WHERE plan.company_id = $com->company_id
    				AND plan.end_date = '$endDate'"));
    
    		if($alertLevel == 'ALERTCOMPLETION1') {
    			$level = Lang::get('mowork.first_grade');
    		} else if($alertLevel == 'ALERTCOMPLETION2') {
    			$level = Lang::get('mowork.second_grade');
    		} else {
    			$level = Lang::get('mowork.third_grade');
    		}
    
    		foreach ($plans as $plan) {
    			$leader = $plan->leader;
    			$member1 = $plan->member;
    			$member1 = explode(',', $member1);
    			$member2 = explode(',', $peopleToWarn);
    			$member = array_merge($member1,$member2);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			//提示内容： 某项目计划将在某天后完成
    			$subject = Lang::get('mowork.project').Lang::get('mowork.plan_completion').Lang::get('mowork.alert').$level;
    			$warningContent = $plan->plan_name. ",".$endDate . "(".Lang::get('mowork.day_left') .': '.$dayToWarn.Lang::get('mowork.days').")"
    					.Lang::get('mowork.is'). Lang::get('mowork.plan_completion').Lang::get('mowork.date');
    					//对member
    					$mail = new EmailController();
    					 
    					foreach ($member as $uid){
    						//对每个成员用户发站内信，email,等
    						//3.1 站内信
    						if(empty($uid)){
    							continue;
    						}
    						MessageEvent::create(array('uid' => $uid, 'source_id' => $plan->plan_id, 'source_type' => '2',
    								'event_name' => $plan->plan_name, 'subject' => $subject,
    								'content' => $warningContent
    						));
    						//3.2 Email
    						$user = User::where('uid',$uid)->first();
    						if($user) {
    							if(!empty($user->email)) {
    								$mail->wakeupMessage($user->email,$user->fullname, $subject, $warningContent);
    							}
    						}
    					}
    		}
    	}
    }
    
    public function issueFinishPreWarning($alertLevel)
    {
    	$companies = Company::get();
    	foreach ($companies as $com) {
    		//1. 先读取部门计划完成扫描设置
    		$scanRow = ScanIssueCompany::where(array('company_id' => $com->company_id,
    				'code' => $alertLevel))->first();
    		if(!$scanRow) {//否则使用平台设置
    			$scanRow = ScanIssue::where('code', $alertLevel)->first();
    		}
    	
    		if($scanRow->is_active != 1) continue; //该级报警没有被启用
    	
    		$dayToWarn = $scanRow->date_range;//例如提前7天警告
    		$methodToWarn = $scanRow->trigger_event;//警告方式,如发邮件，站内信等
    		$leaderToWarn = $scanRow->send_leader; //收到警告的责任人
    		$peopleToWarn = $scanRow->people_list; //收到警告的成员列表
    		 
    		//2. 得到所有应该在当前日期+dayToWarn天应该开始的项目计划
    		$endDate = date('Y-m-d', strtotime(date('Y-m-d') . "  $dayToWarn day"));
    		echo 'start date ==='.$endDate.";daytoWarn===".$dayToWarn."\n";
    		$issues = DB::select(DB::raw("SELECT * FROM open_issue_detail
    				WHERE open_issue_detail.company_id = $com->company_id
    				AND open_issue_detail.plan_complete_date  = '$endDate'"));
    	
    		if($alertLevel == 'ALERT1') {
    			$level = Lang::get('mowork.first_grade');
    		} else if($alertLevel == 'ALERT2') {
    			$level = Lang::get('mowork.second_grade');
    		} else {
    			$level = Lang::get('mowork.third_grade');
    		}
    	    
    		 
    		foreach ($issues as $issue) {
    			$leader = $issue->leader;
    			$member = explode(',', $peopleToWarn);
    			array_push($member, $leader, $leaderToWarn);
    			$member = array_unique($member);
    			
    			$issue_id = $issue->issue_id;
    			$source_id = $issue->source_id;
    			 
    			//提示内容： 某项目计划将在某天后完成
    			$subject = Lang::get('mowork.issue').Lang::get('mowork.plan_completion').Lang::get('mowork.alert').$level;
    			$warningContent = $issue->title. ",".$endDate . "(".Lang::get('mowork.day_left') .': '.$dayToWarn.Lang::get('mowork.days').")"
    					.Lang::get('mowork.is'). Lang::get('mowork.planned_issue_close_date');
    					//对member
    					$mail = new EmailController();
    	
    					foreach ($member as $uid){
    						//对每个成员用户发站内信，email,等
    						//3.1 站内信
    						if(empty($uid)){
    							continue;
    						}
    						MessageEvent::create(array('uid' => $uid, 'source_id' => $issue->id, 'source_type' => '4',
    								'event_name' => $issue->title, 'subject' => $subject,
    								'content' => $warningContent
    						));
    						//3.2 Email
    						$user = User::where('uid',$uid)->first();
    						if($user) {
    							if(!empty($user->email)) {
    								$mail->wakeupMessage($user->email,$user->fullname, $subject, $warningContent);
    							}
    						}
    					}
    		}
    	}
    }
}
