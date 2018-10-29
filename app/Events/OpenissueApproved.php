<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OpenissueApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $userList = array();//接受信息的用户列表
    public $id;//open_issue_detail(id)
    public $approvalTitle;//issue被批准；计划未被批准
    public $siteMessage;//1：送站内消息；0-不送站内消息
    public $smallRoutine;//1：推送小程序；0-推送小程序
    public $email;//1：推送小程序；0-推送小程序
    public $sms;//1：推送小程序；0-推送小程序
    public $company_id;
    public function __construct(array $userList, $id, $approvalTitle, $siteMessage, $smallRoutine, $email,
    		$sms, $company_id)
    {
    	//
    	$this->userList = $userList;
    	$this->id = $id;
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
