<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IssueSource extends Model {
    use SoftDeletes;

    //Node means plan node, node type menas plan-node-type
    protected $primaryKey = 'id';
    protected $table = 'issue_source';
    protected $fillable = array (  
         'code', 'name', 'description', 'status', 'company_id'
    );
    protected $guarded = array (
            'id'
    );

    protected $dates = ['deleted_at'];

    public $timestamps = true;
   
    static public function isExistedIssueSourceCode($code, $company_id)
    {
        $row = self::where('code',$code)->whereRaw(" ( company_id = 0 OR company_id = $company_id ) ")->first();

        if($row) return true;
        return false;
    }
    
    static public function addIssueSourceCode($code, $name, $description, $company_id)
    {
        return self::create(array('code' => $code, 'name' => $name, 'description' => $description, 'company_id' => $company_id));
    }
    
    static public function updateIssueSourceCode($id, $code, $name, $description, $company_id)
    {
        return self::where(array('id' => $id, 'company_id' => $company_id))->update(array('code' => $code, 'name' => $name, 'description' => $description));
    }
    
    static public function deleteIssueSource($id, $company_id )
    {
        return self::where(array('id' => $id, 'company_id' => $company_id))->delete();
    }

    public static function listIssueSource($where)
    {
        return self::select('*')->where($where)->orderBy('id','desc')->get();
    }

    //获取OPENISSUE类型列表
    public static function listIssueSourceApi($company_id)
    {
        try{
        return self::select('id as source_id','name as type_name','code')
                    ->where(['company_id'=>$company_id,'status'=>1])
                    ->orWhere(['company_id'=>0,'status'=>1])
                    ->orderBy('id','desc')->get();
        }catch(\Illuminate\Database\QueryException $e){ return 10003;}
    }

    //查询issuesource
    public static function infoIssueSource($where,$field='id')
    { 
        $result = self::select($field)->where($where)->Where(['status'=>1])->first();
        return $result;
    }

    //查询包含共有的issuesource(project、plan)
    public static function infoIssueSourceTotal($where,$field='id')
    { 
        $result = self::select($field)->where($where)->orWhere(['company_id'=>0,'status'=>1])->first();
        return $result;
    }

    public static function listIssueOther($company_id,$sourceid)
    { 
          $result = self::select('issue_source.id','issue_source.name')
                        ->where(['issue_source.company_id'=>$company_id])
                        ->where('issue_source.status','=',1)
                        ->orderBy('issue_source.id','desc')
                        ->paginate(PAGEROWS);              
        return $result;
    }

    //把issue_source分三类
    public static function listIssueThree($company_id)
    {   
        $project = self::select('issue_source.id','issue_source.name')
                            ->where('issue_source.code','=','Project')
                            ->where('issue_source.status','=',1)
                            ->first();
        if(!empty($project)){ $result_two['project'] = $project; }
        $plan = self::select('issue_source.id','issue_source.name')
                            ->where('issue_source.code','=','Plan')
                            ->where('issue_source.status','=',1)
                            ->first();
        if(!empty($plan)){ $result_two['plan'] = $plan; }
        $other =  self::select('issue_source.id','issue_source.name')
                            ->where(['issue_source.company_id'=>$company_id])
                            ->where('issue_source.code','!=','Plan')
                            ->where('issue_source.code','!=','Project')
                            ->where('issue_source.status','=',1)
                            ->first(); 
        if(!empty($other)){ $result_two['other'] = $other; }
        return $result_two;
    }




}