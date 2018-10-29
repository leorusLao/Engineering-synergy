<?php
namespace App\Models;
use Eloquent;


class Position extends Eloquent {

	protected $primaryKey = 'position_id';
	protected $table = 'position';

	protected $fillable = array('position_title', 'alias', 'position_title_en', 'company_id'
	);
	protected $guarded = array('position_id');
	public $timestamps = true;

	public static function addPosition($title, $title_en, $company_id) 
	{
		self::create(array('position_title' => $title, 'position_title_en' => $title_en, 'company_id' => $company_id));
	}
	
	public static function deletePosition ($position_id, $company_id )
	{
		self::where(array('position_id' => $position_id, 'company_id' => $company_id))->delete();
	}
	
	public static function updatePosition ($position_id,$title, $title_en, $company_id )
	{
		self::where(array('position_id' => $position_id, 'company_id' => $company_id))
		->update(array('position_title' => $title, 'position_title_en' => $title_en));
	}
}

?>