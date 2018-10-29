<?php
namespace App\Models;
use Eloquent;

class OAuthTokenHistory extends Eloquent
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $primaryKey = 'oth_id';
	protected $table = 'oauth_token_history';

	protected $fillable = array('id','uid','client_type','username','identity_type','token', 'expiry_at','token_created','token_updated');

	public $timestamps = true;


}
