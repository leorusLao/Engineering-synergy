<?php
 namespace App\Http\Controllers;
 use Session;
 use Illuminate\Http\Request;
 use Illuminate\Http\Response;
 use Illuminate\Support\Facades\Lang;
 use App\models\Category;
 use App\models\Country;
 use App\models\Zone;
 use App\models\City;
 
 class ResponseJsonController extends Controller  {
 	
    public function dropdownListAjax(Request $request){
    	Session::regenerate();
       
		if($request->has('cat1') && $request->has('type')){
			$parent_id = $request->input('cat1');
			$type = $request->input('type');
			//file_put_contents('111','cat1=='.$parent_id.';type==='.$type);
			$cats = $this->categoryList($parent_id,$type);
			
			return response()->json($cats);
		}
		else if($request->has('type')){
			$type = $request->input('type');
			$cats = $this->categoryList(0,$type);
				
			return response()->json($cats);
		}
		else if($request->has('cat1') && $request->has('cat2')){
			$billing_info = $this->getCatBillingInfo($request->get('cat1'),$request->get('cat2'));
	 		return response()->json($billing_info);
		}
	}
 
	public function signup($signupMethod,$userIdentity,$password,$validatingCode){
		/**
		
		* @param signupMethod: signup method
		  1: mobile phone
		  2: email
		  3: wechat
		  other: invalid method
		
		* @param $userIdentity: signup identification
		   mobile phone number if $signupMethod = 1
		   email address if $signupMethod = 2
		   wehcat id if $signupMethod = 3
		
		* @param $password: password
		  $password required at least 6 characters if signupMethod = 1,2
		  $password is empty if signupMethod = 3
		  		
		* @param array $validatingCode: validating code
		  $validatingCode required if signupMethod = 1
		  $validatingCode empty if signupMethod = 2,3
				
		* return array('code' => $code, 'message' => $message)
		  -101： 无效的注册方式
		  -110： 密码太短
		  -112： 手机与验证码不匹配
		  -113:微信授权失败
		  -114： 该用户已存在
		  -115： 数据库操作失败
		  100： 注册成功
		*/
		
		
	}
	
	
	public function signupByEmail($email,$password)
	{
		
	}
	
	public function signupByEMobilPhone($phone,$password,$vialidatingCode)
	{
		
	
	}
	
	public function categoryList($parent_id,$type)
	{
		$list = array();
		$rows = Category::where('parent_id',$parent_id)->where('type',$type)->orderBy('sorted','asc')->get();
		foreach($rows as $row){
			 	 
				$list[$row->cat_id] = $row->cat_name;
			 
	 	}
		return $list;
	}
	
	static function provinceList($sort = 'asc'){
		//$list = array('0' => Lang::get('helper.all_location'));
		$list = array();
		$ones = Province::orderBy('sort_id',$sort)->get();
		 
		foreach($ones as $one){
			$list[$one->zone_id] =  $one->zone_name;
		}
		return $list;
	}
	
	static function city($province_id=2){
		$list = array();
	    $ones = City::where('province_id','=',$province_id)->get();   	
	    
	    foreach($ones as $one){
			 $list[$one->city_id] =  $one->name;
		}
	 	return $list;
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
  		$rows = Country::where('is_active',1)->orderBy('country_name','asc')->get();
  		foreach($rows as $row){
  				$list[$row->country_id] = $row->country_name;
   		}
  		return $list;
  	}
  	
    static function topCategory(){
    	$list = array();
    	$rows = Category::where('parent_id',0)->orderBy('cat_id','asc')->get();
		foreach($rows as $row){
				$list[$row->cat_id] = $row->cat_name;
		} 
    	return $list; 
    }
   
    static function subCategory($parent_id = 1){
    	$list = array();
    	$rows = Category::where('parent_id',$parent_id)->orderBy('cat_id','asc')->get();
    	foreach($rows as $row){
    			$list[$row->cat_id] = $row->cat_name;
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
    
   
    
    static function getPricListByCat($catId){
    	 
    	$row = Category::where('cat_id',$catId)->first();
    	$list = array('1' => '1个月'.$row->monthly_price.'加元','3' => '3个月'.$row->season_price.'加元',
    			'6' => '6个月'.$row->semiannual_price.'加元','12' =>'12个月'.$row->annual_price.'加元' );
    	return $list;
    }
    
    public function getCatBillingInfo($cat1,$cat2){
    	if($cat2 > 0){
    		$row = Category::where('cat_id',$cat2)->first();
    	}
    	else{
    		$row = Category::where('cat_id',$cat1)->first();
    	}
    	
    	return array( '0' => $row->billing,'1' => $row->monthly_price,'2' => $row->season_price,'3' => $row->semiannual_price, '4' => $row->annual_price);
    
    }
 }

?>