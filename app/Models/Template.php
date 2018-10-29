<?php

namespace App\Models;

use Eloquent;

class Template extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'template';
	protected $fillable = array (
			'template_code' ,'template_name', 'template_type', 'template_type', 'plan_type_id',
			'plan_type_name', 'node_type_name', 'node_type_id', 'level', 'level_id', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
	
	static public function getCompanyTemplate ($company_id)
	{
		return self::whereRaw('company_id =' .$company_id)->orderBy('id','asc')->paginate(PAGEROWS);
	}
	
	static public function isExistedTemplate ($template_code, $company_id)
	{
		$row = self::where(array('template_code' => $template_code, 'company_id' => $company_id))->first();
	
		if($row) return true;
		return false;
	}
	
	static public function isTemplateCodeUsedWithDifferentId ($template_code, $company_id, $this_primary_id)
	{
		$row = self::where(array('template_code' => $template_code, 'company_id' => $company_id))
			->where('id','!=', $this_primary_id)->first();
	
		if($row) return true;
		return false;
	}
	
	static public function addTemplate($template_code, $template_name, $template_type, $plan_type_id, $plan_type_name, 
			$node_type_id, $node_type_name, $level_id, $company_id = 0)
	{
		return self::create(array('template_code' => $template_code, 'template_name' => $template_name, 'template_type' => $template_type,
				'plan_type_id' => $plan_type_id, 'plan_type_name' => $plan_type_name,
				'node_type_id' => $node_type_id, 'node_type_name' => $node_type_name, 'level_id' => $level_id, 'company_id' => $company_id));
	}
	
	static public function deleteTemplate($template_id, $company_id)
	{
		return self::where(array('id' => $template_id, 'company_id' => $company_id))->delete();
	}
	
	static public function updateTemplate($template_id, $template_code, $template_name, $template_type, 
			$plan_type_id, $plan_type_name, $node_type_id, $node_type_name, $company_id = 0)
	{
		return self::where(array('id' => $template_id, 'company_id' => $company_id))->
				update(array('template_code' => $template_code, 'template_name' => $template_name, 
				'template_type' => $template_type, 'plan_type_id' => $plan_type_id,
				'plan_type_name' => $plan_type_name, 'node_type_name' => $node_type_name, 'node_type_id' => $node_type_id));
	}
}

?>