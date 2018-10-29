<?php

namespace App\Models;
use Eloquent;

class CompanyType extends Eloquent
{
	 
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $primaryKey = 'type_id';
	protected $table = 'company_type';

	protected $fillable = array('type_code','name','name_en','is_active');
	protected $guarded = array('type_id');
	public $timestamps = true;

 
}