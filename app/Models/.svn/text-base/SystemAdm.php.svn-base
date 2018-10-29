<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SystemAdm extends Authenticatable {
	protected $primaryKey = 'id';
	protected $table = 'sysadm';
	protected $fillable = array (
			'adm_name','full_name', 'email', 'password', 'bu_id', 
			'user_role', 'user_level', 'is_active'
	);
	
	protected $guarded = array (
			'id' 
	);
	
	public $timestamps = true;
 	 
}

?>