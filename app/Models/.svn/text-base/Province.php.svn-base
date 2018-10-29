<?php

namespace App\Models;

use Eloquent;

class Province extends Eloquent {
	protected $primaryKey = 'province_id';
	protected $table = 'province';
	protected $fillable = array (
			'name',
			'name_en',
			'digit_code',
			'english_code',
			'country_id' 
	);
	protected $guarded = array (
			'province_id' 
	);
	
	public $timestamps = true;
	
	static public function getProvinceIdByCountryAndName($country_id, $name) {
		$row = Province::whereRaw ( 'country_id = '. $country_id .' AND (name = "' . $name . '" OR name_en="' . $name . '")' )->first ();
		if ($row) {
			return $row->province_id;
		} else
			return 0;
	}
}

?>