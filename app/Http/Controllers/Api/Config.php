<?php

namespace App\Http\Controllers\Api;
use App;
use DB;
use Illuminate\Support\Facades\Log;
use Session;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sysconfig;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\Department;
use App\Models\OAuthAccessToken;
use App\Models\OAuthTokenHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Models\Country;
use App\Models\Province;
use App\Models\Buhost;
use App\Http\Controllers\CheckApi;
use App\Http\Controllers\InitController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ReplicationRequest;
use App\Http\Controllers\ReplicationResponse;


class Config extends App\Http\Controllers\Controller
{
     
 /*
    //注册创建公司
    public function companyProfile(Request $request, Response $response)
    {

        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('token','uid','bu_id'));
        if($return !== true){ return $return;}
        
        //company_id、uid必须为数值
        $ary_numric = array('uid','bu_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //email是否合法
        if($request->has('email') && !isValidatedEmailFormat($request->get('email'))){
            return CheckApi::return_10012();
        }

        //phone不符合要求
        if($request->has('phone') && !isValidatedMobileFormat($request->get('phone'))){
            return CheckApi::return_11031();
        }

        //company_id必须为数值
        if($request->has('company_id') && !is_numeric($request->get('company_id'))){
            return CheckApi::return_46001();
        }

        //有company_id则company_name非必须，没有company_id则company_name必须
        if(!$request->has('company_id')){ 
            if(!$request->has('company_name')){return CheckApi::return_46001(); }
        }

        //Buhost数据是否正确
        $return = CheckApi::check_buhost($request->get('bu_id'));
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        if($request->get('company_id') > 0 ) {//update
                $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
                if($return !== true){ return $return;}

                //公司重名
                if($request->has('company_name')){
                    $res = Company::companyNameExit($request->get('company_id'),$request->get('company_name'));
                    if(!empty($res)){ return CheckApi::return_46023();}
                    $ary_company['company_name'] = $request->get('company_name');
                }

                $ary_company['domain_id'] = $request->get('bu_id');
                if($request->has('ceo')){ $ary_company['ceo'] = $request->get('ceo'); }
                if($request->has('phone')){ $ary_company['phone'] = $request->get('phone'); }
                if($request->has('fax')){ $ary_company['fax'] = $request->get('fax'); }
                if($request->has('email')){ $ary_company['email'] = $request->get('email'); }
                if($request->has('website')){ $ary_company['website'] = $request->get('website'); }
                if($request->has('wechat_pub_acct')){ $ary_company['wechat_pub_acct'] = $request->get('wechat_pub_acct'); }
                if($request->has('industry')){ $ary_company['industry'] = $request->get('industry'); }
                if($request->has('company_type')){ $ary_company['company_type'] = $request->get('company_type'); }
                if($request->has('country')){ $ary_company['country'] = $request->get('country'); }
                if($request->has('province')){ $ary_company['province'] = $request->get('province'); }
                if($request->has('city')){ $ary_company['city'] = $request->get('city'); }
                if($request->has('address')){ $ary_company['address'] = $request->get('address'); }
                if($request->has('postcode')){ $ary_company['postcode'] = $request->get('postcode'); }

                $result = Company::where('company_id',$request->get('company_id'))
                                    ->update($ary_company);
                if(!empty($result)){ 
                    $data = array('company_name'=>$request->get('company_name'),'company_id'=>$request->get('company_id'));
                    return CheckApi::return_success($data);
                }else{ 
                    return CheckApi::return_46021();
                }
        
            } else {//create
                //公司重名
                $res = Company::companyExit(array('company_name'=>$request->get('company_name')));
                if(!empty($res)){ return CheckApi::return_46023();}
        
                $currentId = Sysconfig::where('cfg_name','company_current_id')->first();
                $companyId = $currentId->cfg_value.$this->mod9710($currentId->cfg_value);
        
                DB::beginTransaction();
        
                try {
                    $ary_company['company_id'] = $companyId;
                    $ary_company['company_name'] = $request->get('company_name');
                    $ary_company['domain_id'] = $request->get('bu_id');
                    if($request->has('ceo')){ $ary_company['ceo'] = $request->get('ceo'); }
                    if($request->has('phone')){ $ary_company['phone'] = $request->get('phone'); }
                    if($request->has('fax')){ $ary_company['fax'] = $request->get('fax'); }
                    if($request->has('email')){ $ary_company['email'] = $request->get('email'); }
                    if($request->has('website')){ $ary_company['website'] = $request->get('website'); }
                    if($request->has('wechat_pub_acct')){ $ary_company['wechat_pub_acct'] = $request->get('wechat_pub_acct'); }
                    if($request->has('industry')){ $ary_company['industry'] = $request->get('industry'); }
                    if($request->has('company_type')){ $ary_company['company_type'] = $request->get('company_type'); }
                    if($request->has('country')){ $ary_company['country'] = $request->get('country'); }
                    if($request->has('province')){ $ary_company['province'] = $request->get('province'); }
                    if($request->has('city')){ $ary_company['city'] = $request->get('city'); }
                    if($request->has('address')){ $ary_company['address'] = $request->get('address'); }
                    if($request->has('postcode')){ $ary_company['postcode'] = $request->get('postcode'); }

                    Company::create($ary_company);
                    /*                  
                    Sysconfig::where('cfg_name','company_current_id')->increment('cfg_value',1);                    
                    UserCompany::create(array('uid' => $request->get('uid'),'company_id' => $companyId) );
                    */
/*
                    //2.该用户与公司关联,同时赋予系统管理员身份
                    Sysconfig::where('cfg_name','company_current_id')->increment('cfg_value',1);
                    UserCompany::create(array('uid' => $request->get('uid'),'company_id' => $companyId, 'role_id' => '20'));//assign to system administrator
                    
                    //3.初始化各编码起始账号及缺省部门设置
                    InitController::companyInit($companyId);
                    
                    //4.自定义角色起始号
                    InitController::userRoleSelfDefineStarter($companyId);
                    
                    //5.初始化角色资源控制
                    AccountController::initializeRoleResource($companyId); 

                    DB::commit();
                    $data = array('company_name'=>$request->get('company_name'),'company_id'=>$companyId);
                    return CheckApi::return_success($data);
                } catch (Exception $e){
                    return CheckApi::return_46021();
                }
                
                
            }

    }

*/
    /** 
    * 注册创建公司：
    * 
    * @param 
    * @return 
    */
    public function companyProfile(Request $request, Response $response)
    {
        //die(var_dump($request->all()));
        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('token','uid','bu_id'));
        if($return !== true){ return $return;}
     
        //company_id、uid必须为数值
        $ary_numric = array('uid','bu_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }
        
        //email是否合法
        if($request->has('email') && !isValidatedEmailFormat($request->get('email'))){
            return CheckApi::return_10012();
        }

        //phone不符合要求
        if($request->has('phone') && !isValidatedMobileFormat($request->get('phone'))){
            return CheckApi::return_11031();
        }

        //有company_id则company_name非必须，没有company_id则company_name必须
        if(!$request->has('company_id')){ 
            if(!$request->has('company_name')){return CheckApi::return_46001(); }
        }
      
        //company_id必须为数值
        if($request->has('company_id') && !is_numeric($request->get('company_id'))){
            return CheckApi::return_46001();
        }
        
        //Buhost数据是否正确
        $return = CheckApi::check_buhost($request->get('bu_id'));
        if($return !== true){ return $return; }
        
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}
         
        if($request->get('company_id') > 0 ) {//update
                $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
                if($return !== true){ return $return;}

                //公司重名
                if($request->has('company_name')){
                    $res = Company::companyNameExit($request->get('company_id'),$request->get('company_name'));
                    if(!empty($res)){ return CheckApi::return_46023();}
                    $ary_company['company_name'] = $request->get('company_name');
                }

                $ary_company['domain_id'] = $request->get('bu_id');
                   
                if($request->has('ceo')){ $ary_company['ceo'] = $request->get('ceo'); }
                if($request->has('phone')){ $ary_company['phone'] = $request->get('phone'); }
                if($request->has('fax')){ $ary_company['fax'] = $request->get('fax'); }
                if($request->has('email')){ $ary_company['email'] = $request->get('email'); }
                if($request->has('website')){ $ary_company['website'] = $request->get('website'); }
                if($request->has('wechat_pub_acct')){ $ary_company['wechat_pub_acct'] = $request->get('wechat_pub_acct'); }
                if($request->has('industry')){ $ary_company['industry'] = $request->get('industry'); }
                if($request->has('company_type')){ $ary_company['company_type'] = $request->get('company_type'); }
                if($request->has('country')){ $ary_company['country'] = $request->get('country'); }
                if($request->has('province')){ $ary_company['province'] = $request->get('province'); }
                if($request->has('city')){ $ary_company['city'] = $request->get('city'); }
                if($request->has('address')){ $ary_company['address'] = $request->get('address'); }
                if($request->has('postcode')){ $ary_company['postcode'] = $request->get('postcode'); }

                $result = Company::where('company_id',$request->get('company_id'))
                                    ->update($ary_company);
                if(!empty($result)){ 
                    $data = array('company_name'=>$request->get('company_name'),'company_id'=>$request->get('company_id'));
                    return CheckApi::return_success($data);
                }else{ 
                    return CheckApi::return_46021();
                }
        
            } else {//create
                
                //公司重名            	 
                $res = Company::companyExit(array('company_name'=>$request->get('company_name')));
                if(!empty($res)){ return CheckApi::return_46023();}
        
                $currentId = Sysconfig::where('cfg_name','company_current_id')->first();
                $companyId = $currentId->cfg_value.$this->mod9710($currentId->cfg_value);
                
                DB::beginTransaction();
                
                $bu = Buhost::where('bu_id',$request->get('bu_id'))->first();
                
                try {
                    $ary_company['company_id'] = $companyId;
                    $ary_company['company_name'] = $request->get('company_name');
                    $ary_company['forward_domain'] = $bu->bu_site;
                    $ary_company['domain_id'] = $request->get('bu_id');
                    if($request->has('ceo')){ $ary_company['ceo'] = $request->get('ceo'); }
                    if($request->has('phone')){ $ary_company['phone'] = $request->get('phone'); }
                    if($request->has('fax')){ $ary_company['fax'] = $request->get('fax'); }
                    if($request->has('email')){ $ary_company['email'] = $request->get('email'); }
                    if($request->has('website')){ $ary_company['website'] = $request->get('website'); }
                    if($request->has('wechat_pub_acct')){ $ary_company['wechat_pub_acct'] = $request->get('wechat_pub_acct'); }
                    if($request->has('industry')){ $ary_company['industry'] = $request->get('industry'); }
                    if($request->has('company_type')){ $ary_company['company_type'] = $request->get('company_type'); }
                    if($request->has('country')){ $ary_company['country'] = $request->get('country'); }
                    if($request->has('province')){ $ary_company['province'] = $request->get('province'); }
                    if($request->has('city')){ $ary_company['city'] = $request->get('city'); }
                    if($request->has('address')){ $ary_company['address'] = $request->get('address'); }
                    if($request->has('postcode')){ $ary_company['postcode'] = $request->get('postcode'); }


                    //1.主站创建公司
                    $new_company_id = Company::create($ary_company)->company_id;
                     
                    Sysconfig::where('cfg_name','company_current_id')->increment('cfg_value',1);
                    $dep_id = Department::where('dep_code', 'Dep01')->value('dep_id');
                    //2.该用户与公司关联,同时赋予系统管理员身份 并确定 用户的部门
                    UserCompany::create(array('uid' => $request->get('uid'),'company_id' => $companyId, 'role_id' => '20', 'dep_id' => $dep_id));

                    //3.现在已经知道该用户是哪个公司，哪个bu站点，将该用户信息与公司信息同步复制到bu站点
                    $user = User::join('user_company','user_company.uid','=','user.uid')
                                    ->where('user.uid',$request->get('uid'))
                                    ->select('user.*','user_company.company_id','user_company.role_id')->first();
                 
                    $userArray = [  'id' => $user->id, 
                                    'uid' => $user->uid,
                                    'username' => $user->username,
                                    'password' => $user->password,
                                    'usercode' => $user->usercode,
                                    'fullname' => $user->fullname, 
                                    'mobile' => $user->mobile,
                                    'mobile_validated' => $user->mobile_validate, 
                                    'wechat' => $user->wechat, 
                                    'avatar' => $user->avatar, 
                                    'email' => $user->email,
                                    'email_validated' => $user->email_validate, 
                                    'banded_email' => $user->banded_email, 
                                    'qq' => $user->qq,
                                    'weibo' => $user->weibo, 
                                    'gender' => $user->gender, 
                                    'birthdate' => $user->birthdate, 
                                    /*
                    		        'country' => $user->country, 
                                    'province' => $user->province, 
                                    'city' => $user->city, */
                                    'country_id' => $user->country_id, 
                                    'province_id' => $user->province_id, 
                                    'city_id' => $user->city_id,
                                    'address' => $user->address, 
                                    'postcode' => $user->postcode, 
                                    'stickness' => $user->stickness,
                                    'is_active' => $user->is_active, 
                                    'status' => $user->status ,
                                    'prefer_language' => $user->prefer_language,
                                    'group_user' => $user->grop_user, 
                                    'ip_address' => $user->ip_address, 
                                    'api_token' => $user->api_token, 
                                    'company_id' => $user->company_id, 
                                    'role_id' => $user->role_id  ];
                    
                    //3.1复制用户信息到远程bu站点
                    //$res1 = ReplicationRequest::rpcAddUser($bu->bu_site, $userArray);
                    //3.2复制公司信息到远程bu站点并做必要的系统初始化工作
                    //$res2 = ReplicationRequest::rpcAddCompany($bu->bu_site, $ary_company);
                    //复制公司信息到远程bu站点并做必要的系统初始化工作,复制用户信息到远程bu站点
                    $res = ReplicationRequest::rpcAddUserAndCompany( $bu->bu_site, $userArray, $ary_company);
                    $res = json_decode($res);
                  
                    if($res->result == '0000' ){
                        DB::commit();
                        $data = array('company_name'=>$request->get('company_name'),'company_id'=>$companyId);
                        return CheckApi::return_success($data);
                    } else {
                    	DB::rollback();
                    	return CheckApi::return_46021();//need more detail: tell RPC failed
                    }
                } catch (Exception $e){
                	DB::rollback();
                    return CheckApi::return_46021();
                }
            
            }

    }



    public function getCompanyProfile(Request $request, Response $response)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','company_id'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        $result = Company::infoCompany($request->get('company_id'));
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }

    //查询部门成员
    public function departmentMmembers(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','company_id','dep_id'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //dep_id必须为数值
        if(!is_numeric($request->get('dep_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}
            
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
        $result = UserCompany::infoUsersInDep($request->get('company_id'),$request->get('dep_id'),$page_size,$curr_page);
        
        if($result){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }

    }

    //添加部门
    public function createDepartment(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','company_id','dep_name'));
        if($return !== true){ return $return;}

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、部门名是否在已存在公司里面
        $return = CheckApi::check_depnameincomp($request->get('company_id'),$request->get('dep_name'));
        if($return !== true){ return $return;}


        if($request->get('company_id') > 0 && !empty($request->get('dep_name')) ) {//update
            DB::beginTransaction();
            try {
                $company_id = $request->get('company_id');
                $name = $request->get('dep_name');
                $dep_code = '';
                $name_en = $request->get('name_en');
                $upper_id = !empty($request->get('upper_id'))?$request->get('upper_id'):0;
                $upper_en = '';
                $comment = $request->get('comment');
                $manager = !empty($request->get('manager'))?$request->get('manager'):0;
                /*
                $name_en = !empty($request->get('name_en'))?$request->get('name_en'):'';
                $upper_id = !empty($request->get('upper_id'))?$request->get('upper_id'):0;
                $upper_en = '';
                $comment = !empty($request->get('comment'))?$request->get('comment'):'';
                $manager = !empty($request->get('manager'))?$request->get('manager'):0;*/

                $ary = array('company_id'=>$company_id,'name'=>$name,'dep_code'=>$dep_code,'name_en'=>$name_en,'upper_id'=>$upper_id,'upper_en'=>$upper_en,'comment'=>$comment,'manager'=>$manager);
                $result = Department::create($ary);
                $where = array('company_id'=>$company_id,'name'=>$name);
                $res = Department::infoDepartment($where);
                DB::commit();
                $data = array('dep_name'=>$name,'dep_id'=>$res->dep_id);
                return CheckApi::return_success($data);
            } catch (Exception $e){
                DB::rollback();
                return CheckApi::return_10000();
            }
    
        }

    }


    //获取部门基本信息
    public function department(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('uid','token','company_id','dep_id'));
        if($return !== true){ return $return;}

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、部门名是否在已存在公司里面
        $return = CheckApi::check_depincompany($request->get('dep_id'),$request->get('company_id'));
        if($return !== true){ return $return;}

        $result = Department::getDepartmentApi($request->get('dep_id'));
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        } 

    }


    public function getDepartment(Request $request, Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('company_id','dep_id','token','uid'));
        if($return !== true){ return $return;}

        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //用户是否在公司员工里
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //部门是否在公司里
        if($request->get('dep_id') != 'all'){
            $return = CheckApi::check_depincompany($request->get('dep_id'),$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        //未加入任何部门的公司成员
        if($request->get('dep_id')=='all'){
            $userinfo = UserCompany::infoDepUsers($request->get('company_id'),$request->get('dep_id'));
        }else{ 
            $userinfo = UserCompany::infoDepUsers($request->get('company_id'),$request->get('dep_id'));
        }

        //公司下级部门信息列表3
        if($request->get('dep_id')=='all'){ $upper_id = 0;}else{ $upper_id = $request->get('dep_id');}
        $depinfo = Department::infoUpperDeplist($request->get('company_id'),$upper_id);
        $dep_id = $request->get('dep_id');

        foreach ($depinfo as $key => $value) {
            //获取部门及其所有子部门id
            $onedep_id = $value->dep_id;
            $ary_onedepid = DB::table('department')->select('dep_id')
                                ->where(['company_id'=>$request->get('company_id')])
                                ->where('upper_id',$onedep_id)->get();
                                
            $num = UserCompany::where(['company_id'=>$request->get('company_id')])
                   ->where('dep_id',$onedep_id)->count();
            $depinfo[$key]->member_num = $num;
            /*
            $str_onedepid = '';
            if(!$ary_onedepid){
                foreach ($ary_onedepid as $key_one => $value_one) {
                    $str_onedepid = $str_onedepid.','.$value_one->dep_id;
                }
                if(!empty($str_onedepid)){
                    $num = UserCompany::where(['company_id'=>$request->get('company_id')])
                                ->whereRaw("find_in_set(dep_id,'$str_onedepid')")->count();
                    $depinfo[$key]->member_num = $num;
                }         
            }
            */
        }


        if(!empty($depinfo) || !empty($userinfo)){ 
            $data = array('deps'=>$depinfo,'memberInfos'=>$userinfo);
            $res = array('data' => $data,'description'=>'','reasonCode'=>'00000','result'=>'success');
            $json = json_encode($res, JSON_UNESCAPED_UNICODE);
            return $json;
        }else{ 
            $res = array('data' =>'','description'=>Lang::get('mowork.result_isempty'),'reasonCode'=>'46008','result'=>'success');
            $json = json_encode($res, JSON_UNESCAPED_UNICODE);
            return $json;
        }

    }

/*
    //搜索企业或平台用户信息
    public static function search(Request $request,Response $response){ 

        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('com_user_name','token','uid'));
        if($return !== true){ return $return;}

        $uid =  $request->get('uid');
        //用户是否存在
        $return = CheckApi::check_userexit($uid);
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($uid,$request->get('token'));
        if($return !== true){ return $return;}
    }
*/

    //公司或部门内批量删除成员
    public static function deleteDepUser(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('company_id','dep_id','member_ids','token','uid'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}
        
        //dep_id必须为数值
        if(!is_numeric($request->get('dep_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //$member_ids = array(1,3,7);
        //2、member_ids需要是数组
        $return = CheckApi::check_isarray($request->get('member_ids'));
        if($return !== true){ return $return;}

        //3、判断删除的部门是否都在公司里面
        foreach ($request->get('member_ids') as $key => $value) {
            $return = CheckApi::check_depincompany($value,$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        $result = UserCompany::deleteDepUsers($request->get('company_id'),$request->get('member_ids'));
        if(!empty($result)){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }


    }


    //批量用户部门变更
    public static function updateDepUser(Request $request,Response $response)
    {
        //判断参数个数是否足够
        //$return = checkApi::check_format($request,array('company_id','dep_ids','member_ids','token','uid'));
        $return = checkApi::check_format($request,array('company_id','token','uid'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}
        
        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        $member_ids = $request->get('member_ids');
        $dep_ids = $request->get('dep_ids');
        if(!is_numeric($dep_ids)){ return CheckApi::return_46011();}
        //$member_ids = array('1','2','4','5','7');
        //$dep_ids = array('16','16','16','16','16');

//        $return = CheckApi::check_isarray($request->get('dep_ids'));
//        if($return !== true){ return $return;}

        $return = CheckApi::check_isarray($member_ids);
        if($return !== true){ return $return;}

        //用户是否都在公司里
        foreach ($member_ids as $key => $value) {
            $return = CheckApi::check_userincompany($value,$request->get('company_id'));
            if($return !== true){ return $return;}
        }
        //部门是否都在公司里
//        foreach ($request->get('dep_ids') as $key => $value) {
            $return = CheckApi::check_depincompany($dep_ids ,$request->get('company_id'));
            if($return !== true){ return $return;}
//        }
    
        //数组参数要一致
//        if(count($request->get('dep_ids')) !== count($request->get('member_ids'))){
//            return CheckApi::return_46011();
//        }

        $return = true;
        DB::beginTransaction();
        try{
            foreach ($member_ids as $key => $value) {
                Department::updateUserInDepart($value,$dep_ids);
            }
        }catch(Exception $e){ 
            $return = false;
            DB::rollback();
        }
        DB::commit();

        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }

    }


    //公司部门删除
    public function deleteDepartment(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('company_id','dep_id','token','uid'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}
        
        //dep_id必须为数值
        if(!is_numeric($request->get('dep_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}
        
        //2、member_ids需要是数组
        $return = CheckApi::check_isarray($request->get('dep_id'));
        if($return !== true){ return $return;}

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、判断删除的部门是否都在公司里面
        foreach ($request->get('dep_id') as $key => $value) {
            $return = CheckApi::check_depincompany($value,$request->get('company_id'));
            if($return !== true){ return $return;}
        }
        //$dep_id = array(1,3,6,7,8);
        $return = Department::deleteDepartmentAry($request->get('dep_id'));
        
        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }

    }

    //获取公司成员基本信息
    public function memberInfo(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('company_id','member_id','token','uid'));
        if($return !== true){ return $return;}
        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}
        
        //用户不在本公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //被查询的用户不在本公司里面
        $return = CheckApi::check_userincompany($request->get('member_id'),$request->get('company_id'));
        if($return !== true){ return CheckApi::return_46018();}

        $result = UserCompany::infoMemberinfo($request->get('member_id'));

        if($result){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();
        }

    }


    //修改公司成员基本信息
    public static function updateMemberInfo(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('company_id','token','uid','member_id');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }     

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','member_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //mobile不符合要求
        if($request->has('member_phone') && !isValidatedMobileFormat($request->get('member_phone'))){
            return CheckApi::return_11031();
        }

        //工作状态1 2
        if($request->has('work_state')){         
            $work_state = $request->get('work_state');
            $ary_enu = array(1,2);
            if(!in_array($work_state,$ary_enu)){ return CheckApi::return_46011();}
        }
        //email是否合法
        if($request->has('member_email') && !isValidatedEmailFormat($request->get('member_email'))){
            return CheckApi::return_10012();
        }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //用户不在本公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //用户不在本公司里面
        $return = CheckApi::check_userincompany($request->get('member_id'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //部门是否在已存在公司里面
        if($request->has('dep_id')){
            $return = CheckApi::check_depincompany($request->get('dep_id'),$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        //用户公司表
        $ary_usercompany = array( 
            'company_id'=>$request->get('company_id')
        );        
        if($request->has('dep_id')){
            $ary_usercompany['dep_id'] = $request->get('dep_id');//部门ID
        }        
        if($request->has('dep_position')){ 
            $ary_usercompany['position_title'] = $request->get('dep_position');//职位名称
        }
        if($request->has('dep_name')){ 
            $ary_usercompany['dep_name'] = $request->get('dep_name');//部门名称
        }
        if($request->has('work_state')){ 
            $ary_usercompany['status'] = $request->get('work_state');//工作状态
        }

        //用户表
        if($request->has('work_email')){ 
            $ary_user['email'] = $request->get('work_email');//工作邮箱
        }
        if($request->has('member_name')){ 
            $ary_user['fullname'] = $request->get('member_name');//工作名字
        }
        if($request->has('member_phone')){ 
            $ary_user['mobile'] = $request->get('member_phone');//工作电话
        }

        DB::beginTransaction();
        try{
            $result = UserCompany::updateUserCompanyInfoApi($ary_usercompany,$request->get('uid'),
                        $request->get('company_id'));
            if(!empty($ary_user)){
                $result = User::updateUserInfoApi($ary_user,$request->get('uid'));
            }
            DB::commit();
            //'uid'=>$request->get('member_id'),
        }catch(Exception $e){ 
            DB::rollback();
        }
        if($result){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();
        }

    }


    //查询公司所有部门及其子部门 及查询部门下所有的子部门
    public function getSubDeps(Request $request, Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('company_id','token','uid'));
        if($return !== true){ return $return;}

        //数值检测
        $return = CheckApi::check_numeric($request,['uid','company_id']);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //用户是否在公司员工里
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //部门是否在公司里
        if($request->has('dep_id')){
            $return = CheckApi::check_numeric($request,['dep_id']);
            if($return !== true){ return $return; }
            $return = CheckApi::check_depincompany($request->get('dep_id'),$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        //公司下级部门信息列表
        if(!$request->has('dep_id')){ $upper_id = 0;}else{ $upper_id = $request->get('dep_id');}
        $result = Department::getSubDepsApi($request->get('company_id'),$upper_id);

        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();
        }

    }


    //修改部门信息
    public static function updateDepartInfo(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('company_id','token','uid','dep_id','dep_name');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }     

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','dep_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //用户不在本公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //部门是否在已存在公司里面
        if($request->has('dep_id')){
            $return = CheckApi::check_depincompany($request->get('dep_id'),$request->get('company_id'));
            if($return !== true){ return $return;}
        }

        //上级部门是否在已存在公司里面
        if($request->has('parent_dep_id')){
            if($request->get('parent_dep_id') != 0){
                $return = CheckApi::check_depincompany($request->get('parent_dep_id'),$request->get('company_id'));
                if($return !== true){ return $return;}
            }
        }

        //部门经理是否在已存在公司里面
        if($request->has('dep_admin_id')){
            if($request->get('dep_admin_id') != 0){
                $return = CheckApi::check_userincompany($request->get('dep_admin_id'),$request->get('company_id'));
                if($return !== true){ return $return;}
            }
        }

        $dep_id = $request->get('dep_id');
        $ary['name'] = $request->get('dep_name');
        if($request->has('dep_admin_id')){
            $ary['manager'] = $request->get('dep_admin_id');
        }
        if($request->has('parent_dep_id')){
            $ary['upper_id'] = $request->get('parent_dep_id');
        }
        $result = Department::updateDepartInfoApi($dep_id,$ary);

        if($result){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();
        }

    }

     
    /**
    * 获取活动的BU列表
    * @param
    * @return
    */

    public function listBu(Request $request,Response $response)
    { 
            
        $result = Buhost::listBuApi();
        
        if(!empty($result)){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }



}
