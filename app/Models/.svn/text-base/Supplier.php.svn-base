<?php

namespace App\Models;
use Eloquent;
use DB;

class Supplier extends Eloquent
{
     
    /**
     * The attributes that are mass assignable.
     * 
     * a user may belong to mutiple companys
     * 
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'supplier';

    protected $fillable = array('sup_company_id', 'company_id','contact_person','phone', 'email', 'wechat', 'weibo', 'supply_type',
            'tax_code','is_taxable','tax_ration', 'currency', 'credit_class', 'credit_mark', 'credit_quota', 'credit_time', 'trade_times',
            'settling_type','quality_level', 'sup_company_name', 'sup_company_address', 'invoice_type', 'invoice_name', 'sup_material', 
            'supply_type_code', 'tax_type_code', 'invite_status', 'is_active');
    protected $guarded = array('id');
    public $timestamps = true;
      
    static  public function addSupplier($sup_company_id, $company_id, $ceo, $legal_person, $phone, $email)
    {   
        try {
            self::create(array('sup_company_id' => $sup_company_id, 'company_id' => $company_id, 'contact_person' => $ceo? $ceo: $legal_person, 'phone' => $phone, 'email' => $email));
        } catch (\Exception $e) {
            return 0;
        }
        return 1;
    }
    
    static  public function isExistedSupplier($sup_company_id, $company_id)
    {
        $existed = self::where(array('sup_company_id' => $sup_company_id, 'company_id' => $company_id))->first();
        if($existed) return true;
        return false;
    }
    
    //TODO
    static public function updateSuppilerInfo($uid, $company_id, $dep_id, $dep_name, $emp_code, $emp_start, $emp_end) 
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('emp_code' => $emp_code, 'dep_id' => $dep_id? $dep_id: 0, 'dep_name' => $dep_name, 'emp_start' => $emp_start, 'emp_end' => $emp_end? $emp_end: null));
    }
    
    static public function assingEmployeeRole($uid, $company_id, $role_code)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('role_code' => $role_code));
        
    }
    
    static public function dismissEmployee($uid, $company_id)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->update(array('status' => 2));
    
    }
    
    static public function deleteEmployee($uid, $company_id)
    {
        self::where(array('uid' => $uid, 'company_id' => $company_id ))->delete();
    
    }
    
    static public function getEmployees($company_id, $display_rows) {
        $rows = UserCompany::where('company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
        select('user.*','user_company.emp_code', 'user_company.dep_name', 'user_company.emp_start','user_company.emp_end','user_company.status as flag')->paginate($display_rows);
        return $rows;
        
    }
    
    static public function getEmpInfo($company_id, $uid)
    {
        $row = UserCompany::where(array('company_id' => $company_id, 'user_company.uid' => $uid ))->join('user','user.uid','=','user_company.uid')->
        select('user.*','user_company.dep_name', 'user_company.emp_code', 'user_company.emp_start','user_company.emp_end')->first();
        return $row;
    
    }

    //修改供应商资料详情
    public static function updateSuper($company_id,$sup_company_id,$array)
    {
        $where = array('company_id'=>$company_id,'sup_company_id'=>$sup_company_id);
        $result = self::where($where)->update($array);
        return $result;
    }

    //获取供应商资料详情
    public static function getSuper($sup_company_id)
    { 
        $where = array('sup_company_id'=>$sup_company_id);
        $result = self::select('company_id','contact_person as contact_man','credit_class','email','is_taxable as is_tax','phone',
        'quality_level','sup_company_address','sup_company_id','sup_company_name','supply_type','invoice_type as tax_code',
        'supply_type_code','tax_type_code','tax_ratio','invoice_type as tax_type','invoice_type as  tax_type_code')->where($where)->first();
        return $result;
    }

    //查询是否是供应商关系
    public static function supplierRelation($company_id,$sup_company_id){ 
        $result = self::where(array('company_id'=>$company_id,'sup_company_id'=>$sup_company_id))->first();
        return $result;
    }

    //删除企业的供应商
    public static function deleteSupplier($company_id,$cst_sup_id)
    {
        $result = self::where(array('company_id'=>$company_id,'sup_company_id'=>$cst_sup_id))->delete();
        return $result;     
    }


    //获取供应商列表
    public static function getSuperlist($company_id,$page_size,$curr_page)
    { 
        $total_count = self::select('id')->where(array('company_id'=>$company_id))->count();

        $data['total_page'] = ceil($total_count / $page_size); //总页面数
        if($curr_page >= $data['total_page']){ $curr_page = $data['total_page']; } //大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['cst_info'] = self::select('credit_class','quality_level','sup_company_id as sup_company_id', 
                'sup_company_name as sup_company_name')
                ->where(array('company_id'=>$company_id))
                ->offset($size_from)->limit($page_size)->get();
        
        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数

        return $data;
    }
 
    //搜索供应商列表
    public static function searchSupplier($company_id,$cst_sup_name,$page_size,$curr_page)
    {
        //$total_count = self::select('id')->where(array('company_id'=>$company_id))
        //      ->where('sup_company_name','like','%'.$cst_sup_name.'%')->count();
        $sql = "select count(*) as num from supplier where company_id = ? and locate(?,sup_company_name)";
        $total_count = DB::select($sql,[$company_id,$cst_sup_name]);
        $total_count = $total_count[0]->num;
        $data['total_page'] = ceil($total_count / $page_size); //总页面数
        if($curr_page >= $data['total_page']){ $curr_page = $data['total_page']; } //大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0;}

        $data['cst_sup'] = self::select('sup_company_id','sup_company_address','sup_company_name')
                ->where(array('company_id'=>$company_id))->where('sup_company_name','like','%'.$cst_sup_name.'%')
                ->offset($size_from)->limit($page_size)->get();
        $sql = "select sup_company_id, sup_company_address, sup_company_name from supplier where (company_id = ?) 
                and locate(?,sup_company_name) limit ?,?";
        $data['cst_sup'] = DB::select($sql,[$company_id,$cst_sup_name,$page_size,$size_from]);

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数

        return $data;
    }

    //更新供应商信息
    public static function updateSupplierInvite($sup_company_id,$company_id,$array)
    { 
        $result = Supplier::where(array('sup_company_id'=>$sup_company_id,'company_id'=>$company_id))
                        ->update($array);
        return $result;
    }

    //新增供应商信息
    public static function createSupplierInvite($array)
    { 
        $result = Supplier::create($array);
        return $result;
    }

}
