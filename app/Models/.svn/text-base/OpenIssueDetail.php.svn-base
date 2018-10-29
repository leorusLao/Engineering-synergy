<?php

namespace App\Models;

use DB;
use Eloquent;
use \Exception;

class OpenIssueDetail extends Eloquent {
    protected $primaryKey = 'id';
    protected $table = 'open_issue_detail';
    protected $fillable = array (
            'issue_id', 'issue_class', 'description', 'solution', 'attached_file', 'department', 'leader', 'plan_complete_date', 'issuer',
            'issue_date', 'real_complete_date', 'company_id','comment','title','is_completed','source_id'
    );
    protected $guarded = array (
            'id'
    );

    public $timestamps = true;

            
    //单个openissue_detail
    public static function infoDetail($where)
    {
        $result = self::select('*')->where($where)->first()->toArray();
        return $result;
    }
        
    //openissue_detail列表
    public static function listDetail($issue_id,$issue_source)
    {
        $result = self::select('open_issue_detail.*','issue_class.name as class_name',
                        'user.fullname as leader_list_name','user_issuer.fullname as issuer_list_name',
                        'department.name as department_list_name')
                        ->where(['issue_id'=>$issue_id,'source_id'=>$issue_source])
                        ->leftJoin('user','user.uid','=','open_issue_detail.leader')
                        ->leftJoin('user as user_issuer','user_issuer.uid','=','open_issue_detail.issuer')
                        ->leftJoin('department','department.dep_id','=','open_issue_detail.department')
                        ->leftJoin('issue_class','open_issue_detail.issue_class','=','issue_class.id')->get();

        if(!$result->isEmpty()){ 
            foreach ($result as $key => $value) {
                //责任人
                if(strpos($value['leader'],',')>0){
                    $result[$key]['leader_list_name'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                                    ->whereRaw("find_in_set(uid,'".$value['leader']."')")->first()->fullname;
                }
                //提出人
                if(strpos($value['issuer'],',')>0){
                    $result[$key]['issuer_list_name'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                                    ->whereRaw("find_in_set(uid,'".$value['issuer']."')")->first()->fullname;
                }
                //部门
                if(strpos($value['department'],',')>0){
                    $result[$key]['department_list_name'] = Department::select(DB::raw("group_concat(name) as fullname"))
                                    ->whereRaw("find_in_set(dep_id,'".$value['department']."')")->first()->fullname;
                }
            }
        }
        return $result;
    }

    //openissue审批列表
    public static function listApprove($company_id)
    { 
        $result = self::select('open_issue.*','open_issue_detail.id as detail_id','open_issue_detail.plan_complete_date',
                        'open_issue_detail.real_complete_date','open_issue_detail.issuer','open_issue_detail.issue_date',
                        'open_issue_detail.issue_class','open_issue_detail.solution','open_issue_detail.title',
                        'open_issue_detail.leader','open_issue_detail.department','issue_source.code')
                        ->where(['open_issue_detail.company_id'=>$company_id,'open_issue.is_approved'=>0])
                        ->leftJoin('open_issue','open_issue.issue_id','=','open_issue_detail.issue_id')
                        ->leftJoin('issue_source','open_issue.issue_id','=','issue_source.id')->get();

        if(!empty($result)){ 
            foreach ($result as $key => $value) {
                $result[$key]['class_name'] = '';
                $str_department = '';
                $str_leader = '';
                $str_issuer = '';

                //issue类型
                $res_class = DB::table('issue_class')->select('name')
                                ->where(array('id'=>$value['issue_class']))->first();
                if(!empty($res_class->name)){ $result[$key]['class_name'] = $res_class->name; }

                //部门
                $ary_department = explode(',',$value['department']);
                foreach ($ary_department as $key_member => $value_member) {
                    $department = Department::infoDepartment(array('dep_id'=>$value_member),'name');
                    if(!empty($department)){ $str_department = $str_department.$department->name.' ';}
                }                               
                if(!empty($str_department)){ $result[$key]['dep_name'] = mb_substr($str_department,0,-1);}
                $str_department = '';
                              
                //责任人
                $ary_leader = explode(',',$value['leader']);
                foreach ($ary_leader as $key_leader => $value_leader) {
                    $user = User::infoUser(array('uid'=>$value_leader),'fullname');
                    if(!empty($user)){ $str_leader = $str_leader.$user->fullname.' ';}
                }                               
                if(!empty($str_leader)){ $result[$key]['str_leader'] = mb_substr($str_leader,0,-1);}
                $str_leader = '';
                                  
                //提出人   
                $ary_issuer = explode(',',$value['issuer']);
                foreach ($ary_issuer as $key_issuer => $value_issuer) {
                    $user = User::infoUser(array('uid'=>$value_issuer),'fullname');
                    if(!empty($user)){ $str_issuer = $str_issuer.$user->fullname.' ';}
                }                               
                if(!empty($str_issuer)){ $result[$key]['str_issuer'] = mb_substr($str_issuer,0,-1);}
                $str_issuer = '';       

            }
        }
        return $result;
    }

    public static function listIssueToBeApproved($company_id) {
    	$result = self::select('open_issue_detail.id as detail_id','open_issue_detail.plan_complete_date',
                        'open_issue_detail.real_complete_date','open_issue_detail.issuer','open_issue_detail.issue_date',
                        'open_issue_detail.issue_class','issue_class.name as class_name','open_issue_detail.solution',
                        'open_issue_detail.title','department.name as dep_name','user.fullname as str_leader',
                        'open_issue_detail.leader','open_issue_detail.issue_id','open_issue_detail.department',
    			        'issue_source.code','issue_source.name','open_issue_detail.source_id','user2.fullname as str_issuer')
                        ->where(['open_issue_detail.company_id'=>$company_id, 'is_approved' => 0])
                        ->leftJoin('issue_source','open_issue_detail.source_id','=','issue_source.id')
                        ->leftJoin('issue_class','issue_class.id','=','open_issue_detail.issue_class')
                        ->leftJoin('department','department.dep_id','=','open_issue_detail.department')
                        ->leftJoin('user','user.uid','=','open_issue_detail.leader')
                        ->leftJoin('user as user2','user2.uid','=','open_issue_detail.issuer')
                        //->whereRaw("find_in_set(department.dep_id,'".$value['department']."')")
                        ->orderBy('open_issue_detail.id','desc')
                        ->paginate(PAGEROWS);
                        if(!empty($result)){
                        	foreach ($result as $key => $value) {
                        		$str_leader = '';
                        		$str_issuer = '';
                        		if($value['code']=='Project'){
                        			$result[$key]['proj_id'] = $value['issue_id'];
                        			$res_proj = Project::infoProject(['proj_id'=>$value['issue_id']],'proj_name');
                        			if(!empty($res_proj)){
                        				$result[$key]['proj_name'] = $res_proj->proj_name;
                        				$result[$key]['proj_code'] = $res_proj->proj_code;
                        			}
                        		}else if($value['code']=='Plan'){
                        			$result[$key]['plan_id'] = $value['issue_id'];
                        			$res_plan = Plan::infoPlan(['plan_id'=>$value['issue_id']],'plan_name');
                        			if(!empty($res_plan)){
                        				$result[$key]['plan_name'] = $res_plan->plan_name;
                        				$result[$key]['plan_code'] = $res_plan->plan_code;
                        			}
                        		}
                        
                        		//有多部门再查询一次
                        		if(strpos($value['department'],',')>0){
                        			$result[$key]['dep_name'] = Department::select(DB::raw("group_concat(name) as dep_name"))
                        			->whereRaw("find_in_set(dep_id,'".$value['department']."')")->first()->dep_name;
                        		}
                        
                        		//责任人
                        		if(strpos($value['leader'],',')>0){
                        			$result[$key]['str_leader'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                        			->whereRaw("find_in_set(uid,'".$value['leader']."')")->first()->fullname;
                        		}
                        
                        		//提出人
                        		if(strpos($value['issuer'],',')>0){
                        			$result[$key]['str_issuer'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                        			->whereRaw("find_in_set(uid,'".$value['issuer']."')")->first()->fullname;
                        		}
                        
                        	}
                        }
    	
    	return $result;
    }
    //openissue列表
    public static function listIssueDetail($company_id)
    { 
        $result = self::select('open_issue_detail.id as detail_id','open_issue_detail.plan_complete_date',
                        'open_issue_detail.real_complete_date','open_issue_detail.issuer','open_issue_detail.issue_date',
                        'open_issue_detail.issue_class','issue_class.name as class_name','open_issue_detail.solution',
                        'open_issue_detail.title','department.name as dep_name','user.fullname as str_leader',
                        'open_issue_detail.leader','open_issue_detail.issue_id','open_issue_detail.department',
                        'issue_source.code','issue_source.name','open_issue_detail.source_id','user2.fullname as str_issuer')
                        ->where(['open_issue_detail.company_id'=>$company_id])
                        ->leftJoin('issue_source','open_issue_detail.source_id','=','issue_source.id')
                        ->leftJoin('issue_class','issue_class.id','=','open_issue_detail.issue_class')
                        ->leftJoin('department','department.dep_id','=','open_issue_detail.department')
                        ->leftJoin('user','user.uid','=','open_issue_detail.leader')
                        ->leftJoin('user as user2','user2.uid','=','open_issue_detail.issuer')
                        //->whereRaw("find_in_set(department.dep_id,'".$value['department']."')")
                        ->orderBy('open_issue_detail.id','desc')
                        ->paginate(PAGEROWS);

        if(!empty($result)){ 
            foreach ($result as $key => $value) {
                $str_leader = '';
                $str_issuer = '';
                if($value['code']=='Project'){ 
                    $result[$key]['proj_id'] = $value['issue_id'];
                    $res_proj = Project::infoProject(['proj_id'=>$value['issue_id']],'proj_name');
                    if(!empty($res_proj)){
                        $result[$key]['proj_name'] = $res_proj->proj_name;
                        $result[$key]['proj_code'] = $res_proj->proj_code;
                    }
                }else if($value['code']=='Plan'){ 
                    $result[$key]['plan_id'] = $value['issue_id'];
                    $res_plan = Plan::infoPlan(['plan_id'=>$value['issue_id']],'plan_name');
                    if(!empty($res_plan)){
                        $result[$key]['plan_name'] = $res_plan->plan_name;
                        $result[$key]['plan_code'] = $res_plan->plan_code;
                    }
                }

                //有多部门再查询一次
                if(strpos($value['department'],',')>0){
                    $result[$key]['dep_name'] = Department::select(DB::raw("group_concat(name) as dep_name"))
                                ->whereRaw("find_in_set(dep_id,'".$value['department']."')")->first()->dep_name;
                }

                //责任人
                if(strpos($value['leader'],',')>0){
                    $result[$key]['str_leader'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                                ->whereRaw("find_in_set(uid,'".$value['leader']."')")->first()->fullname;
                }
                                  
                //提出人
                if(strpos($value['issuer'],',')>0){
                    $result[$key]['str_issuer'] = User::select(DB::raw("group_concat(fullname) as fullname"))
                                ->whereRaw("find_in_set(uid,'".$value['issuer']."')")->first()->fullname;
                }     

            }
        }
        return $result;
    }


    //获取公司OI列表(未提交审批)
    public static function list_issue_unsubmitapi($company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>0,
                                'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get();
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>0,
                                'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name',
                                'detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>0])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>0])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>0])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ 
                                    $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 0;
                                }
                            }
                        }

                        $res_source['list_issue'.$i]['cont'] = $res_new;
                        $res_source['list_issue'.$i]['total_name'] = $res_new[0]->title;
                        $res_source['list_issue'.$i]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }


    //获取公司OI列表(待审批)
    public static function list_issue_submitapi($company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>0,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get();
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>0,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name',
                                'detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>0])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>0])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>0])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ 
                                    $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 1;
                                }
                            }
                        }

                        $res_source['list_issue'.$i]['cont'] = $res_new;
                        $res_source['list_issue'.$i]['total_name'] = $res_new[0]->title;
                        $res_source['list_issue'.$i]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }


    //获取公司OI列表(审批通过)
    public static function list_issue_passapi($company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>1,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get();
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>1,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name',
                                'detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>1])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>1])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>1])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 2;
                                }
                            }
                        }

                        $res_source['list_issue'.$i]['cont'] = $res_new;
                        $res_source['list_issue'.$i]['total_name'] = $res_new[0]->title;
                        $res_source['list_issue'.$i]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }


    //获取公司OI列表(审批拒绝)
    public static function list_issue_refuseapi($company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>2,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get();
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.submit_approval'=>1,
                                'open_issue_detail.is_approved'=>2,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name',
                                'detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,'detail.is_approved'=>2])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,                                'detail.is_approved'=>2])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0,'detail.submit_approval'=>1,                                'detail.is_approved'=>2])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 3;
                                }
                            }
                        }

                        $res_source['list_issue'.$i]['cont'] = $res_new;
                        $res_source['list_issue'.$i]['total_name'] = $res_new[0]->title;
                        $res_source['list_issue'.$i]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }

    //公司openissue列表
    public static function listIssueApi($company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get();
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','source.name','detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 4;
                                }
                            }
                        }

                        $res_source[]['cont'] = $res_new;
                        $res_source[$key]['total_name'] = $res_new[0]->title;
                        $res_source[$key]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }

    //已审批OPENISSUE列表
    public static function listApprovedApi($uid,$company_id,$page_size,$curr_page)
    { 
        $sql = "select distinct(date_format(issue_date,'%Y-%m-%d')) from open_issue_detail as detail 
                where company_id = $company_id and approval_person=$uid and status = 0 and ( is_approved = 1 or is_approved = 2 )";
        $total_count = DB::select($sql);
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $sql = "select distinct(date_format(issue_date,'%Y-%m-%d')) as approval_date_d from open_issue_detail as detail  where company_id = $company_id and approval_person=$uid and status = 0 and ( is_approved = 1 or is_approved = 2 ) limit $page_size offset $size_from";
            $result = DB::select($sql);

            $i = 0;
            foreach ($result as $key => $value) {
                $sql = "select is_approved as approval_status,issue_date,id as issue_detail_id,issuer,title 
                        from open_issue_detail as detail 
                        where company_id = $company_id and ( is_approved = 1 or is_approved = 2 ) and 
                        date_format(issue_date,'%Y-%m-%d') = '$value->approval_date_d' limit $page_size
                        offset $size_from";

                $res_new = DB::select($sql);

                if(!empty($res_new)){ 
                    foreach ($res_new as $key_new => $value_new) {
/*                        //提出人
                        $str_issuer = '';
                        $ary_issuer = explode(',',$value_new->issuer);
                        foreach ($ary_issuer as $key_issuer => $value_issuer) {
                            $user = User::infoUser(array('uid'=>$value_issuer),'fullname');
                            if(!empty($user)){ $str_issuer = $str_issuer.$user->fullname.' ';}
                        }                               
                        if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = mb_substr($str_issuer,0,-1);}*/
                        $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                            ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                        //$res_new[$key_new]->str_issuer = $str_issuer->fullname;
                        $fullname = $str_issuer->fullname;

                        $ary_id = explode(',',$value_new->issuer);
                        $ary_member = explode(',',$fullname);
                        $ary_member_list = array_combine($ary_id,$ary_member);
                        $ary_chunk_new = array();
                        foreach ($ary_member_list as $key => $value) {
                            $ary_chunk_new[$key]['id'] = $key;
                            $ary_chunk_new[$key]['name'] = $value;
                        }
                        $res_new[$key_new]->ary_member_list = array_values($ary_chunk_new);
                    }

                    $res_source['list_issue'.$i]['cont'] = $res_new;
                    $res_source['list_issue'.$i]['issue_date'] = $res_new[0]->issue_date;
                    $res_source = array_values($res_source);
                }
                $i++;
                $data['list_result'] = $res_source;
            }
        }
        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;

    }

    //待审批OPENISSUE列表
    public static function listPendingApi($company_id,$page_size,$curr_page)
    { 
        $sql = "select distinct(date_format(issue_date,'%Y-%m-%d')) from open_issue_detail as detail 
                where company_id = $company_id and status = 0 and is_approved = 0 and submit_approval = 1 ";
        $total_count = DB::select($sql);
        $total_count = count($total_count);
        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $sql = "select distinct(date_format(issue_date,'%Y-%m-%d')) as approval_date_d from open_issue_detail as detail where company_id = $company_id and status = 0 and is_approved = 0 and submit_approval = 1 limit $page_size offset $size_from";
            $result = DB::select($sql);

            $i = 0;
            foreach ($result as $key => $value) {
                $sql = "select is_approved as approval_status,issue_date,id as issue_detail_id,issuer,title 
                        from open_issue_detail as detail 
                        where company_id = $company_id and is_approved = 0 and  submit_approval = 1 and
                        date_format(issue_date,'%Y-%m-%d') = '$value->approval_date_d' limit $page_size
                        offset $size_from";

                $res_new = DB::select($sql);

                if(!empty($res_new)){ 
                    foreach ($res_new as $key_new => $value_new) {
/*                        //提出人
                        $str_issuer = '';
                        $ary_issuer = explode(',',$value_new->issuer);
                        foreach ($ary_issuer as $key_issuer => $value_issuer) {
                            $user = User::infoUser(array('uid'=>$value_issuer),'fullname');
                            if(!empty($user)){ $str_issuer = $str_issuer.$user->fullname.' ';}
                        }                               
                        if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = mb_substr($str_issuer,0,-1);}*/
                        $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                            ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                        //$res_new[$key_new]->str_issuer = $str_issuer->fullname;
                        $fullname = $str_issuer->fullname;

                        $ary_id = explode(',',$value_new->issuer);
                        $ary_member = explode(',',$fullname);
                        $ary_member_list = array_combine($ary_id,$ary_member);
                        $ary_chunk_new = array();
                        foreach ($ary_member_list as $key => $value) {
                            $ary_chunk_new[$key]['id'] = $key;
                            $ary_chunk_new[$key]['name'] = $value;
                        }
                        $res_new[$key_new]->ary_member_list = array_values($ary_chunk_new);

                    }

                    $res_source['list_issue'.$i]['cont'] = $res_new;
                    $res_source['list_issue'.$i]['issue_date'] = $res_new[0]->issue_date;
                    $res_source = array_values($res_source);
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }
        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;

    }

    //提交OPENISSUE审批
    public static function updateApprovalApi($id)
    { 
        $result = self::where(['id'=>$id,'submit_approval'=>0])->update(['submit_approval'=>1]);
        return $result;
    }

    //提交OPENISSUE审批结果
    public static function updateApprovalResultApi($uid,$id,$is_approved,$comment,$time)
    { 
        $result = self::where(['id'=>$id,'status'=>0,'submit_approval'=>1,'is_approved'=>0])
                ->update(['is_approved'=>$is_approved,
                    'approval_comment'=>$comment,
                    'approval_person'=>$uid,
                    'approval_date'=>$time]);
        return $result;
    }


    //新增OPENISSUE
    public static function createOpenIssueApi($ary)
    {
        $result = self::create($ary);
        return $result;
    }

    //修改OPENISSUE信息
    public static function updateOpenIssueApi($condition,$ary)
    {
        $result = self::where($condition)->update($ary);
        return $result;
    }

    //删除OPENISSUE信息
    public static function deleteOpenissueApi($ary)
    {
        try{
            return self::where($ary)->update(['status'=>1]);
        }catch(Exception $e){ 
            return 0;
        }
    }

    //获取OPENISSUE
    public static function getOpenissueApi($id)
    {
        //try{
        $str_leader = '';
        $str_issuer = '';
        $result['dep_name'] = '';
        $result['str_leader'] = '';
        $result['str_issuer'] = '';
        $result = self::select('open_issue_detail.comment','open_issue_detail.department','open_issue_detail.description',
                                'open_issue_detail.issue_class','open_issue_detail.issue_date','open_issue_detail.issuer',
                                'open_issue_detail.leader','open_issue_detail.plan_complete_date','open_issue_detail.solution',
                                'open_issue_detail.title','source.code as source_code')
                        ->leftJoin('issue_source as source','source.id','=','open_issue_detail.source_id')
                        ->where(['open_issue_detail.id'=>$id])->first();

        if(!empty($result)){
            //issue类型
            $res_class = DB::table('issue_class')->select('name')
                            ->where(array('id'=>$result['issue_class']))->first();
            if(!empty($res_class->name)){ $result['issue_class_name'] = $res_class->name; }

            //部门
            if(!empty($result['department'])){
                $str_department = Department::select(DB::raw("group_concat(name) as dep_name"))
                                            ->whereRaw("find_in_set(dep_id,'".$result['department']."')")->first(); 
                if(!empty($str_department)){ $result['dep_name'] = $str_department->dep_name; } 

                $ary_id = explode(',',$result['department']);
                $ary_member = explode(',',$result['dep_name']);
                $ary_member_list = array_combine($ary_id,$ary_member);
                $ary_chunk_new = array();
                foreach ($ary_member_list as $key => $value) {
                    $ary_chunk_new[$key]['id'] = $key;
                    $ary_chunk_new[$key]['name'] = $value;
                }
                $result['ary_department'] = array_values($ary_chunk_new);          
            }
                          
            //责任人
            if(!empty($result['leader'])){
                $str_leader = User::select(DB::raw("group_concat(fullname) as fullname"))
                                    ->whereRaw("find_in_set(uid,'".$result['leader']."')")->first();
                if(!empty($str_leader)){ $result['str_leader'] = $str_leader->fullname; }

                $ary_id = explode(',',$result['leader']);
                $ary_member = explode(',',$result['str_leader']);
                $ary_member_list = array_combine($ary_id,$ary_member);
                $ary_chunk_new = array();
                foreach ($ary_member_list as $key => $value) {
                    $ary_chunk_new[$key]['id'] = $key;
                    $ary_chunk_new[$key]['name'] = $value;
                }
                 $result['ary_leader'] = array_values($ary_chunk_new);
            }                              
                              
            //提出人   
            if(!empty($result['issuer'])){
                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                    ->whereRaw("find_in_set(uid,'".$result['issuer']."')")->first();
                if(!empty($str_issuer)){ $result['str_issuer'] = $str_issuer->fullname;}

                $ary_id = explode(',',$result['issuer']);
                $ary_member = explode(',',$result['str_issuer']);
                $ary_member_list = array_combine($ary_id,$ary_member);
                $ary_chunk_new = array();
                foreach ($ary_member_list as $key => $value) {
                    $ary_chunk_new[$key]['id'] = $key;
                    $ary_chunk_new[$key]['name'] = $value;
                }
                 $result['ary_issuer'] = array_values($ary_chunk_new);
            }                            
        }
        return $result;

        //}catch(Exception $e){ return 10003; }
        

    }




    //我的任务：openissue列表(部门和提出人可见)
    public static function lisBoardOpenissueApi($uid,$company_id,$dep_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(function($query) use($uid,$company_id){ 
                                $query->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                                ->whereRaw("locate('".$uid."',open_issue_detail.issuer)");
                            })->orWhere(function($query) use($dep_id,$company_id){ 
                                $query->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                                ->whereRaw("locate('".$dep_id."',open_issue_detail.department)");
                            })->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get()->count();

        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(function($query) use($uid,$company_id){ 
                                $query->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                                ->whereRaw("locate('".$uid."',open_issue_detail.issuer)");
                            })->orWhere(function($query) use($dep_id,$company_id){ 
                                $query->where(['open_issue_detail.company_id'=>$company_id,'open_issue_detail.status'=>0])
                                ->whereRaw("locate('".$dep_id."',open_issue_detail.department)");
                            })->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
/*                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 4;
                                }*/
                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                //$res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                $res_new[$key_new]->approval_status = 4;
                                $fullname = $str_issuer->fullname;

                                $ary_id = explode(',',$value_new->issuer);
                                $ary_member = explode(',',$fullname);
                                $ary_member_list = array_combine($ary_id,$ary_member);
                                $ary_chunk_new = array();
                                foreach ($ary_member_list as $key => $value) {
                                    $ary_chunk_new[$key]['id'] = $key;
                                    $ary_chunk_new[$key]['name'] = $value;
                                }
                                $res_new[$key_new]->ary_member_list = array_values($ary_chunk_new);
                            }
                        }

                        $res_source[]['cont'] = $res_new;
                        $res_source[$key]['total_name'] = $res_new[0]->title;
                        $res_source[$key]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }





    //我的任务：openissue进度录入列表(仅提出人可见)
    public static function listOpenissueProgressApi($uid,$company_id,$page_size,$curr_page)
    { 

        try{
        $i = 0;
        $total_count = self::select('open_issue_detail.issue_id')
                            ->where(['open_issue_detail.company_id'=>$company_id,
                                    'open_issue_detail.status'=>0,'input_uid'=>$uid])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->get()->count();

        $data['total_page'] = ceil($total_count/$page_size);//总页面数
        if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}//大于总页面数
        $size_from = $page_size * ($curr_page - 1);
        if($size_from < 0){ $size_from = 0; }

        if($total_count != 0){
            $result = self::select('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->where(['open_issue_detail.company_id'=>$company_id,
                                    'open_issue_detail.status'=>0,'input_uid'=>$uid])
                            ->join('issue_source as source','source.id','=','open_issue_detail.source_id')
                            ->groupBy('open_issue_detail.issue_id','open_issue_detail.source_id','source.code')
                            ->offset($size_from)->limit($page_size)->get();

            if(!$result->isEmpty()){
                foreach ($result as $key => $value) {
                    if($value->code == 'Plan'){
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                                ->join('issue_source as source','source.id','=','detail.source_id')
                                                ->join('plan','plan.plan_id','=','detail.issue_id')
                                                ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                                ->get();
                    }else if($value->code == 'Project'){ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->join('project','project.proj_id','=','detail.issue_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }else{ 
                            $res_new = DB::table('open_issue_detail as detail')->select('source.code','detail.is_approved as approval_status','detail.issuer','detail.submit_approval','source.name','detail.title','detail.id as issue_detail_id')
                                            ->join('issue_source as source','source.id','=','detail.source_id')
                                            ->where(['detail.issue_id'=>$value->issue_id,'detail.source_id'=>$value->source_id,'detail.status'=>0])
                                            ->get();
                    }
                    if(!$res_new->isEmpty()){ 
                        foreach ($res_new as $key_new => $value_new) {
                            //提出人   
                            if(!empty($value_new->issuer)){
/*                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                if(!empty($str_issuer)){ $res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                    $res_new[$key_new]->approval_status = 4;
                                }*/

                                $str_issuer = User::select(DB::raw("group_concat(fullname) as fullname"))
                                                    ->whereRaw("find_in_set(uid,'".$value_new->issuer."')")->first();
                                //$res_new[$key_new]->str_issuer = $str_issuer->fullname;
                                $res_new[$key_new]->approval_status = 4;
                                $fullname = $str_issuer->fullname;

                                $ary_id = explode(',',$value_new->issuer);
                                $ary_member = explode(',',$fullname);
                                $ary_member_list = array_combine($ary_id,$ary_member);
                                $ary_chunk_new = array();
                                foreach ($ary_member_list as $key => $value) {
                                    $ary_chunk_new[$key]['id'] = $key;
                                    $ary_chunk_new[$key]['name'] = $value;
                                }
                                $res_new[$key_new]->ary_member_list = array_values($ary_chunk_new);

                            }
                        }

                        $res_source[]['cont'] = $res_new;
                        $res_source[$key]['total_name'] = $res_new[0]->title;
                        $res_source[$key]['type_name'] = $res_new[0]->name;
                        $res_source = array_values($res_source);
                    }
                    $i++;
                    $data['list_result'] = $res_source;
                }
            }
        }

        $data['curr_page'] = $curr_page; //当前页面 
        $data['page_size'] = $page_size; //单页数  
        $data['total_count'] = $total_count; //总条数
        return $data;
        }catch(Exception $e){ return 10003; }
    }







    //更新OPENISSUE进度
    public static function updateCompleteApi($ary)
    {   
        try{
            return self::where($ary)->update(['is_completed'=>1]);
        }catch(Exception $e){ 
            return 0;
        }
    }

    public static function createDetail($ary)
    {
        return self::create($ary)->id;
    }
    
    public static function updateDetail($ary,$where)
    {
        return self::where($where)->update($ary);
    }

    public static function deleteDetailAry($ary)
    { 
        return self::whereIn('id',$ary)->delete();
    }


}

?>