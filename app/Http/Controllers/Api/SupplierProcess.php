<?php

namespace App\Http\Controllers\Api;
use App;
use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Http\Controllers\ReplicationRequest;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\InvitedUser;
use App\Models\UserCompany;
use App\Models\User;
use App\Models\Myfriend;
use App\Models\Company;


class SupplierProcess extends App\Http\Controllers\Controller
{

    //修改供应商资料详情
    public function updateSuper(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','sup_company_id','sup_company_name','uid','token');
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}

        //is_tax(传入变量)要0或1
        if($request->has('is_tax')){ 
            if($request->get('is_tax') != 0 && $request->get('is_tax') != 1){return CheckApi::return_46011();}
        }

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //sup_company_id必须为数值
        if(!is_numeric($request->get('sup_company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //quality_level最多2个字符
        if($request->has('quality_level')){ 
            if(intval($request->get('quality_level')) > 10 || intval($request->get('quality_level')) < 0){
                return CheckApi::return_46011();
            }
        }

        //email不合法
        if($request->has('email') && !isValidatedEmailFormat($request->get('email'))){return CheckApi::return_10012();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //mobile不符合要求
        if($request->has('phone') && !isValidatedMobileFormat($request->get('phone'))){
            return CheckApi::return_11031();
        }

        //执行sql的条件:
        //1、用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return !== true){ return $return;}

        //2、公司与供应商是否对应
        $return = CheckApi::check_sup_company($request->get('company_id'),$request->get('sup_company_id'));
        if($return !== true){ return $return;}

        $array['contact_person'] = $request->get('contact_man');//供应商联系人
        $array['phone'] = $request->get('phone');//供应商联系人电话
        $client_type = $request->has('client_type')?$request->get('client_type'):'';//客户端类型

        if($request->has('credit_class')){ $array['credit_class'] = $request->get('credit_class'); }//供应商信誉等级
        if($request->has('email')){ $array['email'] = $request->get('email'); }//供应商联系人邮箱
        if($request->has('is_tax')){ $array['is_taxable'] = $request->get('is_tax'); }//供应商是否含增值税
        if($request->has('quality_level')){ $array['quality_level'] = $request->get('quality_level'); }//供应商质量等级
        if($request->has('sup_company_address')){ $array['sup_company_address'] = $request->get('sup_company_address'); }//供应商公司地址
        if($request->has('sup_company_name')){ $array['sup_company_name'] = $request->get('sup_company_name'); }//供应商公司名称
        if($request->has('supply_type')){ $array['supply_type'] = $request->get('supply_type'); }//供应商供货类型
        if($request->has('tax_code')){ $array['invoice_type'] = $request->get('tax_code'); }//供应商发票种类
        if($request->has('tax_ratio')){ $array['tax_ratio'] = $request->get('tax_ratio'); }//供应商税率

        if($request->has('quality_level_code')){ $array['quality_level_code'] = $request->get('quality_level_code'); }//供应商质量等级编号
        if($request->has('supply_type_code')){ $array['supply_type_code'] = $request->get('supply_type_code'); }//供应商供货类型编号
        if($request->has('tax_type')){ $array['invoice_type'] = $request->get('tax_type'); }//供应商发票种类名称
        if($request->has('tax_type_code')){ $array['tax_type_code'] = $request->get('tax_type_code'); }//供应商发票种类编号

        $result = Supplier::updateSuper($request->get('company_id'),$request->get('sup_company_id'),$array);
        if($result){
            //无奈返回      
            $data['contact_man'] = $request->get('contact_man');
            $data['credit_class'] = $request->get('credit_class');
            $data['email'] = $request->get('email');
            $data['is_tax'] = $request->get('is_tax');
            $data['phone'] = $request->get('phone');
            $data['quality_level'] = $request->get('quality_level');
            $data['quality_level_code'] = $request->get('quality_level_code');
            $data['sup_company_address'] = $request->get('sup_company_address');
            $data['sup_company_id'] = $request->get('sup_company_id');
            $data['sup_company_name'] = $request->get('sup_company_name');
            $data['supply_type'] = $request->get('supply_type');
            $data['supply_type_code'] = $request->get('supply_type_code');
            $data['tax_ratio'] = $request->get('tax_ratio');
            $data['tax_type'] = $request->get('tax_type');
            $data['tax_type_code'] = $request->get('tax_type_code');
            return CheckApi::return_success($data);
        }else{
            return CheckApi::return_10000();
        }

    }

    //修改客户资料
    public function updateCustomer(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $ary_params = array('company_id','token','uid','cst_company_id','cst_company_name');      
        $return = CheckApi::check_format($request,$ary_params);
        if($return !== true){ return $return; }  

        //company_id、uid必须为数值
        $ary_numric = array('company_id','uid','cst_company_id');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //mobile不符合要求
        if($request->has('phone') && !isValidatedMobileFormat($request->get('phone'))){
            return CheckApi::return_11031();
        }

        //email是否合法
        if($request->has('email') && !isValidatedEmailFormat($request->get('email'))){
            return CheckApi::return_10012();
        }

        //客户等级
        if($request->has('grade')){ 
            if(!is_numeric($request->get('grade'))){ return CheckApi::return_46011();}
        }
        //重要程度
        if($request->has('important')){ 
            if(!is_numeric($request->get('important'))){ return CheckApi::return_46011();}
        }
        //客户产品类型 
        if($request->has('cust_type')){ 
            if(!is_numeric($request->get('cust_type'))){ return CheckApi::return_46011();}
        }

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //公司与客户是否对应
        $return = CheckApi::check_custom_company($request->get('company_id'),$request->get('cst_company_id'));
        if($return !== true){ return $return;}

        $company_id = $request->get('company_id');//公司id
        $cst_company_id = $request->get('cst_company_id');//客户公司id
        $client_type = $request->has('client_type')?$request->get('client_type'):'';//客户端类型

        $array['contact_person'] = $request->get('contact_man');//客户联系人
        $array['cust_company_name'] = $request->get('cst_company_name');//客户公司名称
        $array['phone'] = $request->get('phone');//客户联系人电话
        $array['cust_company_address'] = $request->get('cst_company_address');//客户公司地址

        if($request->has('credibility')){ $array['credibility'] = $request->get('credit_class'); }//客户信誉度
        if($request->has('cst_type')){ $array['cust_type'] = $request->get('cst_type'); }//客户产品类型
        if($request->has('email')){ $array['email'] = $request->get('email'); }//客户联系人邮箱
        if($request->has('grade')){ $array['grade'] = $request->get('grade'); }//客户等级
        if($request->has('important')){ $array['important'] = $request->get('important'); }//客户重要程度
        if($request->has('product')){ $array['product'] = $request->get('product'); }//客户重要程度
        if($request->has('credit_class_code')){ $array['credit_class_code'] = $request->get('credit_class_code'); }//客户信誉度编号(暂时没有)
        if($request->has('cst_type_code')){ $array['cst_type_code'] = $request->get('cst_type_code'); }//客户产品类型编号(暂时没有)
        if($request->has('grade_code')){ $array['grade_code'] = $request->get('grade_code'); }//客户等级编号(暂时没有)
        if($request->has('important_code')){ $array['important_code'] = $request->get('important_code'); }//客户重要程度编号(暂时没有)
        

        $result = Customer::updateCustomer($company_id,$cst_company_id,$array);
        if($result){
            $data['contact_man'] = $request->get('contact_man');
            $data['credit_class'] = $request->get('credit_class');
            $data['credit_class_code'] = $request->get('credit_class_code');
            $data['cst_company_address'] = $request->get('cst_company_address');
            $data['cst_company_id'] = $request->get('cst_company_id');
            $data['cst_company_name'] = $request->get('cst_company_name');
            $data['cst_type'] = $request->get('cst_type');
            $data['cst_type_code'] = $request->get('cst_type_code');
            $data['email'] = $request->get('email');
            $data['grade'] = $request->get('grade');
            $data['grade_code'] = $request->get('grade_code');
            $data['important'] = $request->get('important');
            $data['important_code'] = $request->get('important_code');
            $data['phone'] = $request->get('phone');
            $data['product'] = $request->get('product');
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }

    }


    //删除企业的客户或供应商
    public function cstSupdelete(Request $request,Response $response)
    {

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('company_id','cst_sup_id','cst_sup_typ','token','uid'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //company_id必须为数值
        if(!is_numeric($request->get('cst_sup_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //cst_sup_type要是1客户，或者2供应商
        if($request->get('cst_sup_typ') != 1 && $request->get('cst_sup_typ') != 2){ 
            return CheckApi::return_46011();
        }

        if($request->get('cst_sup_typ') == 1){ 
            //公司与客户是否对应
            $return = CheckApi::check_custom_company($request->get('company_id'),$request->get('cst_sup_id'));
            if($return !== true){ return $return;}
            $result = Customer::deleteCustomer($request->get('company_id'),$request->get('cst_sup_id'));
        }else{ 
            //公司与供应商是否对应
            $return = CheckApi::check_sup_company($request->get('company_id'),$request->get('cst_sup_id'));
            if($return !== true){ return $return;}
            $result = Supplier::deleteSupplier($request->get('company_id'),$request->get('cst_sup_id'));            
        }
        if($result){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }

    }




    //获取供应商资料详情
    public function getSuper(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('sup_company_id','token','uid'));
        if($return !== true){ return $return;}

        //sup_company_id必须为数值
        if(!is_numeric($request->get('sup_company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        $sup_company_id = $request->get('sup_company_id');
        $result = Supplier::getSuper($sup_company_id);
        if($result){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }
    }


    //获取客户资料详情
    public function getCustomer(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('cst_company_id','token','uid'));
        if($return !== true){ return $return;}

        //cst_company_id必须为数值
        if(!is_numeric($request->get('cst_company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        $cst_company_id = $request->get('cst_company_id');
        $result = Customer::getCustomer($cst_company_id);
        if($result){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_10000();
        }

    }


    //获取客户或供应商信息列表
    public function getSuperOrCustomer(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('company_id','cst_sup_type','token','uid'));
        if($return !== true){ return $return;}

        //company_id必须为数值
        if(!is_numeric($request->get('company_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //cst_sup_type要是1，或者2
        if($request->get('cst_sup_type') != 1 && $request->get('cst_sup_type') != 2){ 
            return CheckApi::return_46011();
        }

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数
  
        if($request->get('cst_sup_type') == 1){ 
            //获取客户列表
            $result = Customer::getCustomerlist($request->get('company_id'),$page_size,$curr_page);
        }else if($request->get('cst_sup_type') == 2){ 
            //获取供应商列表
            $result = Supplier::getSuperlist($request->get('company_id'),$page_size,$curr_page);
        }
        if($result['cst_info']->count()){
            return CheckApi::return_success($result);
        }else{
            return CheckApi::return_46009();
        }

    }


    //搜索企业自己客户或供应商
    public function cstSupSearch(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $return = checkApi::check_format($request,array('company_id','cst_sup_name','cst_sup_type','token','uid'));
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

        //用户是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('company_id'));
        if($return != true){ return $return;}

        //cst_sup_type要是1客户，或者2供应商
        if($request->get('cst_sup_type') != 1 && $request->get('cst_sup_type') != 2){ 
            return CheckApi::return_46011();
        }       
        //分页输入
        $page_size = $request->has('page_size')?$request->get('page_size'):10; //每页N条数据
        $curr_page = $request->has('curr_page')?$request->get('curr_page'):1; //第N页
        if($page_size > 50){ $page_size = 50;} //限制查询条数

        if($request->get('cst_sup_type') == 1){ 
            //客户
            $result = Customer::searchCustomer($request->get('company_id'),$request->get('cst_sup_name'),$page_size,$curr_page);
        }else{ 
            $result = Supplier::searchSupplier($request->get('company_id'),$request->get('cst_sup_name'),$page_size,$curr_page);
        }
        if(!empty($result)){ 
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_46009();
        }


    }


    /** 
    * 邀请公司供应商：
    * 邀请一个公司成为本公司的供应商，若关系不存在则建立，同事双方成为好友
    * @param 
    * @return 
    */
    public function inviteSup(Request $request,Response $response)
    { 
    	$host = $_SERVER['HTTP_HOST'];
       
    	if($host == 'www.mowork.cn') {//BU站不做该检测,只要主站完成相应的检查.
         //判断参数个数是否足够
         $ary_params = array('address','contractPerson','contractPhoneNum','inviterCompanyId','inviterCompanyName',
                            'inviterUserId','inviterUsername','supplierCompanyId',
                            'supplierCompanyName','token','uid');
         $return = checkApi::check_format($request,$ary_params);
         if($return !== true){ return $return;}

         //company_id、uid必须为数值
         $ary_numric = array('inviterCompanyId','uid','inviterUserId','supplierCompanyId');
         $return = CheckApi::check_numeric($request,$ary_numric);
         if($return !== true){ return $return; }

         //token是否真实的/是否已过期
         $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
         if($return !== true){ return $return;}

         //邀请人是否在公司里面
         $return = CheckApi::check_userincompany($request->get('inviterUserId'),$request->get('inviterCompanyId'));
         if($return !== true){ return $return;}
  
        //被邀请人是否在公司里面
        	$return = CheckApi::check_userincompany($request->get('uid'),$request->get('supplierCompanyId'));
        	if($return !== true){ return $return;}
        }
        
        $ary_sup['sup_company_name'] = $request->get('supplierCompanyName');
        $ary_sup['sup_company_address'] = $request->get('address'); //供应商地址
        //$ary_sup['clientType'] = $request->get('clientType'); //终端类型
        $ary_sup['contact_person'] = $request->get('contractPerson'); //供应商对口联系人
        $ary_sup['phone'] = $request->get('contractPhoneNum'); //供应商联系人电话
        $ary_sup['invoice_type'] = $request->get('invoiceType'); //发票类型
        $ary_sup['invoice_name'] = $request->get('invoiceTypeName'); //发票类型名称(没有字段)
        $ary_sup['sup_company_id'] = $request->get('supplierCompanyId'); //供应商公司id
        $ary_sup['supply_type'] = $request->get('supplyType'); //供应商物料类型
        $ary_sup['sup_material'] = $request->get('supplyTypeName'); //供应商物料名称(没有字段)
        $ary_sup['tax_ratio'] = $request->get('taxRate'); //税率
        $ary_sup['company_id'] = $request->get('inviterCompanyId'); //邀请人的公司id

        $ary_invite['host_company'] = $request->get('inviterCompanyId'); //邀请人的公司id
        $ary_invite['host_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_invite['guest_uid'] = $request->get('uid'); //被邀请人id
        $ary_invite['guest_company'] = $request->get('supplierCompanyId'); //供应商公司id
        $ary_invite['invite_status'] = 3; //接受邀请
        $ary_invite['invited_type'] = 30; //公司邀请供应商注册

        $ary_friend['my_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_friend['my_name'] =  $request->get('inviterUsername'); //邀请人名字
        $ary_friend['fri_uid'] =  $request->get('uid'); //被邀请人id
        $ary_friend['fri_name'] = User::infoUser(array('uid'=>$request->get('uid')),'fullname')->fullname; //被邀请人姓名
        $ary_friend['is_active'] = 1;

        $array['supplierCompanyName'] = $request->get('supplierCompanyName'); //供应商公司名称

        $return = false;
        DB::beginTransaction();
        try{
            $return = CheckApi::check_sup_company($ary_sup['company_id'],$ary_sup['sup_company_id']);
            if($return === true){ 
                //存在
                return CheckApi::return_46039();
            }else{ 
                //不存在
                Supplier::createSupplierInvite($ary_sup);
            }
            InvitedUser::createInvitedUser($ary_invite);
            //若不为朋友
            $res1 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('inviterUserId'),'fri_uid'=>$request->get('uid')));
            $res2 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('uid'),'fri_uid'=>$request->get('inviterUserId')));
            if(empty($res1) && empty($res2)){
                Myfriend::insertMyfriend($ary_friend);
            }

            //如果不是BU站点
            $res_buhost = Company::getBuSite($request->get('inviterCompanyId'));
            if($res_buhost){
                if($res_buhost->bu_site != $_SERVER['HTTP_HOST']){ 
                    ReplicationRequest::rpcInviteSup($request->all(),$res_buhost->bu_site);
                    //复制被邀请公司company表息到bu站点customer表
                    $cci = Company::where('company_id',$request->get('supplierCompanyId'))->first();
                    $custCompanyInfo = array('company_id' => $cci->company_id ,
                    		'company_name' => $cci->company_name,
                    		'company_code' => $cci->company_code,
                    		'forward_domain' => '',
                    		'reg_no' => $cci->reg_no,
                    		'biz_des' => $cci->biz_des,
                    		'biz_size' => $cci->biz_size,
                    		'license' => $cci->license,
                    		'legal_person' => $cci->legal_person,
                    		'phone' => $cci->phone,
                    		'website' => $cci->website,
                    		'fax' => $cci->fax ,
                    		'email' => $cci->fax,
                    		'wechat_public_acct' => '',
                    		'weibo' => '',
                    		'ceo' => $cci->ceo,
                    		'ceo_id'  => $cci->ceo_id,
                    		'company_type' => $cci->company_type,
                    		'industry' => $cci->company_type,
                    		'country' => $cci->industry,
                    		'province' => $cci->province,
                    		'city' => $cci->city,
                    		'address' => $cci->address,
                    		'postcode' => $cci->postcode,
                    		'user_permits' => $cci->user_permits,
                    		'effect_date' => $cci->effect_date,
                    		'expiry_date' => $cci->expiry_date,
                    		'is_active' => $cci->is_active,
                    		'status' => $cci->status,
                    		'stickness' => $cci->stickness,
                    		'domain_id' => $cci->domain_id);
                    ReplicationRequest::rpcAddCustCompanyInfo($custCompanyInfo, $res_buhost->bu_site);
                }
            }
            $return = true;
        }catch(Exception $e){ 
            DB::rollback();
        }
        DB::commit();
        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }

    }

    /** 
    * 邀请公司内部人员：
    * 邀请人员加入公司或者部门，若关系不存在则建立，同事双方成为好友
    * @param 
    * @return 
    */
    public function inviteMember(Request $request,Response $response)
    {
        //判断参数个数是否足够
        $ary_params = array('inviteeUserName','inviterCompanyId','inviterCompanyName','inviterUserId',
                            'inviterUsername','phoneNum','token','uid');
        $return = checkApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}

        //company_id、uid必须为数值
        $ary_numric = array('inviterCompanyId','uid','inviterUserId');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //邀请人是否存在
        $return = CheckApi::check_userexit($request->get('inviterUserId'));
        if($return !== true){ return $return;}

        //被邀请人是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //邀请人是否在公司里面
        $return = CheckApi::check_userincompany($request->get('inviterUserId'),$request->get('inviterCompanyId'));
        if($return !== true){ return $return;}

        //gender要是1，或者2
        if($request->has('gender') && $request->get('gender') != 1 && $request->get('gender') != 2 ){ 
            return CheckApi::return_46011();
        }

        //已在公司或部门就不用再邀请进入
        if($request->has('departmentId')){
            //被邀请人是否在部门里面
            $return = CheckApi::check_userindepartment($request->get('uid'),$request->get('departmentId'));
            $ary_comp['dep_id'] = $request->get('departmentId');//邀请进入的部门ID
            if($return === true){ return CheckApi::return_46016();}
        }
        //被邀请人是否在公司里面
        $return = CheckApi::check_userincompany($request->get('uid'),$request->get('inviterCompanyId'));
        if($return === true){ return CheckApi::return_46017();}
       
        $ary_comp['company_id'] = $request->get('inviterCompanyId');//邀请进入的公司ID
        $ary_comp['role_id'] = $request->get('roleId');//被邀请人角色id
        $ary_comp['uid'] = $request->get('uid');
        if($request->has('roleId')){ $ary_comp['role_id'] = $request->get('roleId');}else{ $ary_comp['role_id'] = '51'; }//被邀请人角色id
        if($request->has('departmentId')){ $ary_comp['dep_id'] = $request->get('departmentId');}//被邀请人部门id
       
        if($request->has('gender')){ $ary_user['gender'] = $request->get('gender');}//被邀请人性别
        $ary_user['fullname'] = $request->get('inviteeUserName');//邀请人姓名
       
        $ary_invite['host_company'] = $request->get('inviterCompanyId'); //邀请人的公司id
        $ary_invite['host_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_invite['guest_uid'] = $request->get('uid'); //被邀请人id
        $ary_invite['guest_company'] = $request->get('inviterCompanyId'); //供应商公司id
        $ary_invite['invite_staus'] = 3;
        //$ary_invite['invited_type'] = 30; //邀请公司内部人员

        $ary_friend['my_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_friend['my_name'] =  $request->get('inviterUsername'); //邀请人名字
        $ary_friend['fri_uid'] =  $request->get('uid'); //被邀请人id
        $ary_friend['fri_name'] = $request->get('inviteeUserName'); //被邀请人姓名
        $ary_friend['is_active'] = 1;

        $return = false;
        DB::beginTransaction();
        try{
            UserCompany::insertUserCompany($ary_comp);
            User::updateUser(array('uid'=>$request->get('uid')),$ary_user);            
            InvitedUser::createInvitedUser($ary_invite);
            //若不为朋友
            $res1 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('inviterUserId'),'fri_uid'=>$request->get('uid')));
            $res2 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('uid'),'fri_uid'=>$request->get('inviterUserId')));
            if(empty($res1) && empty($res2)){
                Myfriend::insertMyfriend($ary_friend);
            }
            //如果不是BU站点
            $res_buhost = Company::getBuSite($request->get('inviterCompanyId'));
            if($res_buhost){
                if($res_buhost->bu_site != $_SERVER['HTTP_HOST']){ 
                    ReplicationRequest::rpcInviteMember($request->all(),$res_buhost->bu_site);
                }
            }
            $return = true;
        }catch(Exception $e){
            DB::rollback();
        }           
        DB::commit();

        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }

    }

    
    /** 
    * 邀请公司外部人员：
    * 若关系不存在则建立，同事双方成为好友
    * @param 
    * @return 
    */
    public function inviteFriend(Request $request,Response $response)
    { 
        //判断参数个数是否足够
        $ary_params = array('gender','inviteeUserName','inviterCompanyId','inviterCompanyName',
                            'inviterUserId','inviterUsername','phoneNum','token','uid');
        $return = checkApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}

        //company_id、uid必须为数值
        $ary_numric = array('inviterCompanyId','uid');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }

        //gender要是1，或者2
        if($request->get('gender') != 1 && $request->get('gender') != 2){ 
            return CheckApi::return_46011();
        }

        //邀请人是否存在
        $return = CheckApi::check_userexit($request->get('inviterUserId'));
        if($return !== true){ return $return;}

        //被邀请人是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //邀请人是否在公司里面
        $return = CheckApi::check_userincompany($request->get('inviterUserId'),$request->get('inviterCompanyId'));
        if($return !== true){ return $return;}
        
        $ary_user['gender'] = $request->get('gender');//被邀请人性别
        $ary_user['fullname'] = $request->get('inviteeUserName');//被邀请人姓名
        //$ary_user[''] = $request->get('inviterCompanyId');//邀请人的公司id(没用)
        //$ary_user[''] = $request->get('inviterCompanyName');//邀请人的公司名称(没用)
        //$ary_user[''] = $request->get('inviterUsername');//邀请人名字(没用)
        //if($request->has('phoneNum')){ $ary_comp[''] = $request->get('phoneNum');}//被邀请人电话号码(没用)

        $ary_invite['host_company'] = $request->get('inviterCompanyId'); //邀请人的公司id
        $ary_invite['host_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_invite['guest_uid'] = $request->get('uid'); //被邀请人id
        $ary_invite['guest_company'] = $request->get('inviterCompanyId'); //供应商公司id
        $ary_invite['invite_staus'] = 3;
        //$ary_invite['invited_type'] = 30; //邀请公司内部人员

        $ary_friend['my_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_friend['my_name'] =  $request->get('inviterUsername'); //邀请人名字
        $ary_friend['fri_uid'] =  $request->get('uid'); //被邀请人id
        $ary_friend['fri_name'] = $request->get('inviteeUserName'); //被邀请人姓名
        $ary_friend['is_active'] = 1;

        $return = false;
        DB::beginTransaction();
        try{
            User::updateUser(array('uid'=>$request->get('uid')),$ary_user);
            InvitedUser::createInvitedUser($ary_invite);           
            //若不为朋友
            $res1 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('inviterUserId'),'fri_uid'=>$request->get('uid')));
            $res2 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('uid'),'fri_uid'=>$request->get('inviterUserId')));
            if(empty($res1) && empty($res2)){
                Myfriend::insertMyfriend($ary_friend);
            }
            //如果不是BU站点
            $res_buhost = Company::getBuSite($request->get('inviterCompanyId'));
            if($res_buhost){
                if($res_buhost->bu_site != $_SERVER['HTTP_HOST']){ 
                    ReplicationRequest::rpcInviteFriend($request->all(),$res_buhost->bu_site);
                }
            }
            $return = true;
        }catch(Exception $e){
            DB::rollback();
        }           
        DB::commit();

        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }

    }


    /** 
    * 邀请公司客户：
    * 邀请成员成为公司的客户，若关系不存在则建立，同事双方成为好友
    * @param 
    * @return 
    */
    public function inviteCustomer(Request $request,Response $response)
    { 
        //判断参数个数是否足够
    	$host = $_SERVER['HTTP_HOST'];
        $ary_params = array('customerCompanyId','customerCompanyName','inviterCompanyId','inviterCompanyName',
                            'inviterUserId','inviterUsername','token','uid');
        $return = checkApi::check_format($request,$ary_params);
        if($return !== true){ return $return;}
      
        //company_id、uid必须为数值
        $ary_numric = array('customerCompanyId','uid','inviterCompanyId','inviterUserId');
        $return = CheckApi::check_numeric($request,$ary_numric);
        if($return !== true){ return $return; }
      
        //邀请人是否存在
        $return = CheckApi::check_userexit($request->get('inviterUserId'));
        if($return !== true){ return $return;}
       
        //被邀请人是否存在
        if($host == 'www.mowork.cn') {
           $return = CheckApi::check_userexit($request->get('uid'));
           if($return !== true){ return $return;}
        } 
       
        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}
       
        //邀请人是否在公司里面
        $return = CheckApi::check_userincompany($request->get('inviterUserId'),$request->get('inviterCompanyId'));
        if($return !== true){ return $return;}
          
        //被邀请人是否在公司里面
        if($host == 'www.mowork.cn') {//BU站不做该检测
        	$return = CheckApi::check_userincompany($request->get('uid'),$request->get('customerCompanyId'));
        	if($return !== true){ return $return;}
        }
      
        if($request->has('address')){ $ary_custom['cust_company_address'] = $request->get('address');}//客户地址
        if($request->has('contractPerson')){ $ary_custom['contact_person'] = $request->get('contractPerson');}//对口联系人
        if($request->has('contractPhoneNum')){ $ary_custom['phone'] = $request->get('contractPhoneNum');}//对口联系人电话
        $ary_custom['cust_company_name'] = $request->get('customerCompanyName');//客户公司名称
        //$ary_custom['company_id'] = $request->get('inviterCompanyId');//邀请人的公司id(不用赋值)
        //$ary_custom['cust_company_id'] = $request->get('customerCompanyId');//客户公司id(不用赋值)
        //$ary_custom[''] = $request->get('inviterCompanyName');//邀请人的公司名称(没用)
        //$ary_custom[''] = $request->get('inviterUserId');//邀请人id
        //$ary_custom[''] = $request->get('inviterUsername');//邀请人邀请人名字
        $ary_custom['invite_staus'] = 3;
        $ary_custom['company_id'] = $request->get('inviterCompanyId');
        $ary_custom['cust_company_id'] = $request->get('customerCompanyId');

        $ary_invite['host_company'] = $request->get('inviterCompanyId'); //邀请人的公司id
        $ary_invite['host_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_invite['guest_uid'] = $request->get('uid'); //被邀请人id
        $ary_invite['guest_company'] = $request->get('customerCompanyId'); //被邀请公司id
        $ary_invite['invite_staus'] = 3;        
        $ary_invite['invited_type'] = 20; //邀请客户

        $ary_friend['my_uid'] = $request->get('inviterUserId'); //邀请人id
        $ary_friend['my_name'] =  $request->get('inviterUsername'); //邀请人名字
        $ary_friend['fri_uid'] =  $request->get('uid'); //被邀请人id
        $ary_friend['fri_name'] = $request->get('inviteeUserName'); //被邀请人姓名
        $ary_friend['is_active'] = 1;

        $return = false;
        DB::beginTransaction();
        try{
            //验证邀请是否在customer已经创建
        	 
            $bool = CheckApi::check_custom_company($request->get('inviterCompanyId'),
                    $request->get('customerCompanyId'));
            if($bool===true){ 
                //存在
                return CheckApi::return_46038();
            }else{ 
                //不存在
                Customer::createCustomerApi($ary_custom);
                 
            }
             
            InvitedUser::createInvitedUser($ary_invite);           //若不为朋友
            $res1 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('inviterUserId'),'fri_uid'=>$request->get('uid')));
            $res2 = Myfriend::infoMyfriend(array('my_uid'=>$request->get('uid'),'fri_uid'=>$request->get('inviterUserId')));
           
            if(empty($res1) && empty($res2)){
                Myfriend::insertMyfriend($ary_friend);
            }
            //确定发起邀请公司的bu站点
            $res_buhost = Company::getBuSite($request->get('inviterCompanyId'));
            
            if($res_buhost){
                if($res_buhost->bu_site != $_SERVER['HTTP_HOST']){ //复制被邀请公司客户信息到bu站点customer表
                    ReplicationRequest::rpcInviteCustomer($request->all(),$res_buhost->bu_site);
                    //复制被邀请公司company表息到bu站点customer表
                    
                    $cci = Company::where('company_id',$request->get('customerCompanyId'))->first();
                    $custCompanyInfo = array('company_id' => $cci->company_id ,
                    		 'company_name' => $cci->company_name,
                    		 'company_code' => $cci->company_code,
                    		  'forward_domain' => '',
			                  'reg_no' => $cci->reg_no,
                    		  'biz_des' => $cci->biz_des,
                    		  'biz_size' => $cci->biz_size,
                    		  'license' => $cci->license,
                    		  'legal_person' => $cci->legal_person, 
                    		  'phone' => $cci->phone,
                    		  'website' => $cci->website,
                    		  'fax' => $cci->fax ,
                    		  'email' => $cci->fax,
			                  'wechat_public_acct' => '',
                    		  'weibo' => '',
                    		  'ceo' => $cci->ceo, 
                    		  'ceo_id'  => $cci->ceo_id, 
                    		  'company_type' => $cci->company_type,
			                  'industry' => $cci->company_type,
                    		  'country' => $cci->industry,
                    		  'province' => $cci->province,
                    		  'city' => $cci->city,
                    		  'address' => $cci->address,
                    		  'postcode' => $cci->postcode,
                    		  'user_permits' => $cci->user_permits,
			                  'effect_date' => $cci->effect_date, 
                    		  'expiry_date' => $cci->expiry_date,
                    		  'is_active' => $cci->is_active, 
                    		  'status' => $cci->status, 
                    		  'stickness' => $cci->stickness,
                    		  'domain_id' => $cci->domain_id);
                    ReplicationRequest::rpcAddCustCompanyInfo($custCompanyInfo, $res_buhost->bu_site);
                }
            }
            $return = true;
        }catch(Exception $e){
            DB::rollback();
        }           
        DB::commit();

        if($return){ 
            return CheckApi::return_success(array());
        }else{ 
            return CheckApi::return_10000();
        }
 
    }


    //查看朋友信息(待完善)
    public function myFriend(Request $request,Response $response)
    { 

        //判断参数个数是否足够
        $return = CheckApi::check_format($request,array('fri_com_id','fri_uid','token','uid'));
        if($return !== true){ return $return;}

        //fri_com_id必须为数值
        if(!is_numeric($request->get('fri_com_id'))){ return CheckApi::return_46011();}

        //uid必须为数值
        if(!is_numeric($request->get('uid'))){ return CheckApi::return_46011();}

        //fri_uid必须为数值
        if(!is_numeric($request->get('fri_uid'))){ return CheckApi::return_46011();}

        //用户是否存在
        $return = CheckApi::check_userexit($request->get('uid'));
        if($return !== true){ return $return;}

        //公司是否存在
        $return = CheckApi::check_userincompany($request->get('fri_uid'),$request->get('fri_com_id'));
        if($return !== true){ return $return;}

        //token是否真实的/是否已过期
        $return = CheckApi::check_token($request->get('uid'),$request->get('token'));
        if($return !== true){ return $return;}

        //双方是否是朋友关系
        $res_friend = Myfriend::isFriend($request->get('fri_uid'),$request->get('uid'));
        if(empty($res_friend)){ return CheckApi::return_46024();}

        $result = User::infoFriendUser($request->get('fri_uid'));

        //公司名称
        $res_company = Company::company_name($request->get('fri_com_id'));
        if(!empty($res_company) && $result){ 
            $result['fri_com_name'] = $res_company['company_name'];
            $result['fri_uid'] = $request->get('fri_uid');
            return CheckApi::return_success($result);
        }else{ 
            return CheckApi::return_10000();
        }

    }

    //查询朋友列表(待完善)
    public function listMyFriend(Request $request,Response $response)
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


    }


}