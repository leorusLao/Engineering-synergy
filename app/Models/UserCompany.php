<?php

namespace App\Models;
use DB;
use Eloquent;

class UserCompany extends Eloquent
{
     
    /**
     * The attributes that are mass assignable.
     * 
     * a user may belong to mutiple companys
     * 
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'user_company';

    protected $fillable = array('uid', 'company_id', 'dep_id', 'dep_name', 'role_id', 'emp_code', 'emp_start', 'emp_end',
    		               'position_id', 'position_title', 'is_active', 'status');
    protected $guarded = array('id');
    public $timestamps = true;
    
    static  public function addEmployee($uid, $emp_code, $dep_id, $dep_name, $position_id, $position_title, $emp_start,$company_id)
    {   
        //check email or mobile phone to see if can assocaite to user table 
        self::create(array('uid' => $uid, 'emp_code' => $emp_code, 'dep_id' => $dep_id, 'dep_name' => $dep_name, 
        	'position_id' => $position_id, 'position_title' => $position_title,	
        	'emp_start' => empty($emp_start)? '1900-12-31' : $emp_start, 'company_id' => $company_id));
    }
    
    static  public function isExistedEmpCode($emp_code, $company_id)
    {
        $existed = UserCompany::where(array( 'company_id' => $company_id, 'emp_code' => $emp_code))->first();
        if($existed) return true;
        return false;
    }
    
    static public function updateEmployeeInfo($uid, $company_id, $dep_id, $dep_name, $emp_code, $position_id, 
    		$position_title, $emp_start, $emp_end) 
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('emp_code' => $emp_code, 'dep_id' => $dep_id? $dep_id: 0, 
        		'dep_name' => $dep_name, 'position_id' => $position_id, 'position_title' => $position_title, 'emp_start' => $emp_start, 'emp_end' => $emp_end? $emp_end: null));
    }
    
    static public function assingEmployeeRole($uid, $company_id, $role_code)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('role_code' => $role_code));
        
    }
    
    static public function dismissEmployee($uid, $company_id)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('is_active' => 0, 'status' => 2));
    
    }
    
    static public function frozenEmployee($uid, $company_id)
    {
    	$row = self::where(array('uid' => $uid, 'company_id' => $company_id ))->first();
    	if($row->is_active == 0) {
    		self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('is_active' => 1));
    	} else {
    		self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('is_active' => 0));
    	}
    
    }
    
    static public function deleteEmployee($uid, $company_id)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->delete();
    
    }
    
    static public function getEmployees($company_id, $display_rows = 20) {
        $rows = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
        leftJoin('department','department.dep_id','=','user_company.dep_id')->
        select('user.*','user_company.emp_code', 'user_company.dep_name', 'user_company.emp_start','user_company.emp_end',
        		'user_company.position_title', 'department.name as dep_name', 'user_company.is_active','user_company.status as flag')->paginate($display_rows);
        return $rows;
        
    }
    
    static public function getEmpInfo($company_id, $uid) {
        $row = UserCompany::where(array('company_id' => $company_id, 'user_company.uid' => $uid ))->join('user','user.uid','=','user_company.uid')->
        select('user.*','user_company.dep_id', 'user_company.dep_name', 'user_company.emp_code', 'user_company.position_id', 'user_company.emp_start','user_company.emp_end')->first();
        return $row;
    
    }
    

    public static function infoUserCompanyAll($where)
    { 
        $row = self::select()->where($where)->first();
        return $row;
    }

    public static function insertUserCompany($ary)
    { 
        $affect = self::create($ary);
        return $affect;
    }

    public static function infoUserCompany($where,$field='id')
    {
        $result = self::select($field)->where($where)->first();
        return $result;
    }

    public static function updateUserCompany($where,$ary)
    {
        $affect = self::where($where)->update($ary);
        return $affect;
    }

    //获取公司里面没有在部门的员工
    public static function infoDepUsers($company_id,$dep_id)
    {
        /*      
        $result = self::select('user_company.dep_id','user.avatar as icon','user.uid as member_id',
        'user.username as member_name')
        ->join('user',function($join){
            $join->on(array('user_company.uid'=>'user.uid'));
        })->where(array('user_company.company_id'=>$company_id,'user_company.dep_id'=>$dep_id))->get();
        return $result;*/
        $result = self::select('user_company.dep_id','user.avatar as icon','user.uid as member_id',
                        'user.fullname as member_name')
                        ->join('user','user_company.uid','=','user.uid')
                        ->where(array('user_company.company_id'=>$company_id,'user_company.dep_id'=>$dep_id))
                        ->get();
        return $result;
    }

    //公司或部门内批量删除成员
    public static function deleteDepUsers($company_id,$ary_user)
    { 
        $result = self::whereIn('uid', $ary_user)->update(array('dep_id'=>0));
        return $result;
    }


    //查询部门成员
    public static function infoUsersInDep($company_id,$dep_id,$page_size='10',$curr_page='1')
    { 
        $total_count = UserCompany::where(array('user_company.company_id'=>$company_id,'user_company.dep_id'=>$dep_id))->count();

        $data['total_page'] = ceil($total_count / $page_size); //总页面数
        if($curr_page >= $data['total_page']){ $curr_page = $data['total_page']; } //大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $result = DB::table('user_company as comp')->select('user.uid','user.username','user.mobile','user.email','user.wechat','user.qq','user.weibo')
                ->join('user',function($join){
                    $join->on(array('comp.uid' => 'user.uid'));
                })->where(array('comp.company_id'=>$company_id,'comp.dep_id'=>$dep_id))->orderBy('user.uid')->offset($size_from)->limit($page_size)->get();
        
        $result['curr_page'] = $curr_page; //当前页面   
        $result['page_size'] = $page_size; //单页数    
        $result['total_count'] = $total_count; //总条数
        return $result;
    }

    //获取公司成员基本信息
    public static function infoMemberinfo($uid)
    { 
        $result = self::select('user_company.dep_id','user_company.dep_name','user_company.role_id as dep_position',
                            'user.fullname as member_name','user.mobile as member_phone','user.email as work_email','user_company.status as work_state','user.uid as member_id')
                        ->join('user','user.uid','=','user_company.uid')->where(array('user_company.uid'=>$uid))->first();
        return $result;
    }

    //判断用户是否在公司里
    public static function userincompany($uid,$companyid)
    {
        $result = self::select('id')->where(['uid'=>$uid,'company_id'=>$companyid])->first();
        return $result;
    }

    //更新用户在公司中的信息
    public static function updateUserCompanyInfoApi($ary,$uid,$company_id)
    {
        $result = self::where(['uid'=>$uid,'company_id'=>$company_id])
                    ->update($ary);
        return $result;
    }

    
}
