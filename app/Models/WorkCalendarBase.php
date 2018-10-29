<?php
namespace App\Models;
use Eloquent;
use function foo\func;

class WorkCalendarBase extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'work_cal_base';
	protected $dates = ['start', 'end'];
	protected $fillable = array('cal_date','cal_year','cal_quarter','cal_month','cal_day', 'dow', 'month_name', 'day_name', 'week',
			'is_weekday', 'is_workday', 'is_holiday', 'holiday_des', 'is_payday','title','start', 'end', 'is_all_day', 'background_color'
	);
	protected $guarded = array('id');
	
	public static function getMonthDays($year, $month)
	{
		return self::where(array('cal_year' => $year, 'cal_month' => $month))->count();
	}
	
	public static function getMonthScheduleString($year, $month)
	{
		$rows = self::where(array('cal_year' => $year, 'cal_month' => $month))->orderBy('id','ASC')->get();
		$str = '';
		foreach ($rows as $row) {
			$str .= $row->is_workday.',';
		}
		return rtrim($str,',');
	}
	
	/**
	 * Get the event's id number
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get the event's title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Is it an all day event?
	 *
	 * @return bool
	 */
	public function isAllDay()
	{
		return (bool)$this->all_day;
	}
	
	/**
	 * Get the start time
	 *
	 * @return DateTime
	 */
	public function getStart()
	{
		return $this->start;
	}
	
	/**
	 * Get the end time
	 *
	 * @return DateTime
	 */
	public function getEnd()
	{
		return $this->end;
	}
}

?>