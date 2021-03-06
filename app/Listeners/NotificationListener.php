<?php

namespace App\Listeners;
 
use App\Events\OpenissueProgress;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Lang;
use App\Events\PlanMade;
use App\Models\User;
use App\Models\MessageEvent;
use App\Http\Controllers\EmailController;
use DB;
use App\Events\PlanApproved;
use App\Events\OpenissueApproved;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
	protected $salt = 'Neswoit9$34$#@@$%@6!$26mrdsgslrevbxVBdWfertalgmelre218&*~ewE';
	
    public function __construct()
    {
        //
    }

     
    //Register the listeners for the subscriber.
    public function subscribe($events)
    {
    	$events->listen(
    			'App\Events\ProejctMade',
    			'App\Listeners\NotificationListener@projectMadeNotify'
    			);
    
    	$events->listen(
    			'App\Events\ProjectApproved',
    			'App\Listeners\NotificationListener@projectApprovedNotify'
    			);
    	
    	$events->listen(
    			'App\Events\ProjectAssignedToSupplier',
    			'App\Listeners\NotificationListener@projectAssignedNotify'
    			);
    	
    	$events->listen(
    			'App\Events\SupplierAcceptedProject',
    			'App\Listeners\NotificationListener@supplierAcceptedNotify'
    			);
    	
    	$events->listen(
    			'App\Events\PlanMade',
    			'App\Listeners\NotificationListener@planMadeNotify'
    			);
    	
    	$events->listen(
    			'App\Events\PlanApproved',
    			'App\Listeners\NotificationListener@planApprovedNotify'
    			);
    	
    	$events->listen(
    			'App\Events\TaskDoneAntiDone',
    			'App\Listeners\NotificationListener@taskDoneAntiDoneNotify'
    			);
    	
    	$events->listen(
    			'App\Events\ProgressMade',
    			'App\Listeners\NotificationListener@progressMadeNotify'
    			);
    	
    	$events->listen(
    			'App\Events\OpenissueMade',
    			'App\Listeners\NotificationListener@openissueMadeNotify'
    			);
    	
    	$events->listen(
    			'App\Events\OpenissueApproved',
    			'App\Listeners\NotificationListener@openissueApprovedNotify'
    			);
    	
    	$events->listen(
    			'App\Events\OpenissueProgress',
    			'App\Listeners\NotificationListener@openissueProgressNotify'
    			);
    }
    
    public function projectMadeNotify() 
    {
    	
    }
    
    public function projectApprovedNotify()
    {
    	
    }
    
    public function projectAssignedNotify()
    {
    	
    }
    
    public function supplierAcceptedNotify()
    {
    	
    }
    
    public function planMadeNotify(PlanMade $event)
    {
    	$userList = $event->userList;
    	 
    	if($event->siteMessage == 1) {//站内信
    		//send email to $userList
    		 
    		foreach ($userList as $uid) {
    			$salt = $event->company_id.$this->salt.$uid;
    	 
    			$siteUrl = '/dashboard/plan-approval/stamp/'.hash('sha256',$salt. $event->sourceId) . '/'. $event->sourceId;
    			//DB::enableQueryLog();
    			
    			MessageEvent::create(array('uid' => $uid, 'source_id' => $event->sourceId, 'source_type' => $event->sourceType,
    					'event_name' => $event->eventName, 'subject' => $event->subject, 
    					'content' => $event->content, 'attachment' => $event->attachment, 'site_url' => $siteUrl, 
    					'status' => 0, 'company_id' => $event->company_id
    			));
    			 
    			 
    			//$queries = DB::getQueryLog();
    			//$last_query = end($queries);
    			 
    		}
    	}
    	
    	if($event->email == 1) {//邮件
    		//send email to $userList
    		foreach ($userList as $uid) {
    			$row = User::where('uid',$uid)->first();
    			EmailController::sendmail_password($row->email, 'test', 'test.mowork.com/');//to change
    		}
    	}
    	 
    	//TODO for small routine,sms
    	
    }
    
    public function planApprovedNotify(PlanApproved $event)
    {
    	$userList = $event->userList;
    	$planId = $event->planId;
    	$planCode = $event->planCode;
    	$planName = $event->planName;
    	$approvalTitle = $event->approvalTitle;
    	$company_id = $event->company_id;
     
    	if($event->siteMessage == 1) {//站内信
    	 	/*send to $userList
    		 source_type 	1-项目;2-计划;3-计划节点，4-openissue 
    		 */
    		foreach ($userList as $uid) {
    			 
    			MessageEvent::create(array('uid' => $uid, 'source_id' => $planId, 'source_type' => 2,
    					'event_name' => $planName ?$planName :$event->planCode , 'subject' => $approvalTitle,
    					'status' => 0, 'company_id' => $company_id
    			));
    		}
    	}
    	 
    	if($event->email == 1) {//邮件
    		//send email to $userList
    		foreach ($userList as $uid) {
    			$row = User::where('uid',$uid)->first();
    			EmailController::sendmail_password($row->email, 'test', 'test.mowork.com/');//to change
    		}
    	}
    	
    	//TODO for small routine,sms
    }
    
    public function taskDoneAntiDoneNotify()
    {
    	
    }
    
    public function progressMadeNotify()
    {
    	
    }
    
    public function openissueMadeNotify()
    {
    	 
    }
    
    public function openissueApprovedNotify(OpenissueApproved $event)
    {
    	$userList = $event->userList;
    	$id = $event->id;
    	 
    	$approvalTitle = $event->approvalTitle;
    	$company_id = $event->company_id;
    	//file_put_contents('qqqq', 'siteMessage==='.$event->siteMessage.print_r($userList,true));
    	if($event->siteMessage == 1) {//站内信
    		/*send to $userList
    		 source_type 	1-项目;2-计划;3-计划节点，4-openissue
    		 */
    		foreach ($userList as $uid) {
    	
    			MessageEvent::create(array('uid' => $uid, 'source_id' => $id, 'source_type' => 2,
    					'event_name' => Lang::get('mowork.openissue_approval'), 'subject' => $approvalTitle,
    					'status' => 0, 'company_id' => $company_id
    			));
    		}
    	}
    	
    	if($event->email == 1) {//邮件
    		//send email to $userList
    		foreach ($userList as $uid) {
    			$row = User::where('uid',$uid)->first();
    			EmailController::sendmail_password($row->email, 'test', 'test.mowork.com/');
    		}
    	}
    	 
    	
    }
    
    public function openissueProgressNotify()
    {
    
    }
}
