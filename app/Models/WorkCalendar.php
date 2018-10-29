<?php
namespace App\Models;
use Eloquent;

class WorkCalendar extends Eloquent {

	protected $primaryKey = 'cal_id';
	protected $table = 'work_cal';

	protected $fillable = array('cal_code','cal_name','cal_name_en', 'company_id');
	protected $guarded = array('cal_id');
	public $timestamps = true;

	public static function infoCalendar($where,$field='cal_id')
	{	
		$result = self::select($field)->where($where)->first();
		return $result;
	}
	public static function listCalendar($company_id)
	{
		$result = self::select('cal_name','cal_id')->where(['company_id'=>$company_id])->orWhere(['company_id'=>0])
						->get()->toArray();
		return $result;	
	}

	public static function listCalendarApi($company_id)
	{
		$result['list_calendar'] = self::select('cal_name','cal_id')->where(['company_id'=>$company_id])->orWhere(['company_id'=>0])
						->get()->toArray();
		return $result;	
	}

		
}

?>