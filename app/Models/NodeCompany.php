<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NodeCompany extends Model {
	use SoftDeletes;

	//Node means plan node, node type menas plan-node-type；公司定制化node
	protected $primaryKey = 'id';
	protected $table = 'node_company';
	protected $fillable = array (  
    	'node_id', 'node_no','type_id','name', 'name_en', 'level', 'leader', 'trigger_event', 'task_text', 'task_people', 'done_text' ,'done_people', 
		'company_id', 'department', 'expandable','category', 'is_active', 'is_push', 'key_node'
	);
	protected $guarded = array (
			'id'
	);

	protected $dates = ['deleted_at'];

	public $timestamps = true;

	static public function isExistedNodeCompany($nodeId, $companyId)
	{
		$row = self::where('node_id',$nodeId)->where('company_id', $companyId)->first();

		if($row) return true;
		return false;
	}

	static public function customizeNode($nodeId,$node_no, $type_id, $name, $leader, $trigger, $taskText, $taskPeople, $doneText, $donePeople, $expandable, $companyId)
	{
		$existed = self::isExistedNodeCompany($nodeId, $companyId);
		 
		if($existed) {
			return self::where(array('node_id' => $nodeId, 'company_id' => $companyId))->update(array('node_no' => $node_no, 'type_id' => $type_id, 'name' => $name,
					'leader' => $leader, 'trigger_event' => $trigger, 'task_text' => $taskText, 'task_people' => $taskPeople,
					'done_text' => $doneText, 'done_people' => $donePeople, 'expandable' => $expandable
			));
		}
		return self::create(array('node_id' => $nodeId, 'node_no' => $node_no, 'type_id' => $type_id, 'name' => $name, 
				'leader' => $leader, 'trigger_event' => $trigger, 'task_text' => $taskText, 'task_people' => $taskPeople,
				'done_text' => $doneText, 'done_people' => $donePeople, 'expandable' => $expandable, 'company_id' => $companyId
		));	
	}
	  
	static public function deleteNode($id, $companyId)
	{
		return self::where(array('id' => $id, 'company_id' => $companyId))->delete();
	}

	static public function updateNode($id, $nodeNo, $nodeType, $name, $nameEn, $level, $parentNodeId, $triggerEvent, $triggerList, $sendText,
			$companyId, $departmentId)
	{
		return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->update(array('node_no' => $nodeNo, 'nodeType' => $nodeType, 'name'=> empty($name)? '': $name, 'name_en' => empty($nameEn)? '': $nameEn,
				'level' => $level, 'pnode_id' => $parentNodeId > 0? $parentNodeId:0, 'trigger_event' => $triggerEvent, 'trigger_list' => $triggerList,
				'send_text' => $sendText, 'company_id' => $companyId, 'department' => $departmentId));
	}

	static public function isExistedNode($nodeNo, $companyId, $nodeId)
	{
		$row = self::where('node_no',$nodeNo)->where('node_id', '<>', $nodeId)->whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->first();

		if($row) return true;
		return false;
	}

	static public function addNode($nodeNo, $nodeType, $name, $nameEn, $company_id, $is_push, $key_node, $expandable, $trigger_event)
	{
		$nodeId = self::withTrashed()->max('node_id') + 1;
		return self::create([
			'node_id'		=> $nodeId,
			'node_no'		=> $nodeNo,
			'type_id'		=> $nodeType,
			'name'			=> $name,
			'name_en'		=> $nameEn,
			'company_id'	=> $company_id,
			'is_push'		=> $is_push,
			'key_node'		=> $key_node,
			'expandable'	=> $expandable,
			'trigger_event' => $trigger_event,
		]);
	}

}

?>

