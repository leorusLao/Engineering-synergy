<?php
  
namespace App\Http\Controllers;
use App;
use Illuminate\Support\Facades\Log;
use function Psy\debug;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\Company;
use App\Models\Node;
use App\Models\Project;
use App\Models\Plan;
use App\Models\PlanType;
use App\Models\ProjectDetail;
use App\Models\Folder;
use App\Models\NodeCompany;
use App\Models\NodeFile;
use App\Models\OpenIssueDetail;
use function substr;


class FolderController extends Controller
{
	public function __construct()
	{
		session_start();
		if(Session::has('locale')){
			$this->locale = Session::get('locale');
		}
		else if(isset($_COOKIE['locale'])){
			$this->locale = $_COOKIE['locale'];
		}
		else{
			$this->locale = config('app.locale');
		}
	
	}
	
	public function fileManagement(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.part_file');
			
		$rows = ProjectDetail::join('project','project.proj_id','=','project_detail.proj_id')
		->where('project_detail.company_id',$company_id)->orderBy('project.proj_id','desc')
		->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
	 	 
		return view('dailywork.file-management',array('cookieTrail' => $cookieTrail, 'salt' => $salt, 'rows' => $rows,  'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
	
	}
	
	public function fileMaintenance(Request $request, $token, $detail_id)
	{   
		//零件文档维护
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.part_file_maintenance');
	  
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$detail_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/file-management')->with('result', Lang::get('mowork.operation_disallowed'));
		}
 
		$row = ProjectDetail::where(array('id' => $detail_id, 'company_id' => $company_id))->first();
		
	    if($request->has('submit')) {
	    	 
	    	if(isset($_SESSION['FileNames'])){
	    		 
	    		$category = $request->get('folder_cat');//2 零件文档
	    		//remove duplicated files
	    		$files = array_unique($_SESSION['FileNames']);
	    		//$st = Storage::disk('company')->get('license/file.txt');
	    	 
	    		if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
	    			Storage::disk('company')->makeDirectory($company_id);
	    		}
	    		
	    		if(! Storage::disk('company')->exists($company_id.'/project/'.$row->proj_id)){//create project dir for proj_id if non-existance
	    			Storage::disk('company')->makeDirectory($company_id.'/project/'.$row->proj_id);
	    		}
	    		 
	    		if(! Storage::disk('company')->exists($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id)){
	    			//create part dir for detail_id if non-existance
	    			Storage::disk('company')->makeDirectory($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id);
	    			Folder::create(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
	    					'title' => 'part' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0, 'fullpath' => 'company/'.$company_id. '/project'.$row->proj_id.'/part/'.$detail_id ,
	    					'company_id' => $company_id));
	    		}
	    		
	    		 
	    		//Storage::disk('company')->put('license/file.txt', 'test Contents');
	    		//$directories = Storage::disk('company')->directories($company_id.'/'.$row->proj_id.'/'.$detail_id);
	    		$fd = Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category))->first();
	    	    
	    		foreach ($files as $file) {
	    			//take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload 
	    			$original = substr($file, strlen(session_id()));
	    			 
	    			File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/project/'.$row->proj_id.'/part/'
	    					.$detail_id.'/'.$original);
	    			$size = Storage::disk('company')->size($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id.'/'.$original);
	    			
	    			$found = Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
	    					'title' => $original))->first();//find a file with the same name
	    			
	    			if($found){
	    				Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
	    					 'title' => $original))->update(array(
	    					 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1, 
	    					 'fullpath' => $company_id.'/project/'.$row->proj_id.'/part/'.$detail_id,
	    					 'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
	    			} else {
	    			
	    			   Folder::create(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
	    					'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1, 
	    			   		'fullpath' => $company_id.'/project/'.$row->proj_id.'/part/'.$detail_id,
	    					'filename' => $original,'version' => 1, 'company_id' => $company_id));
	    			}
	    			File::delete(storage_path().'/tmp/'.$file);
	    		}
	    		 
	    	   unset($_SESSION['FileNames']);
	    	   unset($_SESSION['NumOfFiles']);
	    	}
	    	else {
	    		return Redirect::back()->with('result', Lang::get('mowork.file_required'));
	    	}
	    	 
	    }
		
	    $basinfo = ProjectDetail::where('id',$detail_id)->first(); 
	    $rows = Folder::where(array('proj_detail_id' => $detail_id))
	            ->orderBy('category_id')->orderBy('id')->paginate(PAGEROWS);
	    
	    $salt = $company_id.$this->salt.$uid;
		return view('dailywork.file-maintenance',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'basinfo' => $basinfo,
				'token' => $token, 'salt' => $salt, 'detail_id' => $detail_id, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
	
	}

    //新增零件文档
    public function addDetailFile(Request $request, $detail_id)
    {
        $company_id = Session::get('USERINFO')->companyId;
        $uid = Session::get('USERINFO')->userId;

        $row = ProjectDetail::where(array('id' => $detail_id, 'company_id' => $company_id))->first();


        if(isset($_SESSION['FileNames'])){
            $category = $request->get('folder_cat');//2 零件文档
            //remove duplicated files
            $files = array_unique($_SESSION['FileNames']);
            //$st = Storage::disk('company')->get('license/file.txt');
            if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
                Storage::disk('company')->makeDirectory($company_id);
            }
            if(! Storage::disk('company')->exists($company_id.'/project/'.$row->proj_id)){//create project dir for proj_id if non-existance
                Storage::disk('company')->makeDirectory($company_id.'/project/'.$row->proj_id);
            }
            if(! Storage::disk('company')->exists($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id)){
                //create part dir for detail_id if non-existance
                Storage::disk('company')->makeDirectory($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id);
                Folder::create(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
                    'title' => 'part' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0, 'fullpath' => 'company/'.$company_id. '/project'.$row->proj_id.'/part/'.$detail_id ,
                    'company_id' => $company_id));
            }
            //Storage::disk('company')->put('license/file.txt', 'test Contents');
            //$directories = Storage::disk('company')->directories($company_id.'/'.$row->proj_id.'/'.$detail_id);
            $fd = Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category))->first();
            foreach ($files as $file) {
                //take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload
                $original = substr($file, strlen(session_id()));
                File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/project/'.$row->proj_id.'/part/'
                    .$detail_id.'/'.$original);
                $size = Storage::disk('company')->size($company_id.'/project/'.$row->proj_id.'/part/'.$detail_id.'/'.$original);

                $found = Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
                    'title' => $original))->first();//find a file with the same name
                if($found){
                    Folder::where(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
                        'title' => $original))->update(array(
                        'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                        'fullpath' => $company_id.'/project/'.$row->proj_id.'/part/'.$detail_id,
                        'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
                } else {
                    Folder::create(array('proj_id' => $row->proj_id, 'proj_detail_id' => $detail_id, 'category_id' => $category,
                        'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                        'fullpath' => $company_id.'/project/'.$row->proj_id.'/part/'.$detail_id,
                        'filename' => $original,'version' => 1, 'company_id' => $company_id));
                }
                File::delete(storage_path().'/tmp/'.$file);
            }
            unset($_SESSION['FileNames']);
            unset($_SESSION['NumOfFiles']);
        }
        else {
            return Redirect::back()->with('result', Lang::get('mowork.file_required'));
        }
    }
	
	public function projectFileMaintenance(Request $request, $token, $project_id)
	{
		//项目文档维护
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.project_file_maintenance');

		//token比对逻辑
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$project_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/file-management')->with('result', Lang::get('mowork.operation_disallowed'));
		}

		//数据库查找project_id
		$row = Project::where(array('proj_id' => $project_id, 'company_id' => $company_id))->first();

		if($request->has('submit')) {
			if(isset($_SESSION['FileNames'])){
				//remove duplicated files
				$files = array_unique($_SESSION['FileNames']);
				//$st = Storage::disk('company')->get('license/file.txt');

				 //有没有此公司，没有则创建此公司文件夹
				if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id);
				}

                //有没有此公司下此项目，没有则创建此公司下此项目文件夹
				if(! Storage::disk('company')->exists($company_id.'/project/'.$project_id)){//create project ... dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id.'/project/'.$project_id);
					//创建此文件
					Folder::create(array('proj_id' => $project_id , 'category_id' => 1,
							'title' => 'project' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0,
							'fullpath' => 'company/'.$company_id.'/project/'.$project_id,
							'company_id' => $company_id));//项目目录
				}

				//查找此公司下此项目下的目录
				$fd = Folder::where(array('proj_id' => $project_id, 'category_id' => '1'))->first();


				foreach ($files as $file) {
					//take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload
					$original = substr($file, strlen(session_id()));
					File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/project/'.$project_id.'/'.$original);
					$size = Storage::disk('company')->size($company_id.'/project/'.$project_id.'/'.$original);
					$found = Folder::where(array('proj_id' => $project_id,  'category_id' => 1,
							'title' => $original))->first();//find a file with the same name
					if($found){
						Folder::where(array('proj_id' => $project_id,   'category_id' => 1,
								'title' => $original))->update(array(
										'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
										'fullpath' =>  $company_id.'/project/'.$project_id ,
										'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
					} else {
						Folder::create(array('proj_id' => $project_id, 'category_id' => 1,
								'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
								'fullpath' =>  $company_id.'/project/'.$project_id ,
								'filename' => $original,'version' => 1, 'company_id' => $company_id));
					}
					File::delete(storage_path().'/tmp/'.$file);
				}
				unset($_SESSION['FileNames']);
				unset($_SESSION['NumOfFiles']);
			}
			else {
				return Redirect::back()->with('result', Lang::get('mowork.file_required'));
			}
		}

		$basinfo = Project::where('proj_id',$project_id)->first();
		$rows = Folder::where(array('folder.proj_id' => $project_id))
				->leftJoin('project_detail','project_detail.id','=','folder.proj_detail_id')
				->select('folder.*','project_detail.part_name')
		        ->orderBy('id')->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.project-file-maintenance',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'basinfo' => $basinfo, 'token' => $token, 'salt' => $salt, 'project_id' => $project_id, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
	}

    //新增项目文档
    public function addFile(Request $request, $project_id)
    {
        $company_id = Session::get('USERINFO')->companyId;
        if(isset($_SESSION['FileNames'])){
            //删除重复的文件
            $files = array_unique($_SESSION['FileNames']);
//            Log::debug($files);
            //有没有此公司，没有则创建此公司文件夹
            if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
                Storage::disk('company')->makeDirectory($company_id);
            }
            //有没有此公司下此项目，没有则创建此公司下此项目文件夹
            if(! Storage::disk('company')->exists($company_id.'/project/'.$project_id)){//create project ... dir for $company_id if non-existance
                Storage::disk('company')->makeDirectory($company_id.'/project/'.$project_id);
                //创建此文件
                Folder::create(array('proj_id' => $project_id , 'category_id' => 1,
                    'title' => 'project' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0,
                    'fullpath' => 'company/'.$company_id.'/project/'.$project_id,
                    'company_id' => $company_id));//项目目录
            }

            foreach ($files as $file) {
                $original = substr($file, strlen(session_id()));
//                Log::debug($original);

                if(substr($original,0,3) == 'C18'){//零件的文档新增
                    $originalname = substr($original,11);
//                    Log::debug($originalname);

                    $detail_id = substr($original , 0 , 11);
//                    Log::debug($detail_id);

                    $detail_id = substr($detail_id,8);
//                    Log::debug($detail_id);

                    if(! Storage::disk('company')->exists($company_id.'/project/'.$project_id.'/part/'.$detail_id)){
                        Storage::disk('company')->makeDirectory($company_id.'/project/'.$project_id.'/part/'.$detail_id);
                        Folder::create(array('proj_id' => $project_id, 'proj_detail_id' => $detail_id, 'category_id' => 2,
                            'title' => 'part' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0, 'fullpath' => 'company/'.$company_id. '/project'.$project_id.'/part/'.$detail_id ,
                            'company_id' => $company_id));
                    }
                    $fd = Folder::where(array('proj_id' => $project_id, 'proj_detail_id' => $detail_id, 'category_id' => 2))->first();
                    File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/project/'.$project_id.'/part/'
                        .$detail_id.'/'.$originalname);
                    $size = Storage::disk('company')->size($company_id.'/project/'.$project_id.'/part/'.$detail_id.'/'.$originalname);
                    $found = Folder::where(array('proj_id' => $project_id, 'proj_detail_id' => $detail_id, 'category_id' => 2,
                        'title' => $originalname))->first();//find a file with the same name
                    if($found){
                        Folder::where(array('proj_id' => $project_id, 'proj_detail_id' => $detail_id, 'category_id' => 2,
                            'title' => $originalname))->update(array(
                            'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' => $company_id.'/project/'.$project_id.'/part/'.$detail_id,
                            'filename' => $originalname,'version' => $found->version + 1, 'company_id' => $company_id));
                    } else {
                        Folder::create(array('proj_id' => $project_id, 'proj_detail_id' => $detail_id, 'category_id' => 2,
                            'title' => $originalname , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' => $company_id.'/project/'.$project_id.'/part/'.$detail_id,
                            'filename' => $originalname,'version' => 1, 'company_id' => $company_id));
                    }
                    File::delete(storage_path().'/tmp/'.$file);
                }elseif(substr($original,0,3) == 'P18'){//计划的文档新增

                    $originalname = substr($original,11);
//                    Log::debug($originalname);

                    $plan_id = substr($original , 0 , 11);
//                    Log::debug($plan_id);

                    $plan_id = substr($plan_id,8);
//                    Log::debug($plan_id);

                    if(! Storage::disk('company')->exists($company_id.'/plan/'.$plan_id)){
                        Storage::disk('company')->makeDirectory($company_id.'/plan/'.$plan_id);
                        Folder::create(array('proj_id' => $plan_id , 'category_id' =>3,
                            'title' => 'plan' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0,
                            'fullpath' => 'company/'.$company_id.'/plan/'.$plan_id,
                            'company_id' => $company_id));
                    }
                    $fd = Folder::where(array('proj_id' => $plan_id, 'category_id' => '3'))->first();
                    File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/plan/'.$plan_id.'/'.$originalname);
                    $size = Storage::disk('company')->size($company_id.'/plan/'.$plan_id.'/'.$originalname);
                    $found = Folder::where(array('proj_id' => $plan_id,  'category_id' => 3,
                        'title' => $originalname))->first();//find a file with the same name
                    if($found){
                        Folder::where(array('proj_id' => $plan_id,   'category_id' => 3,
                            'title' => $originalname))->update(array(
                            'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' =>  $company_id.'/plan/'.$plan_id ,
                            'filename' => $originalname,'version' => $found->version + 1, 'company_id' => $company_id));
                    } else {
                        Folder::create(array('proj_id' => $plan_id, 'category_id' => 3,
                            'title' => $originalname , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' =>  $company_id.'/plan/'.$plan_id,
                            'filename' => $originalname,'version' => 1, 'company_id' => $company_id));
                    }
                    File::delete(storage_path().'/tmp/'.$file);
                }else{
//                    Log::debug("进入了项目类型");
                    $fd = Folder::where(array('proj_id' => $project_id, 'category_id' => '1'))->first();
                    //项目的文档新增
                    File::move( storage_path().'/tmp/'.$file, public_path().'/company/'.$company_id.'/project/'.$project_id.'/'.$original);
                    $size = Storage::disk('company')->size($company_id.'/project/'.$project_id.'/'.$original);
                    $found = Folder::where(array('proj_id' => $project_id,  'category_id' => 1,
                        'title' => $original))->first();//find a file with the same name
                    if($found){
                        Folder::where(array('proj_id' => $project_id,   'category_id' => 1,
                            'title' => $original))->update(array(
                            'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' =>  $company_id.'/project/'.$project_id ,
                            'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
                    } else {
                        Folder::create(array('proj_id' => $project_id, 'category_id' => 1,
                            'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
                            'fullpath' =>  $company_id.'/project/'.$project_id ,
                            'filename' => $original,'version' => 1, 'company_id' => $company_id));
                    }
                    File::delete(storage_path().'/tmp/'.$file);
                }
            }
            unset($_SESSION['FileNames']);
            unset($_SESSION['NumOfFiles']);
            return response()->json(array('code'=>1,'msg'=>"新增项目文档成功！"));
        }
        else {
            return response()->json(array('code'=>2,'msg'=>LANG::get('mowork.file_required')));
        }
    }


	public function planInstructionDocument(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_file');
		 
		$rows = ProjectDetail::join('project','project.proj_id','=','project_detail.proj_id')
		->leftJoin('plan','plan.project_detail_id','=','project_detail.id')
		->join('work_cal','work_cal.cal_id','=','project.calendar_id')
		->where('project_detail.company_id',$company_id)->orderBy('project.proj_id','desc')
		->paginate(PAGEROWS);
	 	 
		$plantypes = PlanType::whereRaw('company_id = 0 OR company_id = '. $company_id )->get();
		
		$salt = $company_id.$this->salt.$uid;
		
		return view('dailywork.plan-document',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 
				'plantypes' => $plantypes, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
 		
	}
	
	public function planFileMaintenance(Request $request, $token, $plan_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.plan_file_maintenance');
			
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$plan_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/file-management')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		if($request->has('submit')) {
		
			if(isset($_SESSION['FileNames'])){
		
				//remove duplicated files
				$files = array_unique($_SESSION['FileNames']);
				//$st = Storage::disk('company')->get('license/file.txt');
					
				if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id);
				}
					
				if(! Storage::disk('company')->exists($company_id.'/plan/'.$plan_id)){//create plan ... dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id.'/plan/'.$plan_id);
					Folder::create(array('proj_id' => $plan_id , 'category_id' =>3,
							'title' => 'plan' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0,
							'fullpath' => 'company/'.$company_id.'/plan/'.$plan_id,
							'company_id' => $company_id));//Plan目录
				}
				 
				$fd = Folder::where(array('proj_id' => $plan_id, 'category_id' => '3'))->first();
		
				foreach ($files as $file) {
					//take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload
					$original = substr($file, strlen(session_id()));
		
					File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/plan/'.$plan_id.'/'.$original);
					$size = Storage::disk('company')->size($company_id.'/plan/'.$plan_id.'/'.$original);
		
					$found = Folder::where(array('proj_id' => $plan_id,  'category_id' => 3,
							'title' => $original))->first();//find a file with the same name
		
					if($found){
						Folder::where(array('proj_id' => $plan_id,   'category_id' => 3,
								'title' => $original))->update(array(
										'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
										'fullpath' =>  $company_id.'/plan/'.$plan_id ,
										'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
					} else {
		
						Folder::create(array('proj_id' => $plan_id, 'category_id' => 3,
								'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
								'fullpath' =>  $company_id.'/plan/'.$plan_id,
								'filename' => $original,'version' => 1, 'company_id' => $company_id));
					}
					File::delete(storage_path().'/tmp/'.$file);
				}
		
				unset($_SESSION['FileNames']);
				unset($_SESSION['NumOfFiles']);
			}
			else {
				return Redirect::back()->with('result', Lang::get('mowork.file_required'));
			}
		
		}
		 
		$basinfo = Plan::join('project','project.proj_id','=','plan.project_id')
		->where(array('plan.plan_id' => $plan_id, 'plan.company_id' => $company_id))
		->first();
	 	
		$rows = Folder::where(array('folder.proj_id' => $plan_id))
		->orderBy('id')->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
		
		return view('dailywork.plan-file-maintenance',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'basinfo' => $basinfo,
				    'plan_id' => $plan_id, 'token' => $token, 'salt' => $salt, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
		
	}
	
	public function issueDocument(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.issue_file');
		  
		$result = OpenIssueDetail::listIssueDetail($company_id);
		$salt = $company_id.$this->salt.$uid;
		
		return view('dailywork.issue-document',array('cookieTrail' => $cookieTrail, 'result' => $result, 
				'salt' => $salt, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
	
	}
	
	public function issueFileMaintenance(Request $request, $token, $issue_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.issue_file_maintenance');
			
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$issue_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/file-management')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		  
		if($request->has('submit')) {
		
			if(isset($_SESSION['FileNames'])){
		
				//remove duplicated files
				$files = array_unique($_SESSION['FileNames']);
				//$st = Storage::disk('company')->get('license/file.txt');
					
				if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id);
				}
					
				if(! Storage::disk('company')->exists($company_id.'/issue/'.$issue_id)){//create issue ... dir for $company_id if non-existance
					Storage::disk('company')->makeDirectory($company_id.'/issue/'.$issue_id);
					Folder::create(array('proj_id' => $issue_id , 'category_id' =>4,
							'title' => 'issue' , 'parent_id' => 0, 'level' => 1,  'attribute' => 0,
							'fullpath' => 'company/'.$company_id.'/issue/'.$issue_id,
							'company_id' => $company_id));//Issue目录
				}
		     
				$fd = Folder::where(array('proj_id' => $issue_id, 'category_id' => '4'))->first();
		
				foreach ($files as $file) {
					//take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload
					$original = substr($file, strlen(session_id()));
		
					File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/issue/'.$issue_id.'/'.$original);
					$size = Storage::disk('company')->size($company_id.'/issue/'.$issue_id.'/'.$original);
		
					$found = Folder::where(array('proj_id' => $issue_id,  'category_id' => 4,
							'title' => $original))->first();//find a file with the same name
		
					if($found){
						Folder::where(array('proj_id' => $issue_id,   'category_id' => 4,
								'title' => $original))->update(array(
										'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
										'fullpath' =>  $company_id.'/project/'.$issue_id ,
										'filename' => $original,'version' => $found->version + 1, 'company_id' => $company_id));
					} else {
		
						Folder::create(array('proj_id' => $issue_id, 'category_id' => 4,
								'title' => $original , 'fsize' => ceil($size /1024.00).'KB' , 'parent_id' => $fd->id, 'level' => 2,  'attribute' => 1,
								'fullpath' =>  $company_id.'/issue/'.$issue_id,
								'filename' => $original,'version' => 1, 'company_id' => $company_id));
					}
					File::delete(storage_path().'/tmp/'.$file);
				}
		
				unset($_SESSION['FileNames']);
				unset($_SESSION['NumOfFiles']);
			}
			else {
				return Redirect::back()->with('result', Lang::get('mowork.file_required'));
			}
		
		}
		
		$basinfo = OpenIssueDetail::join('issue_source','issue_source.id','=','open_issue_detail.source_id')
		->where(array('open_issue_detail.id' => $issue_id, 'open_issue_detail.company_id' => $company_id))
		->select('issue_source.*','open_issue_detail.issue_id as ppm_id')->first();
		 
		$pcode = '';
		if($basinfo->id == 1) {
			$issue_source = Lang::get('mowork.project');
			$project = Project::where('proj_id',$basinfo->ppm_id)->select('proj_code')->first();
		    $pcode = $project->proj_code;
		    
		} else if($basinfo->id == 2) {
			$issue_source = Lang::get('mowork.plan');
			$plan = Plan::where('plan_id',$basinfo->ppm_id)->select('plan_code')->first();
			$pcode = $plan->plan_code;
		} else {
			$issue_source = Lang::get('mowork.management');
		}
		
		$rows = Folder::where(array('folder.proj_id' => $issue_id))	 
		->orderBy('id')->paginate(PAGEROWS);
		$salt = $company_id.$this->salt.$uid;
		
		return view('dailywork.issue-file-maintenance',array('cookieTrail' => $cookieTrail, 'rows' => $rows, 'basinfo' => $basinfo,
				'token' => $token, 'issue_source' => $issue_source,'salt' => $salt, 
				'issue_id' => $issue_id, 'pcode' => $pcode, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));
		
	}
	
	public function fileView(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		$row = Folder::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath.'/'.$row->filename;
		 
		if(File::exists($file)) {
			$mine = File::mimeType($file);
			header("Content-Type: ".$mine);
			//header("Content-Disposition: attachment; filename=$row->filename");
			//header('Pragma: no-cache');
			header('Content-Disposition: inline; filename="' . $row->filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
			@readfile($file);
		} else {
			return Redirect::back()->with('result', Lang::get('mowork.file_nonexistence'));
		}
		
	}
	
	public function fileDelete(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		
		$row = Folder::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath.'/'.$row->filename;
		
		try {
			if(File::exists($file)) {
				File::delete($file);
			}  
			Folder::where(array('id' => $file_id, 'company_id' => $company_id))->delete();
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
	
	}
	
	public function fileDownload(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		$row = Folder::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath.'/'.$row->filename;
		if(File::exists($file)) {
			$mine = File::mimeType($file);
			header("Content-Type: ".$mine);
			header("Content-Disposition: attachment; filename=$row->filename");
			header('Pragma: no-cache');
			readfile($file);
		} else {
			return Redirect::back()->with('result', Lang::get('mowork.file_nonexistence'));
		}
	}
	
	public function folderTree()
	{ 
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		
		$pfolders = Folder::where('parent_id', '=', 0)->paginate(PAGEROWS);
		
		$pickIds = array();
		foreach ($pfolders as $row) {
			$pickIds[] = $row->id;
		}
        
		$pickIds = array_unique($pickIds);
		
		$allfolders = Folder::whereIn('id', $pickIds)->pluck('title','id')->all();

		return view('dailywork.folder-tree',array('pfolders' => $pfolders, 'allfolders' => $allfolders, 'pageTitle' => Lang::get('mowork.signup'),'locale' => $this->locale));

	}
  
	public function nodeInstructionDocument(Request $request)
	{
		//上传node文档
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		 
		if($request->has('submit') && isset($_SESSION['FileNames'])) {
			$files = array_unique($_SESSION['FileNames']);
			$node_id =  $request->get('node_id');
			$nodeCom = NodeCompany::where(array('company_id' => $company_id, 'node_id' => $node_id))->first();
			 
			if(! Storage::disk('company')->exists($company_id)){//create company dir for $company_id if non-existance
				Storage::disk('company')->makeDirectory($company_id);
			}
			
			$nodeNo = $nodeCom->node_no;
			if(! Storage::disk('company')->exists($company_id.'/'.$nodeNo)) {
				//创建node子目录
				Storage::disk('company')->makeDirectory($company_id.'/node/'.$nodeNo);
			}
			
			$files = array_unique($_SESSION['FileNames']);
			
			foreach ($files as $file) {
				//take away session_id() in the filename which just avoid to override files with the same name in tmp folder for different people to upload
				$original = substr($file, strlen(session_id()));
				 
				File::move( storage_path().'/tmp/'.$file, storage_path().'/company/'.$company_id.'/node/'.$nodeNo.'/'.$original);
				$size = Storage::disk('company')->size($company_id.'/node/'.$nodeNo.'/'.$original);
			
			    //check if there is an old file having the same name
				$found = NodeFile::where(array('node_id' => $node_id, 'company_id' => $company_id, 'filename' => $original))->first();//find a file with the same name
			
				if($found){
					Nodefile::where(array('node_id' => $node_id, 'company_id' => $company_id, 'filename' => $original))->
							update(array('fsize' => ceil($size /1024.00).'KB' ,'version' => $found->version + 1));
				} else {
					Nodefile::create(array('node_id' => $node_id, 'node_no' => $nodeNo, 'fullpath' => $company_id.'/node/'.$nodeNo.'/'.$original,
							'filename' => $original, 'fsize' => ceil($size /1024.00).'KB' ,'version' => 1, 'company_id' => $company_id ));
				}
				File::delete(storage_path().'/tmp/'.$file);
			}
			
			unset($_SESSION['FileNames']);
			unset($_SESSION['NumOfFiles']);
		}
		 
		$cookieTrail = Lang::get('mowork.node_file');//必须在基础配置中customize those nodes
		$rows = NodeCompany::where('node_company.company_id',$company_id )->
		        leftJoin('node_file','node_file.node_id','=','node_company.node_id')->orderBy('node_company.node_id','ASC')->
		        select('node_file.*','node_company.node_id','node_company.node_no','node_company.name')->paginate(PAGEROWS);
		
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.node-instruction-document',array('cookieTrail' => $cookieTrail,  'rows' => $rows,  'salt' => $salt,
				'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		 
	}
	
	
	public function nodeFileView(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		$row = NodeFile::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath;
		 
		if(File::exists($file)) {
			$mine = File::mimeType($file);
			header("Content-Type: ".$mine);
			//header("Content-Disposition: attachment; filename=$row->filename");
			//header('Pragma: no-cache');
			header('Content-Disposition: inline; filename="' . $row->filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');
			@readfile($file);
		} else {
			return Redirect::back()->with('result', Lang::get('mowork.file_nonexistence'));
		}
	
	}
	
	public function nodeFileDelete(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		$row = NodeFile::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath;
	
		try {
			if(File::exists($file)) {
				File::delete($file);
			}
			NodeFile::where(array('id' => $file_id, 'company_id' => $company_id))->delete();
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		} catch (\Exception $e) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
	
	}
	
	public function nodeFileDownload(Request $request, $token, $file_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$cookieTrail = Lang::get('mowork.view');
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$file_id);
		if($compToken != $token) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_disallowed'));
		}
		$row = NodeFile::where(array('id' => $file_id, 'company_id' => $company_id))->first();
		$file = storage_path().'/company/'.$row->fullpath;
		
		if(File::exists($file)) {
			$mine = File::mimeType($file);
			header("Content-Type: ".$mine);
			header("Content-Disposition: attachment; filename=$row->filename");
			header('Pragma: no-cache');
			readfile($file);
		} else {
			return Redirect::back()->with('result', Lang::get('mowork.file_nonexistence'));
		}
	}

}