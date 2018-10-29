<?php
 namespace App\Http\Controllers;
 use Session;
 use Illuminate\Http\Request;
 use Illuminate\Http\Response;
 use Illuminate\Support\Facades\Lang;
 use App\Models\Category;
 use App\Models\Country;
 use App\Models\Province;
 use App\Models\City;
 use App\Models\CompanyIndustry;
 use App\Models\CompanyType;
 use App\Models\WorkCalendarBase;
use App\Models\Department;
use App\Models\Position;
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompany;

 class AjaxController extends Controller  {
 	
    public function dropdownListAjax(Request $request){
    	Session::regenerate();
        
    	if($request->has('country')) {
    		$provinces = AjaxController::provinceList($request->get('country'));
    		 
    		return response()->json($provinces);
    	}
    	else if($request->has('province')) {
    		$cities = AjaxController::cityList($request->get('province'));
    		return response()->json($cities);
    	}
		 
	}
   
	public function getCompanyInfo(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		 
		$row = Company::where('company_id',$request->get('company'))->leftJoin('country','country.country_id','=','company.country')
		        ->leftJoin('province','province.province_id','=','company.province')
		        ->leftJoin('city','city.city_id','=','company.city')
		        ->select('company.*','country.name as country_name','province.name as province_name','city.name as city_name')->first();
		if($row ) {
			return response()->json(array('country' => Lang::get('mowork.country').': '.$row->country_name, 'province' => Lang::get('mowork.province').': '.$row->province_name, 
					'city' => Lang::get('mowork.city').': '.$row->city_name, 'address' => Lang::get('mowork.address').": ".$row->address, 'postcode' => Lang::get('mowork.postcode').': '.$row->postcode, 'biz' => Lang::get('mowork.biz_des').': '.$row->biz_des));
		}
		return Response::json('error', 'No Found');
	}
 	 
	static function provinceList($country =1){
		$list = array('0' => '');
		 
		$ones = Province::where('country_id',$country)->orderBy('province_id','asc')->get();
		 
		foreach($ones as $one){
			$list[$one->province_id] =  $one->name;
		}
		return $list;
	}
	
	static function cityList($province_id=2){
		$list = array('0' => '');
	    $ones = City::where('province_id','=',$province_id)->get();   	
	    
	    foreach($ones as $one){
			 $list[$one->city_id] =  $one->name;
		}
	 	return $list;
	}
	
	static function calYearList(){//until 2040; after 2040; also need update table work_cal_base
	 
		return range(2017,2040);
		
	}
		
	static function language(){
		 
		$rows = Language::all();
		$list = array();
		foreach($rows as $row){
				$list["$row->iso_code"] = $row->native_name;
		}
		
		return $list;
	}
 	
 	static function yearList($endYear,$numberOfYears){
	 
		
		for($kk = 0; $kk < $numberOfYears; $kk++ ){
			$list[$endYear - $kk] = $endYear - $kk;
		}
		return $list;
	}
	 
	 
	static function monthList(){
		 
		for($kk = 1; $kk <13; $kk++){
			if($kk < 10) {
				$index = '0' . $kk;
				$list[$index ] = $index;
			}
			else{
				$list[$kk] = $kk;
			}
		}
		return $list;
	}
	
	static function day($year,$month){
		 
		$thirtyone = array('01','03','05','07','08','10','12');
		$thirty = array('04','06','09','11');
		if(in_array($month, $thirtyone)){
			for($kk = 1; $kk <= 31; $kk++){
				if($kk < 10) {
					$index = '0' . $kk;
					$list[$kk] = $index;
				}
				else{
					$list[$kk] = $kk;
				}
			}
		}
		else if(in_array($month, $thirty)){
			for($kk = 1; $kk <= 30; $kk++){
				if($kk < 10) {
					$index = '0' . $kk;
					$list[$kk] = $index;
				}
				else{
					$list[$kk] = $kk;
				}
			}
			
		}
		else{//month = 02 
			for($kk = 1; $kk <= 28; $kk++){
				if($kk < 10) {
					$index = '0' . $kk;
					$list[$kk] = $index;
				}
				else{
					$list[$kk] = $kk;
				}
			}
			
			if( ($year % 4 == 0 && $year % 100 >0) || ($year % 4 == 0 && $year % 100 == 0 && $year % 400 == 0) ){
				//leap year
				$list[29] = 29;
			}
		}
		return $list;
	}
	
  	static function countryList(){
  		$list = array();
  		$rows = Country::where('is_active',1)->orderBy('country_id','asc')->get();
  		foreach($rows as $row){
  				$list[$row->country_id] = $row->name;
   		}
  		return $list;
  	}
  	
  	static function CompanyIndustryList(){
  		$list = array('0' => '');
  		$rows = CompanyIndustry::where('is_active',1)->orderBy('industry_id','asc')->get();
  		foreach($rows as $row){
  			$list[$row->industry_id] = $row->name;
  		}
  		return $list;
  	}
  	
  	static function CompanyTypeList(){
  		$list = array('0' => '');
  		$rows = CompanyType::where('is_active',1)->orderBy('type_id','asc')->get();
  		foreach($rows as $row){
  			$list[$row->type_id] = $row->name;
  		}
  		return $list;
  	}
  	
  	static function departmentList($company_id) {
  		$list = array('0' => '');
  		$rows = Department::where('company_id', $company_id)->orderBy('dep_id','asc')->get();
  		foreach($rows as $row){
  			$list[$row->dep_id] = $row->name;
  		}
  		return $list;
  	}
  	
  	static function employeeList($company_id) {
  		$list = array('0' => '');
  		$rows = UserCompany::join('user','user.uid','=','user_company.uid')
  		->where(array('company_id' => $company_id, 'user_company.status' =>1))
  		->orderBy('dep_id','asc')->select('user.uid','user.fullname')->get();
  		foreach($rows as $row){
  			$list[$row->uid] = $row->fullname;
  		}
  		return $list;
  	}
  	
  	static function positionList($company_id) {//暂时统一使用平台职位
  		$list = array('0' => '');
  		$rows = Position::where('company_id', 0)->orderBy('position_id','asc')->get();
  		foreach($rows as $row){
  			$list[$row->position_id] = $row->position_title;
  		}
  		return $list;
  	}
  	
    static function quantity($max){
    	$list = array(); 
    	for($ii = 1; $ii <= $max; $ii++){
    		 
    		$list[$ii] = $ii;
    	}
    	return $list;
    }
     
   
 }

?>