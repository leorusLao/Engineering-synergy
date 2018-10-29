<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Symfony\Component\Console\Helper\Table;

class TableRowChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tableName;
    public $record_id;
    public $action;
    public $uid;
    public $company_id;
    public $operation_time;
    public function __construct($tableName, $record_id, $action, $uid, $comapny_id, $operation_time)
    {
        //
        $this->tableName = $tableName;
        $this->record_id = $record_id;
        $this->action = $action;
        $this->uid = $uid;
        $this->company_id = $comapny_id;
        $this->operation_time = $operation_time;
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
