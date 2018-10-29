<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\OAuthAccessToken;
use App\Models\Department;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\OpenIssueDetail;
use App\Models\IssueSource;
use App\Models\IssueClass;
use App\Models\Plan;
use App\Models\PlanTask;
 
use App\Models\Approver;
use App\Models\Buhost;


class CheckApi extends Controller
{   
    private $request;
    private $user;


    //判断参数个数是否足够
    public static function check_format($request,$ary)
    { 
        $bool = true;
        foreach ($ary as $key => $value) {
        	
            if(!$request->has($value)){
                $return = self::return_46001();
                return $return;
            }
        }
        return $bool;
    } 



    //判断参数是否是数值
    public static function check_numeric($request,$ary)
    { 
        $bool = true;
        foreach ($ary as $key => $value) {
            if(!is_numeric($request->get($value))){
                $return = self::return_46011();
                return $return;
            }
        }
        return $bool;
    } 


    //判断微信是否已经注册了
    public static function check_wechatexit($unionid)
    { 
        $user = User::where('wechat',$unionid)->first();
        if($user) {
            $return = self::return_46041();
            return $return;
        }else{ 
            return true; 
        }
    } 


    //判断账户是否已经绑定过微信
    public static function bind_wechat()
    { 
        $user = User::where('wechat','!=','null')->first();
        if($user) {
            $return = self::return_46043();
            return $return;
        }else{ 
            return true; 
        }
    } 


    //判断BU是否存在
    public static function check_buhost($id)
    { 
        $result = Buhost::where('bu_id',$id)->first();
        if(!$result) {
            $return = self::return_46042();
            return $return;
        }else{ 
            return true; 
        }
    } 

    //审批权限
    public static function check_approval($company_id,$uid,$type)
    { 
        $bool = true;
        if($type == 'project'){ 
            $return = Approver::select('id')->where(['project_uid'=>$uid])->first();
        }else if($type == 'plan'){ 
            $return = Approver::select('id')->where(['plan_uid'=>$uid])->first();
        }else if($type == 'openissue'){ 
            $return = Approver::select('id')->where(['issue_uid'=>$uid])->first();
        }
        if(!$return) {
            $return = self::return_46036();
            return $return;
        }else{ 
            return true; 
        }
    }

    //通过url判断权限
    public static function check_role($uid,$company_id,$url)
    { 
        $bool = true;
        $return = UserCompany::select('user_role_url.id')
                        ->join('user_role','user_role.role_id','=','user_role_real.role_id')
                        ->join('user_role_url','user_role_url.role_id','=','user_role.role_id')
                        ->where(['user_company.uid'=>$uid,'user_company.company_id'=>$company_id,
                            'user_role_url.url'=>$url])->first();
        if(!$return) {
            $return = self::return_46036();
            return $return;
        }else{ 
            return true; 
        }
    }

    //判断参数是否是数值
    public static function check_numeric_ary($ary)
    { 
        $bool = true;
        foreach ($ary as $key => $value) {
            if(!is_numeric($value)){
                $return = self::return_46011();
                return $return;
            }
        }
        return $bool;
    } 


    // 用户检测
    public static function check_userinfo($uid,$token,$company_id)
    {
    	if(!is_numeric($uid) || !is_numeric($company_id)){
            return self::return_46011();
        }
    	$token = self::check_token($uid,$token);
    	if($token !== true){ return $token;}
    	$userincompany = self::check_userincompany($uid,$company_id);
    	if($userincompany !== true){ return $userincompany; }
    	return true;
    }
    

    
    //判断用户是否在数据库中
    public static function check_userexit($uid)
    { 
        $user = User::where('uid',$uid)->first();
        if(!$user) {
            $return = self::return_46002();
            return $return;
        }else{ 
            return true; 
        }
    }


    //判断当前用户在公司里(不在返回错误)
    public static function check_userincompany($uid,$company_id)
    { 
        $user = UserCompany::select('uid')->where(array('uid'=>$uid,'company_id'=>$company_id,'status'=>1))->first();
        if(!$user){
            $return = self::return_46006();
            return $return;
        }else{ 
            return true;
        }
    }

    //判断计划属不属于公司(不在返回错误)
    public static function check_userplan($company_id,$plan_id)
    { 
        $plan = Plan::select('plan_id')->where(array('plan_id'=>$plan_id,'company_id'=>$company_id))->first();
        if(!$plan){ 
            $return = self::return_46034();
            return $return;
        }else{ 
            return true;
        }
    }

    //判断节点属不属于公司(不在返回错误)
    public static function check_usernode($company_id,$node_id)
    { 
        $plan = PlanTask::select('task_id')
                            ->join('plan','plan.plan_id','=','plan_task.plan_id')
                            ->where(array('plan_task.task_id'=>$node_id,'plan.company_id'=>$company_id))->first();
        if(!$plan){ 
            $return = self::return_46035();
            return $return;
        }else{ 
            return true;
        }
    }

    //issuer是否与公司对应(不在返回错误)
    public static function check_issuerincompany($issuer,$company_id)
    {
        $ary_member = explode(',',$issuer);
        foreach ($ary_member as $key => $value) {
            $company_user = UserCompany::userincompany($value,$company_id);
            if(empty($company_user)){ 
                $return = self::return_46031();
                return $return;
            }else{ 
                $return = true;
            }
        }
        return $return;
    }

    //leader是否与公司对应(不在返回错误)
    public static function check_leaderincompany($leader,$company_id)
    {
        $ary_member = explode(',',$leader);
        foreach ($ary_member as $key => $value) {
            $company_user = UserCompany::userincompany($value,$company_id);
            if(empty($company_user)){ 
                $return = self::return_46032();
                return $return;
            }else{ 
                $return = true;
            }
        }
        return $return;
    }


    //判断issue_id是否合法
    public static function check_issue_id($issue_id,$source_code,$company_id)
    { 
        $return = false;
        if($source_code=='Project'){
            $result = DB::table('project')->select('proj_id')
                        ->where(['proj_id'=>$issue_id,'company_id'=>$company_id])->first();
            if(!$result){ $return = false; }else{ $return = true; }
        }else if($source_code=='Plan'){ 
            $result = DB::table('plan')->select('plan_id')
                        ->where(['plan_id'=>$issue_id,'company_id'=>$company_id])->first();
            if(!$result){ $return = false; }else{ $return = true; }
        // }else if($source_code=='Other' && $issue_id==0){
        }else if($issue_id==0){
            $return = true;
        }
        if(!$return){ 
            return self::return_46033();
        }else{ 
            return true;
        }
    }


    //判断公司与openissue问题类型是否对应(不在返回错误)
    public static function check_issueclass_company($id,$company_id)
    { 
        $issue_class = IssueClass::select('id')->where(['id'=>$id,'company_id'=>$company_id,'status'=>1])->first();
        if(!$issue_class){ 
            return self::return_46029();
        }else{ 
            return true;
        }
    }


    //判断公司与openissue问题来源是否对应(不在返回错误)
    public static function check_issuesource_company($id,$company_id)
    { 
        $issue_class = IssueSource::select('id')->whereIn('company_id', [$company_id, 0])->where(['id'=>$id,'status'=>1])->first();
        if(!$issue_class){ 
            return self::return_46030();
        }else{ 
            return true;
        }
    }


    //判断当前用户在公司某部门里(不在返回错误)
    public static function check_userindepartment($uid,$dep_id)
    { 
        $user = Usercompany::select('uid')->where(array('uid'=>$uid,'dep_id'=>$dep_id,'status'=>1))->first();
        if(!$user){ 
            $return = self::return_46015();
            return $return;
        }else{ 
            return true;
        }
    }

    //判断部门是否在公司里
    public static function check_depincompany($dep_id,$company_id)
    {   
        if($dep_id != 0){
/*            $dep_id = Department::select('dep_id')->where(array('dep_id'=>$dep_id,'company_id'=>$company_id))
                    ->orWhere(['company_id'=>$company_id,'dep_id'=>0])->first();*/
            $dep_id = Department::select('dep_id')->where(array('dep_id'=>$dep_id,'company_id'=>$company_id))
                                    ->orWhere(['company_id'=>0,'dep_id'=>$dep_id])
                                    ->first();
        }else{ 
            $dep_id = 1;
        }
        if(!$dep_id){ 
            $return = self::return_46012();
            return $return;
        }else{ 
            return true;
        }
    }

    //公司与供应商是否对应
    public static function check_sup_company($company_id,$sup_company_id)
    { 
        $result = Supplier::select('id')->where(array('company_id'=>$company_id,'sup_company_id'=>$sup_company_id))->first();
        if(!$result){ 
            $return = self::return_46013();
            return $return;
        }else{
            return true;
        }
    }

    //公司与客户是否对应
    public static function check_custom_company($company_id,$cust_company_id)
    { 
        $result = Customer::select('id')->where(array('company_id'=>$company_id,'cust_company_id'=>$cust_company_id))->first();
        if(!$result){ 
            $return = self::return_46014();
            return $return;
        }else{
            return true;
        }

    }

    //参数是否是数组
    public static function check_isarray($array)
    { 
        if(!is_array($array)){ 
            $return = self::return_46011();
            return $return;
        }else{ 
            return true;
        }
    }


    //token是否真实的/是否已过期
    public static function check_token($uid,$token)
    { 
        $user = User::where('uid',$uid)->first();  
        $uid_token = $uid.'_token';                  
        if(Redis::exists($uid_token)){ 
            if(Redis::get($uid_token)==$token){ 
                return true;
            }
        }
        if(empty($user->api_token) || $token != $user->api_token ) {
            $return = self::return_46004();
            return $return;
        }
        $moment = date('Y-m-d H:i:s');
        $tokenExpired = OAuthAccessToken::where('uid',$uid)->pluck('expiry_at')->first();
        if(empty($tokenExpired)) {
            $return = self::return_10000();
            return $return;
        }
        if(!empty($tokenExpired) && $moment > $tokenExpired) {
            $return = self::return_46003();
            return $return;
        }else{
            return true;
        }

    }

    //判断项目是否属于这个公司
    public static function check_projectincomp($company_id,$proj_id)
    { 
        $result = Project::where(array('proj_id'=>$proj_id,'company_id'=>$company_id))->first();
        if(!$result){ 
            $return = self::return_46025();
            return $return;
        }else{
            return true;
        }
    }

    //判断项目状态是否存在
    public static function check_projectstatus($proj_id)
    { 
        $result = Project::where(array('proj_id'=>$proj_id))->where('proj_status','!=','5')->first();
        if(!$result){ 
            $return = self::return_46026();
            return $return;
        }else{
            return true;
        }

    }

    //判断零件是否属于项目
    public static function check_projectpart($proj_id,$part_id)
    { 
        $result = ProjectDetail::where(array('proj_id'=>$proj_id,'id'=>$part_id))->first();
        if(!$result){ 
            $return = self::return_46027();
            return $return;
        }else{
            return true;
        }
    }

    //判断零件是否属于这个公司
    public static function check_componypart($company_id,$part_id)
    { 
        $result = ProjectDetail::where(array('id'=>$part_id,'company_id'=>$company_id))->first();
        if(!$result){ 
            $return = self::return_46037();
            return $return;
        }else{
            return true;
        }
    }

    //判断公司的部门名是否已存在
    public static function check_depnameincomp($company_id,$dep_id)
    { 
        $result = Department::where(array('name'=>$dep_id,'company_id'=>$company_id))->first();
        if($result){ 
            $return = self::return_46007();
            return $return;
        }else{
            return true;
        }
    }

    //判断公司员工与Openissue是否对应
    public static function check_issuedetail($company_id,$id)
    {
        $result = OpenIssueDetail::where(array('id'=>$id,'company_id'=>$company_id))->first();
        if(empty($result)){ 
            $return = self::return_46028();
            return $return;
        }else{
            return true;
        }
    }

    //正确返回：00000
    public static function return_success($data,$description='')
    {
        $res = array('data' => $data,'description' => $description,'reasonCode' => '00000','result'=>'success');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //密码长度小余6位
    public static function return_10010()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.password_too_short'),'reasonCode'=>'10010','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //验证找回密码失败
    public static function return_46022()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.findpasswordfail'),'reasonCode'=>'46022','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //旧密码不正确
    public static function return_11000()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.old_password_unmatch'),'reasonCode'=>'11000','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //账户密码不匹配
    public static function return_1020()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.account_password_unmatch'),'reasonCode'=>'1020','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //电话号码不匹配
    public static function return_1011($mobile)
    {
        $res = array('data' => array('mobile'=>$mobile),'description'=>Lang::get('mowork.validating_number_unmatch'),'reasonCode'=>'','result'=>'success');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //该用户已存在
    public static function return_10020()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.user_existed'),'reasonCode'=>'10020','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //公司里的部门名重复
    public static  function return_46007()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.department_exit'),'reasonCode'=>'46007','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //查询结果不存在
    public static  function return_46009()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.result_isempty'),'reasonCode'=>'46009','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //数据库操作失败
    public static  function return_46021()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.database_error'),'reasonCode'=>'46021','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //参数个数是否满足
    public static function return_46001()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.request_format_err'),'reasonCode'=>'46001','result'=>'failure');
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }

    //token是否已过期
    public static function return_46003()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.token_expired'),'reasonCode'=>'46003','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //token不存在
    public static function return_46004()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.faked_token'),'reasonCode'=>'46004','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //非合法邮箱
    public static function return_10012()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.invalid_email'),'reasonCode'=>'10012','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //Email链接已失效
    public static function return_10030()
    {           
        $res = array('data' => '','description'=>Lang::get('mowork.email_overtime'),'reasonCode'=>'10030','result'=>'failure');
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }

    //邮件发送失败
    public static function return_10031()
    {
        $res = array('data' => array('email'=>$email),'description'=>Lang::get('mowork.sendmail_fail'),'reasonCode'=>'10031','result'=>'success');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //手机验证码不对
    public static function return_10011()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.validating_number_unmatch'),'reasonCode'=>'10011','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //验证码不正确
    public static function return_11032()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.number_unmatch'),'reasonCode'=>'11032','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //手机号码格式不正确
    public static function return_11031()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.invalid_mobile'),'reasonCode'=>'11031','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断用户是否在数据库中
    public static function return_46002()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.user_nonexistance'),'reasonCode'=>'46002','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断用户是否有权限访问url
    public static function return_46036()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.nopermission_action'),'reasonCode'=>'46036','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //用户性别填写不正确
    public static function return_46019()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.gender_illegal'),'reasonCode'=>'46019','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //公司与供应商不对应
    public static function return_46013()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_notwithsupplier'),'reasonCode'=>'46013','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //公司与客户不对应
    public static function return_46014()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_notwithcustomer'),'reasonCode'=>'46014','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //客户关系已存在
    public static function return_46038()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_customerisexit'),'reasonCode'=>'46038','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //供应商关系已存在
    public static function return_46039()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_supplierisexit'),'reasonCode'=>'46039','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //用户不在公司里
    public static function return_46006()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.user_noincompany'),'reasonCode'=>'46006','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //计划与公司不对应
    public static function return_46034()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_notwithplan'),'reasonCode'=>'46034','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //节点与公司不对应
    public static function return_46035()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.company_notwithnode'),'reasonCode'=>'46035','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //用户不在公司部门里
    public static function return_46015()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.user_notindepartment'),'reasonCode'=>'46015','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //用户已经在公司里
    public static function return_46016()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.user_incompany'),'reasonCode'=>'46016','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //公司名已存在
    public static function return_46023()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.companyname_isexit'),'reasonCode'=>'46023','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //用户在公司部门里
    public static function return_46017()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.user_indepartment'),'reasonCode'=>'46017','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //图片格式不符合要求
    public static function return_50001()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.photo_formatillegal'),'reasonCode'=>'50001','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //图片大小不符合要求
    public static function return_50002()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.photo_max_size'),'reasonCode'=>'50002','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //系统内存不满足上传要求
    public static function return_50003()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.system_memory'),'reasonCode'=>'50003','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //文件上传失败
    public static function return_50004()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.upload_fail'),'reasonCode'=>'50004','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //无权执行此查询
    public static function return_46018()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.no_permission'),'reasonCode'=>'46018','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //获取图片失败：50005
    public static function return_50005()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.fail_getimage'),'reasonCode'=>'50005','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //部门与公司信息不符
    public static function return_46012()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.companydep_illegal'),'reasonCode'=>'46012','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //获取signature失败：50005
    public static function return_50006()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.fail_getisignatur'),'reasonCode'=>'50006','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断公司员工与Openissue不对应：46028
    public static function return_46028()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.openissue_company_illegal'),'reasonCode'=>'46028','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断公司与openissue问题类型是否对应(不在返回错误)
    public static function return_46029()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.issueclass_company_illegal'),'reasonCode'=>'46029','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断公司与openissue问题来源是否对应(不在返回错误)
    public static function return_46030()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.issuesource_company_illegal'),'reasonCode'=>'46030','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //issuer是否与公司对应(不在返回错误)
    public static function return_46031()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.issuer_company_illegal'),'reasonCode'=>'46031','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //leader是否与公司对应(不在返回错误)
    public static function return_46032()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.leader_company_illegal'),'reasonCode'=>'46032','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //判断issue_id是否合法
    public static function return_46033()
    { 
        $res = array('data' =>array(),'description'=>Lang::get('mowork.issueid_company_illegal'),'reasonCode'=>'46033','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //操作数据库失败、数据库错误
    public static function return_10000()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.invalid_operation'),'reasonCode'=>'10000','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //SQL语句执行错误
    public static function return_10003()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.sql_illegal'),'reasonCode'=>'10003','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //参数值不符合要求
    public static function return_46011()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.param_is_illegal'),'reasonCode'=>'46011','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //公司与项目是否对应
    public static function return_46025()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.project_company_illegal'),'reasonCode'=>'46025','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //项目不存在或者已经删除
    public static function return_46026()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.project_is_noexit'),'reasonCode'=>'46026','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //项目和零件不对应
    public static function return_46027()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.project_detail_illegal'),'reasonCode'=>'46027','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //项目和零件不对应
    public static function return_46037()
    {
        $res = array('data' => '','description'=>Lang::get('mowork.company_detail_illegal'),'reasonCode'=>'46037','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    
    //微信登录失败
    public static function return_46020()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.wechatlogin_fail'),'reasonCode'=>'46020','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //双方不是朋友关系
    public static function return_46024()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.isnot_friend'),'reasonCode'=>'46024','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    //UnionId在数据库中不存在
    public static function return_46040()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.wechatid_notexit'),'reasonCode'=>'46040','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //UnionId在数据库中已经存在
    public static function return_46041()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.wechatid_isexit'),'reasonCode'=>'46041','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //Buhost数据不正确
    public static function return_46042()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.buhost_nocorrect'),'reasonCode'=>'46042','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }


    //账户已经绑定过微信
    public static function return_46043()
    { 
        $res = array('data' => '','description'=>Lang::get('mowork.userbindwechat'),'reasonCode'=>'46043','result'=>'failure');
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }



}
