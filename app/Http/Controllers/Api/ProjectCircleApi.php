<?php
/**
 * Created by PhpStorm.
 * User: wenson
 * Date: 2018/3/7
 * Time: 14:14
 */
namespace App\Http\Controllers\Api;

use App;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Models\ProjectCircle;
use App\Models\Project;
use App\Models\Comments;
use App\Models\Plan;
use App\Models\User;
use App\Models\PlanTask;
use App\Models\UserCompany;
use App\Models\OpenIssueDetail;
use App\Models\IssueSource;


class ProjectCircleApi extends App\Http\Controllers\Controller{

    // 检验 项目/ISSUE 是否与用户有关
    private function checkIid($company_id, $iid, $uid, $type)
    {
        switch($type)
        {
            case 1:
                $res = PlanTask::whereNotNull('leader')->whereNotNull('member_list')->where(['company_id' => $company_id, 'task_id' => $iid])->select(['leader', 'member_list'])->get()->toArray();
                if(empty($res)) {return CheckApi::return_46034();}
                $tmpCount = count($res);
                foreach($res as $k => $v)
                {
                    if($v['leader'] == $uid || in_array($uid, explode(',', $v['member_list']))) {break;}
                    if($k == $tmpCount - 1) {return CheckApi::return_46034();}
                }

                break;
            case 2:
                $res = OpenIssueDetail::where('company_id', $company_id)->select(['leader','department', 'issuer', 'approval_person', 'input_uid'])->get()->toArray();
                if(empty($res)){return CheckApi::return_46028();}
                $dep_id = UserCompany::where(['uid' => $uid, 'company_id' => $company_id])->value('dep_id');

                $tmpCount = count($res);
                foreach($res as $k => $v)
                {
                    if(in_array($uid, explode(',', $v['leader'])) || in_array($dep_id, explode(',', $v['department'])) || in_array($uid, explode(',', $v['issuer'])) || $v['approval_person'] == $uid || $v['input_uid'] == $uid) {
                        break;
                    }
                    if($k == $tmpCount - 1) {return CheckApi::return_46028();}
                }
                break;
            case 3:
                if($iid != 0) {return CheckApi::return_46011();}
                break;
        }

        return true;
    }

    // 创建项目圈消息
    public function createMsg(Request $request)
    {
        // 参数是否完整
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token', 'iid', 'content', 'source', 'type']);
        if($return !== true) {return $return;}

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $iid = $request->get('iid');
        $content = $request->get('content');
        $source = $request->get('source');
        $type = $request->get('type');
        // 检测用户
        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}

        // 参数是否符合要求
        if(CheckApi::check_numeric($request, ['source', 'type']) !== true || !in_array($source, [1, 2]) || !in_array($type, [1, 2, 3])) {
            return CheckApi::return_46011();
        }

        // 用户是否属于项目负责人或成员/用户是否与ISSUE有关
        $return = $this->checkIid($company_id, $iid, $uid, $type);
        if($return !== true) {return $return;}

        switch($type){
            // 任务
            case 1:
                // 任务 属于公司
                $return = CheckApi::check_usernode($company_id, $iid);
                if($return !== true) {return $return;}
                // 项目属于公司
                if($request->has('seeing')){
                    $seeing = $request->get('seeing');
                    $plan_id = Plan::where(['company_id' => $company_id, 'project_id' => $seeing])->value('plan_id');
                    if(empty($plan_id)){return CheckApi::return_46034();}
                    $return = CheckApi::check_userplan($company_id, $plan_id);
                    if($return !== true) {return $return;}
                } else{
                    $plan_id = PlanTask::where(['company_id' => $company_id, 'task_id' => $iid])->value('plan_id');
                    if(empty($plan_id)){return CheckApi::return_46034();}
                    $seeing = Plan::where(['plan_id' => $plan_id, 'company_id' => $company_id])->value('project_id');
                    if(empty($seeing)){return CheckApi::return_46034();}
                }

                $projectCircle = ProjectCircle::create(['trigger_uid' => $uid, 'iid' => $iid, 'company_id' => $company_id, 'content' => $content, 'source' => $source, 'type' => $type, 'seeing' => $seeing]);
                if($projectCircle->save()){
                    return CheckApi::return_success($projectCircle->id);
                }

            // ISSUE
            case 2:
                // issue来源
                $issueSource = IssueSource::pluck('code', 'id')->toArray();

                $issueSourceVal = OpenIssueDetail::where('issue_id', $iid)->value('source_id');

                if(!isset($issueSource[$issueSourceVal])){ return CheckApi::return_46033();}
                $return = CheckApi::check_issue_id($iid, $issueSource[$issueSourceVal], $company_id);
                if($return !== true){ return $return;}
                switch($issueSource[$issueSourceVal]){
                    case 'Project':
                        $seeing = $iid;
                        $plan_id = Plan::where(['company_id' => $company_id, 'project_id' => $seeing])->value('plan_id');
                        if(empty($plan_id)){return CheckApi::return_46034();}
                        $return = CheckApi::check_userplan($company_id, $plan_id);
                        if($return !== true) {return $return;}
                        break;
                    case 'Plan':
                        $seeing = Plan::where(['plan_id' => $iid, 'company_id' => $company_id])->value('project_id');
                        if(empty($seeing)){return CheckApi::return_46034();}

                        break;
                    default:
                        $seeing = $iid;
                        break;
                }

                $projectCircle = ProjectCircle::create(['trigger_uid' => $uid, 'iid' => $iid, 'company_id' => $company_id, 'content' => $content, 'source' => $source, 'type' => $type, 'seeing' => $seeing]);
                if($projectCircle->save()){
                    return CheckApi::return_success($projectCircle->id);
                }

                break;
            case 3:
                if(!$request->has('seeing')) {return CheckApi::return_46011();}
                $seeings = $request->get('seeing');
                $tmp = Project::where('company_id', $company_id)->whereIn('proj_id', $seeings)->select('proj_manager_uid', 'member_list', 'approval_person')->get()->toArray();
                if(empty($tmp)) {return CheckApi::return_46011();}
                foreach($tmp as $v)
                {
                    if($v['proj_manager_uid'] != $uid && !in_array($uid, explode(',', $v['member_list'])) && $v['approval_person'] != $uid) {return CheckApi::return_46011();}
                }
                $seeing = implode(',', $seeings);
                $projectCircle = ProjectCircle::create(['trigger_uid' => $uid, 'iid' => $iid, 'company_id' => $company_id, 'content' => $content, 'source' => $source, 'type' => $type, 'seeing' => $seeing]);
                if($projectCircle->save()){
                    return CheckApi::return_success($projectCircle->id);
                }
                break;
        }

        return CheckApi::return_46021();
    }

    // 用户相关项目
    public function involvePlan(Request $request)
    {
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token']);
        if($return !== true) {return $return;}
        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $res = Project::where(['company_id' => $company_id])->select('proj_manager_uid', 'member_list', 'approval_person', 'proj_id', 'proj_name')->get()->toArray();
        $data = [];

        if(!empty($res)) {
            foreach($res as $v)
            {
                if($v['proj_manager_uid'] == $uid || in_array($uid, explode(',', $v['member_list'])) || $v['approval_person'] == $uid ) {
                        $data[] = ['proj_id' => $v['proj_id'], 'proj_name' => $v['proj_name'] ];
                }
            }
        }

        return CheckApi::return_success($data);
    }

    // 展示项目圈消息
    public function showMsg(Request $request)
    {
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token', 'num']);
        if($return !== true) {return $return;}
        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $num = $request->get('num');
        $page = $request->has('page') ? $request->get('page') : 1;
        // 检测用户
        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}

        $res = ProjectCircle::where(['company_id' => $company_id, 'status' => 1])->select(['id', 'trigger_uid', 'iid', 'content', 'text', 'img', 'source', 'type', 'seeing', 'updated_at'])->orderBy('updated_at', 'desc')->get()->toArray();
        $data = [];
        $userArr = [];
        $idArr = [];
        $result['info'] = [];
        $result['total'] = 0;
        $result['page'] = (int)$page;
        $result['num'] = (int)$num;

        if(!empty($res)) {
            foreach($res as $v)
            {
                if($v['iid'] == 0 && $v['type'] == 3) {
                    $tmp = Project::where('company_id', $company_id)->whereIn('proj_id', explode(',', $v['seeing']) )->select(['proj_manager_uid', 'member_list', 'approval_person', 'proj_name'])->first()->toArray();
                    if($uid == $tmp['proj_manager_uid'] || in_array($uid, explode(',', $tmp['member_list'])) || $uid == $tmp['approval_person']) {
                        $userArr[] = $v['trigger_uid'];
                        $v['proj_name'] = $tmp['proj_name'];
                        $idArr[] = $v['id'];
                        $data[] = $v;
                    }
                } elseif($v['iid'] != 0 && in_array($v['type'], [1, 2]) && is_numeric($v['seeing'])) {
                    $tmp = Project::where(['company_id' => $company_id, 'proj_id' => $v['seeing'] ])->select(['proj_manager_uid', 'member_list', 'approval_person', 'proj_name'])->first()->toArray();
                    if($uid == $tmp['proj_manager_uid'] || in_array($uid, explode(',', $tmp['member_list'])) || $uid == $tmp['approval_person']) {
                        $userArr[] = $v['trigger_uid'];
                        $v['proj_name'] = $tmp['proj_name'];
                        $idArr[] = $v['id'];
                        $data[] = $v;
                    }
                }

            }


            if(!empty($userArr)){
                $user = User::whereIn('uid', $userArr)->pluck('fullname', 'uid')->toArray();
                $avatar = User::whereIn('uid', $userArr)->pluck('avatar', 'uid')->toArray();
                $ids = Comments::whereIn('pc_id', $idArr)->where('status', 1)->orderBy('updated_at', 'asc')->get()->toArray();

                if(!empty($ids)) {
                    foreach($ids as $v)
                    {
                        // 赞
                        if($v['type'] == 1) {
                            $praise[$v['pc_id']][] = ['from_uid' => $v['from_uid'], 'from_name' => $v['from_name'] ];
                        }elseif($v['type'] == 2) {
                            $comment[$v['pc_id']][] = ['id' => $v['id'], 'from_uid' => $v['from_uid'], 'from_name' => $v['from_name'], 'text' => $v['text'] ];
                        }
                    }
                }
                $result['total'] = ceil(count($data) / $num);
                foreach($data as $k => $v)
                {
                    if($k >= ($page - 1) * $num && $k < $page * $num) {
                        $v['trigger'] = isset($user[$v['trigger_uid']]) ? $user[$v['trigger_uid']] : '';
                        $v['avatar'] = isset($avatar[$v['trigger_uid']]) ? $avatar[$v['trigger_uid']] : '';
                        $v['praise'] = isset($praise[$v['id']]) ? $praise[$v['id']] : [];
                        $v['comment'] = isset($comment[$v['id']]) ? $comment[$v['id']] : [];

                       if($v['avatar'] != '' && stripos($v['avatar'], 'http') === false)
                       {
                           $v['avatar'] = config('app.HTTPS').$_SERVER['HTTP_HOST'].$v['avatar'];
                       }

                        $result['info'][] = $v;
                    }

                }
            }
        }

        return CheckApi::return_success($result);
    }

    // 删除项目圈消息
    public function deleteMsg(Request $request)
    {
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token', 'id']);
        if($return !== true) {return $return;}
        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $id = $request->get('id');
        // 检测用户
        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}
        if(!is_numeric($id) || $id <= 0 || floor($id) != $id) {return CheckApi::return_46011();}

        $res = ProjectCircle::where(['trigger_uid' => $uid, 'company_id' => $company_id, 'iid' => 0, 'type' => 3, 'status' => 1])->find($id);
        if(empty($res)) { return CheckApi::return_46011();}

        $projectCircle = ProjectCircle::find($id);
        $projectCircle->delete();
        if($projectCircle->trashed()) {
            return CheckApi::return_success($id);
        }else {
            return CheckApi::return_46021();
        }
    }

    // 点赞
    public function praise(Request $request)
    {
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token', 'id', 'type']);
        if($return !== true) {return $return;}
        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $id = $request->get('id');
        $type = $request->get('type');
        // 检测用户
        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}
        if(!is_numeric($id) || $id <= 0 || floor($id) != $id || !in_array($type, [0, 1])) {return CheckApi::return_46011();}

        if($request->has('name')) {
            $name = $request->get('name');
        }else {
            $name = User::where('uid', $uid)->value('fullname');
            if(empty($name)) {return CheckApi::return_46011();}
        }

        $res = ProjectCircle::where('status', 1)->find($id);
        if(empty($res)) {return CheckApi::return_46011();}

        // 软删除的数据也要查询出来
        $comments = Comments::withTrashed()->where(['pc_id' => $id, 'from_uid' => $uid, 'type' => 1, 'status' => 1])->first();

        if(empty($comments)) {
            if($type == 0) {return CheckApi::return_46011();}
            $comment = Comments::create(['pc_id' => $id, 'from_uid' => $uid, 'from_name' => $name, 'type' => 1]);
            if($comment->save()){
                return CheckApi::return_success($comment->id);
            }else {
                return CheckApi::return_46021();
            }
        }else {
            if(isset($comments->deleted_at) && $comments->deleted_at) {
                if($type == 0) {return CheckApi::return_46011();}
                $res = $comments->restore();
                if($res) {
                    return CheckApi::return_success($comments->id);
                }else {
                    return CheckApi::return_46021();
                }
            }else {
                if($type == 1) {return CheckApi::return_46011();}
                $comments->delete();
                if($comments->trashed()) {
                    return CheckApi::return_success($comments->id);
                }else {
                    return CheckApi::return_46021();
                }
            }
        }
    }

    // 评论 有text 添加评论   无text  删除评论
    public function commentMsg(Request $request)
    {
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token', 'id']);
        if($return !== true) {return $return;}
        $uid = $request->get('uid');
        $company_id = $request->get('company_id');
        $id = $request->get('id');

        // 检测用户
        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}

        if($request->has('text')) {
            $text = $request->get('text');
            if(!trim($text)) {return CheckApi::return_46011();}

            if($request->has('name')) {
                $name = $request->get('name');
            }else {
                $name = User::where('uid', $uid)->value('fullname');
                if(empty($name)) {return CheckApi::return_46011();}
            }

            $res = ProjectCircle::where('status', 1)->find($id);
            if(empty($res)) {return CheckApi::return_46011();}

            $comment = Comments::create(['pc_id' => $id, 'from_uid' => $uid, 'from_name' => $name, 'text' => $text]);
            if($comment->save()) {
                return CheckApi::return_success($comment->id);
            }else {
                return CheckApi::return_46021();
            }
        }else {
            $comment = Comments::where(['status' => 1, 'type' => 2, 'from_uid' => $uid])->find($id);
            if(empty($comment)) {
                return CheckApi::return_46011();
            }else {
                $comment->delete();
                if($comment->trashed()) {
                    return CheckApi::return_success($comment->id);
                }else {
                    return CheckApi::return_46021();
                }
            }
        }
    }

}


