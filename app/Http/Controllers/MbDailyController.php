<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;

use App\Events\TableRowChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Company;
use App\Models\Sysconfig;
use App\Models\UserCompany;
use App\Models\MessageEvent;
use App\Models\Buhost;
use Illuminate\Support\Facades\Log;

class MbDailyController extends  Controller {
	protected $locale;

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
    
	public function trialExpiration(Request $request) 
	{
	   if(!Session::has('EXPIRATION')) {
	   	 return Redirect::to('/');
	   }
	   return view('backend.trial-expiration',array('pageTitle' => Lang::get('mowork.dashboard'),
	   	 	'locale' => $this->locale));
	}
	
	public function dashboard(Request $request, $token = null, $string = null, Response $response)
	{
		if($token)
		{
			$compToken = hash('sha256',$string.strtolower($string).strtoupper($string));
			if($compToken != $token)
			{
				return Redirect::to('/dashboard');
			}

			if(Redis::exists('password_'.$string)){//手机，邮箱登录;从主站跳转BU站

			   if(Auth::guard('web')->attempt(['uid' => Redis::get("uid_".$string), 'password' => Redis::get('password_'.$string)])){

				  $com = Company::where('company_id',Redis::get('companyId_'.$string))->first();
				  $ucom = UserCompany::where(array('company_id' => Redis::get('companyId_'.$string), 'uid' => Redis::get("uid_".$string)))->first();
				  $role_id = isset($ucom->role_id) ? $ucom->role_id:51;
				 
				  $userInfo =  array('email' => Auth::user()->email, 'userId' => Auth::user()->uid,'username' => Auth::user()->username,
						'userRole' => $role_id, 'mobilePhone' => Auth::user()->phone, 'wechat' => Auth::user()->wechat,
						'bandedEmail' => Auth::user()->banded_email, 'bandedMobile' => Auth::user()->banded_mobile,
						'avatar' => Auth::user()->avatar, 'companyId' => $com->company_id, 'companyName' => $com->company_name, 'countryId' => Auth::user()->country_id);
				  Session::put('userId',Auth::user()->uid);
				  $_SESSION['userId'] = Auth::user()->uid;
				  Session::put('USERINFO',(object) $userInfo);
				 
			   }

		   } else if(Redis::exists('unionId_'.$string)) {//微信网页登录从主站跳转BU站
				$salt = $this->wechat_salt;
				$uid = Redis::get("uid_".$string);
				$company_id = Redis::get("companyId_".$string);
				$unionid = Redis::get("unionId_".$string);

				$user = User::where ( 'user.uid', $uid)->leftJoin ( 'user_company', 'user_company.uid', '=', 'user.uid' )
					   ->select ( 'user.*', 'user_company.role_id' )->first ();
				$tmpPassword = $user->password;
				$user->password = Hash::make(config('app.initializationPassword'));
				$user->save();
				Auth::guard('web')->attempt(['uid' => $uid, 'password' => config('app.initializationPassword')]);
				$user->password = $tmpPassword;
				$user->save();


				if(hash('sha256',$user->wechat.$salt) == $unionid ){
					$com = Company::where('company_id',$company_id)->first();
					$ucom = UserCompany::where(array('company_id' => Redis::get('companyId_'.$string), 'uid' => Redis::get("uid_".$string)))->first();
					$role_id = isset($ucom->role_id) ? $ucom->role_id:51;

				 
					$userInfo =  array('email' => $user->email, 'userId' => $user->uid,'username' => $user->username,'userRole' => $role_id,
						'mobilePhone' => $user->phone, 'wechat' => $user->wechat, 'companyId' => $com->company_id,
						'companyName' => $com->company_name, 'avatar' => $user->avatar,
						'bandedEmail' => $user->banded_mobile,'bandedEmail' => $user->banded_email,
						'companyName' => $com->company_name, 'countryId' => $user->country_id);

				   Session::put('USERINFO',(object) $userInfo);
				   $_SESSION['userId'] = $user->uid;
				   Session::put('username', $user->username);
				   Session::put('userId', $user->uid);
	 
				}

			} else {
				return Redirect::to('/dashboard');
			}
		}

	 	if(! Session::has('userId')) return Redirect::to('/');

	 	$uid = Session::get('USERINFO')->userId ? Session::get('USERINFO')->userId : Session::get('userId');
		$_SESSION['uderId'] = $uid;
		//站内事件消息
		//get all unread event notitifications
		$pieces = MessageEvent::where(array('uid' => $uid, 'status' => 0))->count();
		$rows =  MessageEvent::where(array('uid' => $uid, 'status' => 0))->orderBy('id','desc')->paginate(10);

		Session::put('NOTICOUNTS',$pieces);
		Session::put('Notifications',$rows);
	    $forwardDomain = '';
	    if(Session::has('USERINFO')) {
	    	$uinfo = Session::get('USERINFO');
	    	if(isset($uinfo->forwardDomain)) {
	    		$forwardDomain = $uinfo->forwardDomain;
	    	}
	    }

	    $user = User::where('uid', $uid)->first();
            Session::put('username',$user->username);
	    if($user->password) {
	    	$passwordGuarded = true;
	    } else {
	    	$passwordGuarded = false;
	    }

		return view('backend.dashboard',array('pageTitle' => Lang::get('mowork.dashboard'),
				'passwordGuarded' => $passwordGuarded,
				'forwardDomain' => $forwardDomain,
				'locale' => $this->locale));

	}

	public function messageBeRead(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('USERINFO')->userId;
		//站内事件消息被读,更改状态
	 	MessageEvent::where(array('id' => $request->get('msgid'), 'uid' => $uid))->update(array('status' => 1));
	 	//re-gain all unread event notitifications
	 	$pieces = MessageEvent::where(array('uid' => $uid, 'status' => 0))->count();
	 	$rows =  MessageEvent::where(array('uid' => $uid, 'status' => 0))->orderBy('id','desc')->paginate(10);
	 	Session::put('NOTICOUNTS',$pieces);
	 	Session::put('Notifications',$rows);
	 	header('Content-Type: application/html');
	 	echo  'ok';
	}

	public function companyProfile(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');

		if ($request->has('submit')) {
			 //check if the company had setup before

			$row = Company::where('company_id',Session::get('USERINFO')->companyId)->first();
			if($row) {//update
				try {

					//check if company business license upload
					$licensepath = '';
					if(Session::has('LICENSE')) {
						$fullpath = storage_path().'/tmp/'.Session::get('LICENSE');
						$licensepath = storage_path().'/company/license/'.Session::get('USERINFO')->companyId.'-'.Session::get('LICENSE');
						rename($fullpath, $licensepath);
					}

					$companyArray = array('company_name' => $request->get('company_name'), 'reg_no' => empty($request->get('reg_no'))?'':$request->get('reg_no'), 'biz_des' => empty($request->get('biz_des'))?'':$request->get('biz_des'),
							'legal_person' => empty($request->get('legal_person'))?'':$request->get('legal_person'), 'ceo' => empty($request->get('ceo'))?'':$request->get('ceo'), 'phone' => $request->get('phone'),
							'fax' => empty($request->get('fax'))? '':$request->get('fax'), 'email' => $request->get('email'), 'website' => empty($request->get('website'))?'':$request->get('website'),
							'wechat_pub_acct' => empty($request->get('wechat_pub_acct'))?'':$request->get('wechat_pub_acct'),
							'industry' => $request->get('industry'),
							'company_type' => $request->get('company_type'), 'country' => $request->get('country'), 'province' => $request->get('province'), 'city' => $request->get('city'), 'address' => $request->get('address'),
							'postcode' => $request->get('postcode'));

					if($licensepath) {//update new licese
						//remove old license file
						$company = Company::where('company_id',Session::get('USERINFO')->companyId)->first();
						if(file_exists($company->license)) {
						 	unlink ($company->license);
						}
						Company::where('company_id',Session::get('USERINFO')->companyId)->update($companyArray);
					} else {//keep old license
						Company::where('company_id',Session::get('USERINFO')->companyId)->update($companyArray);
					}

					//rpc update -----------------------------------------------------------
					$company_id = Session::get('USERINFO')->companyId;
					$companyinfo = Company::join('buhost','buhost.bu_id','=','company.domain_id')->where('company_id', $company_id)->first();
					$companyArray = array_merge(array('company_id' => $company_id), $companyArray);

					if($companyinfo->forward_domain) {//这里是主站 ，需要同步更新bu站点
						$res = ReplicationRequest::rpcUpdateCompany($companyinfo->bu_site, $companyArray);
					} else {//这里是bu站点，需要同步更新主站
						$res = ReplicationRequest::rpcUpdateCompany('www.mowork.cn', $companyArray);
					}

					$res = json_decode($res);

					if($res->result == '0000' ){
						return Redirect::back()->with('result',Lang::get('mowork.operation_success'));
					} else {
						return Redirect::back()->with('result',Lang::get('mowork.synchronize_failure'));
					}

					//----------------------------------------------------------------------
				} catch (Exception $e){
					return Redirect::back()->with('result',Lang::get('mowork.db_err'));
				}


			} else {
				/*create 创建公司 在站点完成 function comapyCreate() */
				return Redirect::back()->with('result',Lang::get('mowork.create_at_mainsite'));
			}

		}

		$row = Company::where('company_id',Session::get('USERINFO')->companyId)->first();

		$companyTypeList = AjaxController::CompanyTypeList();
		$companyIndustryList = AjaxController::CompanyIndustryList();

		$countryId = 0;
		$provinceId = 0;
		$cityId = 0;

		if($row) {
			$countryId = $row->country;
			$countryList = AjaxController::countryList();
			$provinceId = $row->province;
			$provinceList = AjaxController::provinceList($row->country ? $row->country:1);
			$cityId = $row->city;
		 	$cityList = AjaxController::cityList($row->province);
		} else {
			$countryList = AjaxController::countryList();
			$provinceList = AjaxController::provinceList(1);
			$cityList = AjaxController::cityList(0);
		}

		$licenseImg = '';
		$licenseUrl = '';
		if(isset( $row->license )) {
			$licenseUrl = $row->license;
		}
		if($licenseUrl) {//because storage directory is protected for security
			if(File::exists($licenseUrl)){
			   $imageData = base64_encode(file_get_contents( $licenseUrl ));
			   $licenseImg = 'data: '.mime_content_type($licenseUrl).';base64,'.$imageData;
			}
		}

		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.setup').Lang::get('mowork.update').Lang::get('mowork.company_profile');

		return view('backend.company-profile',array('row' => $row,'licenseImg' => $licenseImg, 'companyTypeList' => $companyTypeList, 'companyIndustryList' => $companyIndustryList, 'countryList' => $countryList, 'provinceList' => $provinceList, 'cityList' => $cityList, 'countryId' => $countryId,
				'provinceId' => $provinceId, 'cityId' => $cityId, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	}

	public function myCompany(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('userId');
		$cookieTrail = Lang::get('mowork.user_info') .' &raquo; '.Lang::get('mowork.my_company');
		$rows = UserCompany::join('company','company.company_id','=','user_company.company_id')
		   ->join('buhost','buhost.bu_id','=','company.domain_id')->where('user_company.uid',$uid)
		   ->select('company.company_name','buhost.*','user_company.company_id')->get();
		$salt = $uid.$this->salt;
		return view('backend.my-company',array('rows' => $rows, 'salt' => $salt, 'cookieTrail' => $cookieTrail,  'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

	}

	public function companyCreation(Request $request)
	{//from company-profile besides adding bu selection

		if(! Session::has('userId')) return Redirect::to('/');

		if ($request->has('submit')) {
			//1eck if the company had setup before

				$currentId = Sysconfig::where('cfg_name','company_current_id')->first();
				$companyId = $currentId->cfg_value.$this->mod9710($currentId->cfg_value);
				$licensepath = '';

				DB::beginTransaction();

				try {

					//check if company business license upload
					if(Session::has('LICENSE')) {
						$fullpath = storage_path().'/tmp/'.Session::get('LICENSE');
						$licensepath = storage_path().'/company/license/'.$companyId.'-'.Session::get('LICENSE');
						rename($fullpath, $licensepath);
					}

					//1.创建公司
					$bu = Buhost::where('bu_id',$request->get('buid'))->first();

					$companyArray = array('company_id' => $companyId,'company_name' => $request->get('company_name'),
							'forward_domain' => $bu->bu_site, 'domain_id' => $request->get('buid'),
							'reg_no' => empty($request->get('reg_no'))?'':$request->get('reg_no'), 'biz_des' => empty($request->get('biz_des'))?'':$request->get('biz_des'),
							'license' => $licensepath, 'legal_person' => empty($request->get('legal_person'))?'':$request->get('legal_person'), 'ceo' => empty($request->get('ceo'))?'':$request->get('ceo'), 'phone' => $request->get('phone'),
							'fax' => empty($request->get('fax'))? '':$request->get('fax'), 'email' => $request->get('email'), 'website' => '',
							'wechat_pub_acct' => '',
							'industry' => $request->get('industry'),
							'company_type' => $request->get('company_type'),  'country' => $request->get('country'), 'province' => $request->get('province'), 'city' => $request->get('city'), 'address' => $request->get('address'),
							'postcode' => $request->get('postcode')	);
					$company_id = Company::create($companyArray)->company_id;

					Sysconfig::where('cfg_name','company_current_id')->increment('cfg_value',1);

					//2.该用户与公司关联,同时赋予系统管理员身份
					UserCompany::create(array('uid' => Session::get('userId'),'company_id' => $companyId, 'role_id' => '20'));//assign to system administrator

					//3.现在已经知道该用户是哪个公司，哪个bu站点，将该用户信息与公司信息同步复制到bu站点
					$user = User::join('user_company','user_company.uid','=','user.uid')->where('user.uid',Session::get('userId'))
					        ->select('user.*','user_company.company_id','user_company.role_id')->first();

					$userArray = ['id' => $user->id, 'uid' => $user->uid,'username' => $user->username,'password' => $user->password,
							'usercode' => $user->usercode,
							'fullname' => $user->fullname, 'mobile' => $user->mobile,'mobile_validated' => $user->mobile_validate,
							'wechat' => $user->wechat, 'avatar' => $user->avatar, 'email' => $user->email,
							'email_validated' => $user->email_validate, 'banded_email' => $user->banded_email, 'qq' => $user->qq,
							'weibo' => $user->weibo, 'gender' => $user->gender, 'birthdate' => $user->birthdate,
							'country' => $user->country, 'province' => $user->province, 'city' => $user->city,
							'country_id' => $user->country_id, 'province_id' => $user->province_id, 'city_id' => $user->city_id,
							'address' => $user->address, 'postcode' => $user->postcode, 'stickness' => $user->stickness,
							'is_active' => $user->is_active, 'status' => $user->status ,
							'prefer_language' => $user->prefer_language,
							'group_user' => $user->grop_user,
							'ip_address' => $user->ip_address,
							'api_token' => $user->api_token,
							'company_id' => $user->company_id,
							'role_id' => $user->role_id];

					//3.1复制公司信息到远程bu站点并做必要的系统初始化工作

					$res = ReplicationRequest::rpcAddUserAndCompany( $bu->bu_site, $userArray, $companyArray);
					$res = json_decode($res);
					Log::debug('result 11111 ==='.print_r($res,true));
				 
					if($res->result == '0000' ){
					    Log::debug("是否进入");
						Session::get('USERINFO')->companyId = $companyId;//setup companyId for session
						DB::commit();
						return Redirect::to('/dashboard/my-company')->with('result',Lang::get('mowork.initial_success')).': '.$bu->bu_site;
					} else {

						DB::rollback();
                        Log::debug("测试退出");
						return Redirect::to('/dashboard/my-company')->with('result',Lang::get('mowork.initial_failure'));
					}

				} catch (Exception $e){

					DB::rollback();
					return Redirect::back()->with('result',Lang::get('mowork.db_err'));
				}

		}

		$companyTypeList = AjaxController::CompanyTypeList();
		$companyIndustryList = AjaxController::CompanyIndustryList();

		$countryId = 0;
		$provinceId = 0;
		$cityId = 0;

		$countryList = AjaxController::countryList();
		$provinceList = AjaxController::provinceList(1);
		$cityList = AjaxController::cityList(0);
	  	$licenseImg = '';

		$bulist = Buhost::where('is_master',0)->orderBy('bu_id','asc')->get();
		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.company_fill_application');

		$domain = $_SERVER['HTTP_HOST'];

		return view('backend.company-creation',array('domain' => $domain, 'bulist' => $bulist, 'licenseImg' => $licenseImg, 'companyTypeList' => $companyTypeList, 'companyIndustryList' => $companyIndustryList, 'countryList' => $countryList, 'provinceList' => $provinceList, 'cityList' => $cityList, 'countryId' => $countryId,
				'provinceId' => $provinceId, 'cityId' => $cityId, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	}




	// 公司名称补全
	public function companyCompletion(Request $request)
	{
		$companyName = $request->get('companyName');
		$tianyanchaToken = config('app.tianyanchaToken');
		$url = 'https://open.api.tianyancha.com/services/v4/open/searchV2.json?word=' . urlencode($companyName);
		$header = ['Authorization:'.$tianyanchaToken];
		$result = $this->httpsRequset($url, $header);

		$json = '';

		if($result['error_code'] == 0) {
			$data = $result['result']['items'];
			$res = [];
			foreach($data as $key => $value) {
				if($key > 9) {
					break;
				}
				$tmpName = strtr($value['name'], ['<em>' => '', '</em>' => '']);
				$res[$tmpName] = $value['base'];
			}
			$json = json_encode($res);
		}

		return $json;
	}

	// 公司信息补全
	public function companyInfoCompletion(Request $request)
	{
		$name = $request->get('name');
		$url = 'https://open.api.tianyancha.com/services/v4/open/baseinfo.json?name=' . urlencode($name);
		$tianyanchaToken = config('app.tianyanchaToken');
		$header = ['Authorization:'.$tianyanchaToken];
		$result = $this->httpsRequset($url, $header);
		$json  = '';
		if($result['error_code'] == 0) {
			$data['reg_no'] = $result['result']['creditCode'];
			$data['biz_des'] = $result['result']['businessScope'];
			$data['ceo'] = $result['result']['legalPersonName'];
			$data['address'] = $result['result']['regLocation'];
			$json = json_encode($data);
		}

		return $json;
	}

	// curl请求 get 带head头
	private function httpsRequset($url, $header)
	{
		$ch = curl_init();  //初始化
		curl_setopt($ch,CURLOPT_URL,$url);  //设置url
		// curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_BASIC);  //设置http验证方法
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //https 跳过证书检查
		curl_setopt($ch,CURLOPT_HEADER,0);  //设置头信息
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //设置curl_exec获取的信息的返回方式

		$header && curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

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

	public function companyEdit(Request $request, $token, $company_id)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$uid = Session::get('userId');

		$cmpToken = hash('sha256',$uid.$this->salt.$company_id);
		if($token != $cmpToken) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed') );
		}

		if ($request->has('submit')) {
			//1eck if the company had setup before

			$row = Company::where('company_id',$company_id)->first();
			if($row) {//update
				try {
					//check if company business license uploaded
					$licensefullpath = '';
					if(Session::has('LICENSE')) {
						$fullpath = storage_path().'/tmp/'.Session::get('LICENSE');
						$licensepath = '/license/'.$company_id.'/';

						if(! Storage::disk('company')->exists($licensepath)){//create license dir for $company_id if non-existance
							Storage::disk('company')->makeDirectory($licensepath);
						}   
						
						$licensefullpath = storage_path().'/company/license/'.$company_id.'/'.Session::get('LICENSE');
						rename($fullpath, $licensefullpath);
						 
					}

					$companyArray = array('company_name' => $request->get('company_name'), 'reg_no' => empty($request->get('reg_no'))?'':$request->get('reg_no'), 'biz_des' => empty($request->get('biz_des'))?'':$request->get('biz_des'),
							'legal_person' => empty($request->get('legal_person'))?'':$request->get('legal_person'), 'ceo' => empty($request->get('ceo'))?'':$request->get('ceo'), 'phone' => $request->get('phone'),
							'fax' => empty($request->get('fax'))? '':$request->get('fax'), 'email' => $request->get('email'), 'website' => empty($request->get('website'))?'':$request->get('website'),
							'wechat_pub_acct' => empty($request->get('wechat_pub_acct'))?'':$request->get('wechat_pub_acct'),
							'industry' => $request->get('industry'),
							'company_type' => $request->get('company_type'), 'country' => $request->get('country'), 'province' => $request->get('province'), 'city' => $request->get('city'), 'address' => $request->get('address'),
							'postcode' => $request->get('postcode')	);

					if($licensefullpath) {//update new licese
						//remove old license file
						$company = Company::where('company_id',$company_id)->first();
						if(file_exists($company->license)) {
							unlink ($company->license);
						}
						
						$companyArray['license'] = $licensefullpath;
						Company::where('company_id',$company_id)->update($companyArray);
						Session::forget('LICENSE');

					} else {//keep old license
						Company::where('company_id',$company_id)->update($companyArray);
					}

					//rpc update -----------------------------------------------------------

					$companyinfo = Company::join('buhost','buhost.bu_id','=','company.domain_id')->where('company_id', $company_id)->first();
					$companyArray = array_merge(array('company_id' => $company_id), $companyArray);

					if($companyinfo->forward_domain) {//这里是主站 ，需要同步更新bu站点
						$res = ReplicationRequest::rpcUpdateCompany($companyinfo->bu_site, $companyArray);
					} else {//这里是bu站点，需要同步更新主站
						$res = ReplicationRequest::rpcUpdateCompany('www.mowork.cn', $companyArray);
					}

					$res = json_decode($res);
					if($res->result == '0000' ){
						return Redirect::back()->with('result',Lang::get('mowork.operation_success'));
					} else {
						return Redirect::back()->with('result',Lang::get('mowork.synchronize_failure'));
					}

				} catch (Exception $e){
					return Redirect::back()->with('result',Lang::get('mowork.db_err'));
				}
				return Redirect::back()->with('result',Lang::get('mowork.operation_success'));

			}

		}

		$row = Company::where('company_id',$company_id)->first();

		$companyTypeList = AjaxController::CompanyTypeList();
		$companyIndustryList = AjaxController::CompanyIndustryList();

		$countryId = 0;
		$provinceId = 0;
		$cityId = 0;

		if($row) {
			$countryId = $row->country;
			$countryList = AjaxController::countryList();
			$provinceId = $row->province;
			$provinceList = AjaxController::provinceList($row->country?$row->country:1);
			$cityId = $row->city;
			$cityList = AjaxController::cityList($row->province);
		} else {
			$countryList = AjaxController::countryList();
			$provinceList = AjaxController::provinceList(1);
			$cityList = AjaxController::cityList(0);
		}
		//$imgurl = storage_path('company/license/2-cb826676ad4badfe99d0d4baa5fc5f8fmashroom.jpg');
		$licenseImg = '';
		$licenseUrl = '';
		if(isset( $row->license )) {
			$licenseUrl = $row->license;
		}
		if($licenseUrl) {//because storage directory is protected for security
			if(File::exists($licenseUrl)){
				$imageData = base64_encode(file_get_contents( $licenseUrl ));
				$licenseImg = 'data: '.mime_content_type($licenseUrl).';base64,'.$imageData;
			}
		}

		$bulist = Buhost::where('is_master',0)->orderBy('bu_id','asc')->get();
		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.setup').Lang::get('mowork.update').Lang::get('mowork.company_profile');

		$currentHost = $request->root();
		$currentHost = explode("//", $currentHost);
		$domain = $currentHost[1];

		return view('backend.company-edit',array('token' => $token, 'company_id' => $company_id, 'row' => $row,'domain' => $domain, 'bulist' => $bulist, 'licenseImg' => $licenseImg,
				'companyTypeList' => $companyTypeList, 'companyIndustryList' => $companyIndustryList, 'countryList' => $countryList, 'provinceList' => $provinceList, 'cityList' => $cityList, 'countryId' => $countryId,
				'provinceId' => $provinceId, 'cityId' => $cityId, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));

	}

	public function purchaseService(Request $request ) {
		if(! Session::has('userId')) return Redirect::to('/');

		$permit = Company::where('company_id', Session::get('USERINFO')->companyId)->where('effect_date','<=',date('Y-m-d'))->where('expiry_date', '>=', date('Y-m-d'))->first();

		if($request->has('submit')) {

			$package = $request->get('package');
			$pack = $package[0];
			$effectDate = date('Y-m-d');

			if($pack == 1) {
				$userPermits = 5;
			}
			else {
				$userPermits = 10;
			}

			if(count($permit)) {//renew permit
				$expiryDate = date('Y-m-d',strtotime($permit->expiry_date . ' 1 year'));
			}
			else { //first time or after expiray to purchase permit
				$expiryDate = date('Y-m-d',strtotime(date('Y-m-d') . ' 1 year - 1 day'));
			}

			try {
				Company::where('company_id',Session::get('USERINFO')->companyId)->update(array('user_permits' => $userPermits,'effect_date' => $effectDate, 'expiry_date' => $expiryDate));
			} catch (\Exception $e) {

				return Redirect::back()->with('result',Lang::get('mowork.update_license_failure'));

			}

			return Redirect::back()->with('result',Lang::get('mowork.update_license_success'));
		}

		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.setup').Lang::get('mowork.purchase_service');
		return view('backend.purchase-service',array('permit' => $permit, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	}

	public function orderHistory(Request $request, Response $response)
	{

		if(! Session::has('userId')) return Redirect::to('/');

		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '. Lang::get('mowork.order_history');
		return view('backend.order-history',array('cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	}

	public function upperProject(Request $request, Response $response)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		if($request->input('submit')){

			$reasonCode = 0;
			$description = '';
			$result = 'success';

			$row = User::where('uid', Session::get('userId'))->first();

			if (!Hash::check($oldPassword, $row->password)) {
				$reasonCode = 11000;
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


			if($result == 'success'){
				return Redirect::back()->with(array( 'result' => Lang::get('mowork.operation_success').': '. Lang::get('mowork.password_updated')));
			}
			else {

				return  Redirect::back()->with(array( 'result' => Lang::get('mowork.operation_failure').': '.$description));
			}
		}

		return view('backend.upperProject',array('locale' => $this->locale));

	}

	public function changePassword(Request $request, Response $response)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		$company_id = isset(Session::get('USERINFO')->companyId) ? Session::get('USERINFO')->companyId:0;//possible has not company yet
		$uid = Session::get('USERINFO')->userId;

		if($request->input('submit')) {

			$reasonCode = 0;
			$description = '';
			$result = 'success';

			$row = User::where('uid', Session::get('userId'))->first();

			$oldPassword = $request->get('oldPassword') == '' ? config('app.initializationPassword') : $request->get('oldPassword');

			if ($row->password && (!Hash::check($oldPassword, $row->password))) {
				$reasonCode = 11000;
				$description = Lang::get('mowork.old_password_unmatch');
				$result = 'failure';
			}
			elseif ($request->get('password') != $request->get('passwordConfirm')) {
				$reasonCode = 11001;
				$description = Lang::get('mowork.password_mismatch');
				$result = 'failure';
			}
			else {
				try{
					User::where('uid',Session::get('userId'))->update(array('password' => Hash::make($request->get('password'))));
				    //###########################################################################################################
					if(Session::has('USERINFO')){//master-bu 同步更新密码
						$userinfo = Session::get('USERINFO');
						if(isset($userinfo ->companyId))
						{
							$company_id = $userinfo ->companyId;
							$companyinfo = Company::join('buhost','buhost.bu_id','=','company.domain_id')->where('company_id', $company_id)->first();
							$user = User::where('uid',Session::get('userId'))->first();
							$userArray = array('uid' => $user->uid, 'password' => $user->password);

							if(isset($companyinfo->forward_domain)) {//这里是主站 ，需要同步更新bu站点
								$res = ReplicationRequest::rpcUpdatePassword($companyinfo->bu_site, $userArray);
							} else {//这里是bu站点，需要同步更新主站
								$res = ReplicationRequest::rpcUpdatePassword('www.mowork.cn', $userArray);
							}

							$res = json_decode($res);
							if($res->result == '0000') {
								$result = 'success';
							} else {
								$result = 'failure';
							}
						}

					}

				   //
				} catch (Exception $e) {
					$reasonCode = 10000;
					$description = Lang::get('mowork.database_error');
				}
			}

			if($result == 'success'){
				if(isset($_SESSION['CHANGPASSWARNING'])) {
					unset($_SESSION['CHANGPASSWARNING']);
				}
				event(new TableRowChanged('user', $row->id, 'password changed', $uid, $company_id, Date('Y-m-d h:i:s')));//触发记录日志
				return Redirect::back()->with(array( 'result' => Lang::get('mowork.password_updated')));
			}
			else {

				return  Redirect::back()->with(array( 'result' => Lang::get('mowork.operation_failure').': '.$description));
			}

		}

		$row = User::where('uid', Session::get('userId'))->first();
		Hash::check(config('app.initializationPassword'), $row->password) && $row->password = '';
		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.change_password');
		return view('backend.change-password',array('active-item' => 'change-password','cookieTrail' => $cookieTrail, 'row' => $row, 'locale' => $this->locale));
	}

	public function personalProfile(Request $request) {
		if(! Session::has('userId')) return Redirect::to('/');

		if($request->input('submit')){

			$success = true;

			// check if email or mobile phone is unique in the database
			if (! empty ( $request->get ( 'email' ) ) && ! Session::get ( 'USERINFO' )->bandedEmail) {
				$existed = User::where ( 'email', $request->get ( 'email' ) )->where ( 'uid', '!=', Session::get ( 'userId' ) )->first ();
				if ($existed) {
					return Redirect::back ()->with ( array (
							'result' => Lang::get ( 'mowork.email_existed' )
					) );
				}
			}

			if (! empty ( $request->get ( 'mobile' ) )) {
				$existed = User::where ( 'mobile', $request->get ( 'mobile' ) )->where ( 'uid', '!=', Session::get ( 'userId' ) )->first ();
				if ($existed) {
					return Redirect::back ()->with ( array (
							'result' => Lang::get ( 'mowork.mobile_existed' )
					) );
				}
			}

			// 1. update country
			$country = Country::where ( 'country_id', $request->country )->first ();
			// 2. update province
			$provinceName = '';
			if (! empty ( $request->get ( 'province' ) )) {
				$province = Province::where ( 'province_id', $request->get ( 'province' ) )->first ();
				$provinceName = $province->name;
			}
			// 3. update city
			$cityName = '';
			if (! empty ( $request->get ( 'city' ) )) {
				$city = City::where ( 'city_id', $request->get ( 'city' ) )->first ();
				$cityName = $city->name;
			}

			try {

				$userArray = array (
						'uid' => Session::get ( 'userId' ),
						'fullname' => $request->fullname,
						'username' => $request->get ( 'nickname' ),
						'email' => $request->get('email'),
						'mobile' => $request->get ( 'mobile' ),
						'country' => $country->name,
						'country_id' => $request->get ( 'country' ),
						'province_id' => $request->get ( 'province' ) ? $request->get ( 'province' ) : 0,
						'province' => $provinceName,
						'city' => $cityName,
						'city_id' => $request->get ( 'city' ),
						'address' => $request->get ( 'address' ),
						'postcode' => $request->get ( 'postcode' ));

				User::where ( 'uid', Session::get ( 'userId' ) )->update ($userArray);

				if(Session::has('USERINFO')){//master-bu 同步更新
					$userinfo = Session::get('USERINFO');
					if(isset($userinfo ->companyId))
					{
						$company_id = $userinfo ->companyId;
						$companyinfo = Company::join('buhost','buhost.bu_id','=','company.domain_id')->where('company_id', $company_id)->first();

					    if($companyinfo->forward_domain) {//这里是主站 ，需要同步更新bu站点
					    	$res = ReplicationRequest::rpcUpdateUser($companyinfo->bu_site, $userArray);
					    } else {//这里是bu站点，需要同步更新主站
					    	$res = ReplicationRequest::rpcUpdateUser('www.mowork.cn', $userArray);
					    }

					    $res = json_decode($res);
					    if($res->result == '0000') {
					    	$success = true;
					    } else {
					    	$success = false;
					    }
					}
	  			}

			} catch ( \Exception $e ) {
				$success = false;
			}

			if ($success) {
				Session::put ( 'username', $request->get ( 'nickname' ) );
				return Redirect::back ()->with ( array (
						'result' => Lang::get ( 'mowork.operation_success' )
				) );
			} else {

				return Redirect::back ()->with ( array (
						'result' => Lang::get ( 'mowork.operation_failure' )
				) );
			}
		}

		$row = User::where('uid',Session::get('userId'))->first();
		$countryList = AjaxController::countryList();
		$provinceList = AjaxController::provinceList($row->country_id);
		$cityList = AjaxController::cityList($row->province_id);

		$cookieTrail = Lang::get('mowork.user_info').' &raquo; '.Lang::get('mowork.setup').Lang::get('mowork.update').Lang::get('mowork.personal_info');
		return view('backend.personal-profile',array('row' => $row, 'countryList' => $countryList, 'provinceList' => $provinceList, 'cityList' => $cityList, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));

	}


	public function shareFriend(Request $request)
	{
		//if(! Session::has('userId')) return Redirect::to('/');
		$row = array();
		if(Session::get('userId')) {
			$row = User::where('uid',Session::get('userId'))->first();
		}

		return view('frontend.share-follow',array('pageTitle' => Lang::get('mowork.share_follow'), 'row' => $row, 'locale' => $this->locale));
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
