<?php

namespace App\Http\Controllers;
use App;
use Input;
use Auth;
use Session;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ModelsUser;
use App\ModelsCategory;
use App\ModelsZone;
use App\ModelsCity;
use App\ModelsAdvert;
use App\ModelsAdvertMedia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class FrontpageController extends  Controller 
{
	protected $locale;
	protected $location;
	protected $zones;
	protected $zone;
	protected  $selectedZone;
	
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

    public function homepage()
    {
        return view('frontend.homepage',array('locale' => $this->locale));
    }
 
  
	public function admLogin(Request $request){
		if($request->input('submit')){
			$username = trim($request->input('email'));
			$password = trim($request->input('password'));
			$identfier = 'adm_name'; //judg if user input email or accounht number
			if(strpos($request->input('email'),'@')) $identfier = 'email';
				
			if(Auth::guard('admins')->attempt([$identfier => $username, 'password' => $password])){
	
				Session::put('email',$username);
				$_SESSION['adm_uid'] = Auth::guard('admins')->user()->id;
				$adm_name = Auth::guard('admins')->user()->adm_name;
				Session::put('admuser',$adm_name? $adm_name:$username);
				$_SESSION['admuser'] = Session::get('admuser');//for avoiding browser back after logout
					
	
				return  Redirect::to("/pfadmin/home");
			}
			else{
				return Redirect::to("/pfadmin/login")->with('login_failed',Lang::get('mowork.login_failed'));
			}
		}
	
		return view('platform.login',array('locale' => $this->locale));
	}
	
	public function logout(Request $request){
		if(Session('admuser')){
			unset($_SESSION);
			session_destroy();
			 
			Session::flush();
			Auth::logout();
			return Redirect::to("/yp2100adm/login");
		}
		else if(Session('username')){
			unset($_SESSION);
			session_destroy();
			 
			 
			Session::flush();
			Auth::logout();
			 
			return Redirect::to("/");
		}
		return Redirect::to("/");
	}
  
	public function locationZone(Request $request,$zone){
		 
		$zone = str_replace('-',' ',$zone);
		$this->location = $zone;
	 
		Session::put('location',$zone);
	
		setcookie('location', $zone, time() + (86400 * 90),'/','mowork.cn');
	  	return Redirect::back();
		 
	}
	
	public function signupSuccess(){
		if(!Session::has('result')) return Redirect::to("/");
	
		return view('frontend.signup-success',array('result' => Session::get('result'),'locale' => $this->locale));
	}
	
	public function aboutUs(){
		return view('frontend.about-us',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.about_us'),'locale' => $this->locale));
	}
	
	public function contact(){
		return view('frontend.contact',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.contact'),'locale' => $this->locale));
	}
	
	public function privacy(){
		return view('frontend.privacy',array('zones' => $this->zones,'pageTitle' => Lang::get('mowork.privacy'),'locale' => $this->locale));
	}
}
