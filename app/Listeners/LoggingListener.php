<?php

namespace App\Listeners;

use App\Events\TableRowChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Logging;

class LoggingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TableRowChanged  $event
     * @return void
     */
    public function handle(TableRowChanged $event)
    {
        //
        Logging::appendLog($event->uid, $event->tableName, $event->record_id, $event->action, $event->company_id);
    }
}
