<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlanMade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     /**
     * Create a new event instance.
     *
     * @return void
     */
    public $userList = array();
    public $sourceId;
    public $sourceType;
    public $eventName;
    public $subject;
    public $content;
    public $attachment;
    public $status;
    public $siteMessage;
    public $smallRoutine;
    public $email;
    public $sms;
    public $company_id;
    
    public function __construct(array $userList, $sourceId, $sourceType, $eventName, $subject, $content, $attachment, 
    		$status, $site_message, $small_routine, $email, $sms, $company_id)
    {
        //
        $this->userList = $userList;//user list
        $this->sourceId = $sourceId;//plan id
        $this->sourceType = $sourceType;//1: plan
        $this->eventName = $eventName;//hand in a plan and wait for approval
        $this->subject = $subject;//hand in a plan and wait for approval
        $this->content = $content;
        $this->attachment = $attachment;
        $this->status = $status;//0 - not been read yet
        $this->siteMessage = $site_message;
        $this->smallRoutine = $small_routine;
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
