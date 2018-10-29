<?php
namespace App\Models;
use Eloquent;


class CfgCurrency extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'cfg_currency';

	protected $fillable = array('code','name','name_en','rate','symbol','local_currency_flag');
	protected $guarded = array('id');
	public $timestamps = true;

		
}

?>