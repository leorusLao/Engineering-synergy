<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlanApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    
    public $userList = array();//接受信息的用户列表
    public $planId;//计划id
    public $planNo;//计划编号
    public $planName;//计划名称
    public $approvalTitle;//计划被批准；计划未被批准
    public $siteMessage;//1：送站内消息；0-不送站内消息
    public $smallRoutine;//1：推送小程序；0-推送小程序
    public $email;//1：推送小程序；0-推送小程序
    public $sms;//1：推送小程序；0-推送小程序
    public $company_id;
    public function __construct(array $userList, $planId, $planCode, $planName, $approvalTitle, $siteMessage, $smallRoutine, $email,
    		$sms, $company_id)
    {
    	//
    	$this->userList = $userList;
    	$this->planId = $planId;
    	$this->planCode = $planCode;
    	$this->planName = $planName;
    	$this->approvalTitle = $approvalTitle;
    	$this->siteMessage = $siteMessage;
    	$this->smallRoutine = $smallRoutine;
    	$this->email = $email;
    	$this->sms = $sms;
    	$this->company_id = $company_id;
    }
    

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
