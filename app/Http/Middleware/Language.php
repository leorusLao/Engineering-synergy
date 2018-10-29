<?php
/*
 * Created on Nov 04, 2017
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * for multi-languages
 */

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Support\Facades\App;

class Language {

    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	// Make sure current locale exists.
    	 
    	if(Session::has('locale')){
    		$locale = Session::get('locale');
    	}
    	else if(isset($_COOKIE['locale'])){
    		$locale = $_COOKIE['locale'];
    	}
    	else{
    		$locale = config('app.locale');
    	}
    
    	App::setLocale($locale);
    	
    	return $next($request);
    }
    
}
?>