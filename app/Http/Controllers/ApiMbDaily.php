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
use Illuminate\Http\Response;

class ApiMbDaily extends  Controller
{
	public static function changePassword($oldPassword,$password,$passwordConfirm,Response $response)
	{
		$reasonCode = 0;
		$description = '';
		$result = 'success';
	 
		$row = User::where('uid', Session::get('userId'))->first();
	    	 
		if (!Hash::check($oldPassword, $row->password)) {
			$reasonCode = -1100;
			$description = Lang::get('mowork.old_password_unmatch');
			$result = 'failure';
		}
		elseif ($password != $passwordConfirm) {
			$reasonCode = -1101;
			$description = Lang::get('mowork.confirm_password_unmatch');
			$result = 'failure';
		}
		else {
			try{
				User::where('uid',Session::get('userId'))->update(array('password' => Hash::make($password)));
			} catch (Exception $e) {
				$reasonCode = 1000;
				$description = Lang::get('mowork.database_error');
			}
		}
		
		$res = array('data' => array('result' => $result,'reasonCode' => $reasonCode, 'description' => $description,'token' => Session::token()),'status' => $response->status());
		$json = json_encode($res, JSON_UNESCAPED_UNICODE);
		 
		return $json;
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

}
