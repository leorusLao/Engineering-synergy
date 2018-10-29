<?php

namespace App\Models;

use Eloquent;

class PartType extends Eloquent {
	protected $primaryKey = 'type_id';
	protected $table = 'part_type';
	protected $fillable = array (
			'type_code', 'name',	'name_en',  'company_id' , 'bu_id' 
	);
	protected $guarded = array (
			'type_id' 
	);
	
	public $timestamps = true;
	
	static public function isExistedPartType ($typeCode, $companyId) 
	{
		$row = self::where('type_code',$typeCode)->whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->first();
	 
		if($row) return true;
		return false;
	}
	
	static public function addPartType($typeCode, $typeName, $englishName, $companyId = 0) 
	{
		return self::create(array('type_code' => $typeCode, 'name' => $typeName, 
				'name_en' => $englishName, 'company_id' => $companyId));
	}
	
	static public function deletePartType($typeId, $companyId) 
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->delete();
	}
	
	static public function updatePartType($typeId, $typeCode, $typeName, $englishName, $companyId) 
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->update(array('type_code' => $typeCode,
				'name' => $typeName, 'name_en' => $englishName));
	}
	
	static public function getPartTypes($companyId)
	{
		return self::whereRaw('company_id = '. $companyId)->get();
	}
}

?>