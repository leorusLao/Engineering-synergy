<?php
 
namespace App\Models;

use Eloquent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class Folder extends Eloquent {
	protected $primaryKey = 'id';
	protected $table = 'folder';
	protected $fillable = array ('proj_id',	'proj_detail_id', 'category_id', 'title', 'parent_id',
			'level', 'real_name',  'attribute', 'fullpath',	'filename',	'fsize',
			'version','company_id'
	);

	protected $guarded = array (
			'id'
	);

	public $timestamps = true;
	
	public function childs() {
		return $this->hasMany('App\Models\Folder','parent_id','id') ;
	}

	public static function readExcel($file)
	{
		$reader = Excel::load($file[0]);
		$data = $reader->all();
		$data = json_decode($data, true);
		return $data;
	}

}