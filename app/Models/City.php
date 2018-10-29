<?php

namespace App\Models;

use Eloquent;

class City extends Eloquent {
	protected $primaryKey = 'city_id';
	protected $table = 'city';
	protected $fillable = array (
			'name',
			'name_en',
			'digit_code',
			'english_code',
			'province_id',
			'country_id' 
	);
	
	protected $guarded = array (
			'province_id' 
	);
	
	public $timestamps = true;
	
	static public function getCityIdByProvinceAndName($province_id, $name)
	{
		$row = City::whereRaw ( 'province_id = '. $province_id .' AND (name = "' . $name . '" OR name_en="' . $name . '")' )->first ();
		if ($row) {
			return $row->city_id;
		} else
			return 0;
	}
}

?>