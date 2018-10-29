<?php

namespace App\Http\Controllers;
use App;
 
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\Models\UserCompany;
use App\Models\ScanPlan;
use App\Models\ScanPlanCompany;
 
class CfgPlanScanController extends Controller {
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
  
	
	public function departmentDelay(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'DEPDELAY';
		if($request->has('submit')) {
		    
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
		 	$people = implode(',',$request->get('people'));
		 	 
			try {
				ScanPlanCompany::customizeScanPlan($code, Lang::get('mowork.department_delay'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
				
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
			 
			return  Redirect::back()->with('result', $result);
		}
	
		$row = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'DEPDELAY'))->first();
		
		if(! $row ) {
			$row = ScanPlan::where('code', 'DEPDELAY')->first();
		}
	    
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.plan_scan_configuration').' &raquo; '.Lang::get('mowork.department_delay');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		             select('user_company.*','user.fullname')->get();
		return view('backend.department-delay',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function planStart(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'PLANSTART';
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
			$people = implode(',',$request->get('people'));
			 
			try {
				ScanPlanCompany::customizeScanPlan($code, Lang::get('mowork.plan_start'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
		
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
		
			return  Redirect::back()->with('result', $result);
		}
		
		$row = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => $code))->first();
		
		if(! $row ) {
			$row = ScanPlan::where('code', $code)->first();
		}
		 
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.plan_scan_configuration').' &raquo; '.Lang::get('mowork.plan_start');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.plan-start',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function planCompletion(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'PLANCOMPLETION';
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
			$people = implode(',',$request->get('people'));
		
			try {
				ScanPlanCompany::customizeScanPlan($code, Lang::get('mowork.plan_completion'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
		
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
		
			return  Redirect::back()->with('result', $result);
		}
		
		$row = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => $code))->first();
		
		if(! $row ) {
			$row = ScanPlan::where('code', $code)->first();
		}
			
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.plan_scan_configuration').' &raquo; '.Lang::get('mowork.plan_completion');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.plan-completion',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function planStartAlert(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range1');
			$scan_name = $request->get('scan_name1');
			$message_via = implode(',',$request->get('message_via1'));
			$people = implode(',',$request->get('people1'));
		 	 
			try {
				ScanPlanCompany::customizeScanPlan('ALERTSTART1', $scan_name , $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
			
			if($request->has('enable2')) {
				$date_range = $request->get('date_range2');
				$scan_name = $request->get('scan_name2');
				$message_via = implode(',',$request->get('message_via2'));
				$people = implode(',',$request->get('people2'));
				try {
					ScanPlanCompany::customizeScanPlan('ALERTSTART2', $scan_name , $date_range, $message_via, $people, $company_id );
					$result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
					$result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanPlanCompany::disableAlert('ALERTSTART2', $company_id);
			}
			 
			
			if($request->has('enable3')) {
				$date_range = $request->get('date_range3');
				$scan_name = $request->get('scan_name3');
				$message_via = implode(',',$request->get('message_via3'));
				$people = implode(',',$request->get('people3'));
				try {
				    ScanPlanCompany::customizeScanPlan('ALERTSTART3', $scan_name , $date_range, $message_via, $people, $company_id );
				    $result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
				    $result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanPlanCompany::disableAlert('ALERTSTART3', $company_id);
			}
		 
			return  Redirect::back()->with('result', $result);
		}
		
		$row1 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTSTART1'))->first();
		$row2 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTSTART2'))->first();
		$row3 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTSTART3'))->first();
		if(! $row1 ) {
			$row1 = ScanPlan::where('code', 'ALERTSTART1')->first();
		}
		if(! $row2 ) {
			$row2 = ScanPlan::where('code', 'ALERTSTART2')->first();
		}
		if(! $row3 ) {
			$row3 = ScanPlan::where('code', 'ALERTSTART3')->first();
		}
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.plan_scan_configuration').' &raquo; '.Lang::get('mowork.alert').Lang::get('mowork.plan_start');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.plan-start-alert',array('cookieTrail' => $cookieTrail, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function planCompletionAlert(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
			
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range1');
			$scan_name = $request->get('scan_name1');
			$message_via = implode(',',$request->get('message_via1'));
			$people = implode(',',$request->get('people1'));
			 
			try {
				ScanPlanCompany::customizeScanPlan('ALERTCOMPLETION1', $scan_name , $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
				
			if($request->has('enable2')) {
				$date_range = $request->get('date_range2');
				$scan_name = $request->get('scan_name2');
				$message_via = implode(',',$request->get('message_via2'));
				$people = implode(',',$request->get('people2'));
				try {
					ScanPlanCompany::customizeScanPlan('ALERTCOMPLETION2', $scan_name , $date_range, $message_via, $people, $company_id );
					$result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
					$result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanPlanCompany::disableAlert('ALERTCOMPLETION2', $company_id);
			}
		
				
			if($request->has('enable3')) {
				$date_range = $request->get('date_range3');
				$scan_name = $request->get('scan_name3');
				$message_via = implode(',',$request->get('message_via3'));
				$people = implode(',',$request->get('people3'));
				try {
					ScanPlanCompany::customizeScanPlan('ALERTCOMPLETION3', $scan_name , $date_range, $message_via, $people, $company_id );
					$result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
					$result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanPlanCompany::disableAlert('ALERTCOMPLETION3', $company_id);
			}
				
			return  Redirect::back()->with('result', $result);
		}
		
		$row1 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTCOMPLETION1'))->first();
		$row2 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTCOMPLETION2'))->first();
		$row3 = ScanPlanCompany::where(array('company_id' => $company_id, 'code' => 'ALERTCOMPLETION3'))->first();
		if(! $row1 ) {
			$row1 = ScanPlan::where('code', 'ALERTCOMPLETION1')->first();
		}
		if(! $row2 ) {
			$row2 = ScanPlan::where('code', 'ALERTCOMPLETION2')->first();
		}
		if(! $row3 ) {
			$row3 = ScanPlan::where('code', 'ALERTCOMPLETION3')->first();
		}
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.plan_scan_configuration').' &raquo; '.Lang::get('mowork.alert').Lang::get('mowork.plan_completion');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.plan-completion-alert',array('cookieTrail' => $cookieTrail, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	 
}