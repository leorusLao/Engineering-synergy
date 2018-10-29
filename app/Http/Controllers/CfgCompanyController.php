<?php

namespace App\Http\Controllers;
use App;
use DB;
use Excel;
 
use Session;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\UserCompany;
use App\Models\Sysconfig;
use App\Models\Approver;
use App\Models\Position;
 
class CfgCompanyController extends Controller {
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
    

	public function departmentSetup(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 
			
		if($company_id < 1) {
			return Redirect::to('/dashboard/company-profile')->with('result',Lang::get('mowork.company_first'));
		}
			
		if($request->has('submit')) {
			 	
			$validator = Validator::make($request->all(), [
					'dep_code' => 'required',
					'dep_name' => 'required',
			]);
			
			if ($validator->fails()) {
				 
				return Redirect::back()->withErrors($validator);
			}
			
			if(Department::isExistedDepCode($company_id, $request->dep_code)) {
				return Redirect::back()->with('result', Lang::get('mowork.depcode_existed'));
			}
		 
			try {
				Department::addDepartment($request->get('dep_code'), $request->get('dep_name'), $request->get('upper'), $request->get('manager'), $company_id);
				
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		
		$rows = Department::where('company_id',$company_id)->paginate(PAGEROWS);
		$departmentList = AjaxController::departmentList($company_id);
		$employeeList = AjaxController::employeeList($company_id);
		$salt = $company_id.$this->salt.$uid;
		 
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' =>1))
		->select('user.uid','user.fullname')->get();
	   
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.department');
		return view('backend.department',array('salt' => $salt, 'cookieTrail' => $cookieTrail,'rows' => $rows,
				    'departmentList' => $departmentList, 'employees' => $employees,
				    'employeeList' => $employeeList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function departmentEdit(Request $request, $token, $id) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt.$id);
		
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/department-setup')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
			try {
				Department::updateDepartment($request->get('dep_code'), $request->get('dep_name'), $request->get('upper'), $request->get('manager'), $id, $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));		
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		
	 	$row = Department::where('dep_id',$id)->where('company_id',$company_id)->first();//double guarantee with company_id
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' =>1))
		   ->select('user.uid','user.fullname')->get();
	 	
		$departmentList = AjaxController::departmentList($company_id);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.department_edit');
		return view('backend.department-edit',array('token' => $token, 'departmentList' => $departmentList, 
				'cookieTrail' => $cookieTrail,'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function departmentDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt.$id);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/department-setup')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
			Department::deleteDepartment ($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
		
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function checkExistedDepartment(Request $request)
	{
		$company_id = Session::get('USERINFO')->companyId;
		$depCode = $request->get('dep_code');
		$depName = $request->get('dep_name');
		
		$existedBoth = Department::where(array('dep_code' => $depCode, 'name' => $depName, 'company_id' => $company_id))->first();
	  	
		if($existedBoth) {
			$res = array('0' => 'existedBoth');
			$json = json_encode($res);
			return $json;
		}  
		
		$existedCode = Department::where(array('dep_code' => $depCode, 'company_id' => $company_id))->first();
		if($existedCode) {
			$res = array('0' => 'existedCode');
			$json = json_encode($res);
			return $json;
		}
		
		$existedName = Department::where(array('name' => $depName, 'company_id' => $company_id))->first();
		if($existedName) {
			$res = array('0' => 'existedName');
			$json = json_encode($res);
			return $json;
		}
		
		$res = array('0' => '');
		
		$json = json_encode($res);
		return $json;
		
	}
	                 
	public function employeeList(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
			
			
		if($company_id < 1) {
			return Redirect::to('/dashboard/company-profile')->with('result',Lang::get('mowork.company_first'));
		}
			
		if($request->has('submit')) {
				
			$validator = Validator::make($request->all(), [
					'emp_code' => 'required',
					'emp_name' => 'required',
					'email' => 'required | email',
			]);
				
			if ($validator->fails()) {
			 
				return Redirect::back()
				->withErrors($validator);
			}
		   	
			$position_id = 0;
			if($request->has('position_id')) {
				$position_id = $request->get('position_id');
			}
			
			$position = Position::where('position_id', $position_id)->first();
			$position_title = '';
			
			if($position) {
				$position_title = $position->position_title;
			}
	 			
			DB::beginTransaction();
			try {
				//3.1复制用户信息到远程主站点
				$userArray = ['email' => $request->get('email'),
						'fullname' =>  $request->get('emp_name'),
						'emp_code' => $request->get('emp_code'),
						'password' => $request->get('password'),
						'dep_id' => $request->get('department') ? $request->get('department'): 0,
						'position_id' => $position_id, 
						'position_title' => $position_title ,
						'emp_start' => $request->get('emp_start'),
						'mobile' => $request->get('phone')?$request->get('phone'): NULL,
						'company_id' => $company_id
				]; 
				 
				 $res = ReplicationRequest::rpcAddUserToMaster($userArray);
				 $res = json_decode($res); 
				 if($res->result == '0000') {
				   //3.2 远程主站添加后，本地添加; 
				    
				      UserCompany::create(array('uid' => $res->uid, 'company_id' => $company_id, 'dep_id' => $request->get('department') ? $request->get('department'): 0,
				   		'emp_code' => $request->get('emp_code'), 'position_id' => $position_id, 'position_title' => $position_title, 
				   		'emp_start' => $request->get('emp_start') ? $request->get('emp_start'):'1900-12-31'
				   	  ));
				      
				      if(!User::isExistedEmail($request->get('email'))) {//如果用户email在本站不存在则创建
				      	    
				      		User::create(array('id' => $res->id, 'uid' => $res->uid,  'email' => $request->get('email'), 
				 			'fullname' => $request->get('emp_name'), 'username' => '', 'password' =>  Hash::make($request->get('password')), 
				 			'mobile' => $request->get('phone')?$request->get('phone'): NULL));
				      }
				   
				 } else {//留下robllback远程主站未作 
				 	DB::rollback();
				 	return Redirect::back()->with('result', Lang::get('mowork.synchronize_failure'));
				 }
			} catch (\Exception $e) {
				 DB::rollback();
				 return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			DB::commit();
	 		return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	  
		$rows = UserCompany::getEmployees($company_id, PAGEROWS);
	 
		$salt = $company_id.$this->salt.$uid;
		$token = hash('sha256',$salt);
		$departmentList = AjaxController::departmentList($company_id);
		$positionList = AjaxController::positionList($company_id);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.employee_management')
		.' &raquo; '.Lang::get('mowork.employee_list');
		return view('backend.employee',array('token' => $token, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'departmentList' => $departmentList, 
				'positionList' => $positionList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function employeeGroupAdd(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	 
		if($request->has('submit')) {//ajax call
			 
			//check if employee list file upload
			if(Session::has('EMPLOYEELIST')) {
				
				$persons = 0;
				$fullpath = storage_path().'/tmp/'.Session::get('EMPLOYEELIST');
				
				$data = Excel::load($fullpath, function($reader) {
					 
				})->get();
				 
		    
				foreach ( $data as $key => $values ) {
					
					foreach ( $values as $row ) {
						$row = ( object ) $row;
						
						if(! strpos($row->email,'@') ) continue; //ignore line without a validated email address
						
						$rows [] = array (
								'emp_code' => $row->emp_code,
								'fullname' => $row->fullname,
								'password' => $request->get('password'),
								'department' => $row->department,
								'email' => $row->email,
								'mobile' => $row->mobile,
								'gendar' => $row->gender,
								'birthdate' => $row->birthdate,
								'start_date' => $row->start_date,
								'province' => $row->province,
								'city' => $row->city,
								'address' => $row->address 
						);
						
						$persnon = $this->batchAddOneEmployee($row->emp_code, 0, $row->department, $company_id, $row->start_date, $row->fullname, $row->email, $request->get('password'),
								$row->mobile, $row->gendar,	$row->birhdate, $row->province, $row->city, $row->address, $row->postcode);
						$persons += $persnon;
					}
				}
			 
				return response()->json(array('persons' => $persons));
		 	}
			
		}
		
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;'.Lang::get('mowork.employee_management').'>&raquo;'.Lang::get('mowork.add_batch');
		 
		$rows = UserCompany::getEmployees($company_id, PAGEROWS);
		$departmentList = AjaxController::departmentList($company_id);
		
		
	 
		return view('backend.group-add',array('cookieTrail' => $cookieTrail,'rows' => $rows, 'departmentList' => $departmentList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function batchAddOneEmployee($emp_code, $dep_id = 0, $dep_name, $company_id, $emp_start, $fullname, $email, $password, $mobile, $gendar, $birthdate, $province, $city, $address, $postcode) {
		if(!Session::has('userId')) return Redirect::to('/');
		 
		if(UserCompany::isExistedEmpCode($emp_code, $company_id)) {
			return 0;
		}
			
		if(User::isExistedEmail($email)) {
			return 0;
		}
			
		DB::beginTransaction();
		try {
			$current_uid = Sysconfig::getCurrentUid();
			UserCompany::addEmployee($current_uid, $emp_code, $dep_id ? $dep_id: 0, $dep_name? $dep_name : '', 
					$emp_start ? $emp_start: '1900-12-31', '', $company_id);
			User::addUserByEmail($current_uid, $fullname, $email, $password, $mobile, $gendar, $birthdate, $province, $city, $address,  $postcode);
			Sysconfig::UidIncrement();
		} catch (\Exception $e) {
			DB::rollback();
			return 0;
		}
		DB::commit();
	 
		return 1;
		     
	}
	
	public function employeeEdit(Request $request, $token, $uid)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$salt = $company_id.$this->salt.Session::get('USERINFO')->userId;
		$cmpToken = hash('sha256',$salt);
	    $userRole = Session::get('USERINFO')->userRole;
	   
		if($token != $cmpToken ||  $userRole != '20') {
			return Redirect::to('/dashboard/employee')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
			
			$dep_name = '';
			if($request->dep_id > 0 ){
			 	$dep = Department::where(array('dep_id' => $request->dep_id))->first();
				$dep_name = $dep->name; 
			}
			
			$position_id = 0;
			if($request->has('position_id')) {
				$position_id = $request->get('position_id');
			}
				
			$position = Position::where('position_id', $position_id)->first();
			$position_title = '';
				
			if($position) {
				$position_title = $position->position_title;
			}
			 
			if(!empty($request->get('email'))) {//检查EMAIL是否被其它账号占用
				$existed = User::whereRaw('email ="'.$request->get('email').'" AND uid != '. $uid)->first();
				if($existed) {
					return Redirect::back()->with('result', Lang::get('mowork.email_existed'));
				}
			}
			
			if(!empty($request->get('phone'))) {//检查手机号是否被其它账号占用
				$existed = User::whereRaw('mobile = "'.$request->get('phone').'" AND uid != '. $uid)->first();
				if($existed) {
					return Redirect::back()->with('result', Lang::get('mowork.mobile_existed'));
				}
			}
			
			try{
				UserCompany::updateEmployeeInfo($uid, $company_id, $request->get('dep_id'), $dep_name, $request->get('emp_code'), 
						$position_id, $position_title, $request->get('emp_start'), $request->get('emp_end'));
			    User::where('uid', $uid)->update(array('fullname' => $request->get('fullname'),
			    		'email' => $request->get('email')? $request->get('email'): NULL, 'mobile' => $request->get('phone')? $request->get('phone'):NULL ));
			 } catch (\Exception $e) {
			 	return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			 }
			 return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		$row = UserCompany::getEmpInfo($company_id, $uid);//double guarantee with company_id
		$departmentList = AjaxController::departmentList($company_id);
		$positionList = AjaxController::positionList($company_id);
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;<a href="/dashboard/employee">'.Lang::get('mowork.employee_management').'</a>&raquo;'.Lang::get('mowork.edit').Lang::get('mowork.employee');
		return view('backend.employee-edit',array('token' => $token, 'departmentList' => $departmentList, 'positionList' => $positionList, 
				'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function employeeDismiss(Request $request, $token, $uid)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$company_id = Session::get('USERINFO')->companyId;
		 
		$salt = $company_id.$this->salt.Session::get('USERINFO')->userId;
		$cmpToken = hash('sha256',$salt);
		$userRole = Session::get('USERINFO')->userRole;
		
		if($token != $cmpToken ||  $userRole != '20') {
			return Redirect::to('/dashboard/employee')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			UserCompany::dismissEmployee($uid, $company_id);
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
	
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function employeeFrozen(Request $request, $token, $uid)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$company_id = Session::get('USERINFO')->companyId;
			
		$salt = $company_id.$this->salt.Session::get('USERINFO')->userId;
		$cmpToken = hash('sha256',$salt);
		$userRole = Session::get('USERINFO')->userRole;
	
		if($token != $cmpToken ||  $userRole != '20') {
			return Redirect::to('/dashboard/employee')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			UserCompany::frozenEmployee($uid, $company_id);
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
	
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function employeeDelete(Request $request, $token, $uid)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$company_id = Session::get('USERINFO')->companyId;
			
		$userRole = Session::get('USERINFO')->userRole;
		$salt = $company_id.$this->salt.Session::get('USERINFO')->userId;
	 	$cmpToken = hash('sha256',$salt);
		 
		if($token != $cmpToken || $userRole != '20') {
			return Redirect::to('/dashboard/employee')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	 
		try {
			UserCompany::deleteEmployee($uid, $company_id);
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
	
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function addEmployeeCheck(Request $request)
	{
	   	$company_id = Session::get('USERINFO')->companyId;
		$empCode = $request->get('emp_code');
		$email = $request->get('email');
	    $user = User::where('email',$email)->first();
	     
		if($request->has('uid')) {//check for updating.
			if($user) {
				$uid = $user->uid;
				$existedBoth = UserCompany::where(array('emp_code' => $empCode, 'uid' => $uid, 'company_id' => $company_id))
					->where('uid', '!=', $request->get('uid'))->first();
				Log::debug('errmsg: '.'existed Both, uid=='.$uid.';request-uid=='.$request->get('uid'));
			} else {
				$existedBoth = '';
			}
		} else {//check for adding new
			if($user) {
				$uid = $user->uid;
				$existedBoth = UserCompany::where(array('emp_code' => $empCode, 'uid' => $uid, 'company_id' => $company_id))->first();
			    $existedEmployee = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id))->first();
			    Log::debug('errmsg: '.'typecode 1==='.$empCode.';uid and com'.$uid.'and '.$company_id);
			} 
			else {
				$existedBoth = '';//go to check 'existedCode
			}
			 
		}
	 
		if($existedBoth) {
			$res = array('0' => 'existedBoth');
			$json = json_encode($res);
			return $json;
		} else if (isset($existedEmployee)) {
			$res = array('0' => 'existedEmployee');
			$json = json_encode($res);
			return $json;
		}
		 
		if($request->has('uid')) {//check for updating
			$existedCode = UserCompany::where(array('emp_code' => $empCode,  'company_id' => $company_id))
			->where('uid','!=',$request->get('uid'))->first();
		} else {//check for adding new
			$existedCode = UserCompany::where(array('emp_code' => $empCode, 'company_id' => $company_id))->first();
		}
	 
		if($existedCode) {
			$res = array('0' => 'existedCode');
			$json = json_encode($res);
			return $json;
		}
	 	 
		$res = array('0' => '');
	 
		$json = json_encode($res);
		return $json;
	}
	
	public function companyApprover(Request $request) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		$company_id = Session::get('USERINFO')->companyId;
		
		if($request->has('submit')) {
			 $existed = Approver::where('company_id',$company_id)->first();
			 
			 try {
			 		if($existed) {
			 			Approver::where('company_id',$company_id)->update(array('project_uid' => $request->get('project_uid') 
			 		));
			 	
			 			if($request->has('plan_uid')) {
			 				Approver::where('company_id',$company_id)->update(array('plan_uid' => $request->get('plan_uid')));
			 			}
			 	
			 			if($request->has('issue_uid')) {
			 				Approver::where('company_id',$company_id)->update(array('issue_uid' => $request->get('issue_uid')));
			 			}
			 		} else {
			 			Approver::create(array('company_id' => $company_id, 'project_uid' => $request->get('project_uid'),
			 			'plan_uid' => $request->has('plan_uid')? $request->get('plan_uid') : $request->get('project_uid'),
			 			'issue_uid' => $request->has('issue_uid')? $request->get('issue_uid') : $request->get('project_uid')
			 			));
			 		}
			 		return Redirect::back()->with('success', Lang::get('mowork.operation_success'));
				} catch (\Exception $e) {
					return Redirect::back()->with('failure', Lang::get('mowork.operation_failure'));
			    }
		}
		$row = Approver::where('company_id', $company_id)->first();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;'. Lang::get('mowork.approver_setup');
		return view('backend.company-approver',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function associateUserToCompany(Request $request) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$rows = array();
		
		if($request->has('submit')) {
			if($request->has('identity')) {
				$rows = User::whereRaw("email ='". $request->get('identity') ."' OR mobile = '".$request->get('identity') ."'")->get();
				 
		        if(count($rows) == 0) {
		           return Redirect::back()->with('result', Lang::get('mowork.nothing_found'));
		        }
		         
			}
			else if($request->has('cbx')) {
				$uid = $request->get('cbx')[0];
				//check if this user joined in this company before
				$existed = UserCompany::where(array('uid' => $uid, 'company_id' => $company_id))->first();
				if($existed) {
					return Redirect::back()->with('result', Lang::get('mowork.existed_employee'));
				} else {
					UserCompany::addEmployee($uid, '', $dep_id = 0, $dep_name = '', null,null,null, $company_id);
					return Redirect::back()->with('result', Lang::get('mowork.associate_user_done'));
				}
			}
		}
	   
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;'. Lang::get('mowork.associate_user');
		return view('backend.associate-user-to-company',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function position (Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 	
		if($request->has('submit')) {
				
			$validator = Validator::make($request->all(), [
					'position' => 'required' 
			]);
			if ($validator->fails()) {
					
				return Redirect::back()->withErrors($validator);
			}
			
			 try {
			 	Position::addPosition($request->get('position'), $request->get('position_en'), $company_id);
			 	return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
			 } catch (\Exception $e) {
			 	return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
			 }
	    }
	    $salt = $company_id.$this->salt.$uid;
	    $rows = Position::where('company_id',$company_id)->get();
	    $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.employee_management')
		.' &raquo; '.Lang::get('mowork.position');
	    return view('backend.position',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	     
	}
	
	public function checkExistedPosition(Request $request)
	{
		$company_id = Session::get('USERINFO')->companyId;
		
		$position = $request->get('position');
	    $position_id = $request->get('position_id');
		
	    if($position_id == 0) {
		    $existed = Position::where(array('position_title' => $position, 'company_id' => $company_id))->first();
	    } else {
	    	$existed = Position::where(array('position_title' => $position, 'company_id' => $company_id))
	    	    ->where('position_id','!=', $position_id)->first();
	    }
	    
		if($existed) {
			$res = array('0' => 'existed');
			 
		} else {
		    $res = array('0' => '');
		    
		}
		 
		$json = json_encode($res);
		return $json;
	}

	public function positionDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt.$id);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/employee/position')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			Position::deletePosition ($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
	
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function positionEdit(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt.$id);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/employee/position')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
			try {
				Position::updatePosition($id, $request->get('position'), $request->get('position_en'), $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		$row = Position::where('position_id',$id)->where('company_id',$company_id)->first();
		  
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.employee_management')
		.' &raquo; '.Lang::get('mowork.position_edit');
		
		 
		return view('backend.position-edit',array('token' => $token, 	'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
}
