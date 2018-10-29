<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\Models\UserCompany;
use App\Models\Supplier;
 
class CfgSupplierController extends Controller {
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

	public function supplier(Request $request)
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
  	
		$rows = Supplier::where('supplier.company_id',$company_id)->join('company','company.company_id','=','supplier.sup_company_id')->
			leftJoin('company_industry','company_industry.industry_id','=','company.industry')->
			leftJoin('country','country.country_id','=', 'company.country')->
			leftJoin('province','province.province_id','=','company.province')->
			leftJoin('city','city.city_id','=','company.city')->
			select('supplier.*','company.company_name','company.reg_no','company.legal_person','company.industry','company.country',
					'company.province','company.city','company.address','company.postcode','company_industry.name as industry_name',
					'country.name as country_name', 'province.name as province_name', 'city.name as city_name')->paginate(PAGEROWS);
	 
		$salt = $company_id.$this->salt.$uid;
		$token = hash('sha256',$salt);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.supply_management');
		return view('backend.supplier',array('token' => $token, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function supplierSelect(Request $request) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		
		$result = '';
		
		if($request->has('submit')) {
			$rows = SearchController::companySelection($request);
			$result = Lang::get('mowork.search_result');	
		} else if($request->has('batchSelect')) {
			 $cbxs = $request->get('cbx');
			 $numAdded = 0;
			 foreach ($cbxs as $sup) {
			  
			 	$company = Company::where('company_id', $sup)->first();
			 	 
			 	if($company) {//add company as supplier
			 		 if(Supplier::isExistedSupplier($company->company_id, $company_id)) continue;
			 		 $numAdded += Supplier::addSupplier($company->company_id, $company_id, $company->ceo, $company->legal_person, $company->phone, $company->email);
			 	}
			 }
			 
			 return Redirect::to('/dashboard/supplier')->with('result', Lang::get('mowork.added_suppliers').$numAdded);
			 
		} else {
			$rows = Company::getExcludeMeCompanies($company_id,PAGEROWS);
		}
	 	
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;<a href="/dashboard/supplier">'.Lang::get('mowork.supply_management')."</a>".' &raquo; '.Lang::get('mowork.tick_checkbox').Lang::get('mowork.supplier');
		return view('backend.supplier-select',array('cookieTrail' => $cookieTrail, 'result' => $result, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	
	public function supplierEdit(Request $request, $token, $id) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt);
		
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
		
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.department_edit');
		return view('backend.department-edit',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}
	
	public function supplierDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		$cmpToken = hash('sha256',$salt);
	
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
	
	 
}
