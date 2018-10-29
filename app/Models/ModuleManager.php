<?php
 
namespace App\Models;

use Eloquent;

class ModuleManager extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'module_manager';
	protected $fillable = [
		'module', 'mode', 'founder', 'verifyer', 'ratifyer','company_id'
	];

	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
}