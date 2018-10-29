<?php
/**
 * Created by PhpStorm.
 * User: Wenson
 * Date: 2018/2/28
 * Time: 17:02
 */
namespace App\Http\Controllers\Api;

use App;
use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\CheckApi;
use App\Models\OpenIssueDetail;
use App\Models\PlanTask;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\IssueSource;

class BacklogController extends App\Http\Controllers\Controller
{
    public function agenda(Request $request)
    {
        // 参数是否完整
        $return = CheckApi::check_format($request, ['company_id', 'uid', 'token']);
        if($return !== true) {return $return;}

        $uid = $request->get('uid');
        $company_id = $request->get('company_id');

        $return = CheckApi::check_userinfo($uid, $request->get('token'), $company_id);
        if($return !== true) {return $return;}

        $result1 = [];
        $result2 = [];
        // 任务
        $res = planTask::whereNotNull('leader')->whereNotNull('member_list')->where('company_id', $company_id)->select(['task_id', 'leader', 'member_list'])->get()->toArray();

        if(!empty($res)) {
            $idArr = [];
            foreach($res as $k => $v)
            {
                if($v['leader'] == $uid || in_array($uid, explode(',', $v['member_list'])))
                {
                    $idArr[] = $v['task_id'];
                }
            }

            $result1 = planTask::whereIn('task_id', $idArr)->select(['task_id', 'name', 'start_date', 'end_date', 'leader', 'member_list', 'status', 'process_status'])->orderBy('end_date','asc')->get()->toArray();
            if(!empty($result1)) {
                $userArr = [];
                foreach($result1 as &$value)
                {
                    $value['member_lists'] = explode(',', $value['member_list']);
                    unset($value['member_list']);
                    $userArr = array_merge($userArr, $value['member_lists'], [$value['leader']]);
                }
                $userArr = array_unique($userArr);
                // 获取用户的姓名
                $user = User::whereIn('uid', $userArr)->pluck('fullname', 'uid')->toArray();

                foreach($result1 as $k => $v)
                {
                    $result1[$k]['leadername'] = $user[$v['leader']];

                    foreach($v['member_lists'] as $kk => $vv)
                    {
                        unset($result1[$k]['member_lists']);
                        $result1[$k]['member_list'][] = ['uid' => $vv, 'name' => $user[$vv]];
                    }
                }
            }
        }

        // ISSUE
        $res = OpenIssueDetail::where('company_id', $company_id)->select(['id', 'issue_id', 'source_id','leader','department', 'issuer', 'approval_person', 'input_uid'])->get()->toArray();
        $dep_id = UserCompany::where(['uid' => $uid, 'company_id' => $company_id])->value('dep_id');

        $idArr = [];

        if(!empty($res))
        {
            $issueSource = IssueSource::pluck('code', 'id')->toArray();

            $userArr = [];
            $departmentArr = [];
            foreach($res as $v)
            {
                $tmp = false;
                isset($issueSource[$v['source_id']]) && $tmp = CheckApi::check_issue_id($v['issue_id'], $issueSource[$v['source_id']], $company_id);
                if($tmp === true && (in_array($uid, explode(',', $v['leader'])) || in_array($dep_id, explode(',', $v['department'])) || in_array($uid, explode(',', $v['issuer'])) || $v['approval_person'] == $uid || $v['input_uid'] == $uid)) {
                    $v['leader'] && $userArr = array_merge(explode(',', $v['leader']), $userArr);
                    $v['issuer'] && $userArr = array_merge(explode(',', $v['issuer']), $userArr);
                    $v['approval_person'] && $userArr[] = $v['approval_person'];
                    $v['input_uid'] && $userArr[] = $v['input_uid'];
                    $idArr[] = $v['id'];
                    $v['department'] && $departmentArr = array_merge($departmentArr, explode(',', $v['department']));
                }
            }
            $userArr = array_unique($userArr);
            $departmentArr = array_unique($departmentArr);
            // 获取用户的姓名
            $user = User::whereIn('uid', $userArr)->pluck('fullname', 'uid')->toArray();
            // 获取部门名称
            $departments = UserCompany::whereIn('dep_id', $departmentArr)->pluck('dep_name', 'dep_id')->toArray();

            $result2 = OpenIssueDetail::whereIn('id', $idArr)->select(['issue_id', 'source_id', 'title', 'description', 'solution', 'leader','department', 'issuer', 'approval_person', 'input_uid', 'plan_complete_date', 'issue_date', 'real_complete_date', 'is_completed', 'is_approved', 'status'])->orderBy('plan_complete_date','asc')->get()->toArray();

            foreach($result2 as &$v)
            {
                if($v['leader']) {
                    $leader = explode(',', $v['leader']);
                    unset($v['leader']);
                    foreach($leader as $vv)
                    {
                        $v['leader'][] = ['uid' => $vv, 'name' => $user[$vv]];
                    }
                }
                if($v['issuer']) {
                    $issuer = explode(',', $v['issuer']);
                    unset($v['issuer']);
                    foreach($issuer as $vv)
                    {
                        $v['issuer'][] = [
                            'uid' => $vv,
                            'name' => isset($user[$vv]) ? $user[$vv] : '',
                        ];
                    }
                }
                if($v['department'])
                {
                    $department = explode(',', $v['department']);
                    unset($v['department']);
                    foreach($department as $vv)
                    {
                        $v['department'][] = [
                            'dep_id' => $vv,
                            'dep_name' => isset($departments[$vv]) ? $departments[$vv] : '',
                        ];
                    }
                }

                $v['approval_person'] && $v['approval_person'] = $user[$v['approval_person']];
                $v['input_uid'] && $v['input_uid'] = $user[$v['input_uid']];
            }

        }

        $result = ['task' => $result1, 'issue' => $result2];
//        dd($result);
        return CheckApi::return_success($result);
    }
}

