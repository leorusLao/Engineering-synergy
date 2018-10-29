<?php

namespace App\Models;

use Eloquent;

class OpenIssue extends Eloquent {//此表不再使用，用 OpenIssueDetail代替
	protected $primaryKey = 'issue_id';
	protected $table = 'open_issue';
	protected $fillable = array (
			'issue_source', 'proj_id', 'proj_code', 'proj_name', 'proj_manager', 'part_name ', 'plan_id', 'plan_code', 'plan_name',
			'plan_type', 'is_approved', 'is_completed','company_id'
	);
	protected $guarded = array (
			'issue_id'
	);

	public $timestamps = true;

    //openissue列表
    public static function listOpenIssue($sourceid,$companyid)
    {
        $where = array('open_issue.issue_source'=>$sourceid,'open_issue.company_id'=>$companyid);
    	$result = self::select('open_issue.*','issue_source.code')
    					->leftJoin('issue_source','open_issue.issue_id','=','issue_source.id')
    					->where($where)->get()->toArray();
    	return $result;
    }
		
    //单个openissue
    public static function infoOpenIssue($issue_id)
    {
    	$result = self::select('open_issue.*','issue_source.code')
    			->leftJoin('issue_source','open_issue.issue_id','=','issue_source.id')
    			->where(array('issue_id'=>$issue_id))->first();
	    //文件
        $result['new_pic'] = '';
        if(!empty($result['attached_file'])){
            $ary_file = explode(',',$result['attached_file']);
            foreach ($ary_file as $key_file => $value_file) {
                $new_pic[$key_file]['suffix'] = strtolower(mb_substr(strstr($value_file,'.'),1));
                $new_pic[$key_file]['name'] = $value_file;
                if(in_array($new_pic[$key_file]['suffix'],array('jpg','gif','jpeg','png'),true)){ 
                    $new_pic[$key_file]['bool'] = true;
                }else{ 
                    $new_pic[$key_file]['bool'] = false;
                }
            }
            if(!empty($new_pic)){
                $result['new_pic'] = $new_pic;
            }
            $str_department = '';
        }
    	return $result;
    }
	
    public static function exitOpenIssue($company_id,$issue_id)
    { 
    	return self::select('issue_id')->where(['company_id'=>$company_id,'issue_id'=>$issue_id])->first();
    }

	public static function updateOpenIssue($ary,$where)
	{
		return self::where($where)->update($ary);
	}



}

?>