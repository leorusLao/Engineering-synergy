<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueClass extends Model {
	use SoftDeletes;
	//Node means plan node, node type menas plan-node-type
	protected $primaryKey = 'id';
	protected $table = 'issue_class';
	protected $fillable = array (  
    	 'code', 'name', 'description', 'status', 'company_id'
	);
	protected $guarded = array (
			'id'
	);

	protected $dates = ['deleted_at'];

	public $timestamps = true;
   
	static public function isExistedIssueClassCode($code, $company_id)
	{
		$row = self::where('code',$code)->whereRaw(" ( company_id = 0 OR company_id = $company_id ) ")->first();
	
		if($row) return true;
		return false;
	}
	
	static public function addIssueClass($code, $name, $description, $company_id)
	{
		return self::create(array('code' => $code, 'name' => $name, 'description' => $description, 'company_id' => $company_id ));
	}
	
	static public function updateIssueClassCode($id, $code, $name, $description, $company_id)
	{
		return self::where(array('id' => $id, 'company_id' => $company_id))->update(array('code' => $code, 'name' => $name, 'description' => $description));
	}
	
	static public function deleteIssueClass($id, $company_id )
	{
		return self::where(array('id' => $id, 'company_id' => $company_id))->delete();
	}	

	public static function infoIssueClass($where)
	{
		return self::where($where)->orderBy('id','desc')->get();
	}

	//获取OPENISSUE类型列表
    public static function listIssueClassApi($company_id)
    {
        try{
        return self::select('id as class_id','name as class_name')
                    ->where(['company_id'=>$company_id,'status'=>1])->orderBy('id','desc')->get();
        }catch(\Illuminate\Database\QueryException $e){ return 10003;}
    }


}