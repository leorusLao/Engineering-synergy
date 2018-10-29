<?php
namespace App\Models;
use Eloquent;

class InvitedType extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'invited_type';

	protected $fillable = array('type_code', 'name', 'name_en');
	protected $guarded = array('id');
	public $timestamps = true;

		
}

?>