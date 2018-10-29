<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
 
use Illuminate\Support\Facades\Validator;
use App\models\User;
 
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\models\OAuthAccessToken;
use App\models\OAuthTokenHistory;

class ApiFront extends  Controller
{
	public static function usernamePasswordUnmatch(Response $response)
	{
		
		$res = array('data' => array('result' => 'failure','reasonCode' => '-1020', 'description' => Lang::get('mowork.account_password_unmatch'),'token' => Session::token()),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);
		
		return $json;
	}
	
	public static function loginSuccess(array $user, Response $response)
	{
		$user = (Object) $user;
		
		$token = randomString(32);
		$timestamp =  date('Y-m-d H:i:s');
		$delay =  getenv('TOKEN_LIFESPAN') ?: 30;
		$expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));
	  
		$find = OAuthAccessToken::where('uid',$user->uid)->where('username',$user->username)->where('identity_type',$user->identityType)->first();
		if ($find) {
			OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
			'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));
			
			OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
		} else {
			 
			OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => $user->clientType, 'username' => $user->username, 'identity_type' => $user->identityType,
			'token' => $token, 'expiry_at' => $expiry_at ));
		}
		
		$res = array('data' => array('result' => 'success','reasonCode' => 0, 'uid' => $user->uid,'userRole' => $user->userRole, 'description' => '', 'token' => $token,'timestamp' => $timestamp),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);
		
		return $json;
	}
	
	public static function signup($signupMethod,$userIdentity,$password,$validatingNumber,$response)
	{
		/**
	
		* @param signupMethod: signup method
		1: mobile phone
		2: email
		3: wechat
		other: invalid method
	
		* @param $userIdentity: signup identification
		mobile phone number if $signupMethod = 1
		email address if $signupMethod = 2
		wehcat id if $signupMethod = 3
	
		* @param $password: password
		$password required at least 6 characters if signupMethod = 1,2
		$password is empty if signupMethod = 3
	
		* @param array $validatingCode: validating code
		$validatingNumber required if signupMethod = 1
		$validatingNumber empty if signupMethod = 2,3
	
		* return array('code' => $code, 'message' => $message)
		result: success | failure
		 
		-1: failure
		-101： 无效的注册方式
		-110： 密码太短
		-111:无效的Email格式
		-112： 手机验证码不匹配
		-113:微信授权失败
		-114： 该用户已存在
		-200： 数据库操作失败
		0： success,注册成功
		*/
		$status = $response->status();
		
		/*
		 * 	
		1: mobile phone
		2: email
		3: wechat
		other: invalid method
		 * 
		 */
		$result = 'failure';
		
		switch ($signupMethod){
			case 1://mobile phone
				if(! self::isValidatingNumberMatched($validatingNumber)) {
					$reasonCode = -1011;
					$description = Lang::get('mowork.validating_number_unmatch');
					break;
				}
				elseif(! self::isPasswordLongEnough($password, 6)) {
					$reasonCode = -1010;
					$description = Lang::get('mowork.password_min_length');
				} elseif(self::isExistedMobilePhone($userIdentity)) {
					$reasonCode = -1021;
					$description = Lang::get('mowork.user_existed');
					break;
				}
				self::signupWithMobilePhone($userIdentity,$password,$validatingCode);
				break;
			case 2://email
				 
				if(! self::isValidatedEmailFormat($userIdentity)) {
					$reasonCode = -1012;
					$description = Lang::get('mowork.invalid_email');
					break;
				}
				elseif(! self::isPasswordLongEnough($password, 6)){
					$reasonCode = -1010;
					$description = Lang::get('mowork.password_min_length');
				}
				elseif(self::isExistedEmail($userIdentity)){
					$reasonCode = -1020;
					$description = Lang::get('mowork.user_existed');
					break;
				}
				 
					 
				if(self::signupWithEmail($userIdentity,$password)) { 
				
					$result = 'success';
				    $reasonCode = 0;
				    $description = Lang::get('mowork.signup_success');
				}
				else {
					$reasonCode = -1000;
					$description = Lang::get('mowork.database_error');
				}
				
				break;
			case 3:
				break;
				self::signupWithWechat();
			default:
				$reasonCode = -1001;
				$description = Lang::get('mowork.invalid_signup_mehtod');
				break;
		}
		
		$res = array('data' => array('result' => $result,'reasonCode' => $reasonCode, 'description' => $description,'token' => Session::token()),'status' => $status);
		 
		return json_encode($res, JSON_UNESCAPED_UNICODE);
	
	}
	
	public static function isValidatingNumberMatched($validatingNumber)
	{
		//TODO
		$sms = 123456;
	    return  $validatingNumber == $sms ? true : false; 	
	}
	
	public static function isExistedMobilePhone($phone)
	{
		if( !is_null(User::where('email', $email)->first()) ){
			return true;
		}
		return false;
	}
	
	public static function signupWithMobilePhone($phone,$password) 
	{
		$success = true;
		try {
			$id = User::create(array('email' => $email,'password' => Hash::make($password)))->id;
			User::where('id',$id)->update(array('uid' => $id));
		} catch (Exception $e) {
			$success = false;
		}
		
		return $success;
	}
	
	public static function signupWithEmail($email,$password)
	{
		   
		try {
			$id = User::create(array('email' => $email,'password' => Hash::make($password)))->id;
			User::where('id',$id)->update(array('uid' => $id));
			$success = true;
		} catch (Exception $e) {
			$success = false;
		}
		
		return $success;
	}
	
	public static function isValidatedEmailFormat($email)
	{
		$rules = array(
				'email'    => 'required|email'
		);
		
		$validator = Validator::make(array('email' => $email), $rules);
		if($validator->fails()) {
			return false;
		}
		
		return true;
	}
	
	public static function isExistedEmail($email)
	{
		if( !is_null(User::where('email', $email)->first()) ){
			return true; 
		}
		return false;
	}
	
	public static function sanitizePhoneNumber($phone){
		return preg_replace("/[^0-9]/","",$phone);
	}
	
	public static function isPasswordLongEnough($password,$minLength){
		return strlen($password) >= $minLength? true: false ;
	}
}