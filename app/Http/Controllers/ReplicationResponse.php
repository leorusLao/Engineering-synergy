<?php

namespace App\Http\Controllers;
use App;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use App\Models\Department;
use App\Models\User;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\UserResourceRole;
use Illuminate\Support\Facades\Log;
 
class ReplicationResponse extends  Controller
{

	public function __construct()
	{
		session_start();
		if(Session::has('locale')){
			$this->locale = Session::get('locale');
		}
		else if(isset($_COOKIE['locale'])){
			$this->locale = $_COOKIE['locale'];
		}
		else{
			$this->locale = config('app.locale');
		}

	}
 
	public static function addUser(Request $request, Response $response)
	{   //Master site call to add user at busite
		$apiKey = $request->get('apiKey');
		$apiSecret = $request->get('apiSecret');
	
		$hostKey = getenv('API_KEY');
		$hostSecret = getenv('API_SECRET');
	    
		/*
		if($apiKey != $hostKey || $apiSecret != $hostSecret) {
			$response ()->json ( [
					'result' => '1001',
					'reason' => 'Authentication Failed'
			] );
		}
		*/
		$company_id = $request->company_id;
		$uid = $request->uid;
		$existed = User::where('uid',$request->get('uid'))->first();
		
		DB::beginTransaction();
		try {
			if(!$existed) {//主站在BU创建用户
				User::create(array('id' => $request->id,  'uid' => $request->uid, 'email' => $request->email,
						'fullname' => $request->fullname, 'username' => $request->username, 'password' =>  $request->password,
						'mobile' => $request->mobile, 'banded_email' => $request->banded_email, 
						'wechat' => $request->wechat, 'gendar' => $request->gendar,
						'avatar' => $request->avatar,
						'birthdate' => $request->birthdate, 'province' => $request->province, 'city' => $request->city,
						'country_id' => $request->country_id, 'province_id' => $request->province_id, 'city_id' => $request->city_id,
						'address' => $request->address, 'postcode' => $request->postcode,
						'stickness' => $request->stickness, 'is_active' => $request->is_active,
						'prefer_language' => $request->perfer_language,
						'status' => $request->status,
						'api_token' => $request->api_token
				));
			}
		
			//user associate to company
				
			$existed = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id ))->first();
			if(!$existed) {
				UserCompany::create(array('uid' => $uid, 'role_id' => $request->role_id, 'company_id' => $company_id));
			}
			DB::commit();
			 	
			return json_encode( [
					'result' => '0000',
					'reason' => 'Created User Successfuly or User Existed Already']);
		} catch (\Exception $e) {
			DB::rollback();
			return json_encode ( [
					'result' => '0002',
					'reason' => 'Failed to Create User'
			] );
		}
	}
	
 public static function updateUser(Request $request, Response $response)
 {    
	$apiKey = $request->get('apiKey');
	$apiSecret = $request->get('apiSecret');
	
	$hostKey = getenv('API_KEY');
	$hostSecret = getenv('API_SECRET');
	 
	/*
	 if($apiKey != $hostKey || $apiSecret != $hostSecret) {
	 $response ()->json ( [
	 'result' => '1001',
	 'reason' => 'Authentication Failed'
	 ] );
	 }
	 */
	 
	$uid = $request->uid;
	$existed = User::where('uid',$uid)->first();
	 
	DB::beginTransaction();
	try {
		if($existed) {
		  	 
			User::where('uid', $uid)->update(array(
					'email' => $request->email,
					'fullname' => $request->fullname,
					'username' => $request->username, 
					'mobile' => $request->mobile,  
					'country' => $request->country,
					'province' => $request->province, 
					'city' => $request->city,
					'country_id' => $request->country_id, 
					'province_id' => $request->province_id, 
					'city_id' => $request->city_id,
					'address' => $request->address, 
					'postcode' => $request->postcode
					 
			));
		}
	 		 
	    DB::commit();
			
		return json_encode( [
				'result' => '0000',
				'reason' => 'Update User Successfuly or User Existed Already']);
	} catch (\Exception $e) {
		DB::rollback();
		return json_encode ( [
				'result' => '0002',
				'reason' => 'Failed to Update User'
		] );
	}
 }
	
	
	public static function createCompany(Request $request, Response $response)
	{
		$apiKey = $request->get('apiKey');
		$apiSecret = $request->get('apiSecret');
	
		$hostKey = getenv('API_KEY');
		$hostSecret = getenv('API_SECRET');
        
        /*	
		if($apiKey != $hostKey || $apiSecret != $hostSecret) {
			$response ()->json ( [
					'result' => '1001',
					'reason' => 'Authentication Failed'
			] );
		}
		*/
		$company_id = $request->company_id;
		 
		DB::beginTransaction();
		try {
			$existed = Company::where('company_id',$company_id)->first();
	 	    if(!$existed) {//1. 在BU创建Company
	 	    	$companyArray = array('company_id' => $request->get('company_id'),
	 	    			'company_name' => $request->get('company_name'),
	 	    			'domain_id' => $request->get('domain_id'),//bu只需要自己的domain_id;不需要再forward_domain
	 	    			'reg_no' => $request->has('reg_no') ? $request->get('reg_no'):'', 
	 	    			'license' => $request->has('license') ? $request->get('license'):'', 
	 	    			'legal_person' => $request->has('legal_person') ? $request->get('legal_person'):'',
	 	    			'ceo' => $request->has('ceo') ? $request->get('ceo'):'', 
	 	    			'phone' => $request->has('phone') ? $request->get('phone'):'',
	 	    			'fax' => $request->has('fax') ? $request->get('fax'):'',
	 	    			'email' => $request->has('email') ? $request->get('email'):'', 
	 	    			'industry' => $request->has('industry') ? $request->get('industry'):0,
	 	    			'company_type' => $request->has('company_type') ? $request->get('company_type'):0, 
	 	    			'country' => $request->has('country') ? $request->get('country'):0, 
	 	    			'province' => $request->has('province') ? $request->get('province'):0, 
	 	    			'city' => $request->has('city') ? $request->get('city'):0, 
	 	    			'address' => $request->has('address') ? $request->get('address'):'',
	 	    			'postcode' => $request->has('postcode') ? $request->get('postcode'):''	);
	 	    	  
	 	     	         Company::create($companyArray);
	 	     	         
	 	      
	 	    }
		    //2. 初始化各编码起始账号及缺省部门设置
		    InitController::companyInit($company_id);

		    //3. 自定义角色起始号
		    InitController::userRoleSelfDefineStarter($company_id);
		    
		    //4. 初始化角色资源控制
		    AccountController::initializeRoleResource($company_id);
		    
		    //5. 创建用户
		    $uid = $request->uid;
		    $existed = User::where('uid',$request->get('uid'))->first();
		    if(!$existed) {//在BU创建用户
		    	$userArray = array(
		    			'id' => $request->id, 
		    			'uid' => $request->uid, 
		    			'fullname' => $request->has('fullname')? $request->fullname:'', 
		    			'username' => $request->has('username')? $request->username:'', 
		    			'password' => $request->has('password') ? $request->password:'',
		    			'banded_email' => $request->banded_email? $request->banded_email: 0,
		    			'gender' => $request->has('gender') ? $request->gender : 0,
		    			'avatar' => $request->has('avatar') ? $request->avatar : '',
		    			'birthdate' => $request->has('birthdate')? $request->birthdate : '1900-12-31', 
		    			'country' => $request->has('country') ? $request->country : '',
		    			'province' => $request->has('province') ? $request->province: '', 
		    			'city' => $request->has('city')? $request->city: '',
		    			'country_id' => $request->has('country_id') ? $request->country_id: 0, 
		    			'province_id' => $request->has('province_id')? $request->province_id : 0,
		    			'city_id' => $request->has('city_id')? $request->city_id: 0,
		    			'address' => $request->has('address')? $request->address: '', 
		    			'postcode' => $request->has('postcode')? $request->postcode: '',
		    			'stickness' => $request->has('stickness') ? $request->stickness: 1, 
		    			'is_active' => $request->has('is_active')? $request->is_active: 1,
		    			'prefer_language' => $request->has('prefer_language') ? $request->perfer_language: 1,
		    			'status' => $request->has('status') ? $request->status : 1,
		    			'api_token' => $request->has('wechat')? $request->api_token:NULL
		    		);
	    		if($request->has('mobile')){ 
	    			$userArray['mobile'] = $request->mobile;
	    		}
	    		if($request->has('wechat')){ 
	    			$userArray['wechat'] = $request->wechat;
	    		}
	    		if($request->has('email')){ 
	    			$userArray['email'] = $request->email;
	    		}
		    	User::create($userArray);
		    }
		    //6. 用户关联公司
		    $existed = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id ))->first();
		    if(!$existed) {
				$dep_id = Department::where(['dep_code' => 'Dep01', 'company_id' => $company_id ])->value('dep_id');
		    	UserCompany::create(array('uid' => $uid, 'role_id' => $request->role_id, 'company_id' => $company_id, 'dep_id' => $dep_id));
		    }
		    DB::commit();
		     
		    return json_encode ( [
		    			'result' => '0000',
		    			'reason' => 'Created Company Successfully'
		    ] );
		} catch (\Exception $e) {
		    	DB::rollback();
		    	 
		    	return json_encode ( [
		    			'result' => '0003',
		    			'reason' => 'Failed to Create Company'
		    	] );
		}
	    
	}
	
	public static function updatePassword(Request $request, Response $response)
	{
		$apiKey = $request->get('apiKey');
		$apiSecret = $request->get('apiSecret');
	
		$hostKey = getenv('API_KEY');
		$hostSecret = getenv('API_SECRET');
	
		/*
		 if($apiKey != $hostKey || $apiSecret != $hostSecret) {
		 $response ()->json ( [
		 'result' => '1001',
		 'reason' => 'Authentication Failed'
		 ] );
		 }
		 */
	
		$uid = $request->uid;
		  
		try {
				User::where('uid', $uid)->update(array('password' => $request->password));
			  
			    return json_encode( [
					'result' => '0000',
					'reason' => 'Updated Password Successfuly']);
		 } catch (\Exception $e) {
		 
			return json_encode ( [
					'result' => '0002',
					'reason' => 'Failed to Update Password'
			] );
		}
	}
	
	public static function updateCompany(Request $request, Response $response)
	{
		$apiKey = $request->get('apiKey');
		$apiSecret = $request->get('apiSecret');
	
		$hostKey = getenv('API_KEY');
		$hostSecret = getenv('API_SECRET');
	
		/*
		 if($apiKey != $hostKey || $apiSecret != $hostSecret) {
		 $response ()->json ( [
		 'result' => '1001',
		 'reason' => 'Authentication Failed'
		 ] );
		 }
		 */
		 
		$company_id = $request->company_id;
	 	try {
			Company::where('company_id', $company_id)->update(array('company_name' => $request->get('company_name'), 'reg_no' => empty($request->get('reg_no'))?'':$request->get('reg_no'), 'biz_des' => empty($request->get('biz_des'))?'':$request->get('biz_des'),
							'legal_person' => empty($request->get('legal_person'))?'':$request->get('legal_person'), 'ceo' => empty($request->get('ceo'))?'':$request->get('ceo'), 'phone' => $request->get('phone'),
							'fax' => empty($request->get('fax'))? '':$request->get('fax'), 'email' => $request->get('email'), 'website' => empty($request->get('website'))?'':$request->get('website'),
							'wechat_pub_acct' => empty($request->get('wechat_pub_acct'))?'':$request->get('wechat_pub_acct'),
							'industry' => $request->get('industry'),
							'company_type' => $request->get('company_type'), 'country' => $request->get('country'), 'province' => $request->get('province'), 'city' => $request->get('city'), 'address' => $request->get('address'),
							'postcode' => $request->get('postcode')));
			 
			return json_encode( [
					'result' => '0000',
					'reason' => 'Updated Company Successfuly']);
		} catch (\Exception $e) {
			 
			return json_encode ( [
					'result' => '0002',
					'reason' => 'Failed to Company Password'
			] );
		}
	}
 
   public static function checkEmail(Request $request, Response $response)
   {
		$email = $request->email;
		$existed = User::where('email',$email)->first();
		if($existed) {
	
			return json_encode( [
					'result' => '0000',
					'reason' => 'Existed Email']);
		}
	
		return json_encode( [
				'result' => '1001',
				'reason' => 'Nonexisted Email']);
  }
  
  public static function checkMobile(Request $request, Response $response)
  {
  	$mobile = $request->mobile;
  	$existed = User::where('mobile',$mobile)->first();
  	if($existed) {
  		return json_encode( [
  				'result' => '0000',
  				'reason' => 'Existed Mobile']);
  	}
  	return json_encode( [
  			'result' => '1001',
  			'reason' => 'Nonexisted Mobile']);
  }
  
  public static function addCustCompanyInfo(Request $request, Response $response)
  {
  	
  	$existed = Company::where('company_id', $request->get('company_id'))->first();
  	if(!$existed) {//1. 在BU创建Company
  		$companyArray = array('company_id' => $request->get('company_id'),
  				'company_name' => $request->get('company_name'),
  				'domain_id' => $request->get('domain_id'),//bu只需要自己的domain_id;不需要再forward_domain
  				'reg_no' => $request->has('reg_no') ? $request->get('reg_no'):'',
  				'license' => $request->has('license') ? $request->get('license'):'',
  				'legal_person' => $request->has('legal_person') ? $request->get('legal_person'):'',
  				'ceo' => $request->has('ceo') ? $request->get('ceo'):'',
  				'phone' => $request->has('phone') ? $request->get('phone'):'',
  				'fax' => $request->has('fax') ? $request->get('fax'):'',
  				'email' => $request->has('email') ? $request->get('email'):'',
  				'industry' => $request->has('industry') ? $request->get('industry'):0,
  				'company_type' => $request->has('company_type') ? $request->get('company_type'):0,
  				'country' => $request->has('country') ? $request->get('country'):0,
  				'province' => $request->has('province') ? $request->get('province'):0,
  				'city' => $request->has('city') ? $request->get('city'):0,
  				'address' => $request->has('address') ? $request->get('address'):'',
  				'postcode' => $request->has('postcode') ? $request->get('postcode'):''	);
  	
  		 Company::create($companyArray);
  	 	 
  	  }
  }
  public static function addUserToMaster(Request $request, Response $response)
  {   //Master site call to add user at busite
	$apiKey = $request->get('apiKey');
	$apiSecret = $request->get('apiSecret');
	
	$hostKey = getenv('API_KEY');
	$hostSecret = getenv('API_SECRET');
	 
	/*
	 if($apiKey != $hostKey || $apiSecret != $hostSecret) {
	 $response ()->json ( [
	 'result' => '1001',
	 'reason' => 'Authentication Failed'
	 ] );
	 }
	 */
	
	$company_id = $request->company_id;
	$email = $request->email;
	$existed = User::where('email',$email)->first();
	DB::beginTransaction();
	
	try {
		if(!$existed) {//在主站在创建用户
			$rows = User::where('mobile', $request->get('mobile'))->count();
			if($rows == 1) {
				//mobile has an account
				$id = User::create(array('email' => $request->get('email'),
						'fullname' => $request->get('fullname'),
						'username' => '',
						'password' =>  Hash::make($request->get('password'))
				))->id;
			} else {
				$id = User::create(array('email' => $request->get('email'), 
				 		'fullname' => $request->get('fullname'),
					    'username' => '', 
					    'password' =>  Hash::make($request->get('password')), 
				 		'mobile' => $request->get('mobile') ? $request->get('mobile'): NULL
				))->id;
			}
			User::where('id',$id)->update(array('uid' => $id));
			$uid = $id;
			
		} else {//existed email
			//check if email and mobile is the same account if mobile is not empty
			if(!empty( $request->get('mobile'))) {
				$rows = User::where('email', $email)->orWhere('mobile', $request->get('mobile'))->count();
				if($rows == 2) {// email and mobile have different account
					//drop mobile
					User::where('email', $email)->update(array(
							'fullname' => $request->get('fullname'),
							'password' =>  Hash::make($request->get('password')),
					));
					$uid = $existed->uid;
					$id = $existed->id;
				} else {
					User::where('email', $email)->update(array(
							'fullname' => $request->get('fullname'),
						    'password' =>  Hash::make($request->get('password')),
							'mobile' => $request->get('mobile')
					));
					$uid = $existed->uid;
					$id = $existed->id;
				}
			} else {
					User::where('email', $email)->update(array(
					    'fullname' => $request->get('fullname'),
					    'password' =>  Hash::make($request->get('password')), 
				 	));
					$uid = $existed->uid;
					$id = $existed->id;
			}
		}
	     
		//user associate to company
	
		$existed = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id ))->first();
		if(!$existed) {
			UserCompany::create(array('uid' => $uid, 'role_id' => '23', 'company_id' => $company_id));
		}
		DB::commit();
			
		return json_encode( [
				'result' => '0000',
				'id' => $id,
				'uid' => $uid,
				'reason' => 'Created User Successfuly or User Existed Already']);
	 } catch (\Exception $e) {
		DB::rollback();
		return json_encode ( [
				'result' => '0002',
				'reason' => 'Failed to Create User'
		] );
	 }
  }
     
}