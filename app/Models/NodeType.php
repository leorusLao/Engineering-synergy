<?php

namespace App\Models;
use Illuminate\Support\Facades\Lang;
use Eloquent;

class NodeType extends Eloquent {
	//Node means plan node, node type menas plan-node-type
	protected $primaryKey = 'type_id';
	protected $table = 'node_type';
	protected $fillable = array (
			 'type_name', 'type_name_en', 'ctrl_by_dep', 'allot_resource', 
			 'fore_color', 'back_color', 
			 'company_id', 'bu_id'
	);
	protected $guarded = array (
			'type_id'
	);

	public $timestamps = true;
    
	static public function getNodeTypeList($companyId)
	{
		$list = array('0' => Lang::get('mowork.please_select'));
//	    $rows =  self::whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->orderBy('type_id','ASC')->get();
	    $rows =  self::where('company_id', $companyId)->orderBy('type_id','ASC')->get();
		foreach($rows as $row){
			$list[$row->type_id] = $row->type_name;
		}
		return $list;
	}
	
	static function CompanyIndustryList(){
		$list = array('0' => '');
		$rows = CompanyIndustry::where('is_active',1)->orderBy('industry_id','asc')->get();
		foreach($rows as $row){
			$list[$row->industry_id] = $row->name;
		}
		return $list;
	}
	
	static public function isExistedNodeType ($typeName, $companyId)
	{
		$row = self::where('type_name',$typeName)->whereRaw(" ( company_id = $companyId ) ")->first();

		if($row) return true;
		return false;
	}

	static public function addNodeType($typeName, $typeNameEn, $ctrlByDep, $forecolor, $backcolor, $companyId)
	{
		return self::create(array('type_name' => $typeName, 'type_name_en' => $typeNameEn, 'ctrl_by_dep' => empty($ctrlByDep)? '':$ctrlByDep, 
				'fore_color' => empty($forecolor)? '' : $forecolor, 
				'back_color' => empty($backcolor)? '': $backcolor, 'company_id' => $companyId))->type_id;
	}

	static public function deleteNodeType($typeId, $companyId)
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->delete();
	}

	static public function updateNodeType($typeId, $typeName, $typeNameEn, $ctrlByDep, $forecolor, $backcolor, $companyId)
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->
		        update(array('type_name' => $typeName, 'type_name_en' => $typeNameEn, 'ctrl_by_dep' => $ctrlByDep, 
				'fore_color' => empty($forecolor)? '' : $forecolor, 'back_color' => empty($backcolor)? '': $backcolor, 'company_id' => $companyId));
	}
}

?>
