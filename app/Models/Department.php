<?php

namespace App\Models;
use Eloquent;
use DB;

class Department extends Eloquent
{
	 
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $primaryKey = 'dep_id';
	protected $table = 'department';

	protected $fillable = array('dep_code','name','name_en','upper_id', 'comment', 'manager', 'company_id', 'status');
	protected $guarded = array('dep_id');
	public $timestamps = true;

	static  public function addDepartment($dep_code, $dep_name, $upper, $manager, $company_id) 
	{
		self::create(array('dep_code' => $dep_code, 'name' => $dep_name, 'upper_id' => $upper? $upper:0, 'cal_id','comment', 'manager' => $manager, 'company_id' => $company_id));
	}
	
	static public function isExistedDepCode($comapny_id,$dep_code) {
		$existed = self::where('company_id',$comapny_id)->where('dep_code',$dep_code)->first();
		if($existed) return true;
		return false;
	}
	
	static  public function updateDepartment($dep_code, $dep_name, $upper, $manager, $dep_id, $company_id)
	{
		self::where('dep_id',$dep_id)->where('company_id',$company_id)->update(array('dep_code' => $dep_code, 
				'name' => $dep_name, 'upper_id' => $upper? $upper: 0, 'manager' => $manager ? $manager :0, 'company_id' => $company_id));
	}
 	
	static  public function deleteDepartment($dep_id, $company_id)
	{
		self::where('dep_id',$dep_id)->where('company_id',$company_id)->delete();
	}

	public static function infoDepartment($where,$field='dep_id')
	{
		$result = self::select($field)->where($where)->first();
		return $result;
	}

	//获取下级部门信息列表
	public static function infoUpperDeplist($company_id,$dep_id)
	{
		$result = self::select('dep_id','name as dep_name')
		->where(['upper_id'=>$dep_id,'company_id'=>$company_id])
		//->orWhere(['company_id'=>0])
		->get();
		return $result;
	}


	//获取部门及子部门信息列表
	public static function getSubDepsApi($company_id,$dep_id)
	{
		if($dep_id == 0) {
			// 获取公司下的所有部门
			$ary_node = Department::where('company_id', $company_id)
				->select('dep_id', 'upper_id', 'name as dep_name')
				->get()->toArray();
		}else {
			// 获取公司指定部门数据
			$ary_node = Department::where(['company_id' => $company_id, 'dep_id' => $dep_id])
				->select('dep_id', 'upper_id', 'name as dep_name')
				->get()->toArray();
		}

		if(empty($ary_node)){
			return [];
		}

		// 获取部门的人数(部门没有人则无该部门数据)
		$ary_number = DB::table('user_company')
			->where('company_id', $company_id)
			->groupBy('dep_id')
			->pluck(DB::Raw('count(dep_id) as member_num'),'dep_id')
			->toArray();

		foreach($ary_node as $key => $val) {
			foreach($ary_node as $k => $v) {
				// 存在数据则不重新赋值   						有的部门不存在
				isset($v['member_num']) || $v['member_num'] = isset($ary_number[$v['dep_id']]) ? $ary_number[$v['dep_id']] : 0;
				isset($ary_node[$k]['member_num']) || $ary_node[$k]['member_num'] = isset($ary_number[$v['dep_id']]) ? $ary_number[$v['dep_id']] : 0;
				// 部门层级为两级  不必循环无用的数据
				if($val['upper_id'] != 0 || $v['upper_id'] == 0) {continue;}
				// 一级部门添加子集
				isset($ary_node[$key]['deps']) || $ary_node[$key]['deps'] = [];
				if($val['dep_id'] == $v['upper_id']) {
					// 一级部门显示的人数包含子集的人数
					$ary_node[$key]['member_num'] += $v['member_num'];
					$ary_node[$key]['deps'][] = $v;
					// 一级部门中删除二级的部门
					unset($ary_node[$k]);
				}
			}
		}
	
		return ['deps' => array_values($ary_node)];
	}
	
	//获取公司全部部门ID集合
	public static function infoDeplist($company_id)
	{
		$result = self::select('dep_id')->where(array('company_id'=>$company_id))->get();
		return $result;
	}

	//获取公司全部部门ID及名称集合
	public static function infoDepartlist($company_id)
	{
		$result = self::select('dep_id','name')->where(array('company_id'=>$company_id))->get()->toArray();
		return $result;
	}

	//获取公司全部部门ID及名称集合API
	public static function listDepartlistApi($company_id)
	{
		$data['list_depart'] = self::select('dep_id','name')->where(array('company_id'=>$company_id,'status'=>1))->get()->toArray();
		return $data;
	}

	//修改用户所在的部门
	public static function updateUserInDepart($uid,$dep_id)
	{ 
		$result = UserCompany::where(array('uid'=>$uid))->update(array('dep_id'=>$dep_id));
		return $result;
	}

	//删除公司的部门
	public static function deleteDepartmentAry($ary_depid)
	{ 
		$result = self::whereIn('dep_id',$ary_depid)->update(array('status'=>0));
		return $result;
	}

	//获取部门基本信息
	public static function getDepartmentApi($dep_id)
	{ 
		$result['dep_info'] = self::select('department.manager as dep_admin_id','user.fullname as dep_admin',
						'department.dep_id','department.name as dep_name','dep2.dep_id as parent_dep_id',
						'dep2.name as parent_dep_name')
						->leftJoin('user','user.uid','=','department.manager')
						->leftJoin('department as dep2','dep2.dep_id','=','department.upper_id')
						->where(['department.dep_id'=>$dep_id])->first();
		return $result;
	}

	
	//修改部门信息
	public static function updateDepartInfoApi($dep_id,$ary)
	{
		$result = self::where('dep_id',$dep_id)->update($ary);
		return $result;
	}
 	
	
}