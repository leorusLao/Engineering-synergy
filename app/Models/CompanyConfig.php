<?php
namespace App\Models;
use Eloquent;

class CompanyConfig extends Eloquent {

	protected $primaryKey = 'id';
	protected $table = 'company_config';

	protected $fillable = array('cfg_name','current_value','current_year','length', 'comment', 'company_id');
	protected $guarded = array('id');
	public $timestamps = true;
	//判断此公司下是否有此cfg_name
    static public function isExistedCfg_name ($cfg_name, $companyId)
    {
        $row = self::where('cfg_name',$cfg_name)->whereRaw("company_id = $companyId")->first();
        if($row) return true;
        return false;
    }

	//加入此计划对应初始编号
    static public function addConfigSet($cfg_name, $current_value, $current_year, $length, $comment, $companyId)
    {
        return self::create(array('cfg_name' => $cfg_name, 'current_value' => $current_value, 'current_year' => $current_year,
            'length' => $length, 'comment' => empty($comment)? '' : $comment, 'company_id' => $companyId));
    }

    //更改此计划对应初始编号
    static public function updateConfigSet($cfg_name, $new_cfg_name ,$current_value, $current_year, $length, $comment, $companyId)
    {
        return self::where(array('cfg_name' => $cfg_name,'company_id' => $companyId))->update(array('cfg_name' => $new_cfg_name,'current_value' => $current_value, 'current_year' => $current_year, 'length' => $length, 'comment' => empty($comment)? '' : $comment));

    }

    //删除此计划对应初始编号
    static public function deleteConfigSet($cc_cfg_name, $companyId)
    {
        return self::where(array('cfg_name' => $cc_cfg_name, 'company_id' => $companyId))->delete();
    }

    //查询plan_type表中的current_value字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来查询company_config表）
    static public function current_value($cfg_name,$companyId)
    {
        return self::where(array('cfg_name' => $cfg_name, 'company_id' => $companyId))->select('current_value')->get();
    }

    //查询plan_type表中的current_year字段（cc_cfg_name值跟company_config中的cfg_name值对应，根据查询此公司下有无这个值来查询company_config表）
    static public function current_year($cfg_name,$companyId)
    {
        return self::where(array('cfg_name' => $cfg_name, 'company_id' => $companyId))->select('current_year')->get();
    }
}

?>
