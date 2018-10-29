<?php

namespace App\Http\Controllers;
use App;
 
use Session;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\Models\UserCompany;
use App\Models\ProjectType;
use App\Models\PartType;
use App\Models\PlanType;
use App\Models\Plan;
use App\Models\NodeType;
use App\Models\Node;
use App\Models\NodeCompany;
use App\Models\NodeSetting;
use App\Models\Department;
use App\Models\ConfigFolder;
use App\Models\WorkCalendarBase;
use App\Models\WorkCalendarReal;
use App\Models\WorkCalendar;
use App\Models\ConfigNumbering;
use App\Models\CompanyConfig;
use function var_dump;


class CfgProjectController extends Controller {
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
        session_cache_limiter(false); //let page no expiration after post data
    }

    public function projectType(Request $request)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if($request->has('submit')) {
             
            if(ProjectType::isExistedProjectType($request->get('name'), $company_id)) {
                return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
            }
             
           try {
                ProjectType::addProjectType ( $request->get ( 'name' ),  $request->get ( 'name_en' ), $company_id );
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
           } catch (\Exception $e) {
              return Redirect::back()->with('result', Lang::get('mowork.db_err'));
           }
        }
        
        $rows = ProjectType::where('company_id', $company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
        $planTypes = PlanType::getPlanTypes($company_id);
        
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.project_type');
        return view('backend.project-type',array('cookieTrail' => $cookieTrail, 'planTypes' => $planTypes, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }
    
    public function projectTypeEdit(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
     
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
        
        if($request->has('submit')) {
             
            $include_plantype = '';
            if($request->has('include_plantype')) {
                $include_plantype = implode(',',$request->get ( 'include_plantype' ));
            }
            
            if(ProjectType::updateProjectType ( $type_id, $request->get ( 'name' ),$request->get ( 'name_en' ),
                $include_plantype, $company_id )) {
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            }  
            
            return Redirect::back()->with('result', Lang::get('mowork.db_err'));
             
        }
    
        $row = ProjectType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
        $planTypes = PlanType::getPlanTypes($company_id);
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.project_type').' &raquo; '.Lang::get('mowork.edit');
        return view('backend.project-type-edit',array('cookieTrail' => $cookieTrail, 'planTypes' => $planTypes, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    
    }
    
    public function projectTypeDelete(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
         
        if(ProjectType::deleteProjectType($type_id, $company_id)) {
            return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
        }
        
        return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
    }
    
    public function partType(Request $request)
    { 

        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if($request->has('submit')) {

            if(PartType::isExistedPartType($request->get('type_code'), $company_id)) {
                return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
            }

            try {
                PartType::addPartType ( $request->get ( 'type_code' ), $request->get ( 'name' ), 
                        $request->get ( 'name_en' ), $company_id );
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            } catch (\Exception $e) {
                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
            }
        }
        
        $rows = PartType::where('company_id', $company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
        
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.part_type');
        return view('backend.part-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }
    
    public function partTypeEdit(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
         
        if($request->has('submit')) {
            
            if(PartType::updatePartType ( $type_id, $request->get ( 'type_code' ), $request->get ( 'name' ), 
                    $request->get ( 'name_en' ), $company_id )) {
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            }
                
            return Redirect::back()->with('result', Lang::get('mowork.db_err'));
    
        }
    
        $row = PartType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
    
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.part_type').' &raquo; '.Lang::get('mowork.edit');
        return view('backend.part-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    
    }
    
    public function partTypeDelete(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
            
        if(PartType::deletePartType($type_id, $company_id)) {
            return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
        }
    
        return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
    }
              
    public function checkExistedPartType(Request $request)
    {
     
        $company_id = Session::get('USERINFO')->companyId;
        $partCode = $request->get('part_type_code');
        $partName = $request->get('part_type_name');
        
        if($request->has('type_id')) {//check for updating
            $existedBoth = PartType::where(array('type_code' => $partCode, 'name' => $partName, 'company_id' => $company_id))
                ->where('type_id','!=',$request->get('type_id'))->first();
        } else {//check for adding new
            $existedBoth = PartType::where(array('type_code' => $partCode, 'name' => $partName, 'company_id' => $company_id))->first();
        }
        
        if($existedBoth) {
            $res = array('0' => 'existedBoth');
            $json = json_encode($res);
            return $json;
        }
        

        if($request->has('type_id')) {//check for updating
            $existedCode = PartType::where(array('type_code' => $partCode,  'company_id' => $company_id))
            ->where('type_id','!=',$request->get('type_id'))->first();
        } else {//check for adding new
            $existedCode = PartType::where(array('type_code' => $partCode, 'company_id' => $company_id))->first();
        }
        
        if($existedCode) {
            $res = array('0' => 'existedCode');
            $json = json_encode($res);
            return $json;
        }
        
        if($request->has('type_id')) {//check for updating
            $existedName = PartType::where(array('name' => $partName, 'company_id' => $company_id))
            ->where('type_id','!=',$request->get('type_id'))->first();
        } else {
            $existedName = PartType::where(array('name' => $partName, 'company_id' => $company_id))->first();
        }
        
        if($existedName) {//check for adding new
            $res = array('0' => 'existedName');
            $json = json_encode($res);
            return $json;
        }
        
        $res = array('0' => '');
        
        $json = json_encode($res);
        return $json;
    }
    
    public function planType(Request $request)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if($request->has('submit')) {
            if(PlanType::isExistedPlanType($request->get('type_code'), $company_id)) {
                return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
            }
            if(PlanType::isExistedPlanTypeNameEn($request->get('type_name_en'), $company_id)) {
                return Redirect::back()->with('result', Lang::get('对应英文名重复'));
            }
            try {
                $result = PlanType::addPlanType ( $request->get ( 'type_code' ), $request->get ( 'type_name' ), $request->get ( 'type_name_en' ), $request->get ( 'cn_pix' ), $request->get ( 'cn_description' ), $request->get ( 'cn_description_en' ), $request->get ( 'cc_cfg_name' ),$company_id );

                //新增一条计划类型默认新增此计划对应的编码规则
                if(!empty($result)){
                    $result = ConfigNumbering::addNumberingSetNew($result['type_id'], $result['type_code'], $request->get('cn_description'), $request->get('cn_description_en'), $request->get('cn_pix'), $request->get('cc_cfg_name'), '年', 'Year', 'YY', '1', '1', '6', $company_id);
                    if(!empty($result)){
                        //需要加入条件 1：判断此公司下是否有此cfg_name 2:没有则加入此计划对应编号（最终各计划）
                        if(CompanyConfig::isExistedCfg_name($request->get ( 'cc_cfg_name' ),$company_id)){
                            return Redirect::back ()->with ( 'result', '已有此计划编号名称，请重新输入计划编号名称' );
                        }else{
                            CompanyConfig::addConfigSet($request->get ( 'cc_cfg_name' ), 0, date('y'), strlen (  'YY'), '', $company_id);
                        }
                    }
                    //下面为失败
                    return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                }else{
                    return Redirect::back ()->with ( 'result', Lang::get ( '操作失败！' ) );
                }
            } catch (\Exception $e) {
                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
            }
        }
        
        $rows = PlanType::where('company_id', $company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
        
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.plan_type');
        return view('backend.plan-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }


    public function planTypeDelete(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;

        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/plan-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }

        //查询plan_type表中的cc_cfg_name字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来删除company_config表中对应数据）
        if($cc_cfg_name = PlanType::cc_cfg_name($type_id,$company_id)[0]["cc_cfg_name"]){
            // 计划删除判断根据plan表（条件筛选，plan_type，company_id:该公司是否有要删除的计划类型）
            if(plan::isExistPlanType($type_id, $company_id)){
                return Redirect::back()->with('result', Lang::get('有此计划类型的计划，请删除完此计划类型的计划再删除！'));
            }else{
                //计划类型删除成功与否,删除成功同时删除此计划类型对应的编码配置(注意数据库数据是否同步，未做事务机制)
                if( planType::deletePlanType($type_id, $company_id)) {
                    if(ConfigNumbering::deleteNumberingSet($type_id, $company_id )){
                        if(CompanyConfig::deleteConfigSet($cc_cfg_name, $company_id)){
                            return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                        }else{
                            return Redirect::back()->with('result', '删除计划对应的初始编号失败');
                        }
                    }else{
                        return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
                    }
                }else{
                    return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
                }
            }
        }else{
            return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
        }
        return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
    }
    
    public function planTypeEdit(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/plan-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
        if($request->has('submit')) {
            //查询plan_type表中的cc_cfg_name字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来更改company_config表）
            if($cc_cfg_name = PlanType::cc_cfg_name($type_id,$company_id)[0]["cc_cfg_name"]){
                if(PlanType::updateplanType ( $type_id, $request->get ( 'type_code' ), $request->get ( 'type_name' ),  $request->get ( 'type_name_en' ), $request->get ( 'cn_pix' ), $request->get ( 'cn_description' ), $request->get ( 'cn_description_en' ), $request->get ( 'cc_cfg_name' ),$company_id )) {
                    //修改一条计划类型默认修改此计划对应的编码规则
                    //拿到为更改之前的cc_cfg_name(config_num表根据要更改的id字段拿)
                    if(ConfigNumbering::updateNumberingSet($type_id, $request->get('cn_pix'), $request->get('cn_description'), $request->get('cn_description_en'), $request->get('cc_cfg_name'),'年', 'Year', 'YY', '1', '0','6')){
                        //需要加入条件 1：判断此公司下是否有此cfg_name 2:有则更改此计划对应编号（最终各计划）
                        if(CompanyConfig::isExistedCfg_name($cc_cfg_name,$company_id)){
                            CompanyConfig::updateConfigSet($cc_cfg_name, $request->get('cc_cfg_name'),0, date('y'), strlen (  'YY'), '', $company_id);
                            return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                        }else{
                            return Redirect::back()->with('result', Lang::get('mowork.db_err'));
                        }
                        return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                    }else{
                        return Redirect::back ()->with ( 'result', Lang::get ( '操作失败！' ) );
                    }
                    //跳转到计划类型列表页面
    //                return Redirect::to('');
                }else{
                    return Redirect::back ()->with ( 'result', Lang::get ( '操作失败！' ) );
                }
            }else{
                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
            }

            return Redirect::back()->with('result', Lang::get('mowork.db_err'));
    
        }
    
        $row = PlanType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
    
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.plan_type').' &raquo; '.Lang::get('mowork.edit');
        return view('backend.plan-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    
    }
    
    public function checkExistedPlanType(Request $request)
    {
         
        $company_id = Session::get('USERINFO')->companyId;
        $typeCode = $request->get('plan_type_code');
        $typeName = $request->get('plan_type_name');
         
        if($request->has('type_id')) {//check for updating
            $existedBoth = PlanType::where(array('type_code' => $typeCode, 'type_name' => $typeName, 'company_id' => $company_id))
            ->where('type_id','!=',$request->get('type_id'))->first();
        } else {//check for adding new
            $existedBoth = PlanType::where(array('type_code' => $typeCode, 'type_name' => $typeName, 'company_id' => $company_id))->first();
        }
                
        if($existedBoth) {
            $res = array('0' => 'existedBoth');
            $json = json_encode($res);
            return $json;
        }
     
        if($request->has('type_id')) {//check for updating
            $existedCode = PlanType::where(array('type_code' => $typeCode,  'company_id' => $company_id))
            ->where('type_id','!=',$request->get('type_id'))->first();
        } else {//check for adding new
            $existedCode = PlanType::where(array('type_code' => $typeCode, 'company_id' => $company_id))->first();
        }
    
        if($existedCode) {
            $res = array('0' => 'existedCode');
            $json = json_encode($res);
            return $json;
        }
         
        if($request->has('type_id')) {//check for updating
            $existedName = PlanType::where(array('type_name' => $typeName, 'company_id' => $company_id))
            ->where('type_id','!=',$request->get('type_id'))->first();
        } else {
            $existedName = PlanType::where(array('type_name' => $typeName, 'company_id' => $company_id))->first();
        }
        
        if($existedName) {//check for adding new
            $res = array('0' => 'existedName');
            $json = json_encode($res);
            return $json;
        }
         
        $res = array('0' => '');
        $json = json_encode($res);
        return $json;
    }
    
    public function nodeType(Request $request)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if($request->has('submit')) {
            
            if(NodeType::isExistedNodeType($request->get('type_name'), $company_id)) {
                return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
            }

            if(!$request->has ( 'deps' ) || empty($request->get('deps'))) {
                return Redirect::back()->with('result', Lang::get('mowork.department_required'));
            }

            try {
             
                NodeType::addNodeType ( $request->get ( 'type_name' ), $request->get ( 'type_name_en' ), implode(',', $request->get ( 'deps' )), $request->get ( 'forecolor' ), $request->get ( 'backcolor' ), $company_id );
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
            }
        }
        
        $rows = NodeType::where('company_id',$company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
        $departments = Department::where('company_id',$company_id)->get();
        
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_type');
        return view('backend.node-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }
    
    public function nodeTypeEdit(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
        
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/node-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
        
        if($request->has('submit')) {
             
                $deps = $request->get ( 'deps' );
                if(!empty($deps)) {
                    $deps = implode(',', $deps);
                } else {
                    return Redirect::back()->with('result', Lang::get('mowork.department_required'));
                }
                
                NodeType::updateNodeType ( $type_id, $request->get ( 'type_name' ), $request->get ( 'type_name_en' ),
                        $deps, $request->get ( 'forecolor' ), $request->get ( 'backcolor' ), $company_id );
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
             
        }
    
        $row =NodeType::where(array('type_id' => $type_id) )->first();
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_type');
        $departments = Department::where('company_id',$company_id)->get();
        
        return view('backend.node-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    
    }
    
    public function nodeTypeDelete(Request $request, $token, $type_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
    
        $compToken = hash('sha256',$this->salt.$type_id.$company_id);
    
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/node-type')->with('result', Lang::get('mowork.operation_disallowed'));
        }
            
        if(NodeType::deleteNodeType($type_id, $company_id)) {
            return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
        }
    
        return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
    }
    
    public function nodeList(Request $request)
    {    //dd($request);
        //公司节点查看添加
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;

        if($request->has('submit')) {
           //添加节点基本信息：（一个节点可用由基本信息+定制信息组成）
            $node_id = $request->has('node_id') ? $request->get('node_id') : 0;
            // 删除
            if(!$request->has('node_no'))
            {
//                dd($node_id);
                $nodeCompany =  NodeCompany::where('node_id', $node_id)->first();
                $nodeCompany->delete();
                if($nodeCompany->trashed()){
                    return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                }else{
                    return Redirect::back()->with('result', Lang::get('mowork.db_err'));
                }
            }

            if(NodeCompany::isExistedNode($request->get('node_no'), $company_id, $node_id)) {
                return Redirect::back()->with('result', Lang::get('mowork.nodecode_existed'));
            }

            try {
                // 编辑
                if($request->has('node_id')) {
                    NodeCompany::where('node_id', $node_id)->update([
                        'node_no' => $request->get('node_no'),
                        'type_id' => $request->get('type_id'),
                        'name' => $request->get('name'),
                        'name_en' => $request->get('name_en'),
                        'is_push' => $request->get('is_push'),
                        'key_node' => $request->get('key_node'),
                        'expandable' => $request->get('expandable'),
                        'trigger_event' => $request->get('trigger_event'),
                    ]);
                }else {
                    // 新增
                    NodeCompany::addNode( $request->get ( 'node_no' ), $request->get ( 'type_id' ), $request->get ( 'name' ), $request->get ( 'name_en' ), $company_id , $request->get('is_push'), $request->get('key_node'), $request->get('expandable'), $request->get('trigger_event') );
                }

                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            } catch (\Exception $e) {
                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
            }
        } else if($request->has('type_filter')) {
            $type_filter = $request->get('type_filter');
            if($type_filter > 0) {//按节点类型过滤
                $rows = NodeCompany::whereRaw('(company_id = 0 OR company_id =' . $company_id .')')
                ->where('type_id', $type_filter)->orderBy('node_no','ASC')->
                select('node_id', 'node_no', 'type_id', 'name', 'name_en', 'expandable', 'is_push', 'key_node', 'is_active', 'trigger_event', 'created_at as initialized')->paginate(PAGEROWS);

            }
        }

        if(!isset($rows)) {
            $rows = NodeCompany::where('company_id', $company_id )->orderBy('node_no','ASC')
              ->paginate(PAGEROWS);
        } 
        
        $nodeTypeList = NodeType::getNodeTypeList($company_id);

        $sendType = config('app.sendType');
        $trigger_event = $sendType;
        foreach($sendType as &$v)
        {
            if(!empty($v)) {
                foreach($v as $kk => $vv)
                {
                    $v[$kk] = Lang::get('mowork.'.config('app.SEND_TYPE')[$vv]);
                }
            }
            $v = implode(',', $v);
        }

        foreach($trigger_event as &$v)
        {
            $v = implode('', $v);
        }



        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_list');
        return view('backend.node-list',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt . $company_id, 'rows' => $rows, 
                'nodeTypeList' => $nodeTypeList, 'sendType' => $sendType, 'trigger_event' => $trigger_event,'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    
    }
    
    public  function customizeNode(Request $request, $token, $node_id) 
    {
        //customize platform node for company 
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        $compToken = hash('sha256',$this->salt.$company_id . $node_id);
        
        if($compToken != $token) {
            return Redirect::to('/dashboard/project-config/node-list')->with('result',Lang::get('mowork.operation_disallowed'));
        }
        
        $result = '';
        if($request->has('submit')) {
            
            $leader = $request->get('leader');
            $message_via = implode(',',$request->get('message_via'));
            $task_text = $request->get('task_text');
            $task_people = implode(',',$request->get('task_people'));
            $done_text = $request->get('done_text');
            $done_people = $request->has('done_people')? implode(',',$request->get('done_people')):'';
            $expandable = 0;
            if($request->has('expandable')) {
                $expandable = 1;
            }
            
            try {//拷贝标准node(主信息) 到公司nodeCompany,并加附加信息
                $node = Node::where('node_id',$node_id)->first();
                NodeCompany::customizeNode($node_id, $node->node_no,$node->type_id,$node->name, $leader,
                        $message_via, $task_text, $task_people, $done_text, $done_people, $expandable, $company_id );
                $result = Lang::get('mowork.operation_success');
            } catch (\Exception $e) {
                $result =  Lang::get('mowork.operation_failure');
            }
        }
        
       
        $row = NodeCompany::where(array('node_id' => $node_id, 'company_id' => $company_id))->first();
        //#################################################
        //first time copy all platform node to nodeCompany if noexistes in nodeCompany
        $sysNode = Node::where('node_id',$node_id)->first();
        if(!$row) {
            NodeCompany::create(array('node_id' => $sysNode->node_id, 'node_no' => $sysNode->node_no, 'type_id' => $sysNode->type_id,
                'name'=> $sysNode->name, 'name_en' => empty($sysNode->name_en)? '': $sysNode->name_en,
                 'trigger_event' => 'email,site_message', 'task_text' => '', 'company_id' => $company_id));
            $row = NodeCompany::where(array('node_id' => $node_id, 'company_id' => $company_id))->first();
        }
        //#################################################
        $basinfo = Node::where(array('node_id' => $node_id))->first();
        $employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
        select('user_company.*', 'user.fullname')->get();
        
          
        return view('backend.customize-node',array('result' => $result, 'salt' => $this->salt, 'row' => $row, 'basinfo' => $basinfo, 
                    'node_id' => $node_id, 'token' => $token, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }
    
    public function batchCustomizeNode(Request $request) 
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if($request->has('submit')) {
             
            //lookup all node in table Node
            $rows = Node::whereRaw('company_id = 0 OR company_id = '.$company_id)->get();
            
            try {
                foreach($rows as $row) {
                    $node_id = $row->node_id;
                    $existed = NodeCompany::where(array('company_id' => $company_id, 'node_id' => $node_id))->first();
                    if(!$existed) {//otherwise do nothing
                        $trigger_event = implode(',', $request->get('message_via'));
                        $task_people = implode(',', $request->get('task_people'));
                        $done_poeple = implode(',', $request->get('done_people'));
                        $expandable = 0;
                        if($request->has('expandable')) {
                            $expandable = 1;
                        }
                        NodeCompany::create(array('node_id' => $row->node_id, 'node_no' => $row->node_no, 'type_id' => $row->type_id,
                            'name'=> $row->name, 'name_en' => empty($row->name_en)? '': $row->name_en,
                            'trigger_event' => $trigger_event, 'task_text' => $request->get('task_text'),
                            'task_people' => $task_people, 'done_text' => $request->get('done_text'),
                            'done_people' => $done_poeple, 'expandable' => $expandable, 'company_id' => $company_id));
                    }
                }
            } catch (\Exception $e) {
                return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
            }
            return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
        }
        
        $employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
        select('user_company.*', 'user.fullname')->get();
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration')
          .' &raquo; '.Lang::get('mowork.batch_customize');
        
        return view('backend.batch-customize-node',array('cookieTrail' => $cookieTrail, 'employees' => $employees,
                    'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    }
    
    public function nodeEdit(Request $request, $token, $node_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        $compToken = hash('sha256',$this->salt.$company_id . $node_id);
        
        if($compToken != $token) {
            return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
        }
        
        if($request->has('submit')) {
            try{
                Node::updateNode($node_id, $request->get('node_code'), $request->get('type_id'), $request->get('node_name'),
                    $request->get('node_en'), $company_id);
                return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
            } catch (\Exception $e) {
                return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
            }
        }
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').
        ' &raquo; '.Lang::get('mowork.node_edit');
        $row = Node::where(array('node_id' => $node_id, 'company_id' => $company_id))->first();
        $nodetypes = NodeType::getNodeTypeList($company_id);
        
        return view('backend.node-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 
                'nodetypes' => $nodetypes, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
    }
    
    public function nodeDelete(Request $request, $token, $node_id)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
         
        $compToken = hash('sha256',$this->salt.$company_id . $node_id);
         
        if($compToken != $token) {
            return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
        }
        
        try {
            Node::where(array('node_id' => $node_id, 'company_id' => $company_id))->delete();
            return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
        } catch (\Exception $e) {
            return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
        }
    }
    
    public function nodeSetting(Request $request)
    {
        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        
        if(NodeSetting::isExistedNodeSetting($company_id)) {
            $row = NodeSetting::where('company_id', $company_id)->first();
        } else {
            $row = NodeSetting::where('company_id', 0)->select('completion_date', 'percent_done', 'cover_children', 'parent_auto', 
                    'task_advise_header', 'task_advise_pmanager', 'task_advise_pmember', 'done_advise_supervisor', 
                    'done_advise_header', 'done_advise_pmanager', 'done_advise_pmember', 'company_id')->first(); //platform default
        }
        
         
        if($request->has('submit')) {
            $completion_date = 0;
            $percent_done = 0;
            $cover_children = 0;
            $parent_auto = 0;
            $task_advise_header = 0;
            $task_advise_pmanager = 0;
            $task_advise_pmember = 0;
            $done_advise_supervisor = 0;
            $done_advise_header = 0;
            $done_advise_pmanager = 0;
            $done_advise_pmember = 0;
            
            if($request->has('completion_date')) {
                $completion_date = 1;
            }
            
            if($request->has('percent_done')) {
                $percent_done = 1;
            }
            
            if($request->has('cover_children')) {
                $cover_children = 1;
            }
            
            if($request->has('parent_auto')) {
                $parent_auto = 1;
            }
            
            if($request->has('task_advise_header')) {
                $task_advise_header = 1;
            }
            
            if($request->has('task_advise_pmanager')) {
                $task_advise_pmanager = 1;
            }
            
            if($request->has('task_advise_pmember')) {
                $task_advise_pmember = 1;
            }
            
            if($request->has('done_advise_supervisor')) {
                $done_advise_supervisor = 1;
            }
            
            if($request->has('done_advise_header')) {
                $done_advise_header = 1;
            }
            
            if($request->has('done_advise_pmanager')) {
                $done_advise_pmanager = 1;
            }
            
            if($request->has('done_advise_pmember')) {
                $done_advise_pmember = 1;
            }
            
            if(NodeSetting::isExistedNodeSetting($company_id)) {
                //update 
                try {
                    NodeSetting::updateSetting($completion_date, $percent_done, $cover_children, $parent_auto, $task_advise_header, $task_advise_pmanager,
                        $task_advise_pmember, $done_advise_supervisor, $done_advise_header, $done_advise_pmanager, $done_advise_pmember, $company_id);
                    return Redirect::back()->with('result', Lang::get ( 'mowork.operation_success' ) );
                } catch ( \Exception $e ) {
                    return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
                }
            }
                
            try { // add
                NodeSetting::addSetting ( $completion_date, $percent_done, $cover_children, $parent_auto, $task_advise_header, $task_advise_pmanager, $task_advise_pmember, $done_advise_supervisor, $done_advise_header, $done_advise_pmanager, $done_advise_pmember, $company_id );
                
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
            } catch ( \Exception $e ) {
                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
            }
        }
        
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_setting');
        return view('backend.node-setting',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt . $company_id, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
        
    }

    //部门日历
    public function departmentCalendar(Request $request)
    {
        if(!Session::has('userId')) return Redirect::to('/');

        $company_id = Session::get('USERINFO')->companyId;
         
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.department_calendar');
        if($request->has('submit')) {
             
            try{
               Department::where(array('dep_id' => $request->get('dep_id'), 'company_id' => $company_id))->update(array('cal_id' => $request->get('cal_id')));
               return json_encode(array('1' => Lang::get('mowork.operation_success')));
            } catch (\Exception $e) {
               return json_encode(array('1' => Lang::get('mowork.operation_failure')));
            }
        }
        $rows = Department::where('company_id',$company_id)->paginate(PAGEROWS);
        $cals = WorkCalendar::whereRaw('company_id = 0 OR company_id = '. $company_id)->get();
        return view('backend.department-calendar-select',
                array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'cals' => $cals, 'pageTitle' => Lang::get('mowork.dashboard'), 'locale' => $this->locale));
    }
    
}