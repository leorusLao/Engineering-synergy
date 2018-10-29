<?php

namespace App\Models;

use Eloquent;

class Node extends Eloquent {
	//Node means plan node, node type menas plan-node-type
	protected $primaryKey = 'node_id';
	protected $table = 'node';
	protected $fillable = array (  
    	 'node_no', 'type_id', 'type_code', 'name', 'name_en', 'level', 'expandable', 'pnode_id', 
		 'instruct_filepath', 'template_filepath', 'other_filepath', 'category',
		 'company_id', 'bu_id'
	);
	protected $guarded = array (
			'type_id'
	);

	public $timestamps = true;
   
	static public function isExistedNode($nodeNo, $companyId)
	{
		$row = self::where('node_no',$nodeNo)->whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->first();

		if($row) return true;
		return false;
	}

	static public function addNode($nodeNo, $nodeType, $name, $nameEn, $category,$company_id)
	{
		return self::create(array('node_no' => $nodeNo, 'type_id' => $nodeType, 'name'=> empty($name)? '': $name, 
				'name_en' => empty($nameEn)? '': $nameEn, 'category' => $category, 'company_id' => $company_id));
	}

	static public function deleteNode($id, $companyId)
	{
		return self::where(array('id' => $id, 'company_id' => $companyId))->delete();
	}

	static public function updateNode($id, $nodeNo, $nodeType, $name, $nameEn, $companyId)
	{
		return self::where(array('node_id' => $id, 'company_id' => $companyId))->update(array('node_no' => $nodeNo, 'type_id' => $nodeType, 
				'name'=> empty($name)? '': $name, 'name_en' => empty($nameEn)? '': $nameEn));
	}
}

?>
