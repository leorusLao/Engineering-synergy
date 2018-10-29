<?php

namespace App\Models;

use Eloquent;

class Country extends Eloquent {
	protected $primaryKey = 'country_id';
	protected $table = 'country';
	protected $fillable = array (
			'name',
			'name_en',
			'iso_code2',
			'postcode_required',
			'is_active' 
	);
	protected $guarded = array (
			'country_id'
	);
	public $timestamps = true;
	
	static public function getCountryIdByName($name)
	{
		$row = Country::whereRaw ( 'name = "' . $name . '" OR name_en="' . $name . '"' )->first ();
		if ($row) {
			return $row->country_id;
		} else
			return 0;
	}
}

?>