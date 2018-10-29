<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
use Validator;
use Illuminate\Http\Request;
 
use App\Models\IssueClass;
use App\Models\IssueSource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
  
 
class CfgIssueController extends Controller {
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

	public function issueSource(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 
		if($company_id < 1) {
			return Redirect::to('/dashboard/company-profile')->with('result',Lang::get('mowork.company_first'));
		}
			
		if($request->has('submit')) {
			  
			$validator = Validator::make($request->all(), [
					'code' => 'required',
					'name' => 'required',
			]);
			
			if ($validator->fails()) {
				 
				return Redirect::back()->withErrors($validator);
			}
			
			if(IssueSource::isExistedIssueSourceCode($request->code, $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.code_existed'));
			}
			
			try {
				IssueSource::addIssueSourceCode($request->get('code'), $request->get('name'), $request->get('description'), $company_id);
				
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
  	    
		$rows = IssueSource::whereRaw('( company_id = 0 OR company_id = ' .$company_id .')' )->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
		 
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_configuration').' &raquo; '.Lang::get('mowork.issue_source');
		return view('backend.issue-source',array('salt' => $salt, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	  
	
	public function issueSourceEdit(Request $request, $token, $id) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$id;
		$cmpToken = hash('sha256',$salt);
		
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/issue-config')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
			try {
				IssueSource::updateIssueSourceCode($id, $request->code, $request->name, $request->description, $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));		
			}
			return Redirect::to('/dashboard/issue-config')->with('result', Lang::get('mowork.operation_success'));
		}
		
	 	$row = IssueSource::where('id',$id)->where('company_id',$company_id)->first();//double guarantee with company_id
		
	 	$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; <a href="/dashboard/issue-config">'.Lang::get('mowork.openissue_configuration').'</a> &raquo; '.Lang::get('mowork.issue_source');
	 	return view('backend.issue-source-edit',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function issueSourceDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$id;
		$cmpToken = hash('sha256',$salt);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/issue-config')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
			IssueSource::deleteIssueSource($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
		
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	public function issueClass(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
			
		if($company_id < 1) {
			return Redirect::to('/dashboard/company-profile')->with('result',Lang::get('mowork.company_first'));
		}
			
		if($request->has('submit')) {
				
			$validator = Validator::make($request->all(), [
					'code' => 'required',
					'name' => 'required',
			]);
				
			if ($validator->fails()) {
					
				return Redirect::back()->withErrors($validator);
			}
				
			if(IssueClass::isExistedIssueClassCode($request->code, $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.code_existed'));
			}
				
			try {
				IssueClass::addIssueClass($request->get('code'), $request->get('name'), $request->get('description'), $company_id);
	
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		 
		$rows = IssueClass::whereRaw('( company_id = 0 OR company_id = ' .$company_id .')' )->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
			
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.openissue_configuration').' &raquo; '.Lang::get('mowork.issue_class');
		return view('backend.issue-class',array('salt' => $salt, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function issueClassEdit(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$id;
		$cmpToken = hash('sha256',$salt);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/issue-config')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
	 		try {
				IssueClass::updateIssueClassCode($id, $request->code, $request->name, $request->description, $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::to('/dashboard/issue-config/issue-class')->with('result', Lang::get('mowork.operation_success'));
		}
	
		$row = IssueClass::where('id', $id)->where('company_id',$company_id)->first();//double guarantee with company_id
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; <a href="/dashboard/issue-config">'.Lang::get('mowork.openissue_configuration').'</a> &raquo; '.Lang::get('mowork.issue_class');
		return view('backend.issue-class-edit',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function issueClassDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$id;
		$cmpToken = hash('sha256',$salt);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/issue-config/issue-class')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		try {
			IssueClass::deleteIssueClass($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
	
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	 
}
