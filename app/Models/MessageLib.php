<?php

namespace App\Models;

use Eloquent;

class MessageLib extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'message_lib';
	protected $fillable = array (
			'uid',
			'to_list',
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
