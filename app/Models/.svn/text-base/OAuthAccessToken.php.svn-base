<?php
namespace App\Models;
use Eloquent;

class OAuthAccessToken extends Eloquent
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $primaryKey = 'id';
	protected $table = 'oauth_access_token';

	protected $fillable = array('uid','client_type','username','identity_type','token', 'expiry_at',);
 
	public $timestamps = true;

	 
}