<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model {
    use SoftDeletes;

    protected $primaryKey = 'proj_id';
    protected $table = 'project';
    protected $fillable = array (
            'proj_code', 'proj_unicode', 'customer_name', 'customer_id', 'company_id', 'proj_name', 
            'parent_proj_id', 'parent_part_id', 'parent_plan_task_id', 'description',
    		'proj_type', 'start_date', 'end_date', 'approval_status', 'proj_status', 'proj_manager', 
    		'proj_manager_id', 'property', 'approval_person', 'approval_comment', 'approval_date', 
    		'suspension_reason','trail_production', 'batch_production','process_trail','mold_sample',
    		'proj_manager','proj_manager_uid', 'member_list','calendar_id','detail_completed'
    );

    protected $guarded = array (
            'proj_id'
    );

    protected $dates = ['deleted_at'];

    public $timestamps = true;


    public static function createProject($array)
    { 
        $id = self::create($array)->proj_id;
        return $id;
    }
	

	public static function updateProject($ary,$where)
	{ 
		$result = self::where($where)->update($ary);
		return $result;
	}


    //openissue下面project项目
    public static function listProjectIssue($company_id,$sourceid)
    { 
        $result = self::select('proj_id','proj_name','proj_code','proj_type',
                        'proj_manager','proj_id as issue_id','has_openissue')
                        ->where(array('company_id'=>$company_id))
                        ->orderBy('proj_id','desc')
                        ->paginate(PAGEROWS);
        return $result;
    }

    
    //公司下面的项目列表
    public static function listProject($company_id)
    { 
        $result = self::select('project.proj_id','project.proj_code','project.customer_name','project.customer_id',
                        'project.proj_name','project.description','project.proj_type','project.start_date','project.end_date',
                        'project.proj_manager','project.updated_at','project.calendar_id','project.approval_status','work_cal.cal_name')
                    ->leftJoin('work_cal','work_cal.cal_id','=','project.calendar_id')
                    ->where(['project.company_id'=>$company_id])
                    ->where('project.proj_status','!=',5)
                    ->orderBy('proj_id','desc')
                    ->paginate(PAGEROWS);
//                    ->get();
        return $result;
    }


    //公司下面的项目列表
    public static function listProjectNew($company_id)
    {
        $result = self::select('project.proj_id','project.proj_code','project.customer_name','project.customer_id',
            'project.proj_name','project.description','project.proj_type','project.start_date','project.end_date',
            'project.proj_manager','project.updated_at','project.calendar_id','project.approval_status','work_cal.cal_name')
            ->leftJoin('work_cal','work_cal.cal_id','=','project.calendar_id')
            ->where(['project.company_id'=>$company_id])
            ->where('project.proj_status','!=',5)
            ->orderBy('proj_id','desc')
            ->get();
        return $result;
    }

    //公司带状态的项目列表
    public static function listApprovalProject($company_id,$approval_status)
    { 
        if(!is_array($approval_status)){$approval_status = [$approval_status];}
        $result = self::select('work_cal.cal_name','work_cal.cal_id','project.proj_id','project.proj_code',
                    'project.customer_name','project.customer_id','project.proj_name','project.description',
                    'project.proj_type','project.start_date','project.end_date','project.proj_manager',
                    'project.updated_at','project.calendar_id','project.approval_status')
                    ->leftJoin('work_cal','work_cal.cal_id','=','project.calendar_id')
                    ->where('project.company_id', $company_id)
                    ->whereIn('project.approval_status',$approval_status)
                    ->orderBy('proj_id','desc')->paginate(PAGEROWS);
        return $result;
    }

    //公司带状态的项目列表
    public static function listApprovalProjectNew($company_id,$approval_status)
    {
        $result = self::select('work_cal.cal_name','work_cal.cal_id','project.proj_id','project.proj_code',
            'project.customer_name','project.customer_id','project.proj_name','project.description',
            'project.proj_type','project.start_date','project.end_date','project.proj_manager',
            'project.updated_at','project.calendar_id','project.approval_status')
            ->leftJoin('work_cal','work_cal.cal_id','=','project.calendar_id')
            ->where(['project.company_id'=>$company_id,'project.approval_status'=>$approval_status])
            ->orderBy('proj_id','desc')->get();
        return $result;
    }

    public static function infoProject($where,$field='proj_id')
    { 
        $result = self::select('*')->where($where)->first();
        return $result;
    }

    //项目编码是否已存在
    public static function exitProject($proj_code)
    { 
        $result = self::select('proj_id')->where(['proj_code'=>$proj_code])->first();
        return $result;
    }

    //除了本身项目编码是否已存在
    public static function exitProjectOther($proj_code,$proj_id)
    { 
        $result = self::select('proj_id')->where(['proj_code'=>$proj_code])->where('proj_id','!=',$proj_id)->first();
        return $result;
    }

    //项目ID是否已存在
    public static function exitProjectId($proj_id)
    { 
        $result = self::select('proj_id')->where(['proj_id'=>$proj_id])->first();
        return $result;
    }

    //删除项目
    public static function deleteProject($where)
    { 
        $affect = self::where($where)->update(['proj_status'=>5]);
        return $affect;
    }

    //批量删除项目
    public static function deleteProjectAry($ary)
    { 
        $affect = self::whereIn('proj_id',$ary)->update(['proj_status'=>5]);
        return $affect;
    }

    //未完成零件的项目
    public static function listVacancyApi($companyid,$page_size,$curr_page)
    {
        $total_count = self::select('proj_id')->where(['company_id'=>$companyid,'detail_completed'=>0])->count();
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['result'] = self::select('proj_id','proj_code','proj_name')
            ->where(['company_id'=>$companyid,'detail_completed'=>0])
            ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        
        return $data;
    }

    //项目总列表
    public static function listProjectApi($companyid,$approval_status,$page_size,$curr_page)
    {
        try{
            if($approval_status != 'all'){
            $total_count = self::select('proj_id')->where(['company_id'=>$companyid])
                                ->where('proj_status','!=',5)
                                ->where('approval_status','=',$approval_status)
                                ->count();
            }else{
            $total_count = self::select('proj_id')->where(['company_id'=>$companyid])
                                ->where('proj_status','!=',5)
                                ->count();
            }
            $data['total_page'] = ceil($total_count/$page_size);//总页面数
            if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
            $size_from = $page_size * ($curr_page - 1);
            if($size_from < 0){ $size_from = 0; }
            
            if($approval_status != 'all'){
                $data['result'] = self::select('proj_id','proj_code','proj_name','approval_status','created_at as time')
                        ->where(['company_id'=>$companyid])
                        ->where('proj_status','!=',5)
                        ->where('approval_status','=',$approval_status)
                        ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();
            }else{
                $data['result'] = self::select('proj_id','proj_code','proj_name','approval_status','created_at as time')
                        ->where(['company_id'=>$companyid])
                        ->where('proj_status','!=',5)
                        ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();
            }

            $data['curr_page'] = $curr_page; //当前页面 
            $data['page_size'] = $page_size; //单页数  
            $data['total_count'] = $total_count; //总条数
            
            return $data;
        }catch(Exception $e){ return 10003; }
    }


    //项目待审批列表
    public static function listPendingApi($companyid,$page_size,$curr_page)
    {
        $total_count = self::select('proj_id')->where(['company_id'=>$companyid,'approval_status'=>2])
            ->where('proj_status','!=',5)->count();
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['result'] = self::select('proj_id','approval_status','proj_code','proj_name','created_at as time')
            ->where(['company_id'=>$companyid,'approval_status'=>2])
            ->where('proj_status','!=',5)
            ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

        $data['curr_page'] = $curr_page; //当前页面 
            $data['page_size'] = $page_size; //单页数  
            $data['total_count'] = $total_count; //总条数
        
        return $data;
    }


    //项目已审批列表
    public static function listApprovedApi($company_id,$page_size,$curr_page)
    {
        $total_count = self::select('proj_id')
            ->where(function($query) use($company_id){
                $query->where('proj_status','!=',5)->where(['company_id'=>$company_id,'approval_status'=>3]);
            })->orWhere(function($query) use($company_id){
                $query->where('proj_status','!=',5)->where(['company_id'=>$company_id,'approval_status'=>4]);
            })->count();
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['result'] = self::select('proj_id','proj_code','approval_status','proj_name','created_at as time')
            ->where(function($query) use($company_id){
                $query->where('proj_status','!=',5)->where(['company_id'=>$company_id,'approval_status'=>3]);
            })->orWhere(function($query) use($company_id){
                $query->where('proj_status','!=',5)->where(['company_id'=>$company_id,'approval_status'=>4]);
            })->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        
        return $data;
    }

    
    //项目分发列表
    public static function listSendApi($companyid,$page_size,$curr_page)
    {
        $total_count = self::select('proj_id')
            ->Where(['project.company_id'=>$companyid,'project.property'=>0])
            ->where('proj_status','!=',5)
            ->rightJoin('project_detail as detail','detail.proj_id','=','project.proj_id')->count();
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['result'] = self::select('detail.proj_id','detail.id as part_id','detail.part_name',
                'project.proj_name','detail.relation','detail.assigned_supplier','detail.supplier_name')
            ->where('proj_status','!=',5)
            ->Where(['project.company_id'=>$companyid,'project.property'=>1])
            ->rightJoin('project_detail as detail','detail.proj_id','=','project.proj_id')
            ->leftJoin('project_relation as relation','relation.part_id','=','detail.id')
            ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        
        return $data;
    }


    //项目关联列表
    public static function listRelationApi($companyid,$page_size,$curr_page)
    {
        $total_count = DB::table('project_relation as relation')->select('relation.proj_id')
            ->where('proj_status','!=',5)
            ->Where(['relation.assigned_supplier'=>$companyid,'relation.send_status'=>1])
            ->leftJoin('project_detail as detail','detail.id','=','relation.part_id')
            ->leftJoin('project','project.proj_id','=','relation.proj_id')
            ->count();
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        $data['result'] = DB::table('project_relation as relation')->select('relation.company_id as assigned_distribute',
            'relation.part_id','relation.proj_id','relation.accept_status as relation','relation.send_at as time')
            ->where('proj_status','!=',5)
            ->Where(['relation.assigned_supplier'=>$companyid,'relation.send_status'=>1])
            ->leftJoin('project_detail as detail','detail.id','=','relation.part_id')
            ->leftJoin('project','project.proj_id','=','relation.proj_id')
            ->orderBy('proj_id','desc')->offset($size_from)->limit($page_size)->get();

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        
        return $data;
    }


    //删除项目
    public static function deleteProjectApi($proj_id)
    {
        try{
            $project = self::select('project_id')->join('plan','plan.project_id','=','project.proj_id')
                            ->where(['project.proj_id'=>$proj_id,'project.approval_status'=>3])
                            ->orWhere(['project.proj_id'=>$proj_id,'project.approval_status'=>4])
                            ->first();
            if(!empty($project)){ 
                $result = self::where(['proj_id'=>$proj_id])->update(['project.proj_status'=>5]);
            }                            
            return $result;
        }catch(Exception $e){ return 10003; }
    }

    
    //获取项目基础信息
    public static function getBaseinfoApi($proj_id)
    {
        $result = self::select('batch_production','calendar_id','customer_name','description','end_date',
                                'member_list as memberid_list','mold_sample','process_trail','proj_manager',
                                'proj_manager_uid','proj_name','proj_type','property','start_date',
                                'trail_production')
                        ->where(['proj_id'=>$proj_id])->where('proj_status','!=',5)->first();
        //公司日历
        if(!empty($result['calendar_id']))
        {
            $result['calendar_name'] = DB::table('work_cal_real')->select('cal_name')
                            ->where(['cal_id'=>$result['calendar_id']])->first()->cal_name;
        }

        //项目成员
        if(!empty($result['memberid_list'])){
            $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                ->whereRaw("find_in_set(uid,'".$result['memberid_list']."')")->first();
            $result['member_list'] = $str_issuer->fullname;

            $ary_id = explode(',',$result['memberid_list']);
            $ary_member = explode(',',$result['member_list']);
            $ary_member_list = array_combine($ary_id,$ary_member);
            foreach ($ary_member_list as $key => $value) {
                $ary_chunk_new[$key]['id'] = $key;
                $ary_chunk_new[$key]['name'] = $value;
            }
            $ary_chunk_new = array_values($ary_chunk_new);

            $result['ary_member_list'] = $ary_chunk_new;
        }

        return $result; 
    }

    //修改立项基础信息
    public static function updateBaseinfoApi($proj_id,$company_id,$ary)
    {
        $result = self::where(['proj_id'=>$proj_id,'company_id'=>$company_id])->update($ary);
        return $result;
    }


    //新建立项基础信息
    public static function createBaseInfo($ary)
    {
        $result = self::create($ary);
        return $result;
    }


    
    //项目提交审批
    public static function updateApprovalApi($proj_id)
    {
        $result = self::where(['proj_id'=>$proj_id,'approval_status'=>1])->where('proj_status','!=',5)->update(['approval_status'=>2]);
        return $result;
    }


    //提交项目审批结果
    public static function updateApprovalResultApi($proj_id,$approval_status,$approval_comment='',$uid,$time)
    { 
        $result = self::where(['proj_id'=>$proj_id,'approval_status'=>2])
                ->update(['approval_status'=>$approval_status,
                    'approval_comment'=>$approval_comment,
                    'approval_person'=>$uid,
                    'approval_date'=>$time]);
        return $result;
    }

    //获取项目(零件)文档列表
    public static function listDocumentApi($proj_id)
    { 
        try{
            $file = self::select('work_file')->where(['proj_id'=>$proj_id])->first();
            //var_dump($file);
            if(!$file){
                $str_file = $file->work_file;
                $ary_file = explode(',',$str_file);
                foreach ($ary_file as $key => $value){
                    //$suffix = substr(strrchr($value,'.'),1);
                    if(!empty($value)){
                        $result[$key]['file'] = 'http://test.mowork.cn/uploads/common/'.$value;
                    }
                }
            }else{ 
                $result = array();
            }
            return $result;
        }catch(Exception $e){ return 10003; }
    }


    //提交项目审批结果
    public static function updateProjectCompletedApi($proj_id)
    { 
        try{
            $result = self::where(['proj_id'=>$proj_id,'detail_completed'=>0])->where('proj_status','!=',5)
                    ->update(['detail_completed'=>1]);
            return $result;
        }catch(Exception $e){ return 10003; }
    }


    
}

?>
