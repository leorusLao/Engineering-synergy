<?php

namespace App\Models;

use Eloquent;
use DB;

class PlanTask extends Eloquent {
    protected $primaryKey = 'task_id';
    protected $table = 'plan_task';
    protected $fillable = array (
            'plan_id', 'name', 'node_id', 'node_no', 'node_type', 'expandable', 'parent_id', 'start_date', 'end_date', 
            'duration', 'milestone', 'ordinal', 'ordinal_priority', 'department', 'leader', 'member_list', 'outsource',
            'outsource_supplier', 'supplier_id','supplier_accepted', 'key_node', 'key_condition', 
            'real_start', 'real_end', 'complete', 'progress_remark', 'status', 'process_status',
            'site_message', 'small_routine', 'email', 'sms', 'company_id'
    );
    
    protected $guarded = array (
            'task_id'
    );

    public $timestamps = true;

    //获取单个计划节点详情api
    public static function getNodoContApi($node_id)
    { 
        try{
            $result = self::select('plan_task.real_start','plan_task.real_end','plan_task.complete','plan_task.duration','plan_task.end_date','user.username as leader',
                            'plan_task.node_no as node_code','plan_task.name as node_name','plan.approval_status',
                            'department.name as dep_name','plan_task.process_status as node_status',
                            'plan_task.progress_remark','plan_task.start_date')
                            ->leftJoin('plan','plan.plan_id','=','plan_task.plan_id')
                            ->leftJoin('user','user.uid','=','plan_task.leader')
                            ->leftJoin('department','department.dep_id','=','plan_task.department')
                            ->where(['plan_task.task_id'=>$node_id])->first();
            return $result;
        }catch(Exception $e){
            return 10003;
        }
    }

    //获取节点列表api
    public static function listNodesApi($plan_status,$ary_search,$page_size,$curr_page)
    {
        try{
            //待提交(1->status：0  and  approval_status：1)
            if($plan_status == 1){
                $ary_status = [0]; 
                $ary_approval_status = [1]; 
            }
            //待审批(2->status：0  and  approval_status：2)
            else if($plan_status == 2){
                $ary_status = [0]; 
                $ary_approval_status = [2]; 
            }
            //已审批(3->status：0  and  approval_status：3  ||  4)
            else if($plan_status == 3){
                $ary_status = [0]; 
                $ary_approval_status = [3,4]; 
            }
            //暂停(5->status：6)
            else if($plan_status == 5){
                $ary_status = [6]; 
                $ary_approval_status = [1,2,3,4]; 
            }
            //完结(6->status：9  ||  10)
            else if($plan_status == 6){
                $ary_status = [9,10]; 
                $ary_approval_status = [1,2,3,4]; 
            }
            //全部
            else if($plan_status == 0){
                $ary_status = [0,6,9,10]; 
                $ary_approval_status = [1,2,3,4]; 
            }

            //关键字查询
            $mydb = DB::table('plan');
            if(!empty($ary_search['plan_code'])){
                $mydb->whereRaw("locate('".$ary_search["plan_code"]."',plan.plan_code)");
            }
            if(!empty($ary_search['plan_name'])){
                $mydb->whereRaw("locate('".$ary_search["plan_name"]."',plan.plan_name)");
            }
            if(!empty($ary_search['node_code'])){
                $mydb->whereRaw("locate('".$ary_search["node_code"]."',plan_task.node_no)");
            }
            if(!empty($ary_search['node_name'])){
                $mydb->whereRaw("locate('".$ary_search["node_name"]."',plan_task.name)");
            }
            $clone_mydb = clone($mydb);
            //下面循环需用到上面的条件，N次克隆
            $clone = 'myclone_';
            for ($i=1; $i < 10 ; $i++) { 
                $myclone = $clone.$i;
                $$myclone = clone($mydb);
            }

            //分页
            $total_count = $mydb->select('plan.plan_id')->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                ->whereIn('plan.status',$ary_status)
                                ->whereIn('plan.approval_status',$ary_approval_status)
                                ->groupBy('plan.plan_id')->count();

            if($page_size <= 0){ $page_size = 1; }
            $data['total_page'] = ceil($total_count/$page_size);
            if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}
            $size_from = $page_size * ($curr_page - 1);
            if($size_from < 0){ $size_from = 0; }
            
            if($total_count != 0){
                $data['result'] = $clone_mydb->select('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                        ->whereIn('plan.status',$ary_status)
                                        ->whereIn('plan.approval_status',$ary_approval_status)
                                        ->groupBy('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->orderBy('plan.plan_id','desc')->offset($size_from)->limit($page_size)->get();
                if(!empty($data['result'])){
                    $num = 0;
                    foreach ($data['result'] as $key => $value) {
                        $num++;
                        $db_value = $clone.$num;//用到上面的克隆条件
                        $ary_node = $$db_value->select(
                                                'user.username as leader','plan_task.node_no as node_code',
                                                'plan_task.name as node_name','department.name as dep_name',
                                                'plan_task.task_id as node_id','plan_task.parent_id')
                                                ->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                                ->leftJoin('user','user.uid','=','plan_task.leader')
                                                ->leftJoin('department','department.dep_id','=','plan_task.department')
                                                ->whereIn('plan.status',$ary_status)
                                                ->whereIn('plan.approval_status',$ary_approval_status)
                                                ->where(['plan.plan_id'=>$value->plan_id])
                                                ->get();
                        if(!empty($ary_node)){ 
                            $ary_unset = [];
                            //放入子节点
                            foreach ($ary_node as $key_first => $value_first) {
                                foreach ($ary_node as $key_second => $value_second) {
                                    if($value_first->node_id == $value_second->parent_id){ 
                                        $ary_node[$key_first]->son_node = 1;
                                        $ary_node[$key_first]->list_sonnode[] = $value_second;
                                        $ary_unset[] = $value_second->node_id;
                                    }
                                }
                            }
                            //对象转成数组
                            foreach ($ary_node as $key_node => $value_node) {
                                $ary[$key_node] = $value_node;
                            }
                            //要删除的子节点
                            foreach ($ary as $key_node => $value_node) {
                                foreach ($ary_unset as $key_unset => $value_unset) {
                                    if($value_unset == $value_node->node_id){
                                        unset($ary[$key_node]);
                                    }
                                }
                            }
                        }                        
                        $ary_node = array_values($ary); //去下标
                        $data['result'][$key]->node_cont = $ary_node;
                    }
                }
            }
            $data['curr_page'] = $curr_page; //当前页面
            $data['page_size'] = $page_size; //单页数
            $data['total_count'] = $total_count; //总条数
            return $data;

        }catch(Exception $e){ return 10003; } 

    }


    //获取单个节点的审批信息
    public static function getApprovalInfoApi($nodeid)
    {
        try{ 
            $result = self::select('plan.approval_date','plan.progress_remark as approval_comment',
                            'plan.status as approval_status','user.fullname')
                            ->join('plan','plan.plan_id','=','plan_task.plan_id')
                            ->leftJoin('user','user.uid','=','plan.approval_person')
                            ->where(['plan_task.task_id'=>$nodeid])
                            ->get();
            return $result;
       }catch(Exception $e){ return 10003; }
    }


    //更改节点进展
    public static function updateProgressApi($nodeid,$progress)
    { 
        DB::beginTransaction();
        try{ 
            $result = self::where(['task_id'=>$nodeid,'process_status'=>0])
                            ->update(['complete'=>$progress]);
            if($progress == 100){ 
                self::where(['task_id'=>$nodeid,'process_status'=>0])->update(['process_status'=>1]);
                $ary_complete = self::select('plan_task.complete')
                                        ->join('plan_task as task','task.plan_id','=','plan_task.plan_id')
                                        ->where(['plan_task.task_id'=>$nodeid])->get();
                if(!empty($ary_complete)){ 
                    foreach ($ary_complete as $key => $value) {
                        if($value->complete != 100){ 
                            $bool = false;
                            break;
                        }else{ 
                            $bool = true;
                        }
                    }
                    //所有节点完成
                    if($bool == true){ 
                        self::join('plan','plan.plan_id','=','plan_task.plan_id')
                                ->where(['plan_task.task_id'=>$nodeid])->update(['plan.status'=>9]);
                    }
                }
            }
        }catch(Exception $e){ 
            DB::rollback();
            return 10003; 
        }
        DB::commit();
        return $result;

    }


    //计划接收确认列表
    public static function listPlanConfirm($uid,$page_size,$curr_page)
    {
        try{

	    //分页
            $total_count = Plan::select('plan.plan_id')->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                ->where(['plan_task.leader'=>$uid])
                                ->groupBy('plan.plan_id')->count();

            if($page_size <= 0){ $page_size = 1; }
            $data['total_page'] = ceil($total_count/$page_size);
            if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}
            $size_from = $page_size * ($curr_page - 1);
            if($size_from < 0){ $size_from = 0; }
            
            if($total_count != 0){
                $data['result'] = Plan::select('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                        ->where(['plan_task.leader'=>$uid])
                                        ->groupBy('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->orderBy('plan.plan_id','desc')->offset($size_from)->limit($page_size)->get();
                if(!empty($data['result'])){
                    foreach ($data['result'] as $key => $value) {
                        $ary_node = DB::table('plan_task')->select(
                                                'plan_task.accept_status','user.username as leader','plan_task.node_no as node_code','plan_task.name as node_name','department.name as dep_name',
                                                'plan_task.task_id as node_id','plan_task.parent_id')
                                                ->leftJoin('plan','plan.plan_id','=','plan_task.plan_id')
                                                ->leftJoin('user','user.uid','=','plan_task.leader')
                                                ->leftJoin('department','department.dep_id','=','plan_task.department')
                                                ->where(['plan.plan_id'=>$value->plan_id,'plan_task.leader'=>$uid])
                                                ->get();
                        if(!empty($ary_node)){ 
                            $ary_unset = [];
                            //放入子节点
                            foreach ($ary_node as $key_first => $value_first) {
                                foreach ($ary_node as $key_second => $value_second) {
                                    if($value_first->node_id == $value_second->parent_id){ 
                                        $ary_node[$key_first]->son_node = 1;
                                        $ary_node[$key_first]->list_sonnode[] = $value_second;
                                        $ary_unset[] = $value_second->node_id;
                                    }
                                }
                            }
                            //对象转成数组
                            foreach ($ary_node as $key_node => $value_node) {
                                $ary[$key_node] = $value_node;
                            }
                            //要删除的子节点
                            foreach ($ary_node as $key_node => $value_node) {
                                foreach ($ary_unset as $key_unset => $value_unset) {
                                    if($value_unset == $value_node->node_id){ 
                                        //if(is_object($ary_node)){
                                            //var_dump(11111111);
                                            //$ary_node = array($ary_node);
                                            unset($ary_node[$key_node]);
                                        //}
                                    }
                                }
                            }
                        }
                        $ary_node = array_values($ary); //去下标
                        $data['result'][$key]->node_cont = $ary_node;
                    }
                }
            }
            $data['curr_page'] = $curr_page; //当前页面
            $data['page_size'] = $page_size; //单页数
            $data['total_count'] = $total_count; //总条数
            return $data;

        }catch(Exception $e){ return 10003; } 

    }


    //更新计划确认
    public static function updatePlanConfirm($uid,$node_id,$status)
    { 
        try{ 
            $result = self::where(['task_id'=>$node_id,'accept_status'=>0,'leader'=>$uid])->update(['accept_status'=>$status]);
        }catch(Exception $e){ 
            return 10003; 
        }
        return $result;

    }



    //节点进度列表api
    public static function listNodeProgressApi($uid,$page_size,$curr_page)
    {
        
        try{
            //能录入计划进度的状态
            $ary_status = [0]; //0-正常, 6-暂停，9-完成 10-完结
            $ary_approval_status = [3]; //0-尚未做具体计划，1-已做，草稿，2-已递交，等待批，3-批准，4-不同意
            //分页
            $total_count = Plan::select('plan.plan_id')->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                ->whereIn('plan.status',$ary_status)
                                ->whereIn('plan.approval_status',$ary_approval_status)
                                ->groupBy('plan.plan_id')->count();

            if($page_size <= 0){ $page_size = 1; }
            $data['total_page'] = ceil($total_count/$page_size);
            if($data['total_page'] <= $curr_page){ $curr_page = $data['total_page'];}
            $size_from = $page_size * ($curr_page - 1);
            if($size_from < 0){ $size_from = 0; }
            
            if($total_count != 0){
                $data['result'] = DB::table('plan')->select('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                        ->whereIn('plan.status',$ary_status)
                                        ->whereIn('plan.approval_status',$ary_approval_status)
                                        ->groupBy('plan.plan_id','plan.plan_code','plan.plan_name')
                                        ->orderBy('plan.plan_id','desc')->offset($size_from)->limit($page_size)->get();
                if(!empty($data['result'])){
                    foreach ($data['result'] as $key => $value) {
                        $ary_node = DB::table('plan')->select(
                                                'user.username as leader','plan_task.node_no as node_code',
                                                'plan_task.name as node_name','department.name as dep_name',
                                                'plan_task.task_id as node_id','plan_task.parent_id')
                                                ->join('plan_task','plan.plan_id','=','plan_task.plan_id')
                                                ->leftJoin('user','user.uid','=','plan_task.leader')
                                                ->leftJoin('department','department.dep_id','=','plan_task.department')
                                                ->whereIn('plan.status',$ary_status)
                                                ->whereIn('plan.approval_status',$ary_approval_status)
                                                ->where(['plan.plan_id'=>$value->plan_id])
                                                ->get();
                        if(!empty($ary_node)){ 
                            $ary_unset = [];
                            //放入子节点
                            foreach ($ary_node as $key_first => $value_first) {
                                foreach ($ary_node as $key_second => $value_second) {
                                    if($value_first->node_id == $value_second->parent_id){ 
                                        $ary_node[$key_first]->son_node = 1;
                                        $ary_node[$key_first]->list_sonnode[] = $value_second;
                                        $ary_unset[] = $value_second->node_id;
                                    }
                                }
                            }
                            //对象转数组
                            foreach ($ary_node as $key_node => $value_node) {
                                $ary[$key_node] = $value_node;
                            }
                            //要删除的子节点
                            foreach ($ary as $key_node => $value_node) {
                                foreach ($ary_unset as $key_unset => $value_unset) {
                                    if($value_unset == $value_node->node_id){ 
                                        //if(is_object($ary_node)){
                                        //    $ary_node = array($ary_node);
                                            unset($ary[$key_node]);
                                        //}
                                    }
                                }
                            }
                            $ary_node = array_values($ary);//去下标
                        }
                        $data['result'][$key]->node_cont = $ary_node;
                    }
                }
            }
            $data['curr_page'] = $curr_page; //当前页面
            $data['page_size'] = $page_size; //单页数
            $data['total_count'] = $total_count; //总条数
            return $data;

        }catch(Exception $e){ return 10003; } 

    }



}

?>
