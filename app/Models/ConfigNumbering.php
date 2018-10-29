<?php

namespace App\Models;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigNumbering extends Model {
	use SoftDeletes;

	protected $primaryKey = 'id';
	protected $table = 'config_num';
	protected $fillable = array (
			'description', 'description_en', 'prefix', 'cycle', 'cycle_en',
			'yyyy', 'mm', 'dd', 'serial_length','cc_cfg_name','company_id'
	);
	protected $guarded = array (
			'id' 
	);

	protected $dates = ['deleted_at'];

	public $timestamps = true;
	
	static public function isExistedPrefix($prefix, $companyId, $id) {
		$existed = self::whereRaw('(company_id = 0 OR company_id='. $companyId .')')->where('prefix',$prefix)->where('id', '<>', $id)->first();
		if($existed) return true;
		return false;
	}
	
	static public function addNumberingSet($prefix, $description, $descriptionEn, $cycle, $cycle_en, $yyyy, $mm, $dayflag, $serialLength, $companyId)
	{
		return self::create(array('prefix' => $prefix, 'description' => $description, 'description_en' => $descriptionEn,
			'cycle' => $cycle, 'cycle_en' => $cycle_en,	'yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dayflag,
			'serial_length' => $serialLength, 'company_id' => $companyId));
 	}


    //新增公司的编码规则，来自平台
    static public function addNumberingSetNew($description, $descriptionEn,$prefix, $cc_cfg_name, $cycle, $cycle_en, $yyyy, $mm, $dayflag, $serialLength, $companyId = 0)
    {
        return self::create(array('description' => $description, 'description_en' => $descriptionEn, 'prefix' => $prefix, 'cc_cfg_name' => $cc_cfg_name, 'cycle' => $cycle, 'cycle_en' => $cycle_en, 'yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dayflag, 'serial_length' => $serialLength, 'company_id' => $companyId));
    }
 	
 	static public function deleteNumberingSet($id, $companyId)
 	{
 		return self::where(array('id' => $id, 'company_id' => $companyId))->delete();
 	}

 	
 	static public function updateNumberingSet($id, $prefix, $description, $descriptionEn, $cc_cfg_name ,$cycle, $cycle_en, $yyyy, $mm, $dd, $serialLength)
 	{
 		return self::where(array('id' => $id))->update(array('prefix' => $prefix, 'description' => $description, 'description_en' => $descriptionEn, 'cc_cfg_name' => $cc_cfg_name, 'cycle' => $cycle, 'cycle_en' => $cycle_en, 'yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dd, 'serial_length' => $serialLength));
 		
 	}

    //查询表中的cc_cfg_name字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来更改company_config表）
    static public function cc_cfg_name($typeId,$companyId)
    {
        return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->select('cc_cfg_name')->get();
    }

    //查询config_num表中的配置字段（cc_cfg_name值跟company_config中的cfg_name值对应）
    static public function result_type_config($typeId,$companyId)
    {
        return self::where(array('type_id' => $typeId, 'company_id' => $companyId))->select('prefix','cycle','YYYY','mm','dd','serial_length')->get();
    }

 }

?>