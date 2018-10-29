<?php

namespace App\Models;
use \Exception;
use Eloquent;

class ProjectDetail extends Eloquent {
    protected $primaryKey = 'id';
    protected $table = 'project_detail';
    protected $fillable = array (
            'proj_id','proj_code', 'part_code',  'part_name', 'part_type', 'quantity', 'note', 'jig', 'gauge',
            'mold', 'processing', 'part_from', 'material', 'mat_size', 'shrink', 'surface', 'part_size',
            'weight', 'assigned_supplier', 'supplier_name', 'supplier_accepted', 'company_id'
    );
    protected $guarded = array (
            'proj_id'
    );

    public $timestamps = true;

    public static function createDetail($ary)
    {
        $id = self::create($ary)->id;
        return $id;
    }

    public static function infoDetail($where,$field='id')
    {
        $result = self::select($field)->where($where)->first();
        return $result;
    }

    public static function updateDetail($ary,$where)
    {
        $result = self::where($where)->update($ary);
        return $result;
    }

    //零件列表
    public static function listDetail($where,$field='id')
    {
        $result = self::select($field)->where($where)->get()->toArray();
        return $result;
    }

    //删除零件
    public static function deleteDetail($where)
    {
        $affect = self::where($where)->update(['status'=>1]);
        return $affect;
    }

    //项目ID批量删除零件
    public static function deleteByprojId($ary)
    {
        $affect = self::whereIn('proj_id',$ary)->update(['status'=>1]);
        return $affect;
    }

    //零件编号批量删除零件
    public static function deleteBypartcoded($ary)
    {
        $affect = self::whereIn('part_code',$ary)->delete();
        return $affect;
    }

    //零件列表
    public static function listPartinfoApi($proj_id)
    {
        $result = self::select('id as part_id','part_code','part_name')
                        ->where(['proj_id'=>$proj_id])
                        ->where('status','!=',1)
        				->get();
        return $result;
    }

	//获取零件信息详情
	public static function getPartinfoApi($part_id)
	{
		$result = self::select('end_time','gauge','jig','mat_size','material','mold','note','part_code',
            'part_from','part_name','id','part_size','part_type','processing','quantity','shrink',
            'start_time','surface','weight')
				->where(['id'=>$part_id])->where('status','!=',1)->first();
                if(!empty($result->start_time)){
                    $time = strtotime($result->start_time);
                    $result->start_time = date('Y-m-d',$time);
                }
                if(!empty($result->end_time)){
                    $time = strtotime($result->end_time);
                    $result->end_time = date('Y-m-d',$time);
                }
		return $result;
	}

	//删除单个零件
    	public static function deletePartinfoApi($part_id)
	{
		$result = self::where(['id'=>$part_id,'status'=>0])->update(['status'=>1]);
		return $result;
	}

	//修改零件信息
	public static function updatePartinfoApi($part_id,$ary)
	{
        try{
    		$result = self::where(['id'=>$part_id])->where('status','!=',1)->update($ary);
    		return $result;
        }catch(Exception $e){
            return 10003;
        }
	}

	//新建零件信息
	public static function createPartinfoApi($ary)
	{
        try{
		  $result = self::create($ary);
		  return $result;
        }catch(Exception $e){
            return 10003;
        }
	}


}

?>
