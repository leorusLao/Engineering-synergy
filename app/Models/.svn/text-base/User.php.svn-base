<?php

namespace App\Models;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $table = 'user';

    protected $fillable = array('uid','username','password','usercode', 'fullname',
            'mobile','mobile_validated','wechat', 'avatar','email','email_validated', 'banded_email', 'qq','weibo',
            'gender', 'birthdate',' country', 'province','city', 'country_id', 'province_id','city_id','address','postcode',
            'stickness','is_active', 'status','attached_company','group_user','referee','ip_address','api_token');
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

    public static function updateUser($where,$ary)
    { 
        $result = self::where($where)->update($ary);
        return $result;
    }

    public static function infoUser($where,$field='id')
    {
        $result = self::select($field)->where($where)->first();
        return $result;
    }

    public static function isExistedEmail($email)
    {
        $result = User::where('email', $email)->first();
        
        if($result) return true;
        
        return false;
    }

    public static function isExistedMobile($mobile)
    {
    	$result = User::where('mobile', $mobile)->first();
    
    	if($result) return true;
    
    	return false;
    }
    
    public static function isBandedEmailMobile($email, $mobile)
    {
    	
    	$result = User::where(array('email' => $email, 'mobile' => $mobile))->first();
    	
    	if($result) return true;
    	
    	return false;
    }
    
    public static function insertUser($ary)
    { 
        $affect = self::create($ary);
        return $affect;
    }
 
    public static function addUserByEmail($uid, $fullname, $email, $password, $mobile,  $gendar = 0, $birthdate = '1900-12-31', $province = '', $city = '', $address = '', $postcode = '')  
    {    
        
        $country_id = Session::get('USERINFO')->countryId;
        
        list($nickname,$domain) = explode('@',$email);
        $gendar = strtolower($gendar);
        if( $gendar == '男' || $gendar == 'm' || $gendar == 'male' ) {
            $gendar = 1;
        } else if ( $gendar == '女' || $gendar == 'f' || $gendar == 'femal' ) {
            $gendar = 2;
        } else {
            $gendar = 0;
        }
        $province_id = 0; 
        $provinces = Province::where(array('country_id' => $country_id, 'name' => $province))->first();
        if($provinces) {
            $province_id = $provinces->province_id;
        }
        
        $city_id = 0;
        $cities = City::where(array('country_id' => $country_id, 'name' => $city ))->first();
        if($cities) {
            $city_id = $cities->city_id;
        }
        
        User::create(array('uid' => $uid, 'email' => $email, 'fullname' => $fullname, 'username' => $nickname, 'password' =>  Hash::make($password), 'mobile' => empty($mobile)? '': $mobile, 
                'banded_email' => '1', 'gendar' => $gendar, 'birthdate' => empty($birthdate) ? '1900-12-31' : $birthdate, 'province' => $province, 'city' => $city, 'country_id' => $country_id,
                'province_id' => $province_id, 'city_id' => $city_id, 'address' => $address, 'postcode' => $postcode
        ));
            
        return $uid;
                 
    }
    
    public static function addUserByEmailSimple($uid, $fullname, $email, $password, $mobile)
    {
    
        $country_id = Session::get('USERINFO')->countryId;
        list($nickname,$domain) = explode('@',$email);
         
        User::create(array('uid' => $uid, 'email' => $email, 'fullname' => $fullname, 'username' => $nickname, 'password' =>  Hash::make($password), 'mobile' => empty($mobile)? '': $mobile,
                'banded_email' => '1'));
    
        return $uid;
            
    }
    
    static public function resetPasswordByEmail($email, $password) 
    {
        return User::where('email', $email)->update(array('password' => Hash::make($password)));
    }
    
    static public function resetPasswordByMobilephone($phone, $password)
    {
        return User::where('mobile', $phone)->update(array('password' => Hash::make($password)));
    }

    //搜索用户信息
    public static function searchUser($username)
    {
        //$sql = "select avatar, uid, username from user where username like '%$username%'";
        $sql = "select avatar, uid, username from user where locate(?,username) limit 10";
        $result = DB::select($sql,array($username));
        return $result;
    }

    //朋友相关信息
    public static function infoFriendUser($uid)
    { 
        $result = self::select('avatar as fri_avatar','email as fri_email','fullname as fri_name','mobile as fri_phone')
                ->where(array('uid'=>$uid))->first();
        return $result;
    }

    //公司员工信息
    public static function infoCompanyUser($companyid)
    { 
        $result = self::select('user.uid','user.fullname','user.username')->join('user_company','user_company.uid','user.uid')
                        ->where(array('user_company.company_id'=>$companyid))->get()->toArray();
        return $result;
    }

    //更新用户信息API
    public static function updateUserInfoApi($user,$uid)
    { 
        $result = self::where(['uid'=>$uid])->update($user);
        return $result;
    }

    //绑定用户wechat的API
    public static function wechatBindApi($uid,$unionid)
    { 
        $result = self::where(['uid'=>$uid])->update(['wechat'=>$unionid]);
        return $result;
    }

    public static function isExistedUid($uid) {
    	$result = User::where('uid', $uid)->first();
    	
    	if($result) return true;
    	
    	return false;
    }

    public static function httpsRequest($url, $data = [], $header = '')
	{
		$ch = curl_init();  //初始化
		curl_setopt($ch,CURLOPT_URL,$url);  //设置url
		// curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);  //设置http验证方法
		if(stripos($url, 'https') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //https 跳过证书检查
        }
        $header && curl_setopt($ch,CURLOPT_HEADER,0);  //设置头信息
        $header && curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if(!empty($data)){
            curl_setopt($ch,CURLOPT_POST,1);   //post方式
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);  //post提交数据
        }

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //设置curl_exec获取的信息的返回方式

        $result = curl_exec($ch);
		curl_close($ch);

		if($result) {
			$arr = json_decode($result, true);
			if(is_array($arr)) {
				return $arr;
			}
        }

        return $result;
    }
  
}
