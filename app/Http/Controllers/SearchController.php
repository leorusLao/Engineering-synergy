<?php

namespace App\Http\Controllers;
use App;
use DB;
   
use Session;
use Validator;
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
 
class SearchController extends Controller {
	protected $locale;
    
	/*
	 * Provide all kind of seachs for backend mainly
	 * 
	 */
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
    
    static public function companySelection(Request $request) {
    	if(!Session::has('userId')) return Redirect::to('/');
    	$company_id = Session::get('USERINFO')->companyId;
    	$userId = Session::get('USERINFO')->userId;
    	
    	$qtext = str_replace(array('"',"'"), '', $request->get('qtext'));
     	 
    	$where = 'company_name like "%' .$qtext. '%" OR phone like "%' .$qtext. '%" OR email like "%' .$qtext. '%" OR legal_person like "%'
    				.$qtext. '%" OR ceo like "%'.$qtext. '%" OR city like "%' .$qtext. '%"'; 
     	$rows = Company::whereRaw($where)->paginate(PAGEROWS);
     	
     	return $rows;
    }
  	 
}
