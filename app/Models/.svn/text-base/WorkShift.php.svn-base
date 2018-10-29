<?php
namespace App\Models;
use Eloquent;

class WorkShift extends Eloquent {

	protected $primaryKey = 'shift_id';
	protected $table = 'work_shift';

	protected $fillable = array('shift_code','shift_name','shift_name_en','worktime','real_worktime','color','company_id');
	protected $guarded = array('shift_id');
	public $timestamps = true;
    
	static public function updateWorkShift($shift_id, $shift_code, $shift_name, $worktime, $color, $company_id)
	{
		return self::where(array('shift_id' => $shift_id, 'company_id' => $company_id))->update(array('shift_code' => $shift_code,
				'shift_name' => $shift_name, 'worktime' => $worktime));
	}

}

?>
