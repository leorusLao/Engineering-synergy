<?php

namespace App\Http\Controllers;
use App;
use DB;
use Session;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\InvitedUser;
use App\Models\Sysconfig;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use App\Models\OAuthAccessToken;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\UserRoleReal;
use App\Http\Controllers\CheckApi;
use App\Models\Myfriend;
use App\Models\Supplier;
use App\Models\Customer;


class AccountApi extends  Controller
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

    //--这是PC版微信登录注册使用！see: (api.php=>/wechat/login)
    public function login(Request $request,Response $response)
    {
        //1.app或者网页在获得微信OAuth2授权之后来MoWork注册或登录
        //2.对于网页微信登录其后跳转http://test.mowork.cn/login/wechat 见(app/public/weixin/authback.php)
         
        if(!$request->has('client_type') || !$request->has('identity_type')){ 
            $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'46001','result'=>'failure');
            return json_encode($res,JSON_UNESCAPED_UNICODE);
        }
         
        $identity_type = $request->get('identity_type');
        if($identity_type=='wechat'){ 
            //微信登录
             
            if(!$request->has('unionid')){ 
                $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'46001','result'=>'failure');
                return json_encode($res,JSON_UNESCAPED_UNICODE);        
            }else{
                $wechatId = $request->get('unionid');
                $user = User::where('wechat',$wechatId)->first();
                
                if ($user) {//existed user
                    if($request->has('uid')){
                        $user->uid = $request->get('uid');
                        $user->save();
                        OAuthAccessToken::where('username', $wechatId)->update(['uid' => $request->get('uid')]);
		            }
                    $res = Api\Account::LoginSuccess( $user, $response );
                    return $res;
                } else {//new user the signup
                    $res = Api\Account::signupWithWechat($request,$response);
                    return $res;
                }
            }

        }else if($identity_type=='email' || $identity_type=='mobile'){ 
            //email、mobile登录
            if(!$request->has('user') || (!$request->has('password') && !$request->has('vericode')) ){
                $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'40001','result'=>'failure');
                return json_encode($res,JSON_UNESCAPED_UNICODE);
            }
            $user = $request->get('user');

            if(!$request->has('vericode') && $request->has('password')){
                $password = $request->get('password');

                if(!isPasswordLongEnough($password, 6)){
                    $res = array('data' => '','description'=>Lang::get('mowork.password_min_length'),'reasonCode'=>'10010','result'=>'failure');
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                    return $json;
                }
            }
 
            if($identity_type == 'mobile'){
                if($request->has('vericode')){ 
                    $vericode = $request->get('vericode');
                    if($vericode==123456){ 
                        $user = User::where('mobile',$user)->first();
                    }else{ 
                        $res = array('data' => '','description'=>Lang::get('mowork.validating_number_unmatch'),'reasonCode'=>'10011','result'=>'failure');
                        $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                        return $json;
                    }
                }
                if($request->has('password')){ 
                    $user_exit = Auth::guard('web')->attempt(['mobile' => $user, 'password' => $password]);
                    if($user_exit){ 
                        $user = User::where('mobile',$user)->first();
                    }else{ 
                        $res = array('data' => '','description'=>Lang::get('mowork.account_password_unmatch'),'reasonCode'=>'1020','result'=>'failure');
                        $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                        return $json;
                    }
                }

            }else if($identity_type == 'email'){
                if(!isValidatedEmailFormat($user)) {
                    $res = array('data' => '','description'=>Lang::get('mowork.invalid_email'),'reasonCode'=>'10012','result'=>'failure');
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                    return $json;
                }
                $user_exit = Auth::guard('web')->attempt(['email' => $user, 'password' => $password]);
                if($user_exit){ 
                    $user = User::where('email',$user)->first();
                }else{ 
                    $res = array('data' => '','description'=>Lang::get('mowork.db_err'),'reasonCode'=>'10000','result'=>'failure');
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                    return $json;
                }
            }
            if($user){
                $result = Api\Account::LoginSuccess($user, $response);
                return $result;
            }else{ 
                $res = array('data' => '','description'=>Lang::get('mowork.db_err'),'reasonCode'=>'10000','result'=>'failure');
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                return $json;
            }
        }else{ 
                $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'40001','result'=>'failure');
                return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

            
    }

    public function bandWechat(Request $request,Response $response)
    {
        //email或Mobile账号绑定微信; 该接口被auth-band-back.php调用见(app/public/weixin/auth-band-back.php)
        if(!$request->has('client_type') || !$request->has('identity_type')){ 
            $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'46001','result'=>'failure');
            return json_encode($res,JSON_UNESCAPED_UNICODE);
        }
        
        $identity_type = $request->get('identity_type');
        if($identity_type=='wechat'){ 
            //微信登录
         
             if(!$request->has('unionid')){ 
                $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'46001','result'=>'failure');
                return json_encode($res,JSON_UNESCAPED_UNICODE);        
            }else{
                $wechatId = $request->get('unionid');
                $band_uid = $request->get('band_uid');
                $user = User::where('wechat',$wechatId)->first();
                
                if (!$user || ( isset($user->uid) && ($user->uid == $band_uid ))) {
                	//return result to weixin/auth-band-back.php
                	//只有当该没有注册的微信号才可以绑定email,手机注册的账号
                	$user = User::where('uid',$band_uid)->update(array('wechat' => $wechatId,'avatar' =>$request->get('headimgurl')));
                    $res = array('data' => '','description'=>Lang::get('mowork.operation_success'),'reasonCode'=>'00000','result'=>'success');
                    $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                    return $json;
                } else {
                	    //该微信号有自己的独立账号，不能与email,手机注册的账号绑定
                	 	$res = array('data' => '','description'=>Lang::get('mowork.operation_failure'),'reasonCode'=>'10001','result'=>'failure');
                		$json = json_encode($res, JSON_UNESCAPED_UNICODE);
                		return $json;
                	 
                }
            }
        }
    }
    
    //用户登录
    public function loginApi(Request $request,Response $response)
    {
        //1.app在获得微信OAuth2授权之后来MoWork注册或登录
        //2.对于网页微信登录其后跳转http://test.mowork.cn/login/wechat 见(app/public/weixin/authback.php)  

        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('client_type','identity_type'));
        if($return !== true){ return $return;}

        if($request->get('identity_type')!='email' && $request->get('identity_type')!='wechat' && $request->get('identity_type')!='mobile'){
            return CheckApi::return_46001();
        }   

        //email则必有password和user
        if($request->get('identity_type')=='email'){ 
            $return = checkApi::check_format($request,array('password','user'));
            if($return !== true){ return $return;}
            if(!isValidatedEmailFormat($request->get('user'))) {
                return CheckApi::return_10012();
            }
            if($request->has('password')){ 
                if(!isPasswordLongEnough($request->get('password'), 6)) {
                    return CheckApi::return_10010();
                }
            }
        }

        //wechat则必有unionid
        if($request->get('identity_type')=='wechat'){ 
            $return = checkApi::check_format($request,array('unionid'));
            if($return !== true){ return $return;}
        }

        //mobile则必有password或者vericode
        if($request->get('identity_type')=='mobile'){ 
            if(!$request->has('password') && !$request->has('vericode')){ 
                return CheckApi::return_46001();
            }
            if($request->has('password')){
                if(!isPasswordLongEnough($request->get('password'), 6)) {
                    return CheckApi::return_10010();
                }
            }
        }
                
        $identity_type = $request->get('identity_type');
        if($identity_type=='wechat'){ 
            //微信登录
            $wechatId = $request->get('unionid');
            $user = User::where('wechat',$wechatId)->first();
            if($user){//existed user
                return Api\Account::LoginSuccess( $user, $response );
            } else {//new user the signup
                return CheckApi::return_46040();
                //return CheckApi::return_46020();
            	//return Api\Account::signupWithWechat($request,$response);
            }

        }else if($identity_type=='email' || $identity_type=='mobile'){ 
            //email、mobile登录
            $user = $request->get('user');
            $password = $request->get('password');
            if($identity_type == 'mobile'){
                if($request->has('vericode')){ 
                    $vericode = $request->get('vericode');
                    if(Redis::exists($user)){ 
                        if(Redis::get($user)==$vericode){ 
                            $user = User::where('mobile',$user)->first();
                        }else{
                            return CheckApi::return_10011(); 
                        }
                    }else{ return CheckApi::return_10011(); }

                }
                if($request->has('password')){ 
                    $user_exit = Auth::guard('web')->attempt(['mobile' => $user, 'password' => $password]);
                    if($user_exit){ 
                        $user = User::where('mobile',$user)->first();
                    }else{ 
                        return CheckApi::return_1020();
                    }
                }

            }else if($identity_type == 'email'){
                $user_exit = Auth::guard('web')->attempt(['email' => $user, 'password' => $password]);
                if($user_exit){ 
                    $user = User::where('email',$user)->first();
                }else{ 
                    return CheckApi::return_1020();
                }
            }
            if($user){
                $result = Api\Account::LoginSuccess($user, $response);
                return $result;
            }else{ 
                return CheckApi::return_1020();
            }
        }
            
    }


    public function inviteEmployee(Request $request,Response $response)
    {
        //use wehcat to invite employee
        if(!$request->has('invitationType') || !$request->has('inviteeId')){
            $result = array('data' => array ('result' => 'failure', 'reasonCode' => '40001', 'description' => Lang::get('mowork.wrongInvitaion'),'status' =>  $response->status()));
            return json_encode($result,JSON_UNESCAPED_UNICODE);
        }

        //1.checkif invitee existed in this company
        $existed = UserCompany::where('company_id',$request->get('companyId'))->where('uid',$request->get('inviteeId'))->first();
        if (! $existed ) {
            try {
                InvitedUser::create(array('host_uid' => $request->get('uid'),'host_company' => $request->get('companyId'),'guest_uid' => $request->get('inviteeId'),'invited_type' => '1', 'guest_company' => $request->get('companyId')));
                UserCompany::create(array('uid' => $request->get('inviteeId'), 'company_id' => $request->get('companyId'),'dep_id' => empty($request->get('departmentId')) ? 0:$request->get('departmentId')));
                $description = Lang::get('mowork.invite_employee_completion');
                $reasonCode = '00000';
                $description = Lang::get('mowork.invite_employee_completion');
            } catch ( \Exception $e ) {
                $reasonCode = '10000';
                $description = Lang::get('mowork.db_err');
            }

        } else {
            $description = Lang::get('mowork.repreated_employee');
            $reasonCode = '46005';
        }

        //record relationship between inviter and invitee and company
            
        $res = array('data' => array('result' => 'success','reasonCode' => $reasonCode , 'description' => $description), 'status' => $response->status());
        $json = json_encode($res, JSON_UNESCAPED_UNICODE);
         
        return $json;
    }


    //inviteType=10的邀请(暂时没用了)
    public function invite(Request $request, Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','inviteType'));
        if($return !== true){ return $return;}

        //先根据邀请type再做 step 1: 看哪些项是必须上传的##########
        $invite_type = $request->get('inviteType');
            
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        if($invite_type == 10){ 
            $client_type = $request->has('clientType')?$request->get('clientType'):'';//邀请类型
            $inviter_companyid = $request->has('inviterCompanyId')?$request->get('inviterCompanyId'):'';//邀请人公司ID    
            $inviter_companyname = $request->has('inviterCompanyName')?$request->get('inviterCompanyName'):'';//邀请人的公司名称  (暂时没用)
            $inviter_userid = $request->has('inviterUserId')?$request->get('inviterUserId'):'';//邀请人ID
            $inviter_username = $request->has('inviterUsername')?$request->get('inviterUsername'):'';//邀请人名称 (暂时没用)
            $department_id = $request->has('departmentId')?$request->get('departmentId'): 0;//部门ID '' => 0
            $department_name = $request->has('departmentName')?$request->get('departmentName'):'';//部门名称
            $role_id = $request->has('roleId')?$request->get('roleId'):'51';//角色ID
            $role_name = $request->has('roleName')?$request->get('roleName'):'';//角色名称
            $invite_type = $request->has('inviteType')?$request->get('inviteType'):'';//邀请类型
            $token = $request->has('token')?$request->get('token'):'';//用户token   
            $invitee_username = $request->has('inviteeUserName')?$request->get('inviteeUserName'):'';//被邀请人姓名
            $invitee_nickname = $request->has('inviteeNickName')?$request->get('inviteeNickName'):'';//被邀请人昵称
            $gender = $request->has('gender')?$request->get('gender'):0;// 性别
            $phone_num = $request->has('phoneNum')?$request->get('phoneNum'):0;//被邀请人电话号码
            $unionid = $request->has('unionId')?$request->get('unionId'):'';//被邀请人电话号码
            $roleName = $request->has('roleName')?$request->get('roleName'):'';//被邀请人角色名称

            try{
                //user
                $where = array('wechat'=>$unionid);
                $field = 'uid';
                $result = User::infoUser($where,$field);

                if(!empty($result)){
                    $guest_uid = $result->uid;
                    $where_uc = array('uid'=>$guest_uid,'company_id'=>$inviter_companyid);
                    $field = 'company_id';
                    $res_company = UserCompany::infoUserCompany($where_uc,$field);
                    
                    //更新user表
                    !empty($invitee_username)?$ary_user['fullname'] = $invitee_username:'';
                    !empty($invitee_nickname)?$ary_user['username'] = $invitee_nickname:'';
                    !empty($gender)?$ary_user['gender'] = $gender:'';
                    !empty($phone_num)?$ary_user['mobile'] = $phone_num:'';
                    if(!empty($ary_user)){
                        $affect = User::updateUser($where,$ary_user);
                    }

                    if(!empty($res_company)){ 
                        //在公司内部
                        $guest_company = $res_company['company_id'];
                        //user_company
                        $array = array('uid'=>$guest_uid,'company_id'=>$inviter_companyid,'dep_id'=>$department_id,'dep_name'=>$department_name,'role_id'=>$role_id);
                        UserCompany::updateUserCompany($where_uc,$array);                  
                    }else{ 
                        //不在公司内部
                        //user_company
                        $array = array('uid'=>$guest_uid,'company_id'=>$inviter_companyid,'dep_id'=>$department_id,'dep_name'=>$department_name,'role_id'=>$role_id);
                        UserCompany::insertUserCompany($array);    
                    }

                    //invited_user
                    $array = array('host_uid'=>$inviter_userid,'host_company'=>$inviter_companyid,'guest_uid'=>$guest_uid,'guest_company'=>$inviter_companyid,'invited_type'=>$invite_type);
                    $res_invited = InvitedUser::infoInvitedUser($array);
                    if(!empty($res_invited)){ 
                        $where = array('host_uid'=>$inviter_userid,'host_company'=>$inviter_companyid,'guest_uid'=>$guest_uid,'guest_company'=>$inviter_companyid,'invited_type'=>$invite_type);
                        $array = array('host_uid'=>$inviter_userid,'host_company'=>$inviter_companyid,'guest_uid'=>$guest_uid,'guest_company'=>$inviter_companyid,'invited_type'=>$invite_type);
                        InvitedUser::updateInvitedUser($where,$array);
                    }else{ 
                        $array = array('host_uid'=>$inviter_userid,'host_company'=>$inviter_companyid,'guest_uid'=>$guest_uid,'guest_company'=>$inviter_companyid,'invited_type'=>$invite_type);
                        InvitedUser::createInvitedUser($array);
                    }
  
                }else{
                    //user          
                    $current_uid = Sysconfig::getCurrentUid();
                    !empty($array['mobile'])?$array['mobile'] = $phone_num:'';
                    $array = array('uid'=>$current_uid,'fullname'=>$invitee_username,'username'=>$invitee_nickname,'gender'=>$gender,'wechat'=>$unionid);
                    $affect = User::insert_user($array);  //这个插入用户有问题？
                    if(!empty($affect)){ 
                        $field = 'uid';
                        $array = array('uid'=>$current_uid);
                        $res_user = User::infoUser($array,$field);
                        $guest_uid = $res_user->uid;
                        Sysconfig::UidIncrement();
                        //var_dump($res_user);
                    }

                    //invited_user
                    $array = array('host_uid'=>$inviter_userid,'host_company'=>$inviter_companyid,'guest_uid'=>$guest_uid,'guest_company'=>$inviter_companyid,'invited_type'=>$invite_type);
                    InvitedUser::createInvitedUser($array);

                    //user_company
                    $array = array('uid'=>$guest_uid,'company_id'=>$inviter_companyid,'dep_id'=>$department_id,'dep_name'=>$department_name,'role_id'=>$role_id);
                    UserCompany::insertUserCompany($array);
 
                }

                $data = array('token'=>$token,'uid'=>$inviter_userid);
                $res = array('data' => $data,'description'=>'','reasonCode'=>'00000','result'=>'success');
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                return $json;

            }catch(Exception $e){
                $res = array('data' => '','description'=>Lang::get('mowork.db_err'),'reasonCode'=>'10000','result'=>'failure');
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                return $json;
            }

        }


    }



    //获取用户信息
    public function getUserInfo(Request $request, Response $response)
    {
        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('token','uid'));
        if($return !== true){ return $return;}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //please correct here: 1 user may have more than 1 company 
         $companies = UserCompany::select('company.company_code','company.company_name','company.company_id',
                    'user_role.role_code','buhost.bu_site')
                     ->where('uid',$request->get('uid'))
                     ->where('user_company.status','1')
                     ->join('company','company.company_id','=','user_company.company_id')
                     ->leftJoin('buhost','buhost.bu_id','=','company.domain_id')
                     ->join('user_role','user_role.role_id','=','user_company.role_id')
                     ->get();
        $result = User::select('uid','username','fullname','mobile','avatar','email','email_validated','gender',
                        'country','province','city','address','user.postcode','wechat')
                        ->where(array('uid'=>$request->get('uid')))->get()->first();
        $result['company'] = $companies;
        $res = array('data' => $result,'description'=>'','reasonCode'=>'00000','result'=>'failure');
    
        //convert null value to empty string
        array_walk_recursive($result, function(&$item, $key) {
            if ($item == null) $item = "";
        });
        if($result){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();
        }

    }

    public function logout(Request $request)
    {
        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','client_type'));
        if($return !== true){ return $return;}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //set token expiry
        OauthAccessToken::where('uid',$request->get('uid'))->where('client_type', $request->has('client_type'))->update(array('expiry_at','1900-12-31: 00:00:00'));
        $res = array('data' => array('result' => 'success','reasonCode' => '00000', 'description' => Lang::get('mowork.logged_out')), 'status' => $response->status());
        $json = json_encode($res, JSON_UNESCAPED_UNICODE);
        return $json;
    }
/*
    //用户注册
    public function register(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('user','regist_type','password'));
        if($return !== true){ return $return;}

        $user = $request->get('user');
        $password = $request->get('password');

        //提交参数是否符合要求
        if($request->get('regist_type') != 'mobile' && $request->get('regist_type') != 'email'){ 
            return CheckApi::return_46001();
        }

        //用户已经存在
        if($request->get('regist_type') == 'mobile'){
            $user_exit = User::where('mobile', $user)->first();
        }else if($request->get('regist_type') == 'email'){
            $user_exit = User::where('email', $user)->first();
        }
        if(!empty($user_exit)) { return CheckApi::return_10020();}


        //获取当前uid
        $currentId = Sysconfig::where(['cfg_name'=>'uid_current_id'])->first()->cfg_value;

        //密码小于6位长度
        if(!isPasswordLongEnough($password, 6)){ return CheckApi::return_10010(); }

        switch ($request->get('regist_type')){
            case 'mobile'://mobile phone
                if(!isValidatedMobileFormat($user)) {
                    return CheckApi::return_11031();
                }
                $id = User::create(array('mobile' => $user,'uid' => $currentId, 'password' => Hash::make($password)))->id;
            break;
            case 'email'://email
                if(!isValidatedEmailFormat($user)) {
                    return CheckApi::return_return_10012();
                }
                $id = User::create(array('email' => $user,'uid' => $currentId, 'password' => Hash::make($password)))->id;           
            break;  
        }
        if($id){ 
            Sysconfig::UidIncrement();
            return CheckApi::return_success(array('uid'=>$id));
        }else{ 
            return CheckApi::return_10000();
        }

    }*/


    /** 
    * 用户注册
    * @param 
    * @return 
    */
    public function register(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('user','regist_type','code'));
        if($return !== true){ return $return;}

        $code = $request->get('code');
        $user = $request->get('user');
        $password = $request->get('password');

        //数值检测
        $return = CheckApi::check_numeric($request,['code']);
        if($return !== true){ return $return; }

        //提交参数是否符合要求
        $regist_type = $request->get('regist_type');
        $ary_enu = array('mobile','email');
        if(!in_array($regist_type,$ary_enu)){ return CheckApi::return_46011();}

        //email的密码小于6位长度
        if($regist_type == 'email'){
            if(!isPasswordLongEnough($password, 6)){ return CheckApi::return_10010(); }
        }

        //用户已经存在
        if($request->get('regist_type') == 'mobile'){
            $user_exit = User::where('mobile', $user)->first();
        }else if($request->get('regist_type') == 'email'){
            $user_exit = User::where('email', $user)->first();
        }
        if(!empty($user_exit)) { return CheckApi::return_10020();}

        //code不正确
        if(Redis::exists($user)){ 
            if(Redis::get($user)!=$code){ return CheckApi::return_11032(); }
        }else{ return CheckApi::return_11032(); }

        //获取当前uid
        //$currentId = Sysconfig::where(['cfg_name'=>'uid_current_id'])->first()->cfg_value;
        DB::beginTransaction();
        try{
            switch ($request->get('regist_type')){
                case 'mobile'://mobile phone
                    if(!isValidatedMobileFormat($user)) { return CheckApi::return_11031();}
                    $ary = array('mobile' => $user,'fullname'=>$user);
                    if(!empty($password)){ $ary['password'] = Hash::make($password);}
                    $id = User::create($ary)->id;
                    if(!empty($id)){
                        $result = User::where(['id'=>$id])->update(['uid'=>$id]);
                    }
                break;
                case 'email'://email
                    if(!isValidatedEmailFormat($user)) {
                        return CheckApi::return_return_10012();
                    }
                    $ary = array('email' => $user,'fullname'=>$user);
                    if(!empty($password)){ $ary['password'] = Hash::make($password);}
                    $id = User::create($ary)->id;  
                    if(!empty($id)){
                        $result = User::where(['id'=>$id])->update(['uid'=>$id]);
                    }
                break;  
            }
            DB::commit();
            return CheckApi::return_success(array('uid'=>$id));
        }catch(Exception $e){ 
            DB::rollback();
            return CheckApi::return_10000();
        }

    }


    //更改用户信息
    public function modifyUserInfo(Request $request)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('token','uid'));
        if($return !== true){ return $return;}
        
        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //emaile is not conform to the rules
        if($request->has('email') && !isValidatedEmailFormat($request->get('email'))){
            return CheckApi::return_10012();
        }

        //mobile不符合要求
        if($request->has('mobile') && !isValidatedMobileFormat($request->get('mobile'))){
            return CheckApi::return_11031();
        }
        
        //性别gender要是1，或者2
        if($request->has('gender') && ( $request->get('gender') != 0 && $request->get('gender') != 1 
            && $request->get('gender') != 2)){ 
            return CheckApi::return_46019();
        }

        if($request->has('unionid')){
            //判断微信号确实不存在
            $return = CheckApi::check_wechatexit($request->get('unionid'));
            if($return !== true){ return $return;}

            //判断账户是否已经绑定过微信
            $return = CheckApi::bind_wechat($request->get('unionid'));
            if($return !== true){ return $return;}
        }

        $uid = $request->get('uid');
        $token = $request->get('token');
        $where = array('uid'=>$uid);

        // 索引字段 wechat mobile email
        if($request->has('wechat')) {
            $tmpRes = User::where('wechat', $request->get('wechat'))->where('uid', '<>', $uid)->count();
            if($tmpRes) { return CheckApi::return_46011();}
        }

        if($request->has('mobile')) {
            $tmpRes = User::where('mobile', $request->get('mobile'))->where('uid', '<>', $uid)->count();
            if($tmpRes) { return CheckApi::return_46011();}
        }

        if($request->has('email')) {
            $tmpRes = User::where('email', $request->get('email'))->where('uid', '<>', $uid)->count();
            if($tmpRes) { return CheckApi::return_46011();}
        }

        //邮政编码放过
        $request->has('address') && $ary['address'] = $request->get('address');
        $request->has('city') && $ary['city'] = $request->get('city');
        $request->has('country') && $ary['country'] = $request->get('country');
        $request->has('email') && $ary['email'] = $request->get('email');
        $request->has('fullname') && $ary['fullname'] = $request->get('fullname');
        $request->has('gender') && $ary['gender'] = $request->get('gender');
        $request->has('mobile') && $ary['mobile'] = $request->get('mobile');
        $request->has('postcode') && $ary['postcode'] = $request->get('postcode');
        $request->has('province') && $ary['province'] = $request->get('province');
        $request->has('unionid') && $ary['wechat'] = $request->get('unionid');



        $bu_site = App\Models\Buhost::where('bu_id', 1)->value('bu_site');

        $data = array_merge($ary, ['uid' => $uid, 'token' => $token]);
        if($bu_site != $_SERVER['HTTP_HOST']) {
            User::updateUser($where,$ary);
            $url = config('app.HTTPS').$bu_site.'/api/user/modify-user-info';
            $res = User::httpsRequest($url, $data);
            $bu_arr = $res['data'];
        }else{
            $tmp = User::where(array_merge($ary, ['uid' => $uid]))->get()->toArray();
            // 主站点已修改
            if(!empty($tmp)){ return CheckApi::return_success([]);}
            User::updateUser($where,$ary);
            $bu_array = UserCompany::select('buhost.bu_site')
                ->where('uid', $uid)
                ->leftJoin('company', 'company.company_id', '=', 'user_company.company_id')
                ->leftJoin('buhost', 'buhost.bu_id', '=', 'company.domain_id')
                ->get()->toArray();
            $bu_arr = [];
            foreach($bu_array as $k => $v)
            {
                in_array($v['bu_site'], $bu_arr) || $bu_arr[] = $v['bu_site'];
            }

            return CheckApi::return_success($bu_arr);
        }

        if(!empty($bu_arr)) {
            foreach($bu_arr as $v)
            {
                if($v != $_SERVER['HTTP_HOST']) {
                    $tmpUrl = config('app.HTTPS').$v.'/api/user/modify-user-info';
                    User::httpsRequest($tmpUrl, $data);
                }
            }
        }

        return CheckApi::return_success(['uid' => $uid]);

//        if($res){
//            $data = array('uid'=>$uid);
//            return CheckApi::return_success($data);
//        }else{
//            return CheckApi::return_10000();
//        }


    }

    //修改密码
    public function modifyPassword(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','old_password','new_password'));
        if($return !== true){ return $return;}
        
        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //The password is incorrect
        $user_exit = Auth::guard('web')->attempt(['uid' => $request->get('uid'), 'password' => $request->get('old_password')]);
        if( empty($user_exit)){ return CheckApi::return_11000();}

        //新密码要大于六位数字
        if(!isPasswordLongEnough($request->get('new_password'), 6)) {return CheckApi::return_10010();}

        $uid = $request->get('uid');
        $token = $request->get('token');
        $new_password = $request->get('new_password');
        $ary = array('password'=>Hash::make($new_password));
        $res = User::updateUser(array('uid'=>$uid),$ary);

        if(!empty($res)){ 
            return CheckApi::return_success($res);              
        }else{ 
            return CheckApi::return_46021();
        }
                            
    }


    //判断用户是否存在
    public function checkIsExit(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('user','identity_type'));
        if($return !== true){ return $return;}

        $user = $request->get('user');

        //类型不正确
        $identity_type = $request->get('identity_type');
        $ary_enu = array('mobile','email');
        if(!in_array($identity_type,$ary_enu)){ return CheckApi::return_46011();}

        if($identity_type == 'mobile'){ 
            if(!isValidatedMobileFormat($user)) {
                return CheckApi::return_11031();
            }
            $res = User::infoUser(array('mobile'=>$user));
        }else if($identity_type == 'email'){ 
            if(!isValidatedEmailFormat($user)) {
                return CheckApi::return_10012();
            }
            $res = User::infoUser(array('email'=>$user));
        }

        if($res){
            return CheckApi::return_success($res);
        }else{
            return CheckApi::return_46009();
        }

    }

    
    /** 
    * 获取手机验证码
    * @param 
    * @return 
    */
    public function getCheckCode(Response $response,Request $request)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('mobile','type','country_code'));
        if($return !== true){ return $return;}

        //枚举
        $type = $request->get('type');
        $ary_enu = array(0,1);
        if(!in_array($type,$ary_enu)){ return CheckApi::return_46011();}

        //mobile不符合要求
        $mobile = $request->get('mobile');
        if(!isValidatedMobileFormat($mobile)){
            return CheckApi::return_11031();
        }

        //这里发送验证码
        $return = Weixin::send_code($mobile,$type);       
        if($return){
            return CheckApi::return_success($return);
        }else{
            return CheckApi::return_10000();
        }
        
    }


    /** 
    * 获取邮箱验证码
    * @param 
    * @return 
    */
    public function getEmailCheckCode(Response $response,Request $request)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('email'));
        if($return !== true){ return $return;}

        $email = $request->get('email');
        //email是否合法
        if(!isValidatedEmailFormat($email)){
            return CheckApi::return_10012();
        }        

        //用户已经存在
        if($request->get('regist_type') == 'mobile'){
            $user_exit = User::where('mobile', $user)->first();
        }else if($request->get('regist_type') == 'email'){
            $user_exit = User::where('email', $user)->first();
        }
        if(!empty($user_exit)) { return CheckApi::return_10020();}

        //这里发送验证码
        $obj_email = new EmailController();
        //$app_path = app_path();
        $time = $_SERVER['REQUEST_TIME'];
        $rand = mt_rand(100000,999999);
        Redis::setex($email,1800,$rand);
        $subject = Lang::get('mowork.emailgetcode');
        $return = EmailController::sendmail_code($email,$subject,$rand);

        if($return){
            return CheckApi::return_success($return);
        }else{
            return CheckApi::return_10000();
        }
        
    }


    //校验验证码(未用到)
    public function checkCode(Response $response,Request $request)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('mobile','code'));
        if($return !== true){ return $return;}

        //2、check the mobile number is correct format
        $mobile = $request->get('mobile');
        if(!isValidatedMobileFormat($mobile)) {
            return CheckApi::return_11031();
        }
        if(Redis::exists($mobile) && Redis::get($mobile) == $request->get('code')){
            Redis::del($mobile);
            return CheckApi::return_success($mobile);
        }else{ 
            return CheckApi::return_1011($mobile);
        }

    }

    //发送密码验证邮件
    public function sendEmail(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('email'));
        if($return !== true){ return $return;}  

        //验证邮箱格式
        $email = $request->get('email');
        if(!isValidatedEmailFormat($email)) { return CheckApi::return_10012(); }

        if(!empty($email)){ 
            $where = array('email'=>$email);
            $uid = User::infoUser($where,'uid');

            if(!empty($uid)){
                $obj_email = new EmailController();
                //$app_path = app_path();
                $time = $_SERVER['REQUEST_TIME'];
                Redis::setex($email,1800,$time);
                $u = encode_id($uid['uid']);
                $e = encode_id($email);
                $t = encode_id($time);
                $url = 'http://weixin.mowork.cn/#/resetPassword?u='.$u.'&e='.$e.'&t='.$t;
                $subject = Lang::get('mowork.emailsubject');
                $bool = EmailController::sendmail_password($email,$subject,$url);
            }else{ 
                return CheckApi::return_46002();
            }
            if($bool){ 
                return CheckApi::return_success(array('email'=>$email));
            }else{ 
                return CheckApi::return_10031();
            }
        
        }

    }


    //发送邮箱注册验证码
    public function sendEmailRegister(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('email'));
        if($return !== true){ return $return;}  

        //验证邮箱格式
        $email = $request->get('email');
        if(!isValidatedEmailFormat($email)) {
            return CheckApi::return_return_10012();
        }

        if(!empty($email)){ 
            $where = array('email'=>$email);
            $uid = User::infoUser($where,'uid');

            if(!empty($uid)){
                $obj_email = new EmailController();
                //$app_path = app_path();
                $time = $_SERVER['REQUEST_TIME'];
                Redis::setex($email,1800,$time);
                $u = encode_id($uid['uid']);
                $e = encode_id($email);
                $t = encode_id($time);
                $url = 'http://weixin.mowork.cn/#/resetPassword?u='.$u.'&e='.$e.'&t='.$t;
                $subject = Lang::get('mowork.emailsubject');
                $bool = EmailController::sendmail_password($email,$subject,$url);
            }else{ 
                return CheckApi::return_46002();
            }
            if($bool){ 
                return CheckApi::return_success(array('email'=>$email));
            }else{ 
                return CheckApi::return_10031();
            }
        
        }

    }



    //通过邮箱找回密码,判断url是否正确
    public function getPasswordByemail(Request $request,Response $response)
    {
        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('u','e','t'));
        if($return !== true){ return $return;}

        $u = $request->get('u');
        $e = $request->get('e');
        $t = $request->get('t');
        $uid = decode_id($u);
        $email = decode_id($e);
        $time = decode_id($t);
        if(!Redis::exists($email) || Redis::get($email) != $time){
            return CheckApi::return_10030();
        }

        $res = User::infoUser(array('uid'=>$uid,'email'=>$email),'email');
        if(!empty($res)){ 
            return CheckApi::return_success($res);
        }else{ 
            return CheckApi::return_46022();
        }

    }


    //设置新密码
    public function setPasswordByemail(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('u','e','t','new_password'));
        if($return !== true){ return $return;}

        $u = $request->get('u');
        $e = $request->get('e');
        $t = $request->get('t');
        $uid = decode_id($u);
        $email = decode_id($e);
        $time = decode_id($t);

        if(!Redis::exists($email) || Redis::get($email) != $time){ return CheckApi::return_10030();}

        //密码要大于六位数字
        if(!isPasswordLongEnough($request->get('new_password'), 6)) { return CheckApi::return_10010(); }

        $where = array('uid'=>$uid,'email'=>$email);
        $field = 'email';
        $res = User::infoUser($where,$field);
        if(empty($res)){ 
            $res = array('data' => '','description'=>Lang::get('mowork.faked_user_info'),'reasonCode'=>'46004','result'=>'failure');
            $json = json_encode($res, JSON_UNESCAPED_UNICODE);
            return $json;
        }else{
            $where = array('uid'=>$uid,'email'=>$email);
            $ary = array('password' => Hash::make($request->get('new_password')));
            $result = User::updateUser($where,$ary);
            if(!empty($result)){
                return CheckApi::return_success(array('uid'=>$uid));
            }else{ 
                $res = array('data' => '','description'=>Lang::get('mowork.faked_user_info'),'reasonCode'=>'46004','result'=>'failure');
                $json = json_encode($res, JSON_UNESCAPED_UNICODE);
                return $json;
            }
        }

    }


    //修改头像上传-base64
    public function modifyAvatar(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('img','token','uid'));
        if($return !== true){ return $return;}

        $img = $request->get('img');
        //$img = 'data:image/jpeg;base64,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';
        $token = $request->get('token');
        $uid = $request->get('uid');

        //用户是否存在
        $return = CheckApi::check_userexit($uid);
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($uid,$token);
        if($return !== true){ return $return;}

        $file = new CheckFile($img);
        //判断图片格式是否合适
        $bool = $file->check_imgformat_base64();
        if(!$bool){ 
            return CheckApi::return_50001();
        }
        //判断图片大小是否符合要求
        $bool = $file->check_size();
        if(!$bool){ 
            return CheckApi::return_50002();
        }
        //判断当前的内存情况
        $bool = $file->check_memory();
        if(!$bool){ 
            return CheckApi::return_50003();
        }
        //图片上传
        $imgurl = $file->upload_file_base64();
        if($imgurl){ 
            $where = array('uid'=>$request->get('uid'));
            $ary = array('avatar'=>$imgurl);
            $result = User::updateUser($where,$ary);
            if($result){
                $path = $imgurl;
                $data = array('avatar'=>$path);
                return CheckApi::return_success($data); 
            }else{ 
                return CheckApi::return_50004();
            }
        }else{ 
                return CheckApi::return_50004();
        }

    }

    //微信传图
    public function uploadImg(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('server_id','token','uid'));
        if($return !== true){ return $return;}

        $server_id = $request->get('server_id');
        $token = $request->get('token');
        $uid = $request->get('uid');

        //用户是否存在
        $return = CheckApi::check_userexit($uid);
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($uid,$token);
        if($return !== true){ return $return;}

        $weixin = new Weixin();
        $token = $weixin->get_token();
        $str_img = $weixin->get_media($token,$server_id);
        if($str_img){
            return CheckApi::return_success(array('img_url'=>$str_img));
        }else{
            $weixin->update_token(); //重置token进redis
            return CheckApi::return_50005();
        }

    }

    //获取JsSdk
    public function getJssdk(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('url','type'));
        if($return !== true){ return $return;}
        $url = $request->get('url');

        $weixin = new Weixin();
        $token = $weixin->get_token();
        $jsapi_ticket = $weixin->get_jsticket($token);
        $data = $weixin->get_signature($jsapi_ticket,$url);
        if($data){
            return CheckApi::return_success($data);
        }else{
            $weixin->update_token(); //重置token进redis
            return CheckApi::return_50006();
        }

    }

    //微信分享设置
    public function weixinShare(Request $request,Response $response)
    {
        $weixin = new Weixin();
        $token = $weixin->get_token();
        $jsapi_ticket = $weixin->get_jsticket($token);
        $data = $weixin->get_signature($jsapi_ticket,'http://test.mowork.cn/index.php');
        if($data){ 
            return $data; 
        }else{ 
            $weixin->update_token(); //重置token进redis
        }

    }


    //搜索企业或平台用户信息
    public function compUserSearch(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('search_type','com_user_name','token','uid'));
        if($return !== true){ return $return;}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}
        
        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //用户不在公司中
        if($request->has('company_id')){
            $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //search_type要是1公司，或者2用户
        if($request->get('search_type') != 1 && $request->get('search_type') != 2){ 
            return CheckApi::return_46011();
        }

        if($request->get('search_type') == 1){
            $result = Company::searchCompany($request->get('com_user_name'));
            if(!empty($request->get('company_id'))){
                foreach ($result as $key => $value) {
                    $is_supplier = Supplier::supplierRelation($request->get('company_id'),$value->company_id);                    
                    $is_customer = Customer::customerRelation($request->get('company_id'),$value->company_id);
                    if(!empty($is_supplier)){ 
                        $result[$key]->relation_type = 1;
                    }elseif(!empty($is_customer)){ 
                        $result[$key]->relation_type = 2;
                    }else{ 
                        $result[$key]->relation_type = 0;
                    }
                }
            }else{ 
                foreach ($result as $key => $value) {
                    $result[$key]->relation_type = 0;
                }
            }
        }else{ 
            $result = User::searchUser($request->get('com_user_name'));
            foreach ($result as $key => $value) {
                $is_friend = Myfriend::isFriend($value->uid,$request->get('uid'));
                if(!empty($is_friend)){ 
                    $result[$key]->relation_type = 1;
                }else{ 
                    $result[$key]->relation_type = 0;
                }
            }
        }
        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();
        }

    }



    //微信首次登陆绑定原有账户
    public function wechatFirstLogin(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $ary_params = array('is_bind');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }      

        //company_id、uid必须为数值
        $ary_numric = array('is_bind');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //枚举
        $is_bind = $request->get('is_bind');
        $ary_enu = array(0,1);
        if(!in_array($is_bind,$ary_enu)){ return CheckApi::return_46011();}

        //token是否真实的/是否已过期
        if($is_bind == 1){
            $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
            if($return !== true){ return $return;}
        }

        if($is_bind == 1){ 
            //绑定
            $result = User::wechatBindApi($request->get('uid'),$request->get('unionid'));
            $data['uid'] = $request->get('uid');
            $data['token'] = $request->get('token');
        }else if($is_bind == 0){
            //判断微信号确实不存在
            $return = CheckApi::check_wechatexit($request->get('unionid'));
            if($return !== true){ return $return;}
            //创建            
            $data = Api\Account::wechatFirstLoginApi($request);
            //var_dump($result);
            //return $result;
        }

        if(!empty($data)){
            return CheckApi::return_success($data);
        }else{
            return CheckApi::return_10000();
        }

    }




}
