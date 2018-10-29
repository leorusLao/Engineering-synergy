<?php

namespace App\Models;
use Eloquent;
use DB;

class Company extends Eloquent
{
	 
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $table = 'company';

	protected $fillable = array('company_id','company_name','company_code', 'forward_domain','domain_id',
			'reg_no','biz_des','biz_size','license','legal_person', 'phone','website','fax', 'email',
			'wechat_public_acct','weibo','ceo', 'ceo_id', 'ceo_wechat', 'credibility','company_type',
			'industry','country','province','city','address','postcode','is_approved',
			'effect_date', 'expiry_date','is_active', 'status', 'stickness','domain_id');
	protected $guarded = array('id');
	public $timestamps = true;

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
			'password','remember_token',
	];
	
	static public function getExcludeMeCompanies($my_company, $page_rows = 20)  
	{
		return  self::where('company_id', '!=', $my_company)->where('status',1)->paginate($page_rows);
	}

	//查询公司信息
	public static function infoCompany($company_id)
	{ 
		$where = array('company_id'=>$company_id);
		$result = self::where($where)->first();
		$result['bu_id'] = $result['domain_id'];
		return $result;
	}

	//查询公司基本信息
	public static function companyName($company_id)
	{ 
		$where = array('company_id'=>$company_id);
		$result = self::select('company_name')->where($where)->first();
		return $result;
	}

	//查询公司是否存在
	public static function companyExit($where)
	{ 
		$result = self::where($where)->first();
		return $result;
	}

	//查询公司名除了本公司是否有重复
	public static function companyNameExit($company_id,$company_name)
	{ 
		$sql = "select company_id from company where company_name = ? && company_id != ?";
		$result = DB::select($sql,[$company_name,$company_id]);
		return $result;
	}

	//查询公司部分信息
	public static function searchCompany($company_name)
	{
		// $result = self::select('company_id','address as company_address','company_name')
		// 			->where('company_name','like','%'.$company_name.'%')->limit(10)->get();
		$sql = "select company_id,address as company_address,company_name from company where locate(?,company_name) limit 10";
		$result = DB::select($sql,[$company_name]);
		return $result;
	}


	//查询公司对应的bu_site
    public static function getBuSite($company_id)
    { 
        $result = Company::select('bu_site')->join('buhost','company.domain_id','=','buhost.bu_id')
                            ->where(['company.company_id'=>$company_id])->first();
        return $result;
    }
    
    public static function getCompaniesByBuid($buid, $page_rows = 20)
    {
    	return  self::where(array('domain_id' =>  $buid, 'status' => 1, 'is_approved' => 1))->paginate($page_rows);
    }
    
    public static function getNewArrivalCompaniesByBuid($buid, $page_rows = 20)
    {
    	return  self::where(array('domain_id' =>  $buid, 'status' => 1))->where('is_approved','!=',1)->paginate($page_rows);
    }
}
