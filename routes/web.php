
<?php

/*
 |--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => ''], function() {
    Route::name('home.page')->get('/','FrontpageController@homepage');//front page: home page

    Route::name('home.page')->get('/contact','FrontpageController@contact');
    Route::name('home.page')->get('/about-us','FrontpageController@aboutUs');
    Route::name('home.page')->get('/solution','FrontpageController@solution');
    Route::name('home.page')->get('/partner','FrontpageController@partner');
    Route::name('home.page')->get('/join-us','FrontpageController@joinUs');
    Route::name('home.page')->get('/privacy','FrontpageController@privacy');
    // 验证手机号 邮箱
    Route::name('home.page')->get('/account-existed-check','AccountController@accountExistedCheck');
    Route::name('home.page')->get('/mobile-check-code','AccountController@mobileCheckCode');
    Route::name('home.page')->get('/email-check-code','AccountController@emailCheckCode');
    // 注册
    Route::name('home.page')->any('/signup','AccountController@signup');
    // 忘记密码
    Route::name('home.page')->any('/lost-password','AccountController@lostPassword');
    // 重置密码
    Route::name('home.page')->any('/reset-password/{token}/{identity}','AccountController@resetPassword');
    // 登录
    Route::name('login')->any('/login','AccountController@login');
    Route::name('home.page')->post('/login/wechat','AccountController@loginWechat');
    // 选择公司
    Route::name('home.page')->any('/select-company','AccountController@selectCompany');
});



Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'permission'] ], function() {

    Route::any('/trial-expiration','MbDailyController@trialExpiration');

    //---------------------------------------------------------------------------------------------

    // 个人信息 显示|修改
    Route::name('admin.profile')->any('/personal-profile','MbDailyController@personalProfile');
    // 更改密码 显示|修改
    Route::name('admin.change.password')->any('/change-password','MbDailyController@changePassword');
    // 我的公司
    Route::name('admin.mycompany')->any('/my-company','MbDailyController@myCompany');
    // 公司创建
    Route::name('admin.company.creation')->any('/company-creation','MbDailyController@companyCreation');
    // 公司编辑
    Route::name('admin.company.edit')->any('/company-edit/{token}/{company_id}','MbDailyController@companyEdit');

    // 公司名称补全
    Route::name('admin.company.completion')->any('/company-completion','MbDailyController@companyCompletion');
    // 公司信息补全
    Route::name('admin.company.info.completion')->any('/company-info-completion','MbDailyController@companyInfoCompletion');
    // 进入公司业务站点
    Route::name('admin.enter.worksite')->any('/enter-worksite/{token}/{company_id}','AccountController@enterWorksite');
    // 绑定微信
    Route::name('admin.bind.wechat')->get('/bind-wechat','AccountController@bindWechat');
    // 绑定微信结果
    Route::name('admin.bind.wechat.result')->any('/bind-wechat-result','AccountController@bindWechatResult');
    // 邀请朋友关注平台
    Route::name('admin.share.friend')->any('/share-friend','MbDailyController@shareFriend');
    // 购买服务
    Route::name('admin.purchase.service')->any('/purchase-service','MbDailyController@purchaseService');
    // 我的订单
    Route::name('admin.order.history')->any('/order-history','MbDailyController@orderHistory');

    //------------------------------------------------------------------------------------------------

    // 项目详细信息浏览
    Route::name('admin.project.view')->get('/project-view/{token}/{proj_id}','PlanController@projectView');
    // 工作台历
    Route::name('admin.project.view')->any('/project-view/{token}/{proj_id}','PlanController@projectView'); 
    // 项目详细信息浏览
    Route::group(['prefix' => 'workboard'], function() {
        Route::name('admin.workboard')->any('/','WorkboardController@workboard');
        // 未接受的任务列表 接收|不接受(可多条)
        Route::name('admin.plan.task.confirm.list')->any('/plan-task-confirm/list','WorkboardController@planTaskConfirmList');
        // 单条
        Route::name('admin.plan.task.confirm')->any('/plan-task-confirm/{token}/{task_id}','WorkboardController@planTaskConfirm');

        // 计划列表
        Route::name('admin.plan.task.list')->any('/plan-task-list','WorkboardController@planTaskList');
        // 进度录入 (列表)
        Route::name('admin.task.progress.input.list')->any('/task-progress-input/list','WorkboardController@taskProgressInputList');
        // 单条
        Route::name('admin.task.progress.input')->any('/task-progress-input/{token}/{task_id}','WorkboardController@taskProgressInput');
        // 进度添加修改
        Route::name('admin.openissue.progress.input.post')->post('/openissue-progress-input/post','WorkboardController@openissueProgressPost');
        // 拖期计划
        Route::name('admin.plan.task.delayed.list')->any('/plan-task-delayed/list','WorkboardController@taskDelayedList');
        // 部门计划
        Route::name('admin.my.department.task.list')->any('/my-department-task/list','WorkboardController@myDepartmentTaskList');
        // ISSUE拖期
        Route::name('admin.openissue.delayed.list')->any('/openissue-delayed/list','WorkboardController@openissueDelayedList');
        // Open Issue 进度录入(列表)
        Route::name('admin.openissue.progress.list')->any('/openissue-progress-list','WorkboardController@openissueProgressList');
        // 单条
        Route::name('admin.openissue.progress.input')->any('/openissue-progress-input/{token}/{id}','WorkboardController@openissueProgressInput');
        // ISSUE拖期
        Route::name('admin.openissue.list')->any('/openissue-list','WorkboardController@openissueList');
        // ISSUE
        Route::name('admin.openissue.view')->any('/openissue-view/{token}/{id}','WorkboardController@openissueView');
        // 任务详细
        Route::name('admin.plan.task.view')->any('/plan-task-view/{token}/{task_id}','WorkboardController@planTaskView');

    });

    //-----------------------------------------------------------------

    // 项目立项
    Route::name('admin.project.setup')->any('/setup-project','ProjectController@setupProject');
    // 项目列表
    Route::name('admin.project.list')->any('/list-project','ProjectController@listProject');
    // 项目列表（新）
    Route::name('admin.project.listNew')->any('/list-projectNew','ProjectController@listProjectNew');

    // 项目详细信息浏览
    Route::name('admin.show.project')->any('/show-project/{token}/{project_id}','ProjectController@showProject');
    // 项目详细信息浏览(新)
    Route::name('admin.show.project')->any('/show-projectNew/{token}/{project_id}','ProjectController@showProjectNew');


    // 指定子项目给供应商
    Route::name('admin.distribute.project')->any('/distribute-project','ProjectController@distributeProject');
    // 项目删除
    Route::name('admin.project.delete')->any('/delete-project','ProjectController@deleteProject');
    // 项目编辑(显示)
    Route::name('admin.project.edit')->any('/edit-project/{token}/{project_id}','ProjectController@editProject');

    // 项目编辑(新)
    Route::name('admin.project.edit')->any('/edit-projectNew/{token}/{project_id}','ProjectController@editProjectNew');

    // 项目发布
    Route::name('admin.project.save')->any('/save-project','ProjectController@saveProject');
    // 项目更新
    Route::name('admin.project.update')->any('/update-project','ProjectController@updateProject');

    // 项目关联
    Route::name('admin.project.accept')->any('/accept-project','ProjectController@acceptProject');
    // 项目审批
    Route::name('admin.project.approve')->any('/approve-project','ProjectController@approveProject');
    // 项目审批(新)
    Route::name('admin.project.approveNew')->any('/approve-projectNew','ProjectController@approveProjectNew');


    //---------------------------------------------------------------------------------------------------------

    // 项目计划
    Route::name('admin.plan.project')->any('/project-plan','PlanController@projectPlan');
    // 部门计划
    Route::group(['prefix' => 'department-plan'], function() {
        Route::name('admin.plan.department')->any('/','DepartmentPlanController@departmentPlan');
        // 计划定制
        Route::name('admin.plan.department.make')->any('/make/{token}/{task_id}','DepartmentPlanController@departmentPlanMake');
    });
    // 计划提交审批
    Route::group(['prefix' => 'plan-approval'], function() {
        // 计划提交审批列表
        Route::name('admin.plan.approval')->any('/','PlanController@planApproval');
        // 计划递交
        Route::name('admin.plan.approval.handin')->any('/handin/{token}/{plan_id}','PlanController@planApprovalHandin');
        // 计划审批
        Route::name('admin.plan.approval.stamp')->any('/stamp/{token}/{plan_id}','PlanController@planApprovalStamp');
    });
    // 节点列表
    Route::name('admin.plan.node.list')->any('/plan-node-list','PlanController@planNodeList');
    // 节点完结
    Route::name('admin.plan.node.complete')->any('/complete-plan-node/{token}/{task_id}','PlanController@completePlanNode');

    // 进度录入
    Route::name('admin.progress.feed.in')->any('/progress-feed-in','PlanController@progressFeedin');
    // 计划模板
    Route::name('admin.template.list')->any('/template-list','TaskController@templateList');

    //-------------------------------------------------------------------------------------------------------

    // ISSUE录入
    Route::name('admin.openissue.input.list')->any('/openissue-input-list','OpenIssueController@openissueInputList');
    // ISSUE录入列表
    Route::name('admin.openissue.input')->any('/openissue-input/{token}/{sourceid}','OpenIssueController@openissueInput');
    // ISSUE录入编辑

    Route::name('admin.openissue.edit')->any('/openissue-edit/{token}/{sourceid}/{issueid}','OpenIssueController@openissueEdit');
    // ISSUE审批
    Route::name('admin.openissue.approval')->any('/openissue-approval','OpenIssueController@openissueApproval');
    
    // ISSUE审批执行
    Route::name('admin.openissue.approval.stamp')->any('/openissue-approval/stamp/{token}/{id}','OpenIssueController@openissueApprovalStamp');
    
    // ISSUE单个审批执行?
    Route::name('admin.openissue.approvalaction')->any('/openissue-approvalaction','OpenIssueController@openissueApprovalAction');
    // ISSUE列表
    Route::name('admin.openissue.list')->any('/openissue-list','OpenIssueController@openissueList');

    // TODO
    Route::any('/openissue-cont','OpenIssueController@openissueCont');
    Route::any('/openissue-show','OpenIssueController@openissueShow');
    Route::any('/openissue-progressction','OpenIssueController@openissueProgresslAction');
    Route::any('/openissue-progress/{token}/{sourceid}/{issueid}','OpenIssueController@openissueProgress');
    Route::any('/openissue-update','OpenIssueController@updateopenissue');

    //------------------------------------------------------------------------------------------------

    // 项目零件文件
    Route::name('admin.file.management')->any('/file-management','FolderController@fileManagement');
    // 项目文档维护
    Route::name('admin.project.file.maintenance')->any('/project/file-maintenance/{token}/{project_id}','FolderController@projectFileMaintenance');

    // 新增项目文档
    Route::name('admin.project.file.add')->any('/project/file-maintenance/{project_id}','FolderController@addFile');



    // 零件文档维护
    Route::name('admin.file.maintenance')->any('/file-maintenance/{token}/{detial_id}','FolderController@fileMaintenance');
    // 项目(计划)文档查看
    Route::name('admin.file.view')->any('/file-view/{token}/{file_id}','FolderController@fileView');
    // 项目(计划)文档删除
    Route::name('admin.file.delete')->any('/file-delete/{token}/{file_id}','FolderController@fileDelete');
    // 项目(计划)文档下载
    Route::name('admin.file.download')->any('/file-download/{token}/{file_id}','FolderController@fileDownload');
    // 项目计划文件
    Route::name('admin.plan.instruction.document')->any('/plan/instruction-document','FolderController@planInstructionDocument');
    // 项目计划文档维护
    Route::name('admin.plan.file.maintenance')->any('/plan/file-maintenance/{token}/{plan_id}','FolderController@planFileMaintenance');
    // 计划节点文件
    Route::name('admin.node.instruction.document')->any('/node/instruction-document','FolderController@nodeInstructionDocument');
    // 计划节点文件查看
    Route::name('admin.node.file.view')->any('/node-file/view/{token}/{file_id}','FolderController@nodeFileView');
    // 计划节点文件删除
    Route::name('admin.node.file.delete')->any('/node-file/delete/{token}/{file_id}','FolderController@nodeFileDelete');
    // 计划节点文件下载
    Route::name('admin.node.file.download')->any('/node-file/download/{token}/{file_id}','FolderController@nodeFileDownload');
    // ISSUE文件
    Route::name('admin.issue.document')->any('/issue/issue-document','FolderController@issueDocument');
    // ISSUE文档维护
    Route::name('admin.issue.file.maintenance')->any('/issue/file-maintenance/{token}/{issue_id}','FolderController@issueFileMaintenance');

    //------------------------------------------------------------------------------------------------

    // 权限管理

    Route::name('admin.permission.management')->any('/permission-management/{pid?}','RoleController@permissionManagement');
    // 角色管理(列表) 新增|编辑
    Route::name('admin.role.management')->any('/role-management','RoleController@roleManagement');
    // 账号管理(列表)
    Route::name('admin.account.management')->any('/account-management','RoleController@accountManagement');
    // 编辑(单个账户)
    Route::name('admin.account.management.role.assignment')->any('/account-management/role-assignment/{token}/{id}','RoleController@roleAssignment');
    // 角色资源控制
    Route::name('admin.role.control')->any('/role-control','RoleController@roleControl');
    // 审核管理
    Route::name('admin.verify')->any('/verify-control','RoleController@verifyControl');

    Route::any('/get-role-info','RoleController@getRoleInfo');
    Route::any('/role-resource-map/setup','RoleController@roleResourceSetup');
    Route::any('/get-role-resource-info','RoleController@getRoleResourceInfo');

    //--------------------------------------------------------------------------------------------

    //基础配置basic config

    // 行政区划管理
    Route::group(['prefix' => 'admin-region'], function() {
        // 国家
        Route::name('admin.region')->get('/','ConfigController@adminRegion');
        // 与上相同
        Route::name('admin.region.country')->get('/country','ConfigController@country');
        // 省份
        Route::name('admin.region.province')->any('/province','ConfigController@province');
        // 城市
        Route::name('admin.region.city')->any('/city','ConfigController@city');
    });

    // 供应商管理
    Route::group(['prefix' => 'supplier'], function() {
        // 列表
        Route::name('admin.supplier')->any('/','CfgSupplierController@supplier');
        // 拣选供应商 添加供应商
        Route::name('admin.supplier.select')->any('/select','CfgSupplierController@supplierSelect');
        // 编辑供应商
        Route::name('admin.supplier.edit')->any('/edit/{token}/{id}','CfgSupplierController@supplierEdit');
        // 删除供应商
        Route::name('admin.supplier.delete')->any('/delete/{token}/{id}','CfgSupplierController@supplierDelete');
    });

    // 客户管理
    Route::group(['prefix' => 'customer'], function() {
        // 列表
        Route::name('admin.customer')->any('/','CustomerController@customer');
        // 拣选客户  添加客户
        Route::name('admin.customer.select')->any('/select','CustomerController@customerSelect');
        // 编辑客户
        Route::name('admin.customer.edit')->any('/edit/{token}/{id}','CustomerController@customerEdit');
        // 删除客户
        Route::name('admin.customer.delete')->any('/delete/{token}/{id}','CustomerController@customerDelete');
    });

    // 货币及兑换设置
    Route::name('admin.currency')->any('/currency','ConfigController@currency');

    // 编码规则设置
    Route::group(['prefix' => 'other-setup'], function() {
        // 流水序列号设置
        Route::name('admin.other.setup')->any('/','ConfigController@otherSetup');
        // 流水序列号设置 编辑
        Route::name('admin.other.setup.edit')->any('/serial-number/edit/{token}/{id}','ConfigController@serialNumberEdit');
        // 流水序列号设置 删除
        Route::name('admin.other.setup.delete')->any('/serial-number/delete/{token}/{id}','ConfigController@serialNumberDelete');
        // 工具文档 列表|新增
        Route::name('admin.other.setup.tool')->any('/tool-file','ConfigController@toolFile');
        // 工具文档编辑
        Route::name('admin.other.setup.tool.edit')->any('/tool-file/edit/{token}/{id}','ConfigController@toolFileEdit');
        // 工具文档删除
        Route::name('admin.other.setup.tool.delete')->any('/tool-file/delete/{token}/{id}','ConfigController@toolFileDelete');
    });

    // 设置修改公司基本情况
    Route::name('admin.company.profile')->any('/company-profile','MbDailyController@companyProfile');
    // 部门管理 列表|新增
    Route::name('admin.department')->any('/department-setup','CfgCompanyController@departmentSetup');
    // 部门编辑
    Route::name('admin.department.edit')->any('/department/edit/{token}/{id}','CfgCompanyController@departmentEdit');
    // 部门删除
    Route::name('admin.department.delete')->any('/department/delete/{token}/{id}','CfgCompanyController@departmentDelete');
   //检查部门编号，名称是否已被使用
    Route::name('admin.department.check.existed')->any('/check-existed-department','CfgCompanyController@checkExistedDepartment');
    
    
    // 员工管理
    Route::group(['prefix' => 'employee'], function() {
        // 员工列表|新增
        Route::name('admin.employee')->any('/employee-list','CfgCompanyController@employeeList');
        // 编辑
        Route::name('admin.employee.edit')->any('/edit/{token}/{emp_id}','CfgCompanyController@employeeEdit');
        // 离职
        Route::name('admin.employee.dismiss')->any('/dismiss/{token}/{emp_id}','CfgCompanyController@employeeDismiss');
        // 冻结
        Route::name('admin.employee.frozen')->any('/frozen/{token}/{emp_id}','CfgCompanyController@employeeFrozen');
        // 删除
        Route::name('admin.employee.delete')->any('/delete/{token}/{emp_id}','CfgCompanyController@employeeDelete');
        // 手工添加员工时检查编号和邮箱是否已存在
        Route::name('admin.employee.check')->any('/add-employee-check','CfgCompanyController@addEmployeeCheck');
        // 批量添加员工
        // 员工列表|新增
        Route::name('admin.employee.position')->any('/position','CfgCompanyController@position');
        // 检查职位是否存在   
        Route::name('admin.employee.position.check')->any('/check-existed-position','CfgCompanyController@checkExistedPosition');
        // 编辑
        Route::name('admin.employee.position.edit')->any('/position/edit/{token}/{pid}','CfgCompanyController@positionEdit');
        // 删除
        Route::name('admin.employee.position.edit')->any('/position/delete/{token}/{pid}','CfgCompanyController@positionDelete');
        Route::name('admin.employee.group.add')->any('/group-add','CfgCompanyController@employeeGroupAdd');
        // 拉用户进公司
        Route::name('admin.employee.associate.company')->any('/associate-user-to-company','CfgCompanyController@associateUserToCompany');
    });

    //----------------------------------------------------------------------------------------------
    // 项目设置
    Route::group(['prefix' => 'project-config'], function() {
        // 项目类型
        Route::group(['prefix' => 'project-type'], function() {
            // 列表|新增
            Route::name('admin.project.type')->any('/','CfgProjectController@projectType');
            // 项目类型编辑
            Route::name('admin.project.type.edit')->any('/edit/{token}/{type_id}','CfgProjectController@projectTypeEdit');
            // 项目类型删除
            Route::name('admin.project.type.delete')->any('/delete/{token}/{type_id}','CfgProjectController@projectTypeDelete');
        });
        // 零件类型
        Route::group(['prefix' => 'part-type'], function() {
            // 列表|新增
            Route::name('admin.part.type')->any('/','CfgProjectController@partType');
            // 编辑
            Route::name('admin.part.type.edit')->any('/edit/{token}/{type_id}','CfgProjectController@partTypeEdit');
            // 删除
            Route::name('admin.part.type.delete')->any('/delete/{token}/{type_id}','CfgProjectController@partTypeDelete');
            // 检查零件类型是否存在
            Route::name('admin.part.type.check')->any('/check-existed-part-type','CfgProjectController@checkExistedPartType');
        });

        // 计划类型
        Route::group(['prefix' => 'plan-type'], function() {
            // 列表|新增
            Route::name('admin.plan.type')->any('/','CfgProjectController@planType');
            // 编辑
            Route::name('admin.plan.type.edit')->any('/edit/{token}/{type_id}','CfgProjectController@planTypeEdit');
            // 删除
            Route::name('admin.plan.type.delete')->any('/delete/{token}/{type_id}','CfgProjectController@planTypeDelete');
            // 检查计划类型是否存在
            Route::name('admin.plan.type.check')->any('/check-existed-plan-type','CfgProjectController@checkExistedPlanType');
        });

        // 节点类型
        Route::group(['prefix' => 'node-type'], function() {
            // 列表|新增
            Route::name('admin.node.type')->any('/','CfgProjectController@nodeType');
            // 编辑
            Route::name('admin.node.type.edit')->any('/edit/{token}/{type_id}','CfgProjectController@nodeTypeEdit');
            // 删除
            Route::name('admin.node.type.delete')->any('/delete/{token}/{type_id}','CfgProjectController@nodeTypeDelete');
        });

        // 计划节点列表
        Route::name('admin.node.list')->any('/node-list','CfgProjectController@nodeList');
        // 节点编辑
        Route::name('admin.node.list.edit')->any('/node/edit/{token}/{node_id}','CfgProjectController@nodeEdit');
        // 节点删除
        Route::name('admin.node.list.delete')->any('/node/delete/{token}/{node_id}','CfgProjectController@nodeDelete');
        // 节点定制
        Route::name('admin.node.list.customize')->any('/customize-node/{token}/{node_id}','CfgProjectController@customizeNode');
        // 节点批量定制
        Route::name('admin.node.list.batch.customize')->any('/batch-customize-node','CfgProjectController@batchCustomizeNode');
        // 节点配置
        Route::name('admin.node.list.setting')->any('/node-setting','CfgProjectController@nodeSetting');
        // 部门日历
        Route::name('admin.department.calendar')->any('/department-calendar','CfgProjectController@departmentCalendar');
        // 审批人设置
        Route::name('admin.company.approver')->any('/company/approver','CfgCompanyController@companyApprover');
    });

    //------------------------------------------------------------------------------------------
    // Issue 设置
    Route::group(['prefix' => 'issue-config'], function() {
        // Open Issue 来源 列表|新增
        Route::name('admin.issue.source')->any('/','CfgIssueController@issueSource');
        // 编辑
        Route::name('admin.issue.source.edit')->any('/issue-source/edit/{token}/{id}','CfgIssueController@issueSourceEdit');
        // 删除
        Route::name('admin.issue.source.delete')->any('/issue-source/delete/{token}/{id}','CfgIssueController@issueSourceDelete');
        // Open Issue 分类 列表|新增
        Route::name('admin.issue.class')->any('/issue-class','CfgIssueController@issueClass');
        // 编辑
        Route::name('admin.issue.class.edit')->any('/issue-class/edit/{token}/{id}','CfgIssueController@issueClassEdit');
        // 删除
        Route::name('admin.issue.class.delete')->any('/issue-class/delete/{token}/{id}','CfgIssueController@issueClassDelete');
    });

    //-----------------------------------------------------------------------------------------------

    // 日历维护
    Route::group(['prefix' => 'calendar'], function() {
        // 日历  列表
        Route::name('admin.calendar')->any('/','ConfigController@calendar');
        // 新增
        Route::name('admin.calendar.add')->any('/add','ConfigController@calendar');
        // 编辑
        Route::name('admin.calendar.edit')->any('/edit/{token}/{id}','ConfigController@calendarEdit');
        // 删除
        Route::name('admin.calendar.delete')->any('/delete/{token}/{id}','ConfigController@calendarDelete');
        // 工作班次
        Route::group(['prefix' => 'work-shift'], function() {
            // 列表
            Route::name('admin.calendar.work')->any('/','ConfigController@workShift');
            // 新增
            Route::name('admin.calendar.work.add')->any('/add','ConfigController@workShiftAdd');
            // 编辑
            Route::name('admin.calendar.work.edit')->any('/edit/{token}/{id}','ConfigController@workShiftEdit');
            // 删除
            Route::name('admin.calendar.work.delete')->any('/delete/{token}/{id}','ConfigController@workShiftDelete');
        });
        // 定制
        Route::name('admin.calendar.make')->any('/make/{token}/{cal_id}','ConfigController@calendarMake');
        // 定制(新)
        Route::name('admin.calendar.make')->any('/makeNew/{token}/{cal_id}','ConfigController@calendarMakeNew');
    });

    //------------------------------------------------------------------------------------------
    
    // 计划控制扫描配置
    Route::group(['prefix' => 'plan-scan'], function() {
        // 部门计划拖期 展示|编辑
        Route::name('admin.plan.scan.dealy')->any('/department-dealy','CfgPlanScanController@departmentDelay');
        // 计划开始
        Route::name('admin.plan.scan.start')->any('/plan-start','CfgPlanScanController@planStart');
        // 计划完成
        Route::name('admin.plan.scan.completion')->any('/plan-completion','CfgPlanScanController@planCompletion');
        // 预警计划开始
        Route::name('admin.plan.scan.alert.start')->any('/plan-start-alert','CfgPlanScanController@planStartAlert');
        // 预警计划结束
        Route::name('admin.plan.scan.alert.completion')->any('/plan-completion-alert','CfgPlanScanController@planCompletionAlert');
    });
    
    //--------------------------------------------------------------------------------------------

    // Issue 扫描配置
    Route::group(['prefix' => 'issue-scan'], function() {
        // 汇总-计划日期
        Route::name('admin.issue.scan.plan')->any('/plan-date','CfgIssueScanController@planDate');
        // 汇总-实际日期
        Route::name('admin.issue.scan.real')->any('/real-date','CfgIssueScanController@realDate');
        // 汇总-提出日期
        Route::name('admin.issue.scan.reported')->any('/reported-date','CfgIssueScanController@reportedDate');
        // ISSUE 预警
        Route::name('admin.issue.scan.alert')->any('/issue-alert','CfgIssueScanController@issueAlert');
    });

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    // TODO
    
    // 度量单位管理
    Route::post('/message-be-read','MbDailyController@messageBeRead');//登录用户读取提醒信息：页面右上角
 
    Route::any('/save-files','ProjectController@saveFiles');//立项管理-项目立项。待定：等周兵修改项目立项后
    Route::any('/delete-files','ProjectController@deleteFiles');//立项管理-项目立项。待定：等周兵修改项目立项后
    Route::any('/approval-project','ProjectController@approvalProject');///立项管理-项目审批

    Route::post('/edit-plan-master','PlanController@editPlanMaster');//计划控制-项目计划-计划编号（计划名称）
    Route::any('/make-plan/{token}/{plan_id}','PlanController@makePlan');//计划控制-项目计划-计划制定
    Route::any('/view-plan-chart/{token}/{plan_id}','PlanController@viewPlanChart');//计划控制-项目计划-计划制定（计划查看）
    Route::any('/view-plan-chart/viwe-task-bar/{token}/{task_id}','PlanController@viewTaskBar');//计划控制-项目计划-计划查看-（点击）任务条

    Route::any('/plan-task/{token}/{plan_id}','PlanController@planTask');//计划控制-项目计划-计划制定-（点击）任务条
    Route::any('/plan-task-link/{token}/{plan_id}','PlanController@planTaskLink');//计划控制-项目计划-计划制定-任务条的连线
    Route::any('/plan-task-create','PlanController@planTaskCreate');//计划控制-项目计划-计划制定-添加任务节点
    Route::any('/plan-task-edit/{token}/{task_id}','PlanController@planTaskEdit');//计划控制-项目计划-计划制定-添加任务节点
    Route::any('/plan-task-update','PlanController@planTaskUpdate');//计划控制-项目计划-计划制定-更新任务节点
    Route::any('/plan-task-delete','PlanController@planTaskDelete');//计划控制-项目计划-计划制定-删除任务节点
    Route::any('/plan-task-link-create','PlanController@planTaskLinkCreate');//计划控制-项目计划-计划制定-创建任务节点之间的联系:SF,FF...
    Route::any('/plan-task-link-delete','PlanController@planTaskLinkDelete');//计划控制-项目计划-计划制定-删除任务节点之间的联系线:SF,FF...
    Route::any('/plan-task-move','PlanController@planTaskMove');//计划控制-项目计划-计划制定： 左右移动任务条
    Route::any('/plan-task-row-move','PlanController@planTaskRowMove');//计划控制-项目计划-计划制定： 上下移动左侧节点任务条
    Route::any('/pause-plan/{token}/{plan_id}','PlanController@pausePlan');//计划控制-项目计划-暂停●恢复
    Route::any('/resume-plan/{token}/{plan_id}','PlanController@resumePlan');//计划控制-项目计划-暂停●恢复
    Route::any('/complete-plan/{token}/{plan_id}','PlanController@completePlan');//计划控制-项目计划-完结●反完结	
    Route::any('/anti-complete-plan/{token}/{plan_id}','PlanController@antiCompletePlan');//计划控制-项目计划-完结●反完结	
    Route::any('/anti-complete-plan-node/{token}/{task_id}','PlanController@antiCompletePlanNode');//计划控制-节点列表-完结●反完结	

    Route::any('/get-plan-node-info','PlanController@getPlanNodeInfo');//计划控制-项目计划-计划制定：（点击）任务条-节点详细设置
    Route::any('/get-plan-task-info2edit','PlanController@getPlanTaskInfo2Edit'); //计划控制-项目计划-计划制定(编辑/任务栏）
    Route::any('/get-node-by-type','PlanController@getNodeByType');//计划控制-项目计划-计划制定：（点击）任务条-节点详细设置
    Route::any('/get-department-by-nodetype','PlanController@getDepartmentByNodeType');//计划控制-模板制定-计划制定：（点击）任务条-节点详细设置
    
    Route::any('/plan-task-detail/view/{token}/{task_id}','PlanController@planTaskDetail');
    //##########################################
    Route::any('/department-task/{token}/{task_id}','DepartmentPlanController@departmentTask');
    Route::any('/department-task-link/{token}/{plan_id}','DepartmentPlanController@departmentTaskLink');
    Route::any('/department-task-create','DepartmentPlanController@departmentTaskCreate');
    Route::any('/department-task-update','DepartmentPlanController@departmentTaskUpdate');
    Route::any('/department-task-delete','DepartmentPlanController@departmentTaskDelete');
    Route::any('/department-task-link-create','DepartmentPlanController@departmentTaskLinkCreate');
    Route::any('/department-task-link-delete','DepartmentPlanController@departmentTaskLinkDelete');
    Route::any('/department-task-move','DepartmentPlanController@departmentTaskMove');
    Route::any('/department-task-row-move','DepartmentPlanController@departmentTaskRowMove');
    Route::any('/get-department-task-info2edit','DepartmentPlanController@getDepartmentTaskInfo2Edit');
    //###########################################

    Route::any('/template/edit/{token}/{id}','TaskController@templateEdit');
    Route::any('/template/delete/{token}/{id}','TaskController@templateDelete');
    Route::any('/template/make/{token}/{template_id}','TaskController@templateMake');
    Route::any('/task/{token}/{template_id}','TaskController@task');
    Route::any('/task-edit/{token}/{id}','TaskController@taskEdit');
    Route::any('/task-update/{token}/{id}','TaskController@taskUpdate');
    Route::any('/task-create','TaskController@taskCreate');
    Route::any('/task-delete','TaskController@taskDelete');
    Route::any('/task-move','TaskController@taskMove');//task-bar move
    Route::any('/task-row-move','TaskController@taskRowMove');//task-name move
    Route::any('/task-link/{token}/{template_id}','TaskController@taskLink');
    Route::any('/task-link-create','TaskController@taskLinkCreate');
    Route::any('/task-link-delete','TaskController@taskLinkDelete');

    Route::any('/get-company-info','AjaxController@getCompanyInfo');

    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    //-------------------------------------------------------------------------------------------

    // 退出
    Route::name('admin.logout')->get('/logout', 'AccountController@logout');
    // -------------------------------------------------------------------------------------------
});

// 主控台
Route::name('admin.index')->get('/dashboard/{token?}/{string?}','MbDailyController@dashboard');





//Route::any('/payment/{token}/{adId}','PaymentController@payment');
//Route::get('/payment-result/{token}/{adId}','PaymentController@paymentResult');


Route::any('/app/view-plan-chart/{token}/{company}/{uid}/{plan_id}/','PlanController@appViewPlan');


Route::get('/signup-success','FrontpageController@signupSuccess');
Route::get('/contact','FrontpageController@contact');

Route::get('/dropdown','AjaxController@dropdownListAjax');
Route::get('/select-lang/{locale}','LanguageController@setLanguage');
Route::any("/upload/{classfication}",'UploadFileController@uploader');
Route::any('/relink','RelinkFileController@removeTempFile');//general remove temp file for dropzone

//BU,platform admin
Route::any('/pfadmin/login','AdminAccountController@login');
Route::get('/pfadmin/home','AdminAccountController@buHome');
Route::get('/pfadmin/project-type','AdminBusinessController@projectType');
Route::get('/pfadmin/part-type','AdminBusinessController@partType');
Route::get('/pfadmin/plan-type','AdminBusinessController@planType');
Route::get('/pfadmin/node-type','AdminBusinessController@nodeType');
Route::get('/pfadmin/node-list','AdminBusinessController@nodeList');
Route::get('/pfadmin/company-list','AdminBusinessController@companyList');
Route::any('/pfadmin/new-arrival-approval','AdminBusinessController@newArrivalApproval');

//Platform Supadm
Route::any('/pfadmin-su/login','AdminAccountController@superLogin');
Route::get('/pfadmin-su/home','AdminAccountController@superHome'); 

Route::any('/request-password-reset-form','Auth\ForgotPasswordController@showLinkRequestForm');
Route::any('/email-test','EmailController@testMailServer');
Route::any('/weixin/share','AccountApi@weixinShare');
Route::get('/dashboard1/test','TestController@index');

