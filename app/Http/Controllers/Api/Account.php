<?php

namespace App\Http\Controllers\Api;
use App;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Company;
use App\Models\Buhost;
use App\Models\OAuthAccessToken;
use App\Models\OAuthTokenHistory;
use App\Http\Controllers\CheckApi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Response;
use \Exception;

class Account extends App\Http\Controllers\Controller
{
    public static function usernamePasswordUnmatch(Response $response)
    {
        
        $res = array('data' => array('result' => 'failure','reasonCode' => '-1020', 'description' => Lang::get('mowork.account_password_unmatch'),'token' => Session::token()),'status' => $response->status());
        $json = json_encode($res, JSON_UNESCAPED_UNICODE);
        
        return $json;
    }

    public static function LoginSuccess($user, Response $response)
    {       
        try{
            $token = Account::randString(32);
            $timestamp =  date('Y-m-d H:i:s');
            $delay =  getenv('TOKEN_LIFESPAN') ?: 1440;
            $expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));

            User::where('uid',$user->uid)->update(array('api_token' => $token));
            $client_type = $user->client_type;
            $identity_type = $user->identity_type;
            
            $find = OAuthAccessToken::where('uid',$user->uid)->first();
            
            if ($find) {//move token to history
                OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $client_type,
                    'username' => $find->username, 'identity_type' => $identity_type,'token' => $find->token,
                    'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at,
                    'token_updated' => $find->updated_at ));
            
                OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
                $uid_token = $find->uid.'_token';
                Redis::setex($uid_token,36000,$token);
            } else {//fresh user
                switch ($identity_type) {
                    case 'email':
                        $username = $user->email;
                        break;
                    case 'mobile':
                        $username = $user->mobile;
                        break;
                    case 'wechat':
                        $username = $user->wechat;
                        break;
                    default:
                        $username = $user->wechat;
                        break;
                }
                OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => $client_type,
                            'username' => $user->wechat, 'identity_type' => $identity_type,
                            'token' => $token, 'expiry_at' => $expiry_at ));
                $uid_token = $user->uid.'_token';
                Redis::setex($uid_token,36000,$token);
            }
            
            $data = array('uid' => $user->uid,'token' => $token); 
            $res = array('data' => $data,'description'=>'','reasonCode'=>'00000','result'=>'success'); 
            $json = json_encode($res, JSON_UNESCAPED_UNICODE);
          
            return $json;
        }catch(Exception $e){ 
            $res = array('data' => '','description'=>Lang::get('mowork.database_error'),'reasonCode'=>'46021','result'=>'failure'); 
            $json = json_encode($res, JSON_UNESCAPED_UNICODE);
            return $json;
        }
    }
    
 
    
    public static function signupWithWechat($request,$response)
    {
        // 邀请用户加入公司时  BU站点创建用户
        if($request->has('company_id'))
        {
            $domain_id = Company::where('company_id', $request->get('company_id'))->value('domain_id');
            $domain_id_arr = $domain_id == 1 ? [$domain_id] : [1, $domain_id];
            $buhost = Buhost::whereIn('bu_id', $domain_id_arr)->pluck('bu_site','bu_id')->toArray();
        }

        if(!$request->has('company_id') || $buhost[1] == $_SERVER['HTTP_HOST'])
        {

            $success = true;
            $reasonCode = '';
            $token = Account::randString(32);
            $timestamp =  date('Y-m-d H:i:s');
            $delay = 1440;
            $expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));

            try {
                $id = User::create(array('wechat' => $request->get('unionid'),'username' =>$request->get('nickname'),'gender' => $request->get('sex'),'province' => $request->get('province'),
                        'city' => $request->get('city'),'country' => $request->get('country'),'avatar' =>$request->get('headimgurl'),'prefer_language' => $request->language))->id;
                User::where('id',$id)->update(array('uid' => $id,'api_token' => $token));

            } catch (Exception $e) {
                $res = array('data' => '','description'=>Lang::get('mowork.db_err'),'reasonCode'=>'10000','result'=>'failure');
                return json_encode($res,JSON_UNESCAPED_UNICODE);
            }

            $user = User::where('user.uid',$id)->leftJoin('user_company','user_company.uid','=','user.uid')
               ->select('user.*','user_company.role_id','user_company.company_id')->first(); //may have not associated with a company

            $find = OAuthAccessToken::where('uid',$id)->first();

            if ($find) {//move token to history
                OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
                        'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));

                OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
            } else {//fresh user

                OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => '2', 'username' => $request->get('unionid'), 'identity_type' => 'wechat',
                        'token' => $token, 'expiry_at' => $expiry_at ));
            }

            //可能需要从gateway站点转去用户的公司站点；
            $data = array('uid' => $user->uid,'userRole' => $user->userRole?$user->userRole:51, 'companyid' => $user->company_id?$user->company_id: 0,
                    'forwarddomain'=> $user->forward_domain?$user->forward_domain:'', 'token' => $token,'timestamp' => $timestamp);
            $res = array('data' => $data,'description'=>'','reasonCode'=>'00000','result'=>'success');

            // BU站点创建用户
            if($request->has('company_id') && $buhost[1] == $_SERVER['HTTP_HOST']) {
                $tmpData = [
			'openid' => $request->get('openid'),
			'nickname' => $request->get('nickname'),
			'sex' => $request->get('sex'),
			'language' => $request->get('language'),
			'city' => $request->get('city'),
			'province' => $request->get('province'),
			'country' => $request->get('country'),
			'headimgurl' => $request->get('headimgurl'),
			//'privilege' => $request->get('privilege'),
			'unionid' => $request->get('unionid'),
			'client_type' => $request->get('client_type'),
			'identity_type' => $request->get('identity_type'),
			'company_id' => $request->get('company_id'),
			'uid' => $id,
			'token' => $token,
			'expiry_at' => $expiry_at,
			'timestamp' => $timestamp,
		];
		
                $ss = self::curl_post('http://' . $buhost[$domain_id].'/api/wechat/login', $tmpData);
            }

            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }

        if($request->has('company_id') && $buhost[1] != $_SERVER['HTTP_HOST']) {
            try {
                User::create(array('uid' => $request->get('uid'), 'wechat' => $request->get('unionid'),'username' =>$request->get('nickname'),'gender' => $request->get('sex'),'province' => $request->get('province'),
                    'city' => $request->get('city'),'country' => $request->get('country'),'avatar' =>$request->get('headimgurl'),'prefer_language' => $request->language, 'api_token' => $request->token));

            } catch (Exception $e) {
                $res = array('data' => '','description'=>Lang::get('mowork.db_err'),'reasonCode'=>'10000','result'=>'failure');
                return json_encode($res,JSON_UNESCAPED_UNICODE);
            }

            $user = User::where('user.uid',$request->uid)->leftJoin('user_company','user_company.uid','=','user.uid')
                ->select('user.*','user_company.role_id','user_company.company_id')->first();
            $find = OAuthAccessToken::where('uid',$request->uid)->first();

            if ($find) {//move token to history
                OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
                    'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));

                OAuthAccessToken::where('id',$find->id)->update(array('token' => $request->token,'expiry_at' => $request->expiry_at ));
            } else {//fresh user

                OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => '2', 'username' => $request->get('unionid'), 'identity_type' => 'wechat',
                    'token' => $request->token, 'expiry_at' => $request->expiry_at ));
            }

            $data = array('uid' => $user->uid,'userRole' => $user->userRole?$user->userRole:51, 'companyid' => $user->company_id?$user->company_id: 0,
                'forwarddomain'=> $user->forward_domain?$user->forward_domain:'', 'token' => $request->token,'timestamp' => $request->timestamp);
            $res = array('data' => $data,'description'=>'','reasonCode'=>'00000','result'=>'success');

            return json_encode($res, JSON_UNESCAPED_UNICODE);
        }
    
    }
       
    //微信不绑定用户则创建
    public static function wechatFirstLoginApi($request)
    {
        $success = true;
        $reasonCode = '';
        $token = Account::randString(32);
        $timestamp =  date('Y-m-d H:i:s');
        $delay =  getenv('TOKEN_LIFESPAN') ?: 1440;
        $expiry_at = date("Y-m-d H:i:s",strtotime($timestamp . " + $delay minute"));
    
        try {
            $ary = array('wechat' => $request->get('unionid'),'username' =>$request->get('nickname'),
                        'gender' => $request->get('sex'),'province' => $request->get('province'),
                        'city' => $request->get('city'),'country' => $request->get('country'),
                        'avatar' =>$request->get('headimgurl'),'prefer_language' => $request->language);
            if($request->has('sex')){ $ary['gender'] = $request->get('sex');}else{ $ary['gender'] = 0;}
            $id = User::create($ary)->id;
            User::where('id',$id)->update(array('uid' => $id,'api_token' => $token));  
            $user = User::select('user.*','user_company.role_id','user_company.company_id')->leftJoin('user_company',
                            'user_company.uid','=','user.uid')->where('user.uid',$id)->first(); //may have not associated with a company
                
            $find = OAuthAccessToken::where('uid',$id)->first();
        
            if ($find) {//move token to history
                OAuthTokenHistory::create(array('id' => $find->id,'uid' => $find->uid,'client_type' => $find->client_type, 'username' => $find->username, 'identity_type' => $find->identity_type,
                        'token' => $find->token, 'expiry_at' => $find->expiry_at, 'token_created' => $find->created_at, 'token_updated' => $find->updated_at ));
        
                $result = OAuthAccessToken::where('id',$find->id)->update(array('token' => $token,'expiry_at' => $expiry_at ));
                $data['uid'] = $find->uid;
                $data['token'] = $find->token;
            } else {//fresh user    
                $result = OAuthAccessToken::create(array('uid' => $user->uid,'client_type' => '2', 'username' => $request->get('unionid'), 'identity_type' => 'wechat',
                        'token' => $token, 'expiry_at' => $expiry_at ));
                $data['uid'] = $user->uid;
                $data['token'] = $token;
            }
            
            return $data;       
        } catch (Exception $e) {            
            return CheckApi::return_10003();
        }
    
    }

    public static function isExistedMobilePhone($phone)
    {
        if( !is_null(User::where('email', $email)->first()) ){
            return true;
        }
        return false;
    }
    
    public static function signupWithMobilePhone($phone,$password) 
    {
        $success = true;
        try {
            $id = User::create(array('email' => $email,'password' => Hash::make($password)))->id;
            User::where('id',$id)->update(array('uid' => $id));
        } catch (Exception $e) {
            $success = false;
        }
        
        return $success;
    }
    
    public static function signupWithEmail($email,$password)
    {
           
        try {
            $id = User::create(array('email' => $email,'password' => Hash::make($password)))->id;
            User::where('id',$id)->update(array('uid' => $id));
            $success = true;
        } catch (Exception $e) {
            $success = false;
        }
        
        return $success;
    }
    
    public static function isValidatedEmailFormat($email)
    {
        $rules = array(
                'email'    => 'required|email'
        );
        
        $validator = Validator::make(array('email' => $email), $rules);
        if($validator->fails()) {
            return false;
        }
        
        return true;
    }
    
    public static function isExistedEmail($email)
    {
        if( !is_null(User::where('email', $email)->first()) ){
            return true; 
        }
        return false;
    }
    
    public static function sanitizePhoneNumber($phone)
    {
        return preg_replace("/[^0-9]/","",$phone);
    }
    
    public static function isPasswordLongEnough($password,$minLength){
        return strlen($password) >= $minLength? true: false ;
    }
    
    public static function randString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function curl_post($url,$data)
    {
        $ch = CURL_init();
        CURL_setopt($ch,CURLOPT_URL,$url);
        CURL_setopt($ch,CURLOPT_POST,1);   //post方式
        CURL_setopt($ch,CURLOPT_POSTFIELDS,$data);  //post提交数据
        CURL_setopt($ch,CURLOPT_HEADER,0);  //不要头部信息
        CURL_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //数据返回给句柄
        CURL_setopt($ch,CURLOPT_TIMEOUT_MS,3000);   //超时放弃
        CURL_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        CURL_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        $data = CURL_exec($ch);
        if(!$data){
            echo CURL_error($ch);
        }else{
            $data = json_decode($data);
            return $data;
        }
    }
}