<?php

namespace App\Models;

use Eloquent;

class MessageBox extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'message_box';
	protected $fillable = array (
			'uid',
			'from_uid',
			'from_nickname',
			'from_type',
			'subject',
			'content',
			'attachement',
			'status',
			'company_id'
	);
	
	protected $guarded = array (
			'id' 
	);
	
	public $timestamps = true;
	
}