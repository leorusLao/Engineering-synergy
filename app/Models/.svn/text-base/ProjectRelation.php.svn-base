<?php

namespace App\Models;

use Eloquent;
use DB;


class ProjectRelation extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'project_relation';
	protected $fillable = array (
 		'company_id','detail_id','proj_id','assigned_supplier','send_status','accept_status'
 	);

	//项目关联列表
	public static function listRelationApi($companyid,$page_size,$curr_page)
	{
		$total_count = self::select('proj_id')
			->Where(['project.company_id'=>$companyid,'project.property'=>0])
			->rightJoin('project_detail as detail','detail.proj_id','=','project.proj_id')->count();
		$data['total_page'] = ceil($total_count/$page_size);//总页面数
		if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
		$size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

		$data['result'] = self::select('detail.proj_id','detail.id as part_id','detail.part_name',
				'project.proj_name','detail.relation','detail.assigned_supplier','detail.supplier_name')
			->Where(['project.company_id'=>$companyid,'project.property'=>1])
			->rightJoin('project_detail as detail','detail.proj_id','=','project.proj_id')
			->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

		$data['curr_page'] = $curr_page; //当前页面 
		$data['page_size'] = $page_size; //单页数  
		$data['total_count'] = $total_count; //总条数
		
		return $data;
	}


	//获取分发项目基础信息
	public static function getSendinfoApi($relation_id,$part_id)
	{
		$result = self::select('assigned_supplier','proj_id','send_status as relation','message_status')
				->where(['id'=>$relation_id,'part_id'=>$part_id])->first()->toArray();	
		//零件信息
		$ary_part = DB::table('project_detail')->select('part_code','part_name')->where(['id'=>$part_id])->first();
		$result['part_code'] = $ary_part->part_code;
		$result['part_name'] = $ary_part->part_name;
		//项目信息
		$ary_part = DB::table('project')->select('proj_code','proj_name')->where(['proj_id'=>$result['proj_id']])->first();
		$result['proj_code'] = $ary_part->proj_code;
		$result['proj_name'] = $ary_part->proj_name;
		//公司信息	
		$result['supplier_name'] = DB::table('company')->select('company_name')
						->where(['company_id'=>$result['assigned_supplier']])->first()->company_name;
		return $result;
	}

	//新增分发关联状态
	public static function createSendStatusApi($part_id,$relation,$remark)
	{
		$result = self::where(['part_id'=>$part_id,'accept_status'=>0])->update(['accept_status'=>$relation,'remark'=>$remark]);
		if($relation==1){ 
			$relation = 2;
		}else if($relation==2){ 
			$relation = 3;
		}
		DB::table('project_detail')->where(['id'=>$part_id])->update(['relation'=>$relation]);
		return $result;
	}


}

?>
