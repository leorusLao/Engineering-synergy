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
 
use App\Models\User;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\UserResourceRole;
 
class ReplicationRequest extends  Controller
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
 
  
	public static function rpcAddCompany($site, array $data)
	{
 		$data_string = json_encode($data);                                             
		$ch = curl_init('http://'.$site.'/api/replication/create-company');                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                             
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
    	'Content-Type: application/json',                                           
    	'Content-Length: ' . strlen($data_string))                                  
	    );
		
		$result = curl_exec($ch);
		return $result;

	}
		
	public static function rpcAddUser($site, array $data){
		$data_string = json_encode($data);
		$ch = curl_init('http://'.$site.'/api/replication/add-user');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		$result = curl_exec($ch);
		return $result;
		
	}
	
	public static function rpcUpdateUser($site, array $data)
	{
		$data_string = json_encode($data);
		$ch = curl_init('http://'.$site.'/api/replication/update-user');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		$result = curl_exec($ch);
	 
		return $result;
	
	}
	
	public static function  rpcUpdatePassword($site, array $data)
	{
		$data_string = json_encode($data);
		$ch = curl_init('http://'.$site.'/api/replication/update-password');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		$result = curl_exec($ch);
		 
		return $result;
	
	}
	
	public static function  rpcUpdateCompany($site, array $data)
	{
		$data_string = json_encode($data);
		$ch = curl_init('http://'.$site.'/api/replication/update-company');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
	 
		$result = curl_exec($ch);
		return $result;
	
	}
	
	public static function rpcAddUserAndCompany( $site,  array $userArray, array $companyArray)
	{
		$tempArray = array_merge($userArray, $companyArray);
		$data_string = json_encode($tempArray);
		$ch = curl_init('http://'.$site.'/api/replication/create-company');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		
		$result = curl_exec($ch);
		return $result;
		
	}
	
	public static function  rpcAddUserToMaster(array $data){
		$data_string = json_encode($data);
		$ch = curl_init('http://www.mowork.cn/api/replication/add-user-tomaster');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		$result = curl_exec($ch);
		return $result;
	
  }
  
  public static function rpcMasterExistedEmail($email) 
  {
  	$data_string = json_encode(array('email' => $email));
  	$ch = curl_init('http://www.mowork.cn/api/replication/check-email');
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Content-Length: ' . strlen($data_string))
  			);
  	$result = curl_exec($ch);
  	return $result;
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
   
  public static function rpcMasterExistedMobile($mobile)
  {
  	$data_string = json_encode(array('mobile' => $mobile));
  	$ch = curl_init('http://www.mowork.cn/api/replication/check-mobile');
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Content-Length: ' . strlen($data_string))
  			);
  	$result = curl_exec($ch);
  	return $result;
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
   
  public static function rpcAddCustCompanyInfo(array $customer, $site)
  {
   
  	$data_string = json_encode($customer);
  	$ch = curl_init('http://'.$site.'/api/replication/add-cust-company-info');
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Content-Length: ' . strlen($data_string))
  			);
  	
  	$result = curl_exec($ch);
  	return $result;
  }
  
   /** 
    * 邀请公司供应商(BU)：
    * @param 
    * @return 
    */
	public static function rpcInviteSup($request,$site)
	{
		$data_string = json_encode($request);
		$ch = curl_init('http://'.$site.'/api/invite/sup');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		
		$result = curl_exec($ch);
		return $result;
		
	}
	 
 
    /** 
    * 邀请公司内部人员(BU)：
    * @param 
    * @return 
    */
	public static function rpcInviteMember($request,$site)
	{
		$data_string = json_encode($request);
		$ch = curl_init('http://'.$site.'/api/invite/member');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		
		$result = curl_exec($ch);
		return $result;
		
	}
	

    /** 
    * 邀请公司外部人员(BU)：
    * @param 
    * @return 
    */
	public static function rpcInviteFriend($request,$site)
	{
		$data_string = json_encode($request);
		$ch = curl_init('http://'.$site.'/api/invite/friend');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);
		
		$result = curl_exec($ch);
		return $result;
		
	}

 
    /** 
    * 邀请公司客户(BU)：
    * @param 
    * @return 
    */
	public static function rpcInviteCustomer($request,$site)
	{
		$data_string = json_encode($request);
		$ch = curl_init('http://'.$site.'/api/invite/cst');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string))
				);

		$result = curl_exec($ch);
		return $result;
		
	}

}
