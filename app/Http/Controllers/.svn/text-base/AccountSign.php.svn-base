<?php

namespace App\Http\Controllers;
use App;
use Session;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\OAuthAccessToken;
use App\Models\OAuthTokenHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;

class AccountSign extends  Controller
{
	public static function usernamePasswordUnmatch(Response $response)
	{

		$res = array('data' => array('result' => 'failure','reasonCode' => '10002', 'description' => Lang::get('mowork.account_password_unmatch'),'token' => Session::token()),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);

		return $json;
	}

	public static function loginSuccess(Response $response)
	{
		$res = array('data' => array('result' => 'success','reasonCode' => '00000', 'description' => '', 'token' => Session::token()),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);

		return $json;
	}

	public static function wechatLoginSuccess($user, Response $response)
	{
		$token = ApiFront::randString(32);
		$timestamp =  date('Y-m-d H:i:s');
		$delay =  getenv('TOKEN_LIFESPAN') ?: 30;
		$expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));

		User::where('uid',$user->uid)->update(array('api_token' => $token));

		$find = OAuthAccessToken::where('uid',$user->uid)->first();

		if ($find) {//move token to history
			OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
					'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));

			OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
		} else {//fresh user

			OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => '2', 'username' => $user->wechat, 'identity_type' => 'wechat',
					'token' => $token, 'expiry_at' => $expiry_at ));
		}

		$res = array('data' => array('result' => 'success','reasonCode' => 0, 'uid' => $user->uid,'userRole' => $user->userRole, 'description' => Lang::get('mowork.login_success'), 'token' => $token,'timestamp' => $timestamp),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);
		 
		return $json;
	}

	public static function signup($signupMethod, $userIdentity, $password, $validatingCode, $response)
	{
		/**

		* @param signupMethod: signup method
		1: mobile phone
		2: email

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
	 
		0： success,注册成功
		*/
		$status = $response->status();

		/*
		 *
		 1: mobile phone
		 2: email
		 3: wechat
  		 *
		 */
		$result = 'failure';
        $description = '';
        
		switch ($signupMethod){
			case 1://mobile phone
				if(! self::isValidatingNumberMatched($userIdentity, $validatingCode)) {
					$reasonCode = '10011';
					$description = Lang::get('mowork.validating_number_unmatch');
					break;
				}
				elseif(! self::isPasswordLongEnough($password, 6)) {
					$reasonCode = '10010';
					$description = Lang::get('mowork.password_min_length');
				} elseif(self::isExistedMobilePhone($userIdentity)) {
					$reasonCode = '10020';
					$description = Lang::get('mowork.user_existed');
					break;
				}
				 
				if(self::signupWithMobilePhone($userIdentity,$password)) {
					$reasonCode = '00000';
				}
				else {
					$reasonCode = '10000';
					$description = Lang::get('mowork.database_error');
				}
					
				break;
			case 2://email
				if(! self::isValidatingEmailMatched($userIdentity, $validatingCode)) {
					$reasonCode = '10011';
					$description = Lang::get('mowork.validating_number_unmatch');
					break;
				}
				else if(! self::isValidatedEmailFormat($userIdentity)) {
					$reasonCode = '10012';
					$description = Lang::get('mowork.invalid_email');
					break;
				}
				elseif(! self::isPasswordLongEnough($password, 6)){
					$reasonCode = '10010';
					$description = Lang::get('mowork.password_min_length');
				}
				elseif(self::isExistedEmail($userIdentity)){
					$reasonCode = '10020';
					$description = Lang::get('mowork.user_existed');
					break;
				}
					

				if(self::signupWithEmail($userIdentity,$password)) {

					$result = 'success';
					$reasonCode = '00000';
					$description = Lang::get('mowork.signup_success');
				}
				else {
					$reasonCode = '10000';
					$description = Lang::get('mowork.database_error');
				}

				break;
					
			default:
				$reasonCode = '10001';
				$description = Lang::get('mowork.invalid_signup_mehtod');
				break;
		}

		$res = array('data' => array('result' => $result,'reasonCode' => $reasonCode, 'description' => $description,'token' => Session::token()),'status' => $status);
			
		return json_encode($res, JSON_UNESCAPED_UNICODE);

	}

	public static function signupWithWechat($request,$response)
	{
		$success = true;
		$reasonCode = '';
		$token = ApiFront::randString(32);
		$timestamp =  date('Y-m-d H:i:s');
		$delay =  getenv('TOKEN_LIFESPAN') ?: 30;
		$expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));

		try {
			//$currentId = Sysconfig::where(['cfg_name'=>'uid_current_id'])->first()->cfg_value;
			$id = User::create(array('wechat' => $request->get('unionid'),'username' =>$request->get('nickname'),'gender' => $request->get('sex'),'province' => $request->get('province'),
					'city' => $request->get('city'),'country' => $request->get('country'),'avatar' =>$request->get('headimgurl'),'prefer_language' => $request->language))->id;
			User::where('id',$id)->update(array('uid' => $id,'api_token' => $token));

		} catch (Exception $e) {
			$success = false;
			$reasonCode = -1000;
		}

		if($reasonCode){
			$res = array('data' => array('result' => 'failure','reasonCode' => 0, 'description' => Lang::get('mowork.database_err'),'status' => $response->status()));
			$json = json_encode($res, JSON_UNESCAPED_UNICODE);
			return $json;
		}

		$user = User::where('uid',$id)->first();
			
			
		$find = OAuthAccessToken::where('uid',$id)->first();

		if ($find) {//move token to history
			OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
					'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));

			OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
		} else {//fresh user

			OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => '2', 'username' => $request->get('unionid'), 'identity_type' => 'wechat',
					'token' => $token, 'expiry_at' => $expiry_at ));
		}

		$res = array('data' => array('result' => 'success','reasonCode' => 0, 'uid' => $user->uid,'userRole' => $user->userRole, 'description' => Lang::get('mowork.wechat_signup_success'), 'token' => $token,'timestamp' => $timestamp),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);

		return $json;

	}

	public static function isValidatingNumberMatched($mobile, $validatingCode)
	{
		$realCode = Redis::get($mobile);
	 	return  $validatingCode == $realCode ? true : false;
	}

	public static function isValidatingEmailMatched($email, $validatingCode)
	{
		$realCode = Redis::get($email);
		return  $validatingCode == $realCode ? true : false;
	}
	
	public static function isExistedMobilePhone($mobile)
	{
		if( !is_null(User::where('mobile', $mobile)->first()) ){
			return true;
		}
		return false;
	}

	public static function signupWithMobilePhone($mobile, $password)
	{ 
		try {
			$id = User::create(array('mobile' => $mobile,'password' => Hash::make($password)))->id;
			User::where('id',$id)->update(array('uid' => $id));
			return true;
		} catch (Exception $e) {
			return false;
		}
 	 
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

	public static function randString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
