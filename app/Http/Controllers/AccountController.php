<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\UserResourceRole;
use App\Models\UserResource;
use phpDocumentor\Reflection\Location;
use Illuminate\Support\Facades\Log;

class AccountController extends  Controller
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



	public function homepage()
	{
		return view('frontend.homepage',array('locale' => $this->locale));
	}


	public function login(Request $request,Response $response){

		if($request->has('username')){
			$username = trim($request->get('username'));
			$password = trim($request->get('password'));

 			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
 				$identBy = 'email';
 			}
            elseif (ctype_digit($username)) {
            	$identBy = 'mobile';
            }

            //###################通过手机获得临时密码的登录情况 ##############################
            if( ($identBy == 'mobile') && Redis::get($username) == $password) {
            	$exists = User::where('mobile',$username)->first();
            	if($exists) {
            		User::where('mobile',$username)->update(array('password' => Hash::make($password)));
            	}
            	$_SESSION['CHANGPASSWARNING'] = Lang::get('mowork.temp_pass_warning');
            }
            //##################################################
            //Attempt to authenticate a user using the given credentials.
			if(Auth::guard('web')->attempt([$identBy => $username, 'password' => $password])) {
				$_SESSION['userId'] = Auth::user()->uid;//for avoiding browser back after logout
				Session::put('username',$username);
				Session::put('userId',Auth::user()->uid);

				//check how many actived companies are associated to this user
 	            $attchedCompanys = UserCompany::where('uid',Auth::user()->uid)->where('user_company.status','1')->join('company','company.company_id','=','user_company.company_id')
 	            ->select('company.*','user_company.role_id')->get();

 	            if( count($attchedCompanys) > 1) {//multiple companies
 	            	//please choose one company to work
 	            	Session::put('Companies', count($attchedCompanys));
 	            	return  Redirect::to("/select-company");
 	            }
 	            else {
 	            	if(count($attchedCompanys) == 1) {//one company only
 	            		$companyId = $attchedCompanys[0]['company_id'];
 	            		$companyName = $attchedCompanys[0]['company_name'];
 	            		$role_id = $attchedCompanys[0]['role_id'];
 	            		$fowardDomain = $attchedCompanys[0]['forward_domain'];
						
 	            		//############如果是BU业务站点login但公司没有被批准
 	            		$isApproved = $attchedCompanys[0]['is_approved'];
 	            		if($isApproved == 0 && !$fowardDomain){
 	            			session_unset();
 	            			session_destroy();
 	            			$request->session()->flush();
 	            			return Redirect::to('/login')->with('login_failed',Lang::get('mowork.company_app_pending'));
 	            		} else if($isApproved == 2 && !$fowardDomain){
 	            			session_unset();
 	            			session_destroy();
 	            			$request->session()->flush();
 	            			return Redirect::to('/login')->with('login_failed',Lang::get('mowork.company_app_refused'));
 	            		}
 	            		//###############################################################################

 	            		if($fowardDomain && $role_id > 20) {
							// 获取6位随机字符串
							while(true)
							{
								$str = randString(6);
								if(!Redis::exists('uid_'.$str)) {
									break;
								}
							}

							$token = hash('sha256',$str.strtolower($str).strtoupper($str));

 	            			Redis::setex('uid_'.$str,10,$request->get('uid'));
 	            			Redis::setex('companyId_'.$str, 10, $companyId);
 	            			Redis::setex('password_'.$str,10, $request->get('password'));

 	            			$currentHost = $request->root();
 	            			$currentHost = explode("//", $currentHost);
 	            			$currentDomain = $currentHost[1];
 	            			if($currentDomain != $fowardDomain) {
 	            				return redirect::to('//'.$fowardDomain.'/dashboard/'.$token.'/'.$str);
 	            			}
 	            		}
 	            		//###############################################################################

 	              	}
 	            	else {//not associated with a company
 	            		$companyId = null;
 	            		$companyName = '';
 	            		$role_id = '51';
 	            		$fowardDomain = '';

 	            	}
 	            }
                   	            
				$userInfo =  array('email' => Auth::user()->email, 'userId' => Auth::user()->uid,'username' => Auth::user()->username,
						'userRole' => $role_id, 'mobilePhone' => Auth::user()->phone, 'wechat' => Auth::user()->wechat,
						'bandedEmail' => Auth::user()->banded_email, 'bandedMobile' => Auth::user()->banded_mobile,
						'avatar' => Auth::user()->avatar, 'companyId' => $companyId, 'companyName' => $companyName,
						'countryId' => Auth::user()->country_id,
						'forwardDomain' => $fowardDomain
				);
				Session::put('USERINFO',(object) $userInfo);
				 

				return  Redirect::to("/dashboard");
			}
			else{
				//$res = array('data' => array('result' => 'failure','reasonCode' => '-1201', 'description' => Lang::get('mowork.account_password_unmatch'),'token' => Session::token()),'status' => $response->status());


				$res = array('data' => array('result' => 'failure','reasonCode' => '1020', 'description' => Lang::get('mowork.account_password_unmatch'),'token' => Session::token()),'status' => $response->status());
				$json = json_encode($res, JSON_UNESCAPED_UNICODE);


				return Redirect::to("/login")->with(array('result' => $json,'login_failed'=>Lang::get('mowork.account_password_unmatch')));
			}
		}

		if(Session::has('userId')) {
			if(Session::has('USERINFO'))
			{

				return Redirect::to('dashboard');
			} else if(Session::has('Companies')) {
				return Redirect::to('/select-company');
			}

		}

		return view('frontend.login',array('pageTitle' => Lang::get('mowork.login'),'locale' => $this->locale));
	}

	public  function selectCompany(Request $request) {
		//for a person is aossociated with multi-companies
		if(! Session::has('userId')) return Redirect::to('/');


		if($request->has('submit')) {
			 $selectedCompany = ($request->get('company'))[0];

			 $sc = Company::where('company_id', $selectedCompany)->first();
			 $fowardDomain = $sc->forward_domain;

			 //double check
			 $row = UserCompany::where('user_company.uid',Session::get('userId'))
			        ->where('user_company.company_id',$selectedCompany)->join('company','company.company_id','=','user_company.company_id')
			        ->join('user','user.uid','=','user_company.uid')->select('user.*','company.company_name','company.company_id','user_company.role_id','company.is_approved')->first();

			 if($row) {
			 	//############如果是BU业务站点login但公司没有被批准
			 	$isApproved = $row->is_approved;
			 	if($isApproved == 0 && !$fowardDomain){
			 	    session_unset();
			        session_destroy();
		            $request->session()->flush();
			 		return Redirect::to('/login')->with('login_failed',Lang::get('mowork.company_app_pending'));
			 	} else if($isApproved == 2 && !$fowardDomain){
			 	    session_unset();
			        session_destroy();
		            $request->session()->flush();
			 		return Redirect::to('/login')->with('login_failed',Lang::get('mowork.company_app_refused'));
			 	}
			 	
			 	
			 	$userInfo =  array('email' => $row->email, 'userId' => $row->uid,'username' => $row->username,
			 			'userRole' => $row->role_id,'mobilePhone' => $row->phone, 'wechat' => $row->wechat, 'bandedEmail' => $row->banded_email,
			 			'avatar' => $row->avatar, 'bandedMobile' => $row->banded_mobile, 'companyId' => $row->company_id,
			 			'companyName' => $row->company_name,
			 			'countryId' => $row->country_id,
			 			'forwardDomain' => $fowardDomain
			 	);
			 	$role_id = $row->role_id;
				 	
			 	Session::put('USERINFO',(object) $userInfo);
			 	  
			 	return Redirect::to('/dashboard');
			 } else {
			 	return Redirect::back()->with('login_failed',Lang::get('mowork.data_error'));
			 }


		}

		$rows = UserCompany::where('uid', Session::get('userId'))->where('user_company.status','1')->join('company','company.company_id','=','user_company.company_id')
		->select('company.*')->get();

		return view('frontend.select-company',array('rows' => $rows,  'pageTitle' => Lang::get('mowork.select_company'),'locale' => $this->locale));

	}

	public function bindWechat(Request $request)
	{
		//for account rigisted using emial or mobile but has not banded with wechat
		if(! Session::has('userId')) return Redirect::to('/');

		$currentHost = $request->root();
		$currentHost = explode("//", $currentHost);
		$currentDomain = $currentHost[1];
		$row = User::where('uid', Session::get('userId'))->first();
		return view('backend.bind-wechat',array('row' => $row, 'currentDomain' => $currentDomain, 'pageTitle' => Lang::get('mowork.bind_wechat'),'locale' => $this->locale));

	}

	public function bindWechatResult(Request $request)
	{
		//for account rigisted using emial or mobile but has not banded with wechat
		$uid = $request->get('uid');
		$result = $request->get('result');

		return view('backend.bind-wechat-result',array('result' => $result, 'pageTitle' => Lang::get('mowork.bind_wechat'),'locale' => $this->locale));

	}

	public function loginWechat(Request $request, Response $response)
	{//微信网页登录

		if(! $request->has('uid')) {
			return Redirect::to('/login');
		}

		// $user = User::where('user.uid',$request->get('uid'))->first();
		//$user = User::where ( 'user.uid', $request->get ( 'uid' ) )->leftJoin ( 'user_company', 'user_company.uid', '=', 'user.uid' )->select ( 'user.*', 'user_company.role_id' )->first ();

		// check how many actived companies are associated to this user
		$attchedCompanys = UserCompany::where ( 'uid',$request->get('uid') )->where ( 'user_company.status', '1' )->join ( 'company', 'company.company_id', '=', 'user_company.company_id' )->select ( 'company.*', 'user_company.role_id' )->get ();
		Session::put('userId', $request->get('uid'));
		// 确保密码的微信用户可以登录
		$password = User::where('uid', $request->get('uid'))->value('password');
		User::where('uid',$request->get('uid'))->update(['password' => Hash::make(config('app.initializationPassword'))]);
		Auth::guard('web')->attempt(['uid' => $request->get('uid'), 'password' => config('app.initializationPassword')]);
		User::where('uid',$request->get('uid'))->update(['password' => $password]);
		if (count ( $attchedCompanys ) > 1) { // multiple companies
		    // please choose one company to work
			Session::put('UNIONID',$request->get('unionid'));
			Session::put('Companies', count($attchedCompanys));
		 	return Redirect::to ( "/select-company" );
		} else {
			  if (count ( $attchedCompanys) == 1) {//one company only
		        	$companyId = $attchedCompanys[0]['company_id'];
		        	$companyName = $attchedCompanys[0]['company_name'];
		        	$role_id = $attchedCompanys[0]['role_id'];


		        	$fowardDomain = $attchedCompanys[0]['forward_domain'];
		        	//###############################################################################

		        	if($fowardDomain && $role_id > 20) {
						// 获取6位随机字符串
						while(true)
						{
							$str = randString(6);
							if(!Redis::exists('uid_'.$str)) {
								break;
							}
						}

						$token = hash('sha256',$str.strtolower($str).strtoupper($str));

		        		Redis::setex('uid_'.$str,10,$request->get('uid'));
		        		Redis::setex('companyId_'.$str, 10, $companyId);
		        		Redis::setex('unionId_'.$str,10, $request->get('unionid'));
		        		$currentHost = $request->root();
		        		$currentHost = explode("//", $currentHost);
		        		$currentDomain = $currentHost[1];
		        		if($currentDomain != $fowardDomain) {
		        			return redirect::to('//'.$fowardDomain.'/dashboard/'.$token.'/'.$str);
		        		}
		        	}
		        	//###############################################################################
 
		       
		      }	else {//not associated with a company
		        	$companyId = null;
		        	$companyName = '';
		        	$role_id = '51';
		        	$fowardDomain = '';
		      }
		}

		$user = User::where ( 'user.uid', $request->get ( 'uid' ) )->leftJoin ( 'user_company', 'user_company.uid', '=', 'user.uid' )->select ( 'user.*', 'user_company.role_id' )->first ();

		$salt = $this->wechat_salt;
		$token = $request->token;
		$cmpToken = hash('sha256',$user->api_token.$salt);

		if($cmpToken != $token) {
			return Redirect::to('/login');
		}

			//further check

		if(hash('sha256',$user->wechat.$salt) == $request->unionid ){

			$_SESSION['userId'] = $user->uid;//for avoiding browser back after logout
			Session::put('username', $user->username);
			Session::put('userId', $user->uid);

			$userInfo =  array('email' => $user->email, 'userId' => $user->uid,'username' => $user->username,'userRole' => $role_id,
						'mobilePhone' => $user->phone, 'wechat' => $user->wechat, 'companyId' => $companyId, 'companyName' => $companyName,
						'avatar' => $user->avatar, 'bandedEmail' => false, 'groupUser' => $user->group_user,
						'unionid' => $request->unionid,
						'forwardDomain' => $fowardDomain
				);
				Session::put('USERINFO',(object) $userInfo);
				 
				return  Redirect::to("/dashboard");
		}
		else {
			return Redirect::to('/login');
		}

	}

	public function signup(Request $request,Response $response){

		if($request->has('submit')){


			$json = AccountSign::signup($request->get('signupMethod'), $request->get('username'), $request->get('password'), $request->get('sms'),$response) ;

			$result = json_decode($json);


			if($result->status == 200 && $result->data->reasonCode == '00000'){
				return Redirect::to("/signup-success")->with('result', Lang::get('mowork.signup_success'));
			}
			else {

				return  Redirect::back()->with('result', $result->data->description);
			}

		}

		return view('frontend.signup',array('pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));

	}

	public function signupSuccess(){
		if(!Session::has('result')) return Redirect::to("/");

		return view('frontend.signup-success',array('result' => Session::get('result'),'locale' => $this->locale));
	}

	public  function lostPassword(Request $request){
		if($request->get('submit')){
			$username = $request->get('username');

			if(strpos($username,'@')) {
				$existed = User::where('email',$username)->first();
				if($existed) {
					$mailer = new EmailController();
					$uqid = uniqid();
					$mailer->passwordReset($username,$existed->username, $uqid);
					Redis::setex($username.'-' . $uqid, 3600, time() );
				 	return Redirect::back()->with('result', Lang::get('mowork.email_send_out'));
				} else {
				 	return Redirect::back()->with('result', Lang::get('mowork.user_nonexistance'));
				}

			} else {//if it is mobile phone, then send sms code as temperory passwod;
				$existed = User::where('mobile',$username)->first();
				if($existed) {
					Weixin::send_code($username,0);//通过手机获得临时密码同时存入redis
					return Redirect::back()->with('result', Lang::get('mowork.sms_pass_send'));
				} else {
					return Redirect::back()->with('result', Lang::get('mowork.user_nonexistance'));
				}
			}


		}

		return view('frontend.lost-password',array('locale' => $this->locale));
	}

	public  function resetPassword(Request $request, $token, $identity){//TODO
		$urlExpired = '';
		if(!Redis::exists($identity . '-' . $token)) {
			$urlExpired = Lang::get('mowork.reset_url_expiry');
		}
		if($request->has('submit')) {
			if($request->get('password') != $request->get('password2')) {
				return Redirect::back()->with('result', Lang::get('mowork.password_mismatch'));
			}

			if(strpos($identity, '@')) {
				$succsss = User::resetPasswordByEmail($identity, $request->get('password'));
			} else {
				//mobile phone: reset-password by mobile phone
				$succsss = User::resetPasswordByMobilephone($identity, $request->get('password'));
			}

			if($succsss) return Redirect::back()->with('result', Lang::get('mowork.operation_success'));

			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
		return view('frontend.reset-password',array('urlExpired' => $urlExpired, 'token' => $token, 'identity' => $identity, 'locale' => $this->locale));
	}

    public function enterWorksite(Request $request, $token, $company_id)
    {
    	if(! Session::has('userId')) return Redirect::to('/');
        
    	$uid =  Session::get('userId');
    	
    	$tokenComp = hash('sha256',$uid.$this->salt.$company_id);
    	if($tokenComp != $token) {
    		return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
    	}
        
    	$row = Company::where('company_id', $company_id)->first();
    	$company_name = $row->company_name;
     
    	if($request->has('submit')) {
 
    		$user = User::where('uid',$uid)->first();
    		if(Hash::check($request->password, $user->password)) {
				// 获取6位随机字符串
				while(true)
				{
					$str = randString(6);
					if(!Redis::exists('uid_'.$str)) {
						break;
					}
				}

				$token2 = hash('sha256',$str.strtolower($str).strtoupper($str));
                
				//不管是email，mobile或微信登录的超级用户都通过密码从主站进入bu工作站
    	     	 Redis::setex('uid_'.$str,10, $user->uid);
    		   	 Redis::setex('password_'.$str,10,$request->password);
    	 	   	 Redis::setex('companyId_'.$str,10,$company_id);
    		     return redirect::to('//'.$row->forward_domain.'/dashboard/'.$token2.'/'.$str);

    		} else {
    			return redirect::back()->with('result', Lang::get('mowork.password_incorrect'));
    		}
    	}
    	$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.companysite_entry');
    	return view('backend.enter-worksite',array('cookieTrail' => $cookieTrail, 'token' => $token, 
    			'company_id' => $company_id , 'company_name' => $company_name, 'locale' => $this->locale));

    }

	public function admLogin(Request $request){
		if($request->get('submit')){
			$username = trim($request->get('email'));
			$password = trim($request->get('password'));
			$identfier = 'adm_name'; //judg if user input email or accounht number
			if(strpos($request->get('email'),'@')) $identfier = 'email';

			if(Auth::guard('admins')->attempt([$identfier => $username, 'password' => $password])){

				Session::put('email',$username);
				$_SESSION['adm_uid'] = Auth::guard('admins')->user()->id;
				$adm_name = Auth::guard('admins')->user()->adm_name;
				Session::put('admuser',$adm_name? $adm_name:$username);
				$_SESSION['admuser'] = Session::get('admuser');//for avoiding browser back after logout


				return  Redirect::to("/yp2100adm/home");
			}
			else{
				return Redirect::to("/yp2100adm/login")->with('login_failed',Lang::get('mowork.login_failed'));
			}
		}

		return view('sysadm.login',array('locale' => $this->locale));
	}

	public function logout(Request $request){

		    $_SESSION = array();
			session_unset();
			session_destroy();

			$request->session()->flush();
			return Redirect::to("/");

	}

	public function aboutUs()
	{
		return view('frontend.about-us',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.about_us'),'locale' => $this->locale));
	}

	public function contact()
	{
		return view('frontend.contact',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.contact'),'locale' => $this->locale));
	}

	public function privacy()
	{
		return view('frontend.privacy',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.privacy'),'locale' => $this->locale));
	}


	public function setPassWord(Request $request, Response $response){
		return view('frontend.setpassword',array());

	}
	
	public function accountExistedCheck(Request $request)
	{
		$username = $request->get('username');
		 
		$existed = User::where('email',$username)->orWhere('mobile',$username)->first();
		if($existed) {
			$res = array('0' => 'existedAccount');
		} else {
			$res = array('0' => '');
		}
		$json = json_encode($res);
		return $json;
	}

	public function mobileCheckCode(Request $request)
	{
		$mobile = $request->get('mobile');
		if(!isValidatedMobileFormat($mobile)) {
			$res = array('data' => '','description'=>Lang::get('mowork.invalid_mobile'),'reasonCode'=>'11031','result'=>'failure');
			$json = json_encode($res, JSON_UNESCAPED_UNICODE);
			return $json;
		}

		if(Redis::get($mobile)) {//check if check code exists, if it is still available, donot call to send code for saving money
			return CheckApi::return_success(Redis::get($mobile));
		}

		//这里发送验证码
		$return = Weixin::send_code($mobile,0);//默认注册
		if($return){
			return CheckApi::return_success($return);
		}else{
			return CheckApi::return_10000();
		}
	}

	public function emailCheckCode(Request $request)
	{
		$email = $request->get('mobile');
		try{//发送验证码到邮箱
			$code = rand(100000,999999);
		 	$subject = Lang::get('mowork.email_verify_code');
			Redis::setEx($email,3600,$code);
		    EmailController::sendmail_code($email,$subject,$code);
		} catch(Exception $e){
			return 10003;
		}
	}

	public static function getRoleResourceControll($company_id, $role_id)
	{   //获取角色资源权限
        
		$rows = UserResourceRole::where(array('role_id' => $role_id,'company_id' => $company_id))->get();
		if(count($rows) == 0 && $role_id == 20) {//check if initialized role resource for this company
			  //self::initializeRoleResource($company_id);
	    }

		$res = array();
		foreach($rows as $row) {
			$res[$row->resource_id] = [$row->pread, $row->padd, $row->pdelete, $row->pupdate, $row->papproval];
		}
		return $res;
	}

	 
	public static function initializeRoleResource($company_id)
	{
		//弃用
		 
	}
	 
}
