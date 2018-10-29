<?php

namespace App\Models;

use Eloquent;

class ProjectType extends Eloquent {
	protected $primaryKey = 'type_id';
	protected $table = 'project_type';
	protected $fillable = array (
			'name',	'name_en', 'include_plantype', 'include_plantype_en',
			'company_id', 'bu_id' 
	);
	protected $guarded = array (
			'type_id' 
	);
	
	public $timestamps = true;
	
	static public function isExistedProjectType ($typeName, $companyId) 
	{
		$row = self::where('name',$typeName)->where("company_id", $companyId)->first();
	 
		if($row) return true;
		return false;
	}
	
	static public function addProjectType($typeName, $englishName, $companyId = 0) 
	{
		return self::create(array('name' => $typeName, 'name_en' => $englishName, 'company_id' => $companyId));
	}
	
	static public function deleteProjectType($typeId, $companyId) 
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->delete();
	}
	
	static public function updateProjectType($typeId, $typeName, $englishName, $includePlanType, $companyId) 
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))
		->update(array('name' => $typeName, 'name_en' => $englishName, 'include_plantype' => $includePlanType));
	}

	//公司项目类型列表
	public static function getProjectTypes($company_id)
	{ 
		$result = self::select('type_id','name')->where(['company_id'=>$company_id])->orWhere(['company_id'=>0])
						->get()->toArray();			
		return $result;	
	}


}

?>