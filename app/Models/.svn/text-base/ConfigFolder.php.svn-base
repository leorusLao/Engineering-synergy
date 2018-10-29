<?php

namespace App\Models;

use Eloquent;

class ConfigFolder extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'config_folder';
	protected $fillable = array (
			'folder_code', 'filetype', 'filetype_en', 'url', 'company_id'
	);
	protected $guarded = array (
			'id' 
	);
	
	public $timestamps = true;
	
	static public function addFolder($foldeCode, $fileType, $fileTypeEn, $companyId)
	{
		return self::create(array('folder_code' => $foldeCode, 'filetype' => $fileType, 'filetype_en' => $fileTypeEn, 'company_id' => $companyId));
		
	}
	
	static public function isExistedFolder($folderCode, $companyId)
	{
		$existed = self::whereRaw('(company_id = 0 OR company_id='. $companyId .')')->where('folder_code',$folderCode)->first();
		if($existed) return true;
		return false;
	}
	
	static public function updateFolderCode($id, $foldeCode, $fileType, $fileTypeEn, $companyId)
	{
		return self::where(array('id' => $id, 'company_id' => $companyId))->update(array('folder_code' => $foldeCode, 'filetype' => $fileType, 'filetype_en' => $fileTypeEn));
	
	}
	
	static public function deleteFolderCode($id, $companyId)
	{	 
		self::where(array('id' => $id, 'company_id' => $companyId))->delete();
	}
	
	static public function getFolderTypeList($companyId)
	{
		$list = array();
		$rows =  self::whereRaw(" ( company_id = 0 OR company_id = $companyId ) ")->orderBy('id','ASC')->get();
		foreach($rows as $row){
			$list[$row->id] = $row->filetype;
		}
		return $list;
	}
 }

?>