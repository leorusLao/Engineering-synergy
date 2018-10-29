<?php
namespace App\Models;
use Eloquent;

class CfgGaudge extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'cfg_gaudge';

	protected $fillable = array('type','name','unit','symbol','ratio','precise','company_id','created_by');

	public $timestamps = true;

	public static function infoMeasurement($where)
	{ 
		$row = self::select()->where($where)->first();
		return $row;
	}
	public static function updateMeasurement($where,$ary)
	{ 
		$affect = self::where($where)->update($ary);
		return $affect;
	}
	public static function createMeasurement($ary)
	{ 
		$affect = self::create($ary);
		return $affect;
	}

}

?>