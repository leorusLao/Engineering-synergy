<?php
use Illuminate\Http\Request;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */
 
Route::any ( '/user/login', 'AccountApi@loginApi' );
Route::any ( '/wechat/login', 'AccountApi@login' );//网页版重新定义url
Route::any ( '/band/wechat', 'AccountApi@bandWechat' );//邮箱或手机注册账号绑定微信
Route::any ( '/user/jssdk', 'AccountApi@jssdk' );
Route::any ( '/user/logout', 'AccountApi@logout' );
Route::any ( '/user/invite-employee', 'AccountApi@inviteEmployee' );
Route::any ( '/user/get-user-info', 'AccountApi@getUserInfo' );
Route::any ( '/user/register','AccountApi@register');
//Route::any ( '/user/invite','AccountApi@invite');
Route::any ( '/user/modify-user-info', 'AccountApi@modifyUserInfo' );
Route::any ( '/user/change-password', 'AccountApi@modifyPassword' );
Route::any ( '/user/check-is-exit', 'AccountApi@checkIsExit' );
Route::any ( '/user/email-getpassword', 'AccountApi@getPasswordByemail' );
Route::any ( '/user/email-setpassword', 'AccountApi@setPasswordByemail' );
Route::any ( '/user/get-check-code', 'AccountApi@getCheckCode' );
Route::any ( '/user/get-email-check-code', 'AccountApi@getEmailCheckCode' );

Route::any ( '/user/check-code', 'AccountApi@checkCode' );
Route::any ( '/user/modify-avatar', 'AccountApi@modifyAvatar' );
Route::any ( '/user/send-email', 'AccountApi@sendEmail' );
Route::any ( '/user/uploadImg', 'AccountApi@uploadImg' );
Route::any ( '/user/get-jssdk', 'AccountApi@getJssdk' );
Route::any ( '/user/wechat-first-login', 'AccountApi@wechatFirstLogin' );
Route::any ( '/com-user/search', 'AccountApi@compUserSearch' );

Route::any ( '/company/company-profile', 'Api\Config@companyProfile' );
Route::any ( '/company/get-company-profile', 'Api\Config@getCompanyProfile' );
Route::any ( '/company/department-members','Api\Config@departmentMmembers');
Route::any ( '/company/create-department','Api\Config@createDepartment');
Route::any ( '/company/getDepartmentMember','Api\Config@getDepartment');
Route::any ( '/company/modify-memberinfo','Api\Config@updateMemberInfo');
Route::any ( '/dep-user/delete', 'Api\Config@deleteDepUser' );
Route::any ( '/user-dep/update', 'Api\Config@updateDepUser' );
Route::any ( '/dep/delete', 'Api\Config@deleteDepartment' );
Route::any ( '/com/member-info', 'Api\Config@memberInfo' );
Route::any ( '/company/get-sub-deps', 'Api\Config@getSubDeps' );
Route::any ( '/company/department', 'Api\Config@department' );
Route::any ( '/company/modify-deptinfo', 'Api\Config@updateDepartInfo' );
Route::any ( '/company/get-bu-list', 'Api\Config@listBu' );

Route::any ( '/sup-info/update', 'Api\SupplierProcess@updateSuper' );
Route::any ( '/sup-info/view', 'Api\SupplierProcess@getSuper' );
Route::any ( '/cst-info/update', 'Api\SupplierProcess@updateCustomer' );
Route::any ( '/cst-or-sup/list', 'Api\SupplierProcess@getSuperOrCustomer' );
Route::any ( '/cst-info/view', 'Api\SupplierProcess@getCustomer' );
Route::any ( '/cst-sup/search', 'Api\SupplierProcess@cstSupSearch' );
Route::any ( '/cst-sup/delete', 'Api\SupplierProcess@cstSupdelete' );
Route::any ( '/invite/sup', 'Api\SupplierProcess@inviteSup' );
Route::any ( '/invite/member', 'Api\SupplierProcess@inviteMember' );
Route::any ( '/invite/friend', 'Api\SupplierProcess@inviteFriend' );
Route::any ( '/invite/cst', 'Api\SupplierProcess@inviteCustomer' );
Route::any ( '/friend/view', 'Api\SupplierProcess@myFriend' );
Route::any ( '/friend/list', 'Api\SupplierProcess@listMyFriend' );


Route::any ( '/project/list-vacancy', 'Api\ProjectApi@listVacancy' );
Route::any ( '/project/list-project', 'Api\ProjectApi@listProject' );
Route::any ( '/project/list-pending', 'Api\ProjectApi@listPending' );
Route::any ( '/project/list-approved', 'Api\ProjectApi@listApproved' );
Route::any ( '/project/list-send', 'Api\ProjectApi@listSend' );
Route::any ( '/project/list-relation', 'Api\ProjectApi@listRelation' );
Route::any ( '/project/delete-project', 'Api\ProjectApi@deleteProject' );
Route::any ( '/project/get-baseinfo', 'Api\ProjectApi@getBaseInfo' );
Route::any ( '/project/create-baseinfo', 'Api\ProjectApi@createBaseInfo' );
Route::any ( '/project/get-sendinfo', 'Api\ProjectApi@getSendInfo' );
Route::any ( '/project/create-sendstatus', 'Api\ProjectApi@createSendStatus' );
Route::any ( '/project/update-baseinfo', 'Api\ProjectApi@updateBaseInfo' );
Route::any ( '/project/update-approval', 'Api\ProjectApi@updateApproval' );
Route::any ( '/project/update-approval-result', 'Api\ProjectApi@updateApprovalResult' );
Route::any ( '/project/list-partinfo', 'Api\ProjectApi@listPartInfo' );
Route::any ( '/project/get-partinfo', 'Api\ProjectApi@getPartInfo' );
Route::any ( '/project/delete-partinfo', 'Api\ProjectApi@deletePartInfo' );
Route::any ( '/project/create-partinfo', 'Api\ProjectApi@createPartInfo' );
Route::any ( '/project/update-partinfo', 'Api\ProjectApi@updatePartInfo' );
Route::any ( '/project/create-document', 'Api\ProjectApi@createDocument' );
Route::any ( '/project/delete-document', 'Api\ProjectApi@deleteDocument' );
Route::any ( '/project/list-document', 'Api\ProjectApi@listDocument' );
Route::any ( '/project/get-document', 'Api\ProjectApi@getDocument' );
Route::any ( '/project/list-calendar', 'Api\ProjectApi@listCalendar' );
Route::any ( '/project/list-property', 'Api\ProjectApi@listProperty' );
Route::any ( '/project/list-department', 'Api\ProjectApi@listDepartment' );
Route::any ( '/project/create-property', 'Api\ProjectApi@createSupplierMessage' );
Route::any ( '/project/update-project-completed', 'Api\ProjectApi@updateProjectCompleted' );


Route::any ( '/openissue/list-source', 'Api\OpenissueApi@listSource');
Route::any ( '/openissue/list-projectissue', 'Api\OpenissueApi@listProjectIssue');
Route::any ( '/openissue/list-planissue', 'Api\OpenissueApi@listPlanIssue');
Route::any ( '/openissue/create-openissue', 'Api\OpenissueApi@createOpenIssue');
Route::any ( '/openissue/update-openissue', 'Api\OpenissueApi@updateOpenIssue');
Route::any ( '/openissue/list-pending', 'Api\OpenissueApi@listPending');
Route::any ( '/openissue/list-approved', 'Api\OpenissueApi@listApproved');
Route::any ( '/openissue/get-openissue', 'Api\OpenissueApi@getOpenIssue');
Route::any ( '/openissue/update-approval', 'Api\OpenissueApi@updateApproval' );
Route::any ( '/openissue/update-approval-result', 'Api\OpenissueApi@updateApprovalResult' );
Route::any ( '/openissue/list-openissue', 'Api\OpenissueApi@listOpenIssue');
Route::any ( '/openissue/list-openissue-all', 'Api\OpenissueApi@listOpenIssueAll');
Route::any ( '/openissue/delete-openissue', 'Api\OpenissueApi@deleteOpenIssue' );
Route::any ( '/openissue/update-complete-status', 'Api\OpenissueApi@updateCompleteStatus' );
Route::any ( '/openissue/list-class', 'Api\OpenissueApi@listClass' );

Route::any ( '/plan/list-plans', 'Api\PlanApi@listPlans' );
Route::any ( '/plan/update-status', 'Api\PlanApi@updateStatus' );
Route::any ( '/plan/get-baseinfo', 'Api\PlanApi@getBaseInfo' );
Route::any ( '/plan/get-nodeinfo', 'Api\PlanApi@getNodeInfo' );
Route::any ( '/plan/get-nodecont', 'Api\PlanApi@getNodeCont');
Route::any ( '/plan/update-approval', 'Api\PlanApi@updateApproval');
Route::any ( '/plan/update-approval-result', 'Api\PlanApi@updateApprovalResult');
Route::any ( '/plan/list-nodes', 'Api\PlanApi@listNodes');
Route::any ( '/plan/get-approvalinfo', 'Api\PlanApi@getApprovalInfo');
Route::any ( '/plan/update-progress', 'Api\PlanApi@updateProgress');
Route::any ( '/plan/create-partplan', 'Api\PlanApi@createPartPlan' );
Route::any ( '/plan/update-partplan', 'Api\PlanApi@updatePartPlan' );
Route::any ( '/plan/list-partplan', 'Api\PlanApi@listPartPlan' );
Route::any ( '/plan/delete-partplan', 'Api\PlanApi@deletePartPlan' );
Route::any ( '/plan/get-partplan', 'Api\PlanApi@getPartPlan' );
Route::any ( '/plan/list-plan-type', 'Api\PlanApi@listPlanType');

Route::any ( '/workboard/list-planconfirm', 'Api\WorkboardApi@listPlanConfirm');
Route::any ( '/workboard/update-planconfirm', 'Api\WorkboardApi@updatePlanConfirm');
Route::any ( '/workboard/list-project-approval', 'Api\WorkboardApi@listProjectApproval' );
Route::any ( '/workboard/list-plan-approval', 'Api\WorkboardApi@listPlanApproval');
Route::any ( '/workboard/list-openissue-approval', 'Api\WorkboardApi@listOpenissueApproval');
Route::any ( '/workboard/list-nodeprogress', 'Api\WorkboardApi@listNodeProgress');
Route::any ( '/workboard/list-plan', 'Api\WorkboardApi@listBoardPlan');
Route::any ( '/workboard/list-depart-plan', 'Api\WorkboardApi@listDepartmentPlan');
Route::any ( '/workboard/list-openissue', 'Api\WorkboardApi@listBoardOpenissue');
Route::any ( '/workboard/list-openissue-progress', 'Api\WorkboardApi@listOpenissueProgress');

Route::any ( '/replication/add-user', 'ReplicationResponse@addUser');//BU站点only
Route::any ( '/replication/create-company', 'ReplicationResponse@createCompany');//BU站点路由only
Route::any ( '/replication/add-user-tomaster', 'ReplicationResponse@addUserToMaster');//主站路由only
Route::any ( '/replication/update-user', 'ReplicationResponse@updateUser');//主站及BU站

Route::any ( '/replication/update-password', 'ReplicationResponse@updatePassword');//主站及BU站
Route::any ( '/replication/update-company', 'ReplicationResponse@updateCompany');//主站及BU站
Route::any ( '/replication/check-email', 'ReplicationResponse@checkEmail');//主站
Route::any ( '/replication/check-mobile', 'ReplicationResponse@checkMobile');//主站
Route::any ( '/replication/add-cust-company-info', 'ReplicationResponse@addCustCompanyInfo');//BU站点only

Route::any ( '/backlog', 'Api\BacklogController@agenda');
Route::any ( '/project-circle/create', 'Api\ProjectCircleApi@createMsg');
Route::any ( '/project-circle/involve', 'Api\ProjectCircleApi@involvePlan');
Route::any ( '/project-circle/show', 'Api\ProjectCircleApi@showMsg');
Route::any ( '/project-circle/delete', 'Api\ProjectCircleApi@deleteMsg');
Route::any ( '/project-circle/praise', 'Api\ProjectCircleApi@praise');
Route::any ( '/project-circle/comment', 'Api\ProjectCircleApi@commentMsg');
Route::any ( '/project-config/node-list', 'CfgProjectController@NodeList');



