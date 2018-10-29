<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
define('PAGEROWS', 20);
define('TOKENVALID', 120);//Token valid in 120 minutes
define('DOMAIN', "mowork.cn");
define('TRIALDAYS', 14);

class Controller extends BaseController
{
	  
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $salt = 'Neswoit9$34$#@@$%@6!$26mrdsgslrevbxVBdWfertalgmelre218&*~ewE';
    protected $email_salt = 'Mewhr35235hf#$%4356$#%634*&^%*&(5434tgtremjfdmGHAawawwERTW$Ett';
    protected $wechat_salt = '&SDFe4wfs9342w99t@3rfdsbNseEEWdge038@3^$%^';
    protected function mod9710($str) {
    	$code = $str.'86';
    	return 98 - bcmod($code, 97);
    }
    
    protected function randomString($length = 10) {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
    		$randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
    }
    
    public static function encrypt($string)
    {
    	$cipher = "aes-256-cfb";
    	$key = openssl_random_pseudo_bytes(32);
    	//$ivlen = openssl_cipher_iv_length($cipher);
    	//$iv = openssl_random_pseudo_bytes($ivlen);
    	$iv = '^AGSm#d098SDFA*%';
    	$ciphertext = openssl_encrypt($string, $cipher, $key, $options=0, $iv);
    	return $ciphertext;
    }
    
    public static function decrypt($string)
    {
    	$cipher = "aes-256-cfb";
    	$key = openssl_random_pseudo_bytes(32);
    	//$ivlen = openssl_cipher_iv_length($cipher);
    	//$iv = openssl_random_pseudo_bytes($ivlen);
    	$iv = '^AGSm#d098SDFA*%';
    	$original = openssl_decrypt($string, $cipher, $key, $options=0, $iv);
    	return $original;
    }
}


