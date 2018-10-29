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
use App\Models\ScanIssue;
use App\Models\ScanIssueCompany;
 
class CfgIssueScanController extends Controller {
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
   
	public function planDate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'PLANDATE';
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
			$people = implode(',',$request->get('people'));
			 
			try {
				ScanIssueCompany::customizeScanIssue($code, Lang::get('mowork.plan_date'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
		
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
		
			return  Redirect::back()->with('result', $result);
		}
		
		$row = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => $code))->first();
		
		if(! $row ) {
			$row = ScanIssue::where('code', $code)->first();
		}
		 
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_scan_configuration').' &raquo; '.Lang::get('mowork.plan_date');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.issue-plan-date',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function realDate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'REALDATE';
		if($request->has('submit')) {
		
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
			$people = implode(',',$request->get('people'));
		
			try {
				ScanIssueCompany::customizeScanIssue($code, Lang::get('mowork.real_date'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
		
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
		
			return  Redirect::back()->with('result', $result);
		}
		
		$row = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => $code))->first();
		
		if(! $row ) {
			$row = ScanIssue::where('code', $code)->first();
		}
			
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_scan_configuration').' &raquo; '.Lang::get('mowork.real_date');
		
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.issue-real-date',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function reportedDate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$code = 'ISSUEMADE';
		if($request->has('submit')) {
	
			$date_range = $request->get('date_range');
			$message_via = implode(',',$request->get('message_via'));
			$people = implode(',',$request->get('people'));
	
			try {
				ScanIssueCompany::customizeScanIssue($code, Lang::get('mowork.reported_date'), $date_range, $message_via, $people, $company_id );
				$result = Lang::get('mowork.operation_success');
	
			} catch (\Exception $e) {
				$result =  Lang::get('mowork.operation_failure');
			}
	
			return  Redirect::back()->with('result', $result);
		}
	
		$row = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => $code))->first();
	
		if(! $row ) {
			$row = ScanIssue::where('code', $code)->first();
		}
			
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_scan_configuration').' &raquo; '.Lang::get('mowork.reported_date');
	
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*', 'user.fullname')->get();
		return view('backend.issue-reported-date',array('cookieTrail' => $cookieTrail, 'row' => $row, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function issueAlert(Request $request)
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
				ScanIssueCompany::customizeScanIssue('ALERT1', $scan_name , $date_range, $message_via, $people, $company_id );
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
					ScanIssueCompany::customizeScanIssue('ALERT2', $scan_name , $date_range, $message_via, $people, $company_id );
					$result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
					$result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanIssueCompany::disableAlert('ALERT2', $company_id);
			}
			 
			
			if($request->has('enable3')) {
				$date_range = $request->get('date_range3');
				$scan_name = $request->get('scan_name3');
				$message_via = implode(',',$request->get('message_via3'));
				$people = implode(',',$request->get('people3'));
				try {
				    ScanIssueCompany::customizeScanIssue('ALERT3', $scan_name , $date_range, $message_via, $people, $company_id );
				    $result = Lang::get('mowork.operation_success');
				} catch (\Exception $e) {
				    $result =  Lang::get('mowork.operation_failure');
				}
			} else {
				ScanIssueCompany::disableAlert('ALERT3', $company_id);
			}
		 
			return  Redirect::back()->with('result', $result);
		}
		
		$row1 = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => 'ALERT1'))->first();
		$row2 = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => 'ALERT2'))->first();
		$row3 = ScanIssueCompany::where(array('company_id' => $company_id, 'code' => 'ALERT3'))->first();
		if(! $row1 ) {
			$row1 = ScanIssue::where('code', 'ALERT1')->first();
		}
		if(! $row2 ) {
			$row2 = ScanIssue::where('code', 'ALERT2')->first();
		}
		if(! $row3 ) {
			$row3 = ScanIssue::where('code', 'ALERT3')->first();
		}
		
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_scan_configuration').' &raquo; '.Lang::get('mowork.issue_alert');
				
		$employees = UserCompany::where('user_company.company_id',$company_id)->join('user','user.uid','=','user_company.uid')->
		select('user_company.*','user.fullname')->get();
		return view('backend.issue-alert',array('cookieTrail' => $cookieTrail, 'row1' => $row1, 'row2' => $row2, 'row3' => $row3, 'employees' => $employees, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
 	 
	 
}