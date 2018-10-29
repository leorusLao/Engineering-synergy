<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\WorkCalendar;
use App\Models\WorkShift;
use App\Models\WorkCalendarBase;
use App\Models\WorkCalendarReal;
use App\Models\CfgGaudge;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\CfgCurrency;
use App\Models\ConfigNumbering;
use App\Models\ConfigFolder;
use App\Models\CompanyConfig;

class ConfigController extends  Controller {
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


    public function adminRegion(Request $request, Response $response)
    {

        if(!Session::has('userId')) return Redirect::to('/');

        $rows = Country::paginate(PAGEROWS);
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.region_management') .' &raquo; '.Lang::get('mowork.country');
        return view('backend.admin-region',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

    }

	public function country(Request $request, Response $response)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
	
		$rows = Country::paginate(PAGEROWS);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.region_management');
		return view('backend.country',array('cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function province(Request $request, Response $response)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		
		$countryList = Country::where('is_active',1)->orderBy('country_id','asc')->get();
			
		$selectedCountry = 1;
		
		if($request->has('country')) {
			$selectedCountry =  $request->get('country');	
		}
		$rows = Province::where('province.country_id',$selectedCountry)->join('country','country.country_id','=','province.country_id')->orderBy('country_id','asc')->select('province.*','country.name as country')->paginate(PAGEROWS);
		
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.region_management').' &raquo; '.Lang::get('mowork.province');
		return view('backend.province',array('cookieTrail' => $cookieTrail,'rows' => $rows, 'countryList' => $countryList, 'selectedCountry' => $selectedCountry, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function city(Request $request, Response $response)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
	    
		$countryList = Country::where('is_active',1)->orderBy('country_id','asc')->get();
			
		$selectedCountry = 1;
		$selectedProvince = 19;
		if($request->has('country')) {
			$selectedCountry =  $request->get('country');
			Session::put('selectedCountry',$selectedCountry);
			$selectedProvince = 1;
		} else if(Session::has('selectedCountry')) {
			$selectedCountry = Session::get('selectedCountry');
		}
		
		if($request->has('province')) {
			$selectedProvince =  $request->get('province');
			Session::put('selectedProvince', $selectedProvince);
		} else if(Session::has('selectedProvince')) {
			$selectedProvince = Session::get('selectedProvince');
		}
		$provinceList = Province::where('country_id', $selectedCountry)->orderBy('country_id','asc')->get();
		$rows = City::where(array('city.country_id' => $selectedCountry, 'city.province_id' => $selectedProvince))->join('province','province.province_id','=','city.province_id')->join('country','country.country_id','=','city.country_id')->orderBy('city.province_id','asc')->select('city.*','province.name as province','country.name as country')->paginate(PAGEROWS);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.region_management').' &raquo; '.Lang::get('mowork.city');;
		return view('backend.city',array('cookieTrail' => $cookieTrail,'rows' => $rows,  'countryList' => $countryList, 'selectedCountry' => $selectedCountry, 'provinceList' => $provinceList, 'selectedProvince' => $selectedProvince, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function calendar(Request $request, Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	    $company_id = Session::get('USERINFO')->companyId;
	    $uid = Session::get('USERINFO')->userId;
	    
	    if ($request->has('submit')) {
	    	
	      	try {
	    		WorkCalendar::create(array('cal_code' => $request->get('cal_code'), 'cal_name' => $request->get('cal_name'), 'company_id' => $company_id));
	      	} catch (\Exception $e) {
	      		return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	      	}
	      	return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
	    }
	    
	    $rows = WorkCalendar::whereRaw('company_id =0 OR company_id = '.$company_id)->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.calendar_setup').' &raquo; '.Lang::get('mowork.cal_name');
		return view('backend.calendar',array('salt' => $salt,'cookieTrail' => $cookieTrail,'rows' => $rows, 'company_id' => $company_id,
				'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function calendarEdit(Request $request, $token, $id)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$cmpToken = hash('sha256',$company_id.$this->salt.$uid.$id);
		
		if($token != $cmpToken) {
			return Redirect::back()->with('result', Lang::get('mowork.operaiton_disallowed'));
		}
		
		if($request->has('submit')) {
		 
			WorkCalendar::where('cal_id',$id)->update(array('cal_code' => $request->get('cal_code'), 'cal_name' => $request->get('cal_name')));
			return Redirect::to('/dashboard/calendar')->with('result', Lang::get('mowork.operation_success'));
		}
		
		$row = WorkCalendar::where('cal_id',$id)->first();
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.calendar_setup').' &raquo; '.Lang::get('mowork.cal_name');
		return view('backend.calendar-edit',array('row' => $row, 'token' => $token, 'id' => $id, 'cookieTrail' => $cookieTrail, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function calendarDelete(Request $request, $token, $id)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$cmpToken = hash('sha256',$company_id.$this->salt.$uid.$id);
	
		if($token != $cmpToken) {
			return Redirect::back()->with('result', Lang::get('mowork.operaiton_disallowed'));
		}
		 
		try {	
			WorkCalendar::where('cal_id',$id)->delete();
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
			 	
	}
	
	public function workShift(Request $request, Response $response)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$username = Session::get('USERINFO')->username;
		$role = Session::get('USERINFO')->userRole;
		
		$rows = WorkShift::where('company_id',$company_id)->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.calendar_setup').' &raquo; '.Lang::get('mowork.work_shift');;
		return view('backend.work-shift',array('salt' => $salt,'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function workShiftEdit(Request $request, $token, $shift_id)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$username = Session::get('USERINFO')->username;
		$role = Session::get('USERINFO')->userRole;
	 	$salt = $company_id.$this->salt.$uid;
	 	
	 	$cmpToken = hash('sha256',$salt.$shift_id);
	 	 
	 	if($cmpToken != $cmpToken) {
	 		return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
	 	}
	 	
	 	if($request->has('submit')) {
	 		try {
	 			WorkShift::updateWorkShift($shift_id, $request->get('shift_code'), $request->get('shift_name'),
	 					$request->get('worktime'), $request->get('shift_color'), $company_id);
	 			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
	 		} catch (\Exception $e) {
	 			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	 		}
	 	}
	 	
	 	$row = WorkShift::where(array('shift_id' => $shift_id, 'company_id' => $company_id))->first();
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.calendar_setup').' &raquo; '.Lang::get('mowork.work_shift');;;
		 
		return view('backend.work-shift-edit',array('token' => $token, 'shift_id' => $shift_id, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function workdaySetup(Request $request, Response $response)
	{
			
  		if(!Session::has('userId')) return Redirect::to('/');
   
		$company_id = Session::get('USERINFO')->companyId;
		 
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.calendar');
		
		$curYear = date('Y');
		$curMonth = date('n');
		$result = '';
		 
		if( $request->has('direction') && $request->has('month')) {//left arrow or right arrow touched
		 	
			$direction = $request->get('direction');
			$curYear = $request->get('year');
			
		 	if($direction == '-') {
				$curMonth = $request->get('month') - 1;
				if($curMonth == 0 && $curYear == 2017) {
					$curMonth = 1;
					$curYear = 2017;
					$result = 'Final Date';
				}
				else if($curMonth == 0 && $curYear > 2017) {
					$curMonth = 12;
					$curYear--;
				}
				
		 	} else {
		 		$curMonth = $request->get('month') + 1;
		 		if($curMonth == 13) {
		 			$curMonth = 1;
		 			$curYear++;
		 		}
		 		
		 		if($curYear == 2041) {
		 			$curYear = 2040;
		 			$curMonth = 12;
		 		}
		 	}
	 		 
		} else if ($request->has('year')) {//year option changed
			$curYear = $request->get('year');
			$curMonth = $request->get('month');
		}
		 
		if( empty($request->get('month')) ) { //to avoid too faster page refresh having no month assigned
			$curMonth = date('n');
		}
		
		if($request->has('submit')) {
			 $year = $request->get('year');
			 $month = $request->get('month');
			 $days = WorkCalendarBase::getMonthDays($year, $month);
			 $workdays = '';
			 for ($kk = 1; $kk <= $days; $kk++) {
			 	$var = 'day'.$kk;
			    $workdays .= ($request->$var).',';	
			 }
			 $workdays = rtrim($workdays,',');
	         
			 if(empty($company_id)) return Redirect::back()->with('result', Lang::get('mowork.cal_without_company'));
			 if(WorkCalendarReal::isExistedCompanyYear($company_id, $year)){
			 	WorkCalendarReal::updateCompanyYear($year, $month, $workdays, $company_id);
			 } else {
			 	WorkCalendarReal::addCompanyYear($year, $month, $workdays, $company_id);
			 }
			 
		}
		
		$selectedY = $curYear;
	    $rows = WorkCalendarBase::where('cal_year',$curYear)->where('cal_month',$curMonth)->get();
	    
	     
	    $ownCal = WorkCalendarReal::where(array('cal_year' => $curYear, 'company_id' => $company_id))->first();
	    $realSchedule = ''; 
	    if($ownCal) {
	    	$var = 'month'.$curMonth;
	    	$realSchedule = explode(',',$ownCal->$var);
	    }
	   
	    $yearList = AjaxController::calYearList();
	     
		return view('backend.workday-setup',array('cookieTrail' => $cookieTrail,'result' => $result, 'yearList' => $yearList, 'selectedY' => $selectedY, 'selectedM' => $curMonth, 'rows' => $rows, 
				'realSchedule' => $realSchedule, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function calendarMake(Request $request, $token, $cal_id)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid;
		 
		$cmpToken = hash('sha256',$salt.$cal_id);
		
		if($cmpToken != $cmpToken) {
			return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
		}
		
		$cal = WorkCalendar::where('cal_id', $cal_id)->first();
		$cal_name = $cal->cal_name;
		  
	
		$curYear = date('Y');
		$curMonth = date('n');
		$result = '';
		
		//检查是否有标准缺省日历，没有则制作
		$hasDefaulCal = WorkCalendarReal::where(array('cal_year' => $curYear, 'company_id' => $company_id, 'cal_id' => 1))->first();
		if(!$hasDefaulCal) {
			InitController::initCalendar($company_id, $curYear);
		}
			
		if( $request->has('direction') && $request->has('month')) {//left arrow or right arrow touched
	
			$direction = $request->get('direction');
			$curYear = $request->get('year');
				
			if($direction == '-') {
				$curMonth = $request->get('month') - 1;
				if($curMonth == 0 && $curYear == 2017) {
					$curMonth = 1;
					$curYear = 2017;
					$result = 'Final Date';
				}
				else if($curMonth == 0 && $curYear > 2017) {
					$curMonth = 12;
					$curYear--;
				}
	
			} else {
				$curMonth = $request->get('month') + 1;
				if($curMonth == 13) {
					$curMonth = 1;
					$curYear++;
				}
				 
				if($curYear == 2041) {
					$curYear = 2040;
					$curMonth = 12;
				}
			}
		 	
		} else if ($request->has('year')) {//year option changed
			$curYear = $request->get('year');
			$curMonth = $request->get('month');
		}
			
		if( empty($request->get('month')) ) { //to avoid too faster page refresh having no month assigned
			$curMonth = date('n');
		}
	
		if($request->has('submit')) {
			$year = $request->get('year');
			$month = $request->get('month');
			$days = WorkCalendarBase::getMonthDays($year, $month);
			$workdays = '';
			for ($kk = 1; $kk <= $days; $kk++) {
				$var = 'day'.$kk;
				$workdays .= ($request->$var?$request->$var :0).',';
			}
			$workdays = rtrim($workdays,',');
	
			if(empty($company_id)) return Redirect::back()->with('result', Lang::get('mowork.cal_without_company'));
			if(WorkCalendarReal::isExistedCompanyYear($company_id, $year, $cal_id)){
				WorkCalendarReal::updateCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id);
			} else {
			 	WorkCalendarReal::addCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id);
			}
	
		}
	
		$selectedY = $curYear;
		$rows = WorkCalendarBase::where('cal_year',$curYear)->where('cal_month',$curMonth)->get();
		 
	
		$ownCal = WorkCalendarReal::where(array('cal_year' => $curYear, 'company_id' => $company_id, 'cal_id' => $cal_id))->first();
		$realSchedule = '';
		//是否已制作本月日历
		$cal_made = Lang::get('mowork.not_made');
		if($ownCal) {
			$var = 'month'.$curMonth;
			//
			if(!$ownCal->$var) {//本月日历尚未制作，暂时使用标准日历
				$realSchedule = WorkCalendarBase::getMonthScheduleString($curYear, $curMonth);
				$realSchedule = explode(',',$realSchedule);
				
			} else {
				$realSchedule = explode(',',$ownCal->$var);
			}
			
			$did_month = 'did_'.$var; 
			if($ownCal->$did_month) {
				$cal_made = Lang::get('mowork.made');
			}
		}
	
		$yearList = AjaxController::calYearList();
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.scheduler');
		
		return view('backend.workday-setup',array('cookieTrail' => $cookieTrail,'result' => $result, 'yearList' => $yearList, 'selectedY' => $selectedY, 'selectedM' => $curMonth, 'rows' => $rows,
				'realSchedule' => $realSchedule, 'cal_name' => $cal_name, 'token' => $token, 'cal_id' => $cal_id, 
				'cal_made' => $cal_made, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}


    public function calendarMakeNew(Request $request, $token, $cal_id)
    {

        if(!Session::has('userId')) return Redirect::to('/');
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;
        $salt = $company_id.$this->salt.$uid;

        $cmpToken = hash('sha256',$salt.$cal_id);

//        if($cmpToken != $cmpToken) {
//            return Redirect::back()->with('result',Lang::get('mowork.operation_disallowed'));
//        }

        $cal = WorkCalendar::where('cal_id', $cal_id)->first();
        $cal_name = $cal->cal_name;


        $curYear = date('Y');
        $curMonth = date('n');
        $result = '';

        //检查是否有标准缺省日历，没有则制作
        $hasDefaulCal = WorkCalendarReal::where(array('cal_year' => $curYear, 'company_id' => $company_id, 'cal_id' => 1))->first();
        if(!$hasDefaulCal) {
            InitController::initCalendar($company_id, $curYear);
        }

        if( $request->has('direction') && $request->has('month')) {//left arrow or right arrow touched

            $direction = $request->get('direction');
            $curYear = $request->get('year');

            if($direction == '-') {
                $curMonth = $request->get('month') - 1;
                if($curMonth == 0 && $curYear == 2017) {
                    $curMonth = 1;
                    $curYear = 2017;
                    $result = 'Final Date';
                }
                else if($curMonth == 0 && $curYear > 2017) {
                    $curMonth = 12;
                    $curYear--;
                }

            } else {
                $curMonth = $request->get('month') + 1;
                if($curMonth == 13) {
                    $curMonth = 1;
                    $curYear++;
                }

                if($curYear == 2041) {
                    $curYear = 2040;
                    $curMonth = 12;
                }
            }

        } else if ($request->has('year')) {//year option changed
            $curYear = $request->get('year');
            $curMonth = $request->get('month');
        }

        if( empty($request->get('month')) ) { //to avoid too faster page refresh having no month assigned
            $curMonth = date('n');
        }

        if($request->has('submit')) {
            $year = $request->get('year');
            $month = $request->get('month');
            $days = WorkCalendarBase::getMonthDays($year, $month);
            $workdays = '';
            for ($kk = 1; $kk <= $days; $kk++) {
                $var = 'day'.$kk;
                $workdays .= ($request->$var?$request->$var :0).',';
            }
            $workdays = rtrim($workdays,',');

            if(empty($company_id)) return Redirect::back()->with('result', Lang::get('mowork.cal_without_company'));
            if(WorkCalendarReal::isExistedCompanyYear($company_id, $year, $cal_id)){
                WorkCalendarReal::updateCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id);
            } else {
                WorkCalendarReal::addCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id);
            }

        }

        $selectedY = $curYear;
        $rows = WorkCalendarBase::where('cal_year',$curYear)->where('cal_month',$curMonth)->get();


        $ownCal = WorkCalendarReal::where(array('cal_year' => $curYear, 'company_id' => $company_id, 'cal_id' => $cal_id))->first();
        $realSchedule = '';
        //是否已制作本月日历
        $cal_made = Lang::get('mowork.not_made');
        if($ownCal) {
            $var = 'month'.$curMonth;
            //
            if(!$ownCal->$var) {//本月日历尚未制作，暂时使用标准日历
                $realSchedule = WorkCalendarBase::getMonthScheduleString($curYear, $curMonth);
                $realSchedule = explode(',',$realSchedule);

            } else {
                $realSchedule = explode(',',$ownCal->$var);
            }

            $did_month = 'did_'.$var;
            if($ownCal->$did_month) {
                $cal_made = Lang::get('mowork.made');
            }
        }

        $yearList = AjaxController::calYearList();
        $cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.scheduler');

        return view('backend.workday-setup',array('cookieTrail' => $cookieTrail,'result' => $result, 'yearList' => $yearList, 'selectedY' => $selectedY, 'selectedM' => $curMonth, 'rows' => $rows,
            'realSchedule' => $realSchedule, 'cal_name' => $cal_name, 'token' => $token, 'cal_id' => $cal_id,
            'cal_made' => $cal_made, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

    }
	
	public function workShiftAdd(Request $request, Response $response)
	{
			
		if(!Session::has('userId')) return Redirect::to('/');
		
		$rules = array(
				'shift_code'    => 'required',
				'shift_name'    => 'required',
				'worktime'    => 'required|numeric'
		);
		
	 
		$validator = Validator::make($request->all(), $rules);
		if($validator->fails()) {
			return Reditect::back()->withErrors($validator);
		}
		
		$rows = WorkShift::create(array('shift_code' => $request->get('shift_code'),'shift_name' => $request->get('shift_name'),
				'worktime' => $request->get('worktime'),'color'=> $request->get('color')? $request->get('color'):'#00CCCC','company_id' => Session::get('USERINFO')->companyId));
		
		return Redirect::back()->with("result",Lang::get('mowork.operation_success'));
		
	}
	
	public function measurement(Request $request, Response $response)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		if($request->input('submit')){
			$json = ApiMbDaily::changePassword($request->input('oldPassword'), $request->input('password'), $request->input('passwordConfirm'), $response);
			$result = json_decode($json);
			if($result->status == 200 && $result->data->result == 'success'){
				return Redirect::back()->with(array( 'result' => Lang::get('mowork.operation_success').': '. Lang::get('mowork.password_updated')));
			}
			else {
				return  Redirect::back()->with(array( 'result' => Lang::get('mowork.operation_failure').': '.$result->data->description));
			}
		}
	
		$result = CfgGaudge::where('company_id',0)->get();
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.gauge_management');
		return view('backend.measurement',array('result' => $result,'cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	}
	
	
	public function editmeasurement(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$id = $request['id'];
		$companyid = Session::get('USERINFO')->companyId;
	
		if(!empty($id) && !empty($companyid)){
			$where = array('company_id'=>$companyid,'id'=>$id);
			$row = CfgGaudge::infoMeasurement($where);
			if(empty($row)){
				//return Redirect::route('/dashboard/measurement')->withErrors('更新失败！');
			}
		}else{
			//return Redirect::route('/dashboard/measurement')->withErrors('更新失败！');
		}
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.gauge_management');
		return view('backend.edit-measurement',array('row'=>$row,'cookieTrail'=>$cookieTrail));
	}
	
	
	public function ajaxmeasurement(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	   	 
		$id = $request['measurement_id'];
		$companyid = Session::get('USERINFO')->companyId;
		if(!empty($id) && !empty($companyid)){
			file_put_contents('qqq',print_r($request->all(),true));
			$where = array('company_id'=>$companyid,'id'=>$id);
			$row = CfgGaudge::infoMeasurement($where);
			if(!empty($row)){
				$ary = array(
						'type'=>$request['measurement_type'],
						'name'=>$request['measurement_name'],
						'unit'=>$request['measurement_unit'],
						'symbol'=>$request['measurement_symbol'],
						'ratio'=>$request['measurement_ratio'],
						'precise'=>$request['measurement_precise'],
						'created_by'=>$request['measurement_creater']
						
				);
				$affect = CfgGaudge::updateMeasurement($where,$ary);
				if($affect > 0){
					return response()->json(array('code'=>1,'msg'=>'保存成功'));
				}else{
					return response()->json(array('code'=>2,'msg'=>'保存失败'));
				}
			}else{
				//return Redirect::route('/dashboard/measurement')->withErrors('更新失败！');
			}
		}else if(empty($id) && !empty($companyid)){
			$ary = array(
					'type'=>$request['measurement_type'],
					'company_id'=>$companyid,
					'name'=>$request['measurement_name'],
					'unit'=>$request['measurement_unit'],
					'symbol'=>$request['measurement_symbol'],
					'ratio'=>$request['measurement_ratio'],
					'precise'=>$request['measurement_precise'],
					'created_by'=>$_SESSION['userId']
					 
			);
		 
			$affect = CfgGaudge::createMeasurement($ary);
			if($affect > 0){
				return response()->json(array('code'=>1,'msg'=>'保存成功'));
			}else{
				return response()->json(array('code'=>2,'msg'=>'保存失败'));
			}
		}
	
	}
	
	
	public function addmeasurement(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$companyid = Session::get('USERINFO')->companyId;
	
		if(empty($companyid)){
			return Redirect::route('/dashboard')->withErrors('更新失败！');
		}
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.gauge_management');
		return view('backend.add-measurement',array('cookieTrail'=>$cookieTrail));
	}
	
	
	public function deletemeasurement(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	
		$id = $request['id'];
		$companyid = Session::get('USERINFO')->companyId;
	
		if(!empty($id) && !empty($companyid)){
			$where = array('company_id'=>$companyid,'id'=>$id);
			$row = CfgGaudge::infoMeasurement($where);
			if(empty($row)){
				//return Redirect::route('/dashboard/measurement')->withErrors('更新失败！');
			}else{
				$where = array('id'=>$id);
				$affect = CfgGaudge::delete_measurement($where);
				if($affect > 0){
					return response()->json(array('code'=>1,'msg'=>'保存成功'));
				}else{
					return response()->json(array('code'=>2,'msg'=>'保存失败'));
				}
			}
		}else{
			//return Redirect::route('/dashboard/measurement')->withErrors('更新失败！');
		}
	}
    
	public function currency(Request $request) 
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$rows = CfgCurrency::orderBy('id', 'ASC')->paginate(PAGEROWS);
		  
		$salt = $company_id.$this->salt.$uid;
		$token = hash('sha256',$salt);
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.currency_setup');
		return view('backend.currency',array('token' => $token, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}

	public function otherSetup(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		if($request->has('submit')) {
			$id = $request->has('id') ? $request->get('id') : 0;
			if(!$request->has('prefix'))
			{
				// 如果我们配置了软删除想要彻底删除一条数据
                // 删除判断根据company_config（条件筛选，current_value，company_id）
				$configNumbering = ConfigNumbering::find($id);
				if($configNumbering->forceDelete()) {
					return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
				}else {
					return Redirect::back()->with('result', Lang::get('mowork.db_err'));
				}
			}

			if(ConfigNumbering::isExistedPrefix($request->get('prefix'), $company_id, $id)){
				return Redirect::back()->with('result', Lang::get('mowork.prefix_existed'));
			}
			try {
				if ($id == 0) {
					// 添加
					ConfigNumbering::addNumberingSet($request->get('prefix'), $request->get('description'), $request->get('description_en'),$request->get('cycle'), $request->get('cycle_en'), $request->get('yyyy'), $request->get('mm'), $request->get('dd'), $request->get('serial_length'), $company_id);
				} else {
					// 编辑
                        if($cc_cfg_name = ConfigNumbering::cc_cfg_name($id,$company_id)[0]["cc_cfg_name"]){
                            if(ConfigNumbering::updateNumberingSet($id, $request->get('prefix'), $request->get('description'), $request->get('description_en'), $request->get('cc_cfg_name'), $request->get('cycle'), $request->get('cycle_en'), $request->get('yyyy'), $request->get('mm'), $request->get('dd'), $request->get('serial_length'))){
                                //需要加入条件 1：判断此公司下是否有此cfg_name 2:有则更改此计划对应编号（最终各计划）
                                CompanyConfig::updateConfigSet($cc_cfg_name, $request->get('cc_cfg_name'),0, date('y'), strlen (  'YY'), '', $company_id);
                                return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
                            }else{
                                return Redirect::back()->with('result', Lang::get('mowork.db_err'));
                            }
                        }else{
                            return Redirect::back()->with('result', Lang::get('mowork.db_err'));
                        }
				}
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch(\Exception $e){
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}


		$rows = ConfigNumbering::whereRaw("company_id = ".$company_id)->orderBy('id', 'ASC')->paginate(PAGEROWS);

		$salt = $company_id.$this->salt.$uid;
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.coding_config');
		return view('backend.other-setup',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

	}
	 
	public function toolFile(Request $request)
	{    
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	    
		if($request->has('submit')) {
			if(ConfigFolder::isExistedFolder($request->get('folder_code'), $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.foldertype_existed'));
			}
			
			try {
				ConfigFolder::addFolder($request->get('folder_code'),$request->get('filetype'), $request->get('filetype_en'), $company_id);
				return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
			}
		}
		
		$rows = ConfigFolder::orderBy('id', 'ASC')->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
	 	$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;<a href="/dashboard/other-setup">'.Lang::get('mowork.other_config').'</a>&raquo;'.Lang::get('mowork.tool_file');
		return view('backend.tool-file',array('salt' => $salt, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function serialNumberEdit(Request $request, $token, $id)
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid .$id;
		$cmpToken = hash('sha256',$salt);
		
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/other-setup')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
			$dayflag = 0;
			if($request->has('dayflag')) {
				$dayflag = 1;
			}
			try {
				ConfigNumbering::updateNumberingSet($id, $request->get('prefix'), $request->get('description'), $request->get('description_en'), $request->get('yyyy'), $request->get('mm'), $dayflag, $request->get('serial_length'), $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		
		$row = ConfigNumbering::where('id',$id)->where('company_id',$company_id)->first();//double guarantee with company_id
		
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;<a href="/dashboard/other-setup">'.Lang::get('mowork.other_config').'</a>&raquo;'.Lang::get('mowork.serial_number');
		return view('backend.serial-number-edit',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function serialNumberDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid . $id;
		$cmpToken = hash('sha256',$salt);
		 
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/other-setup')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
			ConfigNumbering::deleteNumberingSet($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
		
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	
	}
	
	
	public function toolFileEdit(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid .$id;
		$cmpToken = hash('sha256',$salt);
		
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/other-setup')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
			 
			try {
				ConfigFolder::updateFolderCode($id, $request->get('folder_code'), $request->get('filetype'), $request->get('filetype_en'), $company_id);
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
		
		$row = ConfigFolder::where('id',$id)->where('company_id',$company_id)->first();//double guarantee with company_id
		
		$cookieTrail = Lang::get('mowork.system_configuration').'&raquo;<a href="/dashboard/other-setup">'.Lang::get('mowork.other_config').'</a>&raquo;'.Lang::get('mowork.tool_file');
		return view('backend.tool-file-edit',array('token' => $token, 'cookieTrail' => $cookieTrail,'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function toolFileDelete(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	  	$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$salt = $company_id.$this->salt.$uid.$id;
		$cmpToken = hash('sha256',$salt);
	
		if($token != $cmpToken) {
			return Redirect::to('/dashboard/other-setup/tool-file')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		try {
			ConfigFolder::deleteFolderCode($id, $company_id );
		} catch ( \Exception $e ) {
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.db_err' ) );
		}
		
		return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
	}
	
	
}