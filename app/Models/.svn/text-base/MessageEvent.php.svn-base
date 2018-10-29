<?php

namespace App\Models;

use Eloquent;

class MessageEvent extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'message_event';
	protected $fillable = array (
			'uid',
			'source_id',
			'source_type',
			'event_name',
			'subject',
			'content',
			'site_url',
			'attachement',
			'status',
			'company_id'
	);
	
	protected $guarded = array (
			'id' 
	);
	
	public $timestamps = true;
	
}