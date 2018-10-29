<?php

namespace App\Http\Controllers;
use App;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

Class LanguageController extends Controller {
	/*
	 * the Visitor choose a human language 
	 * 
	 */
    public function setLanguage(Request $request, $locale)
    {
    	App::setLocale($locale);
        Session::put('locale', $locale); 
          
        //setcookie('locale', $locale, time() + (86400 * 90),'/','mowork.cn');
        setcookie('locale', $locale, time() + (86400 * 90),'/','.mowork.cn',0,true); 
        //$url = Request::url();current
       
        $url = URL::previous();
        $url = str_replace(array('/zh-cn','/zh-tw','/en'),'/'.$locale,$url);
         
        return Redirect::to($url);
        
    }

}

?>