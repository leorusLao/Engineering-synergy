<?php

namespace App\Http\Controllers;
use App;
use function array_push;
use Auth;
use i;
use function Psy\debug;
use Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\Company;
use App\Models\CompanyConfig;
use App\Models\WorkCalendarReal;
use App\Models\Department;
use App\Models\UserRoleConfig;
use App\Models\Node;
use App\Models\NodeCompany;
use App\Models\Position;
use App\Models\WorkCalendarBase;
use App\Models\ProjectType;
use App\Models\PartType;
use App\Models\PlanType;
use App\Models\ConfigNumbering;
use App\Models\NodeType;
use Illuminate\Support\Facades\Log;

class InitController extends  Controller {
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
	
	public static function getAndInitProjectCoding() 
	{//获取项目编号
		if(!Session::has('userId')) return ;
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		//check project coding setting
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->first();
		$yy = date('y');
		$mm = date('m');
		if($row) {
			if($yy != $row->current_year) {//entered a new year; renewl projectCoding setting;then get it again
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->
				update(array('current_value' => 1, 'current_year' => $yy));
				$row = CompanyConfig::where(array('company_id' => $company_id, 'projectCoding'))->first();
			} else {//still in the same calendar
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'));
			}
		} else {//do initialization
			CompanyConfig::create(array('cfg_name' => 'projectCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
			'company_id' => $company_id));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->first();
		}
		
		$val = sprintf('%04d',$row->current_value);
		$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
		join('company_type','company_type.type_id','=','company.company_type')->first();
		if($basinfo) {
			$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code 
		} else {
			$append =  '0000';
		}
		$res = array('proj_code' => 'X'.$yy.$mm.$val, 'proj_unicode' => 'X'.$append.$yy.$mm.$val);
		return json_encode($res);
	}
	
	public static function getAndInitProductPlanCoding()
	{//产品开发计划编号
		if(!Session::has('userId')) return ;
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		//check project coding setting
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'productPlanCoding'))->first();
		$yy = date('y');
		$mm = date('m');
		if($row) {
			if($yy != $row->current_year) {//entered a new year; renew productPlanCoding setting;then get it again
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'productPlanCoding'))->
				update(array('current_value' => 1, 'current_year' => $yy));
				$row = CompanyConfig::where(array('company_id' => $company_id, 'productPlanCoding'))->first();
			} else {//still in the same calendar
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'productPlanCoding'))->increment('current_value',1);
			}
		} else {//do initialization
			CompanyConfig::create(array('cfg_name' => 'productPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
					'company_id' => $company_id));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'productPlanCoding'))->first();
		}
	
		$val = sprintf('%04d',$row->current_value);
		$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
		join('company_type','company_type.type_id','=','company.company_type')->first();
		if($basinfo) {
			$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
		} else {
			$append =  '0000';
		}
 	
		$res = array('plan_code' => 'P'.$yy.$mm.$val, 'plan_unicode' => 'P'.$append.$yy.$mm.$val);
		return json_encode($res);
	}
	
	public static function getAndInitMoldPlanCoding()
	{//模具开发计划编号
		if(!Session::has('userId')) return ;
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		//check project coding setting
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'moldPlanCoding'))->first();
		$yy = date('y');
		$mm = date('m');
		if($row) {
			if($yy != $row->current_year) {//entered a new year; renew moldPlanCoding setting;then get it again
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'moldPlanCoding'))->
				update(array('current_value' => 1, 'current_year' => $yy));
				$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'moldPlanCoding'))->first();
			} else {//still in the same calendar
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'moldPlanCoding'))->increment('current_value',1);
			}
		} else {//do initialization
			CompanyConfig::create(array('cfg_name' => 'moldPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
					'company_id' => $company_id));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'moldPlanCoding'))->first();
		}
	
		$val = sprintf('%04d',$row->current_value);
		$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
		join('company_type','company_type.type_id','=','company.company_type')->first();
		if($basinfo) {
			$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
		} else {
			$append =  '0000';
		}
			
	 	$res = array('plan_code' => 'M'.$yy.$mm.$val, 'plan_unicode' => 'M'.$append.$yy.$mm.$val);
	 	return json_encode($res);
	}
	
   public static function getAndInitJigPlanCoding()
   {//夹具开发计划编号
	  if(!Session::has('userId')) return ;
	  $company_id = Session::get('USERINFO')->companyId;
	  $uid = Session::get('USERINFO')->userId;

	  //check jig coding setting
	  $row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'jigPlanCoding'))->first();
	  $yy = date('y');
	  $mm = date('m');
	  if($row) {
		if($yy != $row->current_year) {//entered a new year; renew jigPlanCoding setting;then get it again
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'jigPlanCoding'))->
			update(array('current_value' => 1, 'current_year' => $yy));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'jigPlanCoding'))->first();
		} else {//still in the same calendar
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'jigPlanCoding'))->increment('current_value',1);
		}
	  } else {//do initialization
		CompanyConfig::create(array('cfg_name' => 'jigPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'jigPlanCoding'))->first();
 	  }
	
	  $val = sprintf('%04d',$row->current_value);

	  $basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
	  join('company_type','company_type.type_id','=','company.company_type')->first();

	  if($basinfo) {
		  $append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
	  } else {
		  $append =  '0000';
	  }
	  $res = array('plan_code' => 'J'.$yy.$mm.$val, 'plan_unicode' => 'J'.$append.$yy.$mm.$val);
	  return json_encode($res);
	}

	public static function getAndInitGaugePlanCoding()
	{//检具开发计划编号
	if(!Session::has('userId')) return ;
	$company_id = Session::get('USERINFO')->companyId;
	$uid = Session::get('USERINFO')->userId;
	
	//check project coding setting
	$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'gaugePlanCoding'))->first();
	$yy = date('y');
	$mm = date('m');
	if($row) {
		if($yy != $row->current_year) {//entered a new year; renew moldPlanCoding setting;then get it again
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'gaugePlanCoding'))->
			update(array('current_value' => 1, 'current_year' => $yy));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'gaugePlanCoding'))->first();
		} else {//still in the same calendar
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'gaugePlanCoding'))->increment('current_value',1);
		}
	} else {//do initialization
		CompanyConfig::create(array('cfg_name' => 'gaugePlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'gaugePlanCoding'))->first();
	}
	
	$val = sprintf('%04d',$row->current_value);

	$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
	join('company_type','company_type.type_id','=','company.company_type')->first();
	if($basinfo) {
		$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
	} else {
		$append =  '0000';
	}
	 
	 $res = array('plan_code' => 'G'.$yy.$mm.$val, 'plan_unicode' => 'G'.$append.$yy.$mm.$val);
	 return json_encode($res);
   }
	
	public static function getAndInitAutomationPlanCoding()
	{//自动化线开发计划编号
	if(!Session::has('userId')) return ;
	$company_id = Session::get('USERINFO')->companyId;
	$uid = Session::get('USERINFO')->userId;
	
	//check project coding setting
	$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'automationPlanCoding'))->first();
	$yy = date('y');
	$mm = date('m');
	if($row) {
		if($yy != $row->current_year) {//entered a new year; renew moldPlanCoding setting;then get it again
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'automationPlanCoding'))->
			update(array('current_value' => 1, 'current_year' => $yy));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'automationPlanCoding'))->first();
		} else {//still in the same calendar
			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'automationPlanCoding'))->increment('current_value',1);
		}
	} else {//do initialization
		CompanyConfig::create(array('cfg_name' => 'productPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'automationPlanCoding'))->first();
	}
	
	$val = sprintf('%04d',$row->current_value);
	$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
	join('company_type','company_type.type_id','=','company.company_type')->first();
	if($basinfo) {
		$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
	} else {
		$append =  '0000';
	}
	
	
	  $res = array('plan_code' => 'A'.$yy.$mm.$val, 'plan_unicode' => 'A'.$append.$yy.$mm.$val);
 	  return json_encode($res);  
   }
   
   public static function getAndInitPartCoding()
   {//获取零件编号
   	if(!Session::has('userId')) return ;
   	$company_id = Session::get('USERINFO')->companyId;
   	$uid = Session::get('USERINFO')->userId;
   	
   	//check part coding setting
   	$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'partCoding'))->first();
   	$yy = date('y');
   	$mm = date('m');
   	if($row) {
   		if($yy != $row->current_year) {//entered a new year; renew moldPlanCoding setting;then get it again
   			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'partCoding'))->
   			update(array('current_value' => 1, 'current_year' => $yy));
   			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'partCoding'))->first();
   		} else {//still in the same calendar
   			CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'partCoding'))->increment('current_value',1);
   		}
   	} else {//do initialization
   		CompanyConfig::create(array('cfg_name' => 'partCoding','current_value' => 1,'current_year' => $yy,'length' => 6,
   				'company_id' => $company_id));
   		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'partCoding'))->first();
   	}
   	
   	$val = sprintf('%06d',$row->current_value);
   	 
   	$res = array('part_code' => 'C'.$yy.$mm.$val);
   	return json_encode($res);
   }
   
   public static function isYearCalendarExisted ($year) 
   {
   	   if(!Session::has('userId')) return ;
   	   $company_id = Session::get('USERINFO')->companyId;
   	   $uid = Session::get('USERINFO')->userId;
   	   
   	   $row = WorkCalendarReal::where(array('cal_year' => $year, 'company_id' => $company_id))->first();
   	   if($row) {
   	   	  return true;
   	   }
   	   
   	   return false;
   }

	public static function getAndInitProjectCodingApi($company_id) 
	{//获取项目编号
		
		//check project coding setting
		$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->first();
		$yy = date('y');
		$mm = date('m');
		if($row) {
			if($yy != $row->current_year) {//entered a new year; renewl projectCoding setting;then get it again
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->
				update(array('current_value' => 1, 'current_year' => $yy));
				$row = CompanyConfig::where(array('company_id' => $company_id, 'projectCoding'))->first();
			} else {//still in the same calendar
				CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->increment('current_value',1);
			}
		} else {//do initialization
			CompanyConfig::create(array('cfg_name' => 'projectCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
			'company_id' => $company_id));
			$row = CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => 'projectCoding'))->first();
		}
		
		$val = sprintf('%04d',$row->current_value);
		$basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->
		join('company_type','company_type.type_id','=','company.company_type')->first();
		if($basinfo) {
			$append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code 
		} else {
			$append =  '0000';
		}
	 	
		$res = array('proj_code' => 'X'.$yy.$mm.$val, 'proj_unicode' => 'X'.$append.$yy.$mm.$val);
		return $res;
	}
	
	public static function companyInit($company_id)
	{//创建公司时:初始化项目编号，零件编码，产品开发计划编号，模具开发计划编号,夹具开发计划编号，检具开发计划编号,以及设置公司缺省部门
		$yy = date('y');
		$mm = date('m');
		CompanyConfig::where('company_id', $company_id)->delete();//if exists then delete
		CompanyConfig::create(array('cfg_name' => 'projectCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		CompanyConfig::create(array('cfg_name' => 'partCoding','current_value' => 1,'current_year' => $yy,'length' => 6,
				'company_id' => $company_id));
		CompanyConfig::create(array('cfg_name' => 'productPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		CompanyConfig::create(array('cfg_name' => 'moldPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		CompanyConfig::create(array('cfg_name' => 'jigPlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		CompanyConfig::create(array('cfg_name' => 'gaugePlanCoding','current_value' => 1,'current_year' => $yy,'length' => 4,
				'company_id' => $company_id));
		
		Department::where('company_id', $company_id)->delete();//if exists then delete
		$deps = Department::where('company_id',0)->get();
		
		foreach ($deps as $row) {
			Department::create(array('dep_code' => $row->dep_code, 'name' => $row->name, 'name_en' => $row->name_en, 
					'upper_id' => 0, 'cal_id' => 0, 'comment' => $row->comment, 'manager' => 0, 'company_id' => $company_id,
					'status' => 1
			));
		}
	    //职位编号
		$positions = Position::where('company_id',0)->get();
		foreach ($positions as $row) {
			Position::create(array('position_title' => $row->position_title, 'alias' => $row->alais,
					'position_title_en' => $row->position_title_en,  'company_id' => $company_id
			));
		}
		
		//复制平台节点
		self::copyPlatformNodes($company_id);
		
		//初始化公司日历
		self::initCalendar($company_id, date('Y'));
		
		//初始化公司的项目类型，来自平台
		self::initProjectType($company_id);
		
		//初始化公司的零件类型，来自平台
		self::initPartType($company_id);
		
		//初始化公司的计划类型，来自平台
		self::initPlanType($company_id);


        //初始化公司的编码规则，来自平台
        self::initCompanyConfig($company_id);
		
		//初始化公司的节点类型，来自平台
		self::initNodeType($company_id);
		
		//TODO 根据公司的BU设定的项目类型，零件类型，计划类型，节点类型；初始化公司的项目类型，零件类型，计划类型，节点类型
	}

    //获取参数值自动初始化各种计划编号（拿plan_type中的所有计划类型根据config_num和company_config中的字段来加工）
//    1：所有计划类型的type_id
//    2:拿到所有计划类型的type_id对应的所有编码配置（type_id与config_num(id)联查）
//    3：拿到所有计划类型对应的公司配置（通过字段cfg_name拿到对应的cfg_name,current_value,current_year）(type_id与config_num(id)与company_config(cfg_name)联查)
//    4：加工生成所有计划编号（perfix+cycle(什么循环)+YYYY（年长度，必须）+mm(0/1)+dd(0/1)+serial_length）
    public static function getAndInitAllPlanCoding()
    {   //所有开发计划编号
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;

        //1：所有计划类型的type_id
        $type_ids = PlanType::getPlanTypes($company_id);
        $result_type_ids = [];
        foreach($type_ids as $key => $row ) {
            array_push($result_type_ids,$row['type_id']);
        }

        //2:拿到所有计划类型的type_id对应的所有编码配置（type_id与config_num(id)联查）
        $result_type_config = [];
        foreach ($result_type_ids as $key => $type_id){
        	$config_arr = ConfigNumbering::result_type_config($type_id,$company_id);
        	if(!empty($config_arr[0])){
        		$row_config = array(
	        		"prefix"=>$config_arr[0]['prefix'],
	        		"cycle"=>$config_arr[0]['cycle'],
	        		"YYYY"=>$config_arr[0]['YYYY'],
	        		"mm"=>$config_arr[0]['mm'],
	        		"dd"=>$config_arr[0]['dd'],
	        		"serial_length"=>$config_arr[0]['serial_length']
        	    );
                array_push($result_type_config,$row_config);
        	}
        }

        $result_cfg_name = [];
        foreach ($result_type_ids as $key => $id){
            $row_cfg_arr = ConfigNumbering::cc_cfg_name($id,$company_id);
            if(!empty($row_cfg_arr[0])) array_push($result_cfg_name,$row_cfg_arr[0]["cc_cfg_name"]);
        }

        //company_config中的数据只能通过config_num中的cc_cfg_name对应（config_num中有编辑功能会改变cc_cfg_name字段）
        $result_current_value = [];
        foreach ($result_cfg_name as $key => $cfg_name){
            $row_current_value = CompanyConfig::current_value($cfg_name,$company_id)[0]["current_value"];
            array_push($result_current_value,$row_current_value);
        }

        $result_current_year = [];
        foreach ($result_cfg_name as $key => $cfg_name){
            $row_current_year = CompanyConfig::current_year($cfg_name,$company_id)[0]["current_year"];
            array_push($result_current_year,$row_current_year);        //3：拿到所有计划类型对应的公司配置（通过字段cfg_name拿到对应的current_value,current_year）(type_id与config_num(id)与company_config(cfg_name)联查)

        }

        //4：加工生成所有计划编号（perfix+cycle(什么循环)+YYYY（年长度，必须）+mm(0/1)+dd(0/1)+serial_length）
        //计划类型id  计划类型初始编号

//        return json_encode(array($result_type_ids,$result_type_config,$result_current_value));

        /*if(count($result_type_ids) >= count($result_type_config)&& count($result_type_ids) >= count($result_current_value)){*/

            for($i=0; $i < count($result_type_config);$i++){
                $result_type_config[$i]['current_year'] = $result_current_year[$i];
                $result_type_config[$i]['current_value'] = $result_current_value[$i];
                $result_type_config[$i]['type_id'] = $result_type_ids[$i];
                $result_type_config[$i]['cfg_name'] = $result_cfg_name[$i];
            }

            /*return json_encode($result_type_config);*/

            //所有的计划编号
            $result_palnNum = [];
            $yy = date('y');
            $yyyy = date('Y');
            $mm = date('m');
            $dd = date('d');
            //每条配置加工
            foreach ($result_type_config as $key => $type_config){
                //当前年是不是等于平台存在的年份(新建计划类型的时候和当前年对应的上不？)
                if($yy != $type_config['current_year']) {
                    //按年在循环
                    CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => $type_config['cfg_name']))->
                    update(array('current_value' => 0, 'current_year' => $yy));
                } else {
                    //改为执行成功在更新数据库的current_value值
//                    CompanyConfig::where(array('company_id' => $company_id, 'cfg_name' => $type_config['cfg_name']))->increment('current_value',1);

                    //补0操作
                    $valCurrent = sprintf("%0".$type_config['serial_length']."d",$type_config['current_value']+1);

                    //1：年时（月可要可不要，日可要可不要）2：月时（年必须要，日可要可不要）3：日时（年必须要，月必须要）
                    $lengYear = strlen($type_config['YYYY']);

                    if($lengYear == 2){
                        //加上2位年
                        $val = $type_config['prefix'].$yy;
                        if($type_config['mm']){
                            //加上2位月
                            $val = $type_config['prefix'].$yy.$mm;
                            if($type_config['dd']){
                                //加上2位日
                                $val = $type_config['prefix'].$yy.$mm.$dd.$valCurrent;
                            }else{
                                $val = $type_config['prefix'].$yy.$mm.$valCurrent;
                            }
                        }else{
                            if($type_config['dd']){
                                //有日没有月的话必须加上月
                                $val = $type_config['prefix'].$yy.$mm.$dd.$valCurrent;
                            }else{
                                $val = $type_config['prefix'].$yy.$valCurrent;
                            }
                        }
                    }else{
                        //加上4位年
                        $val = $type_config['prefix'].$yyyy;
                        if($type_config['mm']){
                            //加上2位月
                            $val = $type_config['prefix'].$yyyy.$mm;
                            if($type_config['dd']){
                                //加上2位日
                                $val = $type_config['prefix'].$yyyy.$mm.$dd.$valCurrent;
                            }else{
                                $val = $type_config['prefix'].$yyyy.$mm.$valCurrent;
                            }
                        }else{
                            if($type_config['dd']){
                                //有日没有月的话必须加上月
                                $val = $type_config['prefix'].$yyyy.$mm.$dd.$valCurrent;
                            }else{
                                $val = $type_config['prefix'].$yyyy.$valCurrent;
                            }
                        }
                    }

                    //未在前缀和年之间加入$append
//                    $basinfo = Company::where('company_id',$company_id)->join('company_industry','company_industry.industry_id','=','company.industry')->join('company_type','company_type.type_id','=','company.company_type')->first();
//                    if($basinfo) {
//                        $append = $basinfo->industry_code.$basinfo->type_code;//append insdutry code and company type code
//                    } else {
//                        $append =  '0000';
//                    }
                    $res = array('type_id' => $type_config['type_id'], 'plan_code' => $val, 'plan_unicode' => $val);
                    array_push($result_palnNum,$res);
                }
            }
            Log::debug($result_palnNum);
            return json_encode($result_palnNum);
        /*}else{
            return false;
        }*/
    }



	public static function copyPlatformNodes($company_id)
	{
		$nodes = Node::where(array('company_id' => 0, 'category' => '01'))->orderBy('node_id','asc')->get();
		foreach ($nodes as $row) {
			NodeCompany::create(array('node_id' => $row->node_id, 'node_no' => $row->node_no, 'type_id' => $row->type_id, 
					'name' => $row->name, 'name_en' => $row->name_en, 'level' => $row->level, 'leader' => 0, 
				    'expandable' => $row->expandable, 'company_id' => $company_id));
		}
	}
 
	public static function initCalendar($company_id, $year)
	{
			
		for($month = 1; $month < 13; $month++) {
			$days = WorkCalendarBase::where(array('cal_year' => $year, 'cal_month' => $month))->get();
			$workdays = '';
			foreach($days as $day ) {
				$workdays .= $day->is_weekday.',';
			}
			$workdays = rtrim($workdays,',');
			if($month == 1) {
			    WorkCalendarReal::addCompanyYear($year, $month, $workdays, 1, Lang::get('mowork.default_calendar'),$company_id);
			} else {
			    WorkCalendarReal::updateCompanyYearInitial($year, $month, $workdays, 1, Lang::get('mowork.default_calendar'),$company_id);
			}
		}	
	}
	
	public static function initProjectType($company_id)
	{
		$rows = ProjectType::where(array('company_id' => 0, 'bu_id' => 0))->get();
		foreach ($rows as $row) {
			ProjectType::addProjectType($row->name, $row->name_en, $company_id);
		}
	}
	
	public static function initPartType($company_id)
	{
		$rows = PartType::where(array('company_id' => 0, 'bu_id' => 0))->get();
		foreach ($rows as $row) {
			PartType::addPartType($row->type_code, $row->name, $row->name_en,$company_id);
		}
	}

    //新增公司的零件类型，来自平台
	public static function initPlanType($company_id)
	{
		$rows = PlanType::where(array('company_id' => 0, 'bu_id' => 0))->get();
		foreach ($rows as $row) {
			PlanType::addPlanType($row->type_code, $row->type_name, $row->type_name_en,$row->cn_pix,$row->cn_description,$row->cn_description_en,$row->cc_cfg_name,$company_id);
		}
	}



    //新增公司的编码规则，来自平台
    public static function initCompanyConfig($company_id)
    {
        $rows = ConfigNumbering::where(array('company_id' => 0))->get();
        foreach ($rows as $row) {
            ConfigNumbering::addNumberingSetNew($row->description, $row->description_en, $row->prefix, $row->cc_cfg_name, $row->cycle,$row->cycle_en, $row->yyyy,$row->mm,$row->dd,$row->serial_length, $company_id);
        }
    }
	
	public static function initNodeType($company_id)
	{
		$rows = NodeType::where(array('company_id' => 0, 'bu_id' => 0))->get();
		$mapType = array();
		foreach ($rows as $row) {
			$new_typeId = NodeType::addNodeType($row->type_name, $row->type_name_en, $row->ctrl_by_dep, $row->fore_color,
					$row->back_color, $company_id);
			$mapType[$row->type_id] = $new_typeId;
		}
		 
		foreach ($mapType as $key => $val) {//修改公司节点的节点类型type_id
			NodeCompany::where(array('company_id' => $company_id, 'type_id' => $key))->update(array('type_id' => $val));
		}
	    
		$dep = Department::where('company_id', $company_id)->orderBy('dep_id','desc')->first();
		NodeType::where('company_id', $company_id)->update(array('ctrl_by_dep' => $dep->dep_id));
	}
	
	public static function copyBuNodes($company_id)
	{
	
	} 
	
	public static function userRoleSelfDefineStarter($company_id)
	{
		$existed = UserRoleConfig::where('company_id',$company_id)->first();
		if(!$existed) {
			UserRoleConfig::create(array('start_role_id' => 101, 'current_role_id' => 101, 'company_id' => $company_id));
		}
	}

}