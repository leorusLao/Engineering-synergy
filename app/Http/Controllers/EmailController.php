<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserCompany;

class EmailController extends  Controller {
	
	public function  testMailServer(Request $request) 
	{ 
		$email = $request->get('email');
		
		$token = sha1($this->email_salt.$email);
		$user = array();
		$user['email'] = $email;
		$user['name'] = 'tester';
		$user['sender'] = 'info@mowork.cn';
		  
		$locale = app::getLocale();
		 
	    Mail::send('email', ['url'=>'http://www.qq.com', 'uid' => 1, 'email' => $email, 'domain' => DOMAIN, 'token' => $token, 'title' => "This is testing mail", 'message' => "Testing Mail Server Start"], function ($message) use ($user)
		{
			$message->from($user['sender'], 'TESTER');
			$message->to($user['email'])->subject('Test Email');
		});
		if(count(Mail::failures()) > 0){
			$errors = 'Failed to send password reset email, please try again.';
		}
 	 
	}
	
	//发送邮件找回密码
	public static function sendmail_password($email,$subject,$url){ 	
		Mail::send('email.password-reset-zh-cn',['url'=>$url],function($message) use($email,$subject){
			$message->to($email)->subject($subject);
		});
		if(empty(Mail::failures())){
			return true;			
		}else{ 
			return false;
		}
	}


	//发送邮件找回密码
	public static function sendmail_code($email,$subject,$rand){ 	
		Mail::send('email.password-getcode-zh-cn',['url'=>$rand],function($message) use($email,$subject){
			$message->to($email)->subject($subject);
		});
		if(empty(Mail::failures())){
			return true;			
		}else{ 
			return false;
		}
	}


	public function isEmaillReal($sender = 'no-reply@mowork.cn', array $emails)
	{
		$SMTP_Valid = new SMTP_ValidateEmail();
	 
		$result = $SMTP_Valid->validate($emails, $sender);
		 
		if($result) {
			return true;
		}
		
		return  false;
	}
	
	public function validateAndSend($sender = 'no-reply@mowork.cn', array $emails)
	{
		$SMTP_Valid = new SMTP_validateEmail();
	
		$result = $SMTP_Valid->validate($emails, $sender);
		 
		if($result) {
			 
			Mail::send(['title' => "This is testing mail", 'message' => "Testing Mail Server Start"], function ($message)
			{
				$message->from($sender, 'TESTER MAN');
				$message->to($emails[0]);
			});
		}
	
	}
	
	public function welcomeSingup($uid, $username, $email){
	
		$token = sha1($this->email_salt.$email);
		$domain = DOMAIN;
	
		$user = array();
		$user['email'] = $email;
		$user['name'] = $username;
		$user['sender'] = 'no-reply@mowork.cn';//env('MAIL_USERNAME');
		//$user['bcc_email'] = 'customer.service@mowork.cn';
 
		$locale = App::getLocale();
	 
		Mail::send('email.welcome-'.$locale, array('uid' => $uid, 'username' => $username,'email' => $email, 'domain' => $domain,'token' => $token, 'locale' => $locale ),function($message) use ($user){
			$message->from($user['sender'], 'MoWork');
			$message->to($user['email'], $user['name'])->subject(Lang::get("mowork.email_verify_subject").'!');
			//$message->bcc($user['bcc_email'], 'MoWork');
		});
	
	}
	
	public function wakeupMessage($email, $username, $subject, $content)
	{
		$user = array();
		$user['name'] = $username;
		$user['email'] = $email;
		$user['sender'] = 'info@mowork.cn';
		$user['subject'] = $subject;
		Mail::send('email.wakeup-message', array('content' => $content),function($message) use ($user){
			$message->from($user['sender'], 'MoWork');
			$subject = '=?UTF-8?B?'.base64_encode($user['subject']).'?=';
			$message->to($user['email'])->subject($subject);
			//$message->bcc($user['bcc_email'], 'MoWork');
		});
	}
		
	public function passwordReset($email, $username, $uniqueId){
	      
		$domain = DOMAIN;
			
		$user = array();
		$user['name'] = $username;
		$user['email'] = $email;
		$user['sender'] = 'info@mowork.cn';//env('MAIL_USERNAME');
		//$user['bcc_email'] = 'customer.service@mowork.cn';
		$locale = App::getLocale();
		
		$url = "http://test.". $domain . "/reset-password/" . $uniqueId . "/" . $email; //TODO replace "http://test." => "http://www."
		  
		if($locale == 'zh-cn'){
		 
			Mail::send('email.password-reset-zh-cn', array('url' => $url),function($message) use ($user){
				$message->from($user['sender'], 'MoWork');
				$subject = '=?UTF-8?B?'.base64_encode(Lang::get("mowork.reset_password_subject")).'?=';
				$message->to($user['email'])->subject($subject);
				//$message->bcc($user['bcc_email'], 'MoWork');
			});
		}
		else {
			Mail::send('email.password-reset-en',array('url' => $url),function($message) use ($user){
				$message->from($user['sender'], 'MoWork');
				$message->to($user['email'])->subject(Lang::get("mowork.reset_password_subject"));
				//$message->bcc($user['bcc_email'], 'MoWork');
			});
		}
	}
	
}