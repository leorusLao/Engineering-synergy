<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\ModelsUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\ModelsCountry;
use App\ModelsProvince;
use App\ModelsCity;
use App\ModelsCompany;
use App\ModelsSysconfig;
use App\ModelsUserCompany;
use App\ModelsCompanyOrder;


class OrderController extends  Controller {
	protected $locale;
	
	
	public function purchaseService(Request $request)
	{
		if(! Session::has('userId')) return Redirect::to('/');
		
		$row = Session::get('USERINFO');
	     
		if($request->input('submit')){ //TODO for real payment: check if there are business licence and artificial person
			if(! $request->has('package')) {
				return Ridrect::back()->with('result', Lang::get('mowork.pick_package_required'));
			}
				
			$packages = $request->get('package');
			$package = $packages[0];
				
			if($package == 1) {
				$user_permits = 5;
				$total = 5000;
			}
			else {
				$user_permits = 10;
				$total = 8000;
			}
				
			//1 add order history
			//die(var_dump(Session::get('USERINFO'))."<br><br>".Session::get('COMPANIES'));
			 
			$ip = $_SERVER['REMOTE_ADDR'];
			$agent = $_SERVER['HTTP_USER_AGENT'];
			$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			
		    CompanyOrder::create(array('track_id' => 'TR'.$row->companyId, 'uid' => $row->userId, 'company_id' => $row->companyId, 'prod_id' => '0', 'prod_type' => 0,
			 		'payment_address' => '9302 Tyne Road','payment_city' => 'Pudong', 'payment_zone' => 'Shanghai','payment_country' => 'China', 'payment_method' => '1',
			 		'payment_code' => 'ALIPAY','total' => $total, 'tax' => 0,'order_status_id' => 5, 'order_status_name' => 'Completion','lang_id' => 1,'currency' => 'CNY',
			 		'text_note' => "Puchase permit $user_permits RMB$total", 'ip' => $ip, 'user_agent' => $agent, 'accept_language' => $language));
				
			//2 update license; if multiple licenses;
		    
			$com = Company::where('company_id',$row->companyId)->first();
				
			 
			if( $com ) {
				if($com->expiry_date > date('Y-m-d')) { //renew license
					$expiryDate = date("Y-m-d",strtotime($com->expiry_date . " + 1 year"));
					Company::where('company_id',$row->companyId)->update(array('user_permits' => $user_permits,
							'effect_date' => date('Y-m-d'), 'expiry_date' => $expiryDate));
					return Redirect::back()->with('result',Lang::get('mowork.update_license_success'));
					
				} else { //first time to buy license
					$expiryDate = date("Y-m-d",strtotime(date('Y-m-d') . " + 1 year - 1 day"));
					
					Company::where('company_id',$row->companyId)->update(array('user_permits' => $user_permits,
						'effect_date' => date('Y-m-d'), 'expiry_date' => $expiryDate));
					
					User::where('uid',$row->userId)->update(array('role_code' => '20'));
					
					return Redirect::back()->with('result',Lang::get('mowork.sysadm_assigned'));
				}
		 		 
			}
			else {
				 
				return Redirect::back()->with('result',Lang::get('mowork.update_license_failure'));
			}
		}
		
		$permit = Company::where('company_id', $row->companyId)->where('effect_date','<=',date('Y-m-d'))->where('expiry_date','>=',date('Y-m-d'))->first();
	
		$cookieTrail = Lang::get('mowork.user_info').'&raquo;'.Lang::get('mowork.purchase_service');
		return view('backend.purchase-service',array('permit' => $permit, 'cookieTrail' => $cookieTrail, 'locale' => $this->locale));
	
	}
}


