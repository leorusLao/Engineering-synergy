<?php
namespace App\Models;
use Eloquent;

class WorkCalendarReal extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'work_cal_real';

	protected $fillable = array('cal_year', 'cal_id', 'cal_name', 'month1', 'month2','month3', 'month4', 'month5', 'month6','month7','month8',
			'month9','month10', 'month11','month12',
			'did_month1', 'did_month2','did_month3', 'did_month4', 'did_month5', 'did_month6',
			'did_month7', 'did_month8',	'did_month9','did_month10', 'did_month11','did_month12',
			'department_id', 'company_id');
	protected $guarded = array('id');
	public $timestamps = true;

	static  public function isExistedCompanyYear($company_id, $year, $cal_id)
	{
		$existed = self::where(array('company_id' => $company_id, 'cal_year' => $year, 'cal_id' => $cal_id))->first();
		if($existed) return true;
		return false;
	}
	
	static  public function isExistedDepartmentYear($company_id, $department_id, $year, $cal_id)
	{
		$existed = self::where(array('company_id' => $company_id, 'department_id' => $department_id, 'cal_year' => $year,  'cal_id' => $cal_id))->first();
		if($existed) return true;
		return false;
	}
	
	static public function addCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id)
	{
		$id = self::create(array('cal_year' => $year, 'month'.$month => $workdays, 'cal_id' => $cal_id, 
				'cal_name' => $cal_name, 'company_id' => $company_id))->id;
		
	}
	
	static public function updateCompanyYear($year, $month, $workdays, $cal_id, $cal_name, $company_id)
	{
		 
		return self::where(array('cal_year' => $year, 'cal_id' => $cal_id, 'company_id' => $company_id))->update(array('cal_year' => $year, 
				'month'.$month => $workdays, 'did_month'.$month => 1, 'cal_name' => $cal_name));
	
	}
	
	static public function updateCompanyYearInitial($year, $month, $workdays, $cal_id, $cal_name, $company_id)
	{
			
		return self::where(array('cal_year' => $year, 'cal_id' => $cal_id, 'company_id' => $company_id))->update(array('cal_year' => $year,
				'month'.$month => $workdays, 'cal_name' => $cal_name));
	
	}
}

?>