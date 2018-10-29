<?php

namespace App\Models;

use Eloquent;

class PlanType extends Eloquent {
	protected $primaryKey = 'type_id';
	protected $table = 'plan_type';
	protected $fillable = array (
			'type_code', 'type_name', 'type_name_en', 
			'company_id', 'cn_pix','cn_description','cn_description_en','cc_cfg_name','bu_id'
	);
	protected $guarded = array (
			'type_id'
	);

	public $timestamps = true;

	static public function isExistedPlanType ($typeCode, $companyId)
	{
		$row = self::where('type_code',$typeCode)->whereRaw("company_id = $companyId")->first();
		if($row) return true;
		return false;
	}

    static public function isExistedPlanTypeNameEn ($typeNameEn, $companyId)
    {
        $row = self::where('type_name_en',$typeNameEn)->whereRaw("company_id = $companyId")->first();
        if($row) return true;
        return false;
    }
	static public function addPlanType($typeCode, $typeName, $typeNameEn,$cn_pix, $cn_description,$cn_description_en,$cc_cfg_name,$companyId = 0)
	{
		return self::create(array('type_code' => $typeCode, 'type_name' => empty($typeName)? '':$typeName, 'type_name_en' => empty($typeNameEn)?'' : $typeNameEn,'cn_pix' => $cn_pix,'cn_description' => $cn_description,'cn_description_en' => $cn_description_en,'cc_cfg_name' => $cc_cfg_name, 'company_id' => $companyId));
	}

	static public function deletePlanType($typeId, $companyId)
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->delete();
	}

	static public function updatePlanType($typeId, $typeCode, $typeName, $typeNameEn, $cn_pix, $cn_description, $cn_description_en ,$cc_cfg_name, $companyId)
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->update(array('type_code' => $typeCode, 'type_name' => empty($typeName)? '':$typeName, 'type_name_en' => empty($typeNameEn)?'' : $typeNameEn, 'cn_pix' => $cn_pix,'cn_description' => $cn_description,'cn_description_en' => $cn_description_en,'cc_cfg_name' => $cc_cfg_name,'company_id' => $companyId));
	}

	//查询plan_type表中的cc_cfg_name字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来更改company_config表）
    static public function cc_cfg_name($typeId,$companyId)
    {
        return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->select('cc_cfg_name')->get();
    }



	public static function getPlanTypes($company_id)
	{ 
		$result = self::Where(['company_id'=>$company_id])
					->select('type_id','type_name')->get();
		return $result;
	}

}

?>