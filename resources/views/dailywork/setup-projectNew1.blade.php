@extends('backend-base')

@section('css.append')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- page specific plugin styles -->
    <!-- third-party plugins (smartwizard/datetimepicker/multiple select/dropzone/jqgrid) -->

    {{--Include SmartWizard CSS--}}
    <link href="/asset/SmartWizard-master/dist/css/smart_wizard.min.css" rel="stylesheet" type="text/css"/>
    {{--Optional SmartWizard theme[arrow]--}}
    <link href="/asset/SmartWizard-master/dist/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css"/>
    {{--datetimepicker plugin--}}
    <link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    {{--chosen plugin--}}
    <link rel="stylesheet" href="/asset/chosen_v1.8.3/chosen.min.css">
    {{--dropzone plugin--}}
    <link rel="stylesheet" href="/asset/dropzone4/dropzone.css">
    {{--jqgrid plugin [Requirements：jquery-ui主题文件]--}}
    <link rel="stylesheet" href="/asset/Guriddo_jqGrid_JS_5.3.0/css/ui.jqgrid.css">
    <link rel="stylesheet" href="/asset/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <link rel="stylesheet" href="/asset/Guriddo_jqGrid_JS_5.3.0/css/ui.jqgrid-bootstrap-ui.css">
    <link rel="stylesheet" href="/asset/Guriddo_jqGrid_JS_5.3.0/css/ui.jqgrid-bootstrap.css">

    <style type="text/css">
        div.content {
            padding: 15px;
        }
        .sw-theme-arrows > ul.step-anchor > li > a {
            line-height: 40px;
        }
        .sw-theme-arrows > .sw-container {
            padding-top: 50px;
        }
        .col-form-label {
            padding-top: calc(.375rem + 1px);
            padding-bottom: calc(.375rem + 1px);
            margin-bottom: 0;
            font-size: inherit;
            line-height: 1.5;
        }
        div.col-sm-3{
            padding-left: 0;
        }
        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            padding-left: 8%;
            padding-right: 8%;
        }

        /*alter jqgrid style*/
        .ui-jqgrid .ui-jqgrid-htable .ui-th-div {
            height: 40px;
            display: table-cell;
            width: 100px;
            vertical-align: middle;
            text-align: center;
        }
        .ui-jqgrid .ui-jqgrid-bdiv {
            border-top: 1px solid #ccc;
        }
        .ui-jqgrid .ui-jqgrid-btable tbody tr.jqgrow td {
            padding-right: 0px;
        }
        .ui-jqgrid tr.jqgrow td, .ui-jqgrid tr.jqgroup td {
            padding: 0px;
        }
        .ui-jqgrid .ui-jqgrid-view input, .ui-jqgrid .ui-jqgrid-view select, .ui-jqgrid .ui-jqgrid-view textarea, .ui-jqgrid .ui-jqgrid-view button {
            display: table-cell;
        }
        .ui-jqgrid .ui-pager-control .ui-pager-table td {
            line-height: 30px;
        }

        /*alter bootstrap style*/
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 0px;
            line-height: 40px;
            vertical-align: middle;
        }

        /*alter app.css style*/
        input[type=checkbox] {
             zoom: 100%;
        }
    </style>
@stop

@section('content')
    <div class="content">
        <!-- SmartWizard html -->
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">1:新建项目</a></li>
                <li><a href="#step-2">2:新建零件</a></li>
                <li><a href="#step-3">3:新建计划</a></li>
            </ul>
            <div>
                <div id="step-1" class="">
                    <form class="form-horizontal" id="sample-form">
                        <div class="form-group">
                            {{--项目编号--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_number')}} </label>
                            <div class="col-sm-3">
                                <input id="project_number" readonly="readonly" value="<?php echo $result['project_number']?>" class="form-control" name="project_number" placeholder="{{Lang::get('mowork.project_number')}}">
                            </div>
                            {{--项目名称--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_name')}} </label>
                            <div class="col-sm-3">
                                <input id="project_name" class="form-control" placeholder="项目名称">
                            </div>
                            {{--项目类别--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_category')}} </label>
                            <div class="col-sm-3">
                                <select id="project_category" class="form-control" name="project_category" placeholder="项目类别" >
                                    @foreach($result['project_type'] as $key => $row)
                                        <option value="{{$row['type_id']}}">{{$row['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {{--客户名称--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.customer_name')}} </label>
                            <div class="col-sm-3">
                                <select id="customer_number" class="form-control" name="customer_number">
                                    <?php foreach ($result['customer'] as $key => $value) { ?>
                                    <option value="{{$value['cust_company_id']}}">{{$value['company_name']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                            {{--项目经理--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_manager')}} </label>
                            <div class="col-sm-3">
                                <select id="proj_manager_uid" class="form-control" name="proj_manager_uid">
                                    <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <option value="{{$value['uid']}}">{{$value['fullname']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                            {{--项目成员--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_member')}} </label>
                            <div class="col-sm-3">
                                <select multiple id="project_member_value" class="chosen-select form-control" name="project_member" data-placeholder="选择成员">
                                    <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <option value="{{$value['uid']}}" username="<?php echo $value['fullname']?>">{{$value['fullname']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {{--项目日历--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_calendar')}} </label>
                            <div class="col-sm-3">
                                <div>
                                    <select id="project_calendar" class="form-control" name="project_calendar" placeholder="项目日历">
                                        <?php foreach ($result['calendar'] as $key => $value) { ?>
                                        <option value="{{$value['cal_id']}}">{{$value['cal_name']}}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            {{--项目性质--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.project_nature')}} </label>
                            <div class="col-sm-3">
                                {{--<input type="text" id="form-field-1" placeholder="项目性质"--}}
                                {{--class="col-xs-12 col-sm-9">--}}
                                <select id="project_plan" class="form-control" name="project_plan" placeholder="项目性质">
                                    <option value="1">{{Lang::get('mowork.public_plan')}}</option>
                                    <option value="0">{{Lang::get('mowork.private_plan')}}</option>
                                </select>
                            </div>
                            {{--接受日期--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.date_acceptance')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_acceptance" data-link-format="yyyy-mm-dd">
                                        <input id="start_date" class="form-control border-left-squar" name="start_date" size="16"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{--结束日期--}}
                            <label class="col-sm-1 col-form-label text-info" for="">
                                * {{Lang::get('mowork.date_end')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                        <input id="end_date" class="form-control border-left-squar" size="16" name="end_date"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            {{-- 工艺验证日期 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_validation')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                        <input id="validation_date" class="form-control border-left-squar" size="16" name="end_date"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            {{-- 出模样件日期 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_sample')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                        <input id="sample_date" class="form-control border-left-squar" size="16" name="end_date"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{-- 试产验证日期 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_verification')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                        <input id="pilot_date" class="form-control border-left-squar" size="16" name="end_date"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            {{-- 批量放产日期 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_delivery')}} </label>
                            <div class="col-sm-3">
                                <div style="width: 100%">
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd"
                                         data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                        <input id="production_date" class="form-control border-left-squar" size="16" name="end_date"
                                               type="text" value="" required="required">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{-- 项目描述 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.project_desction')}} </label>
                            <div class="col-sm-12">
                                <textarea id="project_desction" class="autosize-transition form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            {{-- 项目文件上传 --}}
                            <label class="col-sm-1 control-label no-padding-right text-info"
                                   for=""> 项目文件上传 </label>
                            {{--<div class="col-sm-12">--}}
                                {{--<div>--}}
                                    {{--<form action="{{ url('/upload/instruction') }}" class="dropzone"--}}
                                          {{--id="mydropzone" style="min-height: 20px;">--}}
                                        {{--<input name="_token" value="{{ csrf_token() }}" type="hidden">--}}
                                    {{--</form>--}}
                                {{--</div>--}}
                                {{--{{ Form::open(['url'=>"/dashboard/file-maintenance/$token/$detail_id"])}}--}}
                                    {{--<div class="form-group">--}}
                                        {{--{{ Form::label("".Lang::get('mowork.folder_category').":") }}--}}
                                    {{--</div>--}}
                                    {{--<input type="hidden" name='folder_cat' value="2">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<button class="btn btn-info">{{Lang::get('mowork.upload_file')}}</button>--}}
                                        {{--<input type="hidden" name="submit" value="submit">--}}
                                    {{--</div>--}}
                                {{--{!! Form::close() !!}--}}
                            {{--</div>--}}
                        </div>
                    </form>
                </div>
                <div id="step-2" class="">
                    <div class="jqGrid_wrapper">
                        <table id="creatParts"></table>
                        <div id="partsGridNav"></div>
                    </div>
                </div>
                <div id="step-3" class="">
                    <div class="jqGrid_wrapper">
                        <table id="creatPlans"></table>
                        <div id="plansGridNav"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer.append')
    <!-- page specific plugin scripts -->
    <!-- 导航向导:smartwizard/日历datetimepicker/多选输入下拉框/文件上传dropzone/验证/jqgrid表格/弹窗 -->
    {{--Include SmartWizard JavaScript source--}}
    <script src="/asset/SmartWizard-master/dist/js/jquery.smartWizard.min.js" type="application/javascript"></script>
    {{--Include SmartWizard JavaScript sourc--}}
    <script src="/asset/js/bootstrap-datetimepicker.js"></script>
    {{--Include chosen JavaScript sourc--}}
    <script src="/asset/chosen_v1.8.3/chosen.jquery.min.js"></script>
    {{--Include dropzone JavaScript sourc--}}
    <script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
    {{--Include jqgrid JavaScript sourc--}}
    <script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/i18n/grid.locale-en.js"></script>
    <script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/jquery.jqGrid.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            //为所有ajax默认添加请求头
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
            Date.prototype.Format = function (fmt) {
                var o = {
                    "M+": this.getMonth() + 1, //月份
                    "d+": this.getDate(), //日
                    "h+": this.getHours(), //小时
                    "m+": this.getMinutes(), //分
                    "s+": this.getSeconds(), //秒
                    "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                    "S": this.getMilliseconds() //毫秒
                };
                if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (var k in o)
                    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                return fmt;
            };
            //datetimepicker
            $('.form_date').datetimepicker({
                language: 'zh',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                startDate: new Date().Format("yyyy-MM-dd"),
            });

            // 设置jqgrid宽度
            var jqParts_width = $('.jqGrid_wrapper').width() - 30;

            // 零件类型
            var part_type = "";
            @foreach($result['part_type'] as $key => $row)
                part_type += "{{$row['name']}}:{{$row['name']}};"
            @endforeach
            part_type = part_type.substring(0, part_type.length-1);
            // 零件编号
            var part_number = "";
            part_number = "<?php echo $result['part_number']?>";


            //计划类型
            var plan_type = "";
            @foreach($result['plan_type'] as $key => $row)
                plan_type += "{{$row['type_name']}}:{{$row['type_name']}};"
            @endforeach
            plan_type = plan_type.substring(0, plan_type.length-1);
            //计划负责人
            var plan_leader = "";
            @foreach($result['company_user'] as $key => $row)
                plan_leader += "{{$row['fullname']}}:{{$row['fullname']}};"
            @endforeach
            plan_leader = plan_leader.substring(0, plan_leader.length-1);
            //计划成员
            var plan_member = "";
            @foreach($result['company_user'] as $key => $row)
                plan_member += "{{$row['fullname']}}:{{$row['fullname']}};"
            @endforeach
            plan_member = plan_member.substring(0, plan_member.length-1);
            // 计划编号
            var plan_number = "";
            plan_number = "<?php echo $result['project_coding']?>";
            // 模具编号
            var mold_number = "";
            mold_number = "<?php echo $result['mold_coding']?>";
            // 检具编号
            var jig_number = "";
            jig_number = "<?php echo $result['jig_coding']?>";
            // 夹具编号
            var gauge_number = "";
            gauge_number = "<?php echo $result['gauge_coding']?>";


            // 新增每行零件数据时,生成计划缺省信息
            var plans_grid_data = [];


            //项目立项完成提交项目、零件、计划逻辑
            function btnSubmit(){
                //项目信息
                // var data = {
                //     "project_info":{
                //         "project_code" : $("#project_number").val(),//项目名称
                //         "project_name" : $("#project_name").val(),//项目名称
                //         "project_category" : $("#project_category").val(),//项目类型
                //         "customer_number" : $("#customer_number").val(),//客户编号
                //         "proj_manager_uid" : $("#proj_manager_uid").val(),//项目经理
                //         "project_member_value" : $("#project_member_value").val(),//项目成员编号
                //         "project_calendar" : $("#project_calendar").val(),//项目日历未完善
                //         "project_plan" : $("#project_plan").val(),//项目性质
                //         "start_date" : $("#start_date").val(),//接受日期
                //         "end_date" : $("#end_date").val(),//结束日期
                //         "validation_date" : $("#validation_date").val(),//工艺验证日期
                //         "sample_date" : $("#sample_date").val(),//出模样件日期
                //         "pilot_date" : $("#pilot_date").val(),//试产验证日期
                //         "production_date" : $("#production_date").val(),//批量放产日期
                //         "project_desction" : $("#project_desction").val()//项目描述
                //     }
                // };
                var project_code = $("#project_number").val();
                console.log(project_code);
                var project_name = $("#project_name").val();
                var project_category = $("#project_category").val();
                var customer_number = $("#customer_number").val();
                var proj_manager_uid = $("#proj_manager_uid").val();
                var project_member_value = $("#project_member_value").val();
                console.log(typeof(project_member_value));
                var project_calendar = $("#project_calendar").val();
                var project_plan = $("#project_plan").val();
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var validation_date = $("#validation_date").val();
                var sample_date = $("#sample_date").val();
                var pilot_date = $("#pilot_date").val();
                var production_date = $("#production_date").val();
                var project_desction = $("#project_desction").val();

                //零件信息
                var part_num = 0;
                var str_part_num = '&';

                $('#creatParts tr.jqgrow').each(function(i){
                    $('#creatParts tr.jqgrow:eq(' + i + ') td').each(function(j){
                        key = 'part_' + i + '_' + j;
                        cont = $(this).text().replace(/(^\s*)|(\s*$)/g, "");
                        str_part_num = str_part_num + key + '=' + cont + '&';
                    });
                    part_num++;
                });
                str_part_num = str_part_num + 'part_num=' + part_num;

                //计划信息
                var plan_num = 0;
                var str_plan_num = '&';

                $('#creatPlans tr.jqgrow').each(function(i){
                    $('#creatPlans tr.jqgrow:eq(' + i + ') td').each(function(j){
                        key = 'plan_' + i + '_' + j;
                        cont = $(this).text().replace(/(^\s*)|(\s*$)/g, "");
                        str_plan_num = str_plan_num + key + '=' + cont + '&';
                    });
                    plan_num++;
                });
                str_plan_num = str_plan_num + 'plan_num=' + plan_num;
                console.log(str_plan_num);

                // 项目立项组成需要提交的数据
                var project_info = '&project_code='+project_code+'&project_name='+project_name+'&project_category='+project_category+'&customer_number='+customer_number+'&proj_manager_uid='+proj_manager_uid+'&project_member_value='+project_member_value+'&project_calendar='+project_calendar+'&project_plan='+project_plan+'&start_date='+start_date+'&end_date='+end_date+'&validation_date='+validation_date+'&sample_date='+sample_date+'&pilot_date='+pilot_date+'&production_date='+production_date+'&project_desction='+project_desction+'&str_part_num='+str_part_num+'&str_plan_num='+str_plan_num;
                //将项目信息、零件信息、计划信息加入总信息
                var data = project_info+str_part_num+str_plan_num;
                console.log(data);

                //ajax提交项目立项需要提交的数据
                $.ajax({
                    type: 'post',
                    data: data,
                    url:'/dashboard/save-project',
                    success:function(msg){
                        if(msg.code == 1){
                            alert(msg.msg);
                            location.href = '/dashboard/list-project';
                        }else{
                            alert(msg.msg);
                            location.href = '/dashboard/setup-project';
                        }
                    }
                });
            }


            // Smart Wizard
            $('#smartwizard').smartWizard(
                {
                    selected: 0,  // Initial selected step, 0 = first step
                    keyNavigation:true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    autoAdjustHeight:true, // Automatically adjust content height
                    cycleSteps: false, // Allows to cycle the navigation of steps
                    backButtonSupport: true, // Enable the back button support
                    useURLhash: true, // Enable selection of the step based on url hash
                    lang: {  // Language variables
                        next: '下一步',
                        previous: '上一步'
                    },
                    toolbarSettings: {
                        toolbarPosition: 'bottom', // none, top, bottom, both
                        toolbarButtonPosition: 'right', // left, right
                        showNextButton: true, // show/hide a Next button
                        showPreviousButton: true, // show/hide a Previous button
                        toolbarExtraButtons: [
                            $('<button></button>').text('发布')
                                .addClass('btn btn-info btn-submit')
                                // .on('click', function(){
                                //     alert("123");
                                // })
                        ]
                    },
                    anchorSettings: {
                        anchorClickable: true, // Enable/Disable anchor navigation
                            enableAllAnchors: false, // Activates all anchors clickable all times
                            markDoneStep: true, // add done css
                            enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                    },
                    contentURL: null, // content url, Enables Ajax content loading. can set as data data-content-url on anchor
                    disabledSteps: [],    // Array Steps disabled
                    errorSteps: [],    // Highlight step with errors
                    theme: 'arrows',
                    transitionEffect: 'fade', // Effect on navigation, none/slide/fade
                    transitionSpeed: '400'
                }
            );
            // Initialize the showStep event（判断是否是最后一步，是则让发布按钮生效）
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
                if(stepNumber == 2) {
                    $('button.btn-submit').bind('click',btnSubmit);
                }else{
                    $('button.btn-submit').unbind('click',btnSubmit);
                }
            });


            //多选下拉框
            $(".chosen-select").chosen({width: "100%"});


            //jqgrid(parts_grid、_grid)
            $("#creatParts").jqGrid(
                {
                    editurl: 'clientArray',//定义对form编辑时的url
                    datatype: "json",
                    styleUI: 'Bootstrap',
                    pager: '#partsGridNav',
                    width: jqParts_width,
                    height: 350,
                    multiselect: true,
                    rownumbers: true,
                    hoverrows: true,
                    rowNum: 10,
                    rowList: [100, 200, 300],
                    viewrecords: true,
                    recordtext: "第{0}-{1}条，共{2}条",
                    cellEdit: false,
                    sortable:true,
                    sortname: 'part_number',
                    sortorder: "asc",
                    gridview: true,
                    hiddengrid: false,
                    // caption:"零件表",
                    hidegrid:true,
                    loadonce:true,
                    colNames: [
                        // '操作',
                        "{{Lang::get('mowork.part_number')}}",
                        "{{Lang::get('mowork.part_name')}}",
                        "{{Lang::get('mowork.part_type')}}",
                        "{{Lang::get('mowork.source')}}",
                        "{{Lang::get('mowork.quantity')}}",
                        "{{Lang::get('mowork.fixture')}}",
                        "{{Lang::get('mowork.gauge')}}",
                        "{{Lang::get('mowork.mould')}}",
                        "{{Lang::get('mowork.part_size')}}",
                        "{{Lang::get('mowork.part_weight')}}",
                        "{{Lang::get('mowork.part_material')}}",
                        "{{Lang::get('mowork.material_specification')}}",
                        "{{Lang::get('mowork.shrink')}}",
                        "{{Lang::get('mowork.processing_technology')}}",
                        "{{Lang::get('mowork.surface_process')}}",
                        "{{Lang::get('mowork.comment')}}"
                    ],
                    colModel: [
                        {
                            name: 'part_number',
                            index: "{{Lang::get('mowork.part_number')}}",
                            align:'center',
                            width:100,
                            editable: false,
                            edittype:"text",
                            sortable: true,
                            sortorder:"asc",
                            formoptions: {
                                label: "{{Lang::get('mowork.part_number')}}"
                            },
                            formatter:function(){
                                var rowNum =  $("#creatParts").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                                var head_part_number = part_number.substr(0,5);
                                if(rowNum == 0){
                                    part_number = parseInt(part_number.substr(-6));
                                }else{
                                    part_number = parseInt(part_number.substr(-6))+ 1;
                                }
                                var tempLength = part_number.toString().length;
                                if(tempLength == 1){
                                    part_number = head_part_number +"00000" + part_number;
                                }else if(tempLength == 2){
                                    part_number = head_part_number + "0000" + part_number;
                                }
                                else if(tempLength == 3){
                                    part_number = head_part_number + "000" + part_number;
                                }else if(tempLength == 4){
                                    part_number = head_part_number + "00" + part_number;
                                }else if(tempLength == 5){
                                    part_number = head_part_number + "0" + part_number;
                                }else{
                                    part_number = head_part_number + part_number;
                                }
                                return part_number;
                            }
                        },
                        {
                            name: 'part_name',
                            index: "{{Lang::get('mowork.part_name')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            formoptions: {
                                colpos: 1,
                                rowpos: 1,
                                label: "{{Lang::get('mowork.part_name')}}"
                            },
                            editrules: {required:true}
                        },
                        {
                            name: 'part_type',
                            index: "{{Lang::get('mowork.part_type')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:part_type},
                            formoptions: {
                                colpos: 2,
                                rowpos: 1,
                                label: "{{Lang::get('mowork.part_type')}}"
                            }
                        },
                        {
                            name: 'source',
                            index: "{{Lang::get('mowork.source')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:"1:自制;2:外购;3:客供"},
                            formoptions: {
                                colpos: 3,
                                rowpos: 1,
                                label: "{{Lang::get('mowork.source')}}"
                            }
                        },
                        {
                            name: 'quantity',
                            index: "{{Lang::get('mowork.quantity')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            formoptions: {
                                colpos: 4,
                                rowpos: 1,
                                label: "{{Lang::get('mowork.quantity')}}"
                            },
                            editrules: {number:true,minValue:1}
                        },
                        {
                            name: 'fixture',
                            index: "{{Lang::get('mowork.fixture')}}",
                            align:'center', width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 5,
                                rowpos: 1,
                                label: "{{Lang::get('mowork.fixture')}}"
                            },
                            editrules: {number:true,minValue:0}
                        },
                        {
                            name: 'gauge',
                            index: "{{Lang::get('mowork.gauge')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 1,
                                rowpos: 2,
                                label: "{{Lang::get('mowork.gauge')}}"
                            },
                            editrules: {number:true,minValue:0}
                        },
                        {
                            name: 'mould',
                            index: "{{Lang::get('mowork.mould')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 2,
                                rowpos: 2,
                                label: "{{Lang::get('mowork.mould')}}"
                            },
                            editrules: {number:true,minValue:0}
                        },
                        {
                            name: 'part_size',
                            index: "{{Lang::get('mowork.part_size')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 3,
                                rowpos: 2,
                                label: "{{Lang::get('mowork.part_size')}}"
                            }
                        },
                        {
                            name: 'part_weight',
                            index: "{{Lang::get('mowork.part_weight')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 4,
                                rowpos: 2,
                                label: "{{Lang::get('mowork.part_weight')}}"
                            }
                        },
                        {
                            name: 'part_material',
                            index: "{{Lang::get('mowork.part_material')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 5,
                                rowpos: 2,
                                label: "{{Lang::get('mowork.part_material')}}"
                            }
                        },
                        {
                            name: 'material_specification',
                            index: "{{Lang::get('mowork.material_specification')}}",
                            align:'center', width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 1,
                                rowpos: 3,
                                label: "{{Lang::get('mowork.material_specification')}}"
                            }
                        },
                        {
                            name: 'shrink',
                            index: "{{Lang::get('mowork.shrink')}}",
                            align:'center',
                            width:100,
                            sortable: false,
                            edittype: "text",
                            editable: true,
                            formoptions: {
                                colpos: 2,
                                rowpos: 3,
                                label: "{{Lang::get('mowork.shrink')}}"
                            }
                        },
                        {
                            name: 'processing_technology',
                            index: "{{Lang::get('mowork.processing_technology')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 3,
                                rowpos: 3,
                                label: "{{Lang::get('mowork.processing_technology')}}"
                            }
                        },
                        {
                            name: 'surface_process',
                            index: "{{Lang::get('mowork.surface_process')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 4,
                                rowpos: 3,
                                label: "{{Lang::get('mowork.surface_process')}}"
                            }
                        },
                        {
                            name: 'comment',
                            index: "{{Lang::get('mowork.comment')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            sortable: false,
                            formoptions: {
                                colpos: 5,
                                rowpos: 3,
                                label: "{{Lang::get('mowork.comment')}}",
                            }
                        }
                    ],
                    afterInsertRow: function (rowid, rowdata,rowelem) {
                        // var plans_rowNum =  $("#creatPlans").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                        var plans_rowNum =  plans_grid_data.length; //获取显示配置记录数量

                        var head_plan_number = plan_number.substr(0,5);
                        if(plans_rowNum == 0){
                            plan_number = parseInt(plan_number.substr(-6));
                        }else{
                            plan_number = parseInt(plan_number.substr(-6))+ 1;
                        }
                        var plans_tempLength = plan_number.toString().length;
                        if(plans_tempLength == 1){
                            plan_number = head_plan_number +"00000" + plan_number;
                        }else if(plans_tempLength == 2){
                            plan_number = head_plan_number + "0000" + plan_number;
                        }
                        else if(plans_tempLength == 3){
                            plan_number = head_plan_number + "000" + plan_number;
                        }else if(plans_tempLength == 4){
                            plan_number = head_plan_number + "00" + plan_number;
                        }else if(plans_tempLength == 5){
                            plan_number = head_plan_number + "0" + plan_number;
                        }else{
                            plan_number = head_plan_number + plan_number;
                        }
                        //增加与零件相对应的项目计划
                        var tempData = {"part_number": part_number,"plan_number": plan_number,"plan_type":"项目计划"};
                        plans_grid_data.push(tempData);

                        //增加与零件相对应的夹具计划
                        if(rowdata.fixture > 0){
                                var plans_rowNum =  plans_grid_data.length; //获取显示配置记录数量
                                //夹具计划编号逻辑
                                var jig_rowNum = true;
                                if(plans_rowNum > 0){
                                    for( j=1; j<plans_rowNum; j++){
                                        if(plans_grid_data[j-1].part_number == plans_grid_data[j].part_number){
                                            jig_rowNum = false;
                                            break;
                                        }
                                    }
                                }
                                //夹具头部
                                var head_jig_number = jig_number.substr(0,5);
                                //夹具尾部
                                if(jig_rowNum){
                                    jig_number = parseInt(jig_number.substr(-6));
                                }else{
                                    jig_number = parseInt(jig_number.substr(-6))+ 1;
                                }
                                var jig_tempLength = jig_number.toString().length;
                                if(jig_tempLength == 1){
                                    jig_number = head_jig_number +"00000" + jig_number;
                                }else if(jig_tempLength == 2){
                                    jig_number = head_jig_number + "0000" + jig_number;
                                }
                                else if(jig_tempLength == 3){
                                    jig_number = head_jig_number + "000" + jig_number;
                                }else if(jig_tempLength == 4){
                                    jig_number = head_jig_number + "00" + jig_number;
                                }else if(jig_tempLength == 5){
                                    jig_number = head_jig_number + "0" + jig_number;
                                }else{
                                    jig_number = head_jig_number + jig_number;
                                }

                                var tempData = {"part_number": part_number,"plan_number": jig_number,"plan_type":"夹具计划"};
                                plans_grid_data.push(tempData);
                        }

                        //增加与零件相对应的检具计划
                        if(rowdata.gauge > 0){
                                //检具计划编号逻辑
                                var plans_rowNum =  plans_grid_data.length; //获取显示配置记录数量
                                var gauge_rowNum = true;
                                if(plans_rowNum > 0){
                                    for(j=0; j<plans_rowNum; j++){
                                        // if(plans_grid_data[j-1].part_number == plans_grid_data[j].part_number){
                                        //     gauge_rowNum = false;
                                        //     break;
                                        // }
                                        if(plans_grid_data[j].plan_number.indexOf("J18") == 0){
                                            gauge_rowNum = false;
                                            break;
                                        }
                                    }
                                }
                                var head_gauge_number = gauge_number.substr(0,5);
                                if(gauge_rowNum){
                                    gauge_number = parseInt(gauge_number.substr(-6));
                                }else{
                                    gauge_number = parseInt(gauge_number.substr(-6))+ 1;
                                }
                                var gauge_tempLength = gauge_number.toString().length;
                                if(gauge_tempLength == 1){
                                    gauge_number = head_gauge_number +"00000" + gauge_number;
                                }else if(gauge_tempLength == 2){
                                    gauge_number = head_gauge_number + "0000" + gauge_number;
                                }
                                else if(gauge_tempLength == 3){
                                    gauge_number = head_gauge_number + "000" + gauge_number;
                                }else if(gauge_tempLength == 4){
                                    gauge_number = head_gauge_number + "00" + gauge_number;
                                }else if(gauge_tempLength == 5){
                                    gauge_number = head_gauge_number + "0" + gauge_number;
                                }else{
                                    gauge_number = head_gauge_number + gauge_number;
                                }

                                var tempData = {"part_number": part_number,"plan_number": gauge_number,"plan_type":"检具计划"};
                                plans_grid_data.push(tempData);
                        }

                        //增加与零件相对应的模具计划
                        if(rowdata.mould > 0){
                                var plans_rowNum =  plans_grid_data.length; //获取显示配置记录数量
                                //模具计划编号逻辑
                                var mold_rowNum = true;
                                if(plans_rowNum > 0){
                                    for(j=0; j<plans_rowNum; j++){
                                        if(plans_grid_data[j].plan_number.indexOf("M18") == 0){
                                            mold_rowNum = false;
                                            break;
                                        }
                                    }
                                }
                                var head_mold_number = mold_number.substr(0,5);
                                if(mold_rowNum){
                                    mold_number = parseInt(mold_number.substr(-6));
                                }else{
                                    mold_number = parseInt(mold_number.substr(-6))+ 1;
                                }
                                var mold_tempLength = mold_number.toString().length;
                                if(mold_tempLength == 1){
                                    mold_number = head_mold_number +"00000" + mold_number;
                                }else if(mold_tempLength == 2){
                                    mold_number = head_mold_number + "0000" + mold_number;
                                }
                                else if(mold_tempLength == 3){
                                    mold_number = head_mold_number + "000" + mold_number;
                                }else if(mold_tempLength == 4){
                                    mold_number = head_mold_number + "00" + mold_number;
                                }else if(mold_tempLength == 5){
                                    mold_number = head_mold_number + "0" + mold_number;
                                }else{
                                    mold_number = head_mold_number + mold_number;
                                }

                                var tempData = {"part_number": part_number,"plan_number": mold_number,"plan_type":"模具计划"};
                                plans_grid_data.push(tempData);
                        }
                    }
                }
            );
            $("#creatParts").navGrid('#partsGridNav',{edit:true,add:true,del:true,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
                    // options for the Edit Dialog
                    {
                        editCaption: "修改零件",
                        left:10,
                        top:50,
                        width:1400,
                        height: 280,
                        reloadAfterSubmit:false,
                        jqModal:false,
                        bSubmit: "保存",
                        bCancel: "关闭",
                        closeAfterEdit:true,
                        recreateForm: true
                    },
                    // options for the add Dialog
                    {
                        addCaption: "添加零件",
                        left:10,
                        top:50,
                        width:1400,
                        height: 280,
                        reloadAfterSubmit:false,
                        jqModal:false,
                        bSubmit: "保存",
                        bCancel: "关闭",
                        closeAfterAdd:true,
                        recreateForm: true
                    },
                    {
                        deleteCaption: "删除零件",
                        left:10,
                        top:50,
                        reloadAfterSubmit:false,
                        jqModal:false,
                        bSubmit: "删除",
                        bCancel: "取消",
                        closeAfterAdd:true,
                        recreateForm: true
                    }
            );
            // the bindKeys()
            $("#creatParts").jqGrid('bindKeys');
            $(window).bind('resize', function () {
            });



            // plans_grid_data（计划表）
            $("#creatPlans").jqGrid(
                {
                    data: plans_grid_data,
                    datatype: "local",   //datatype: "json",
                    url: "../JQGridTest/Index",  //控制器
                    editurl: 'clientArray',//定义对form编辑时的url
                    styleUI: 'Bootstrap',
                    pager: '#plansGridNav',
                    width: jqParts_width,
                    height: 345,
                    multiselect: true,
                    rownumbers: true,
                    hoverrows: true,
                    rowNum: 20,
                    rowList: [100, 200, 300],
                    viewrecords: true,
                    recordtext: "第{0}-{1}条，共{2}条",
                    cellEdit: true,
                    colNames: [
                        // '操作',
                        "{{Lang::get('mowork.part_number')}}",
                        "{{Lang::get('mowork.plan_number')}}",
                        "{{Lang::get('mowork.plan_name')}}",
                        "{{Lang::get('mowork.plan_type')}}",
                        "{{Lang::get('mowork.plan_leader')}}",
                        "{{Lang::get('mowork.plan_member')}}",
                        "{{Lang::get('mowork.plan_description')}}",
                        "{{Lang::get('mowork.start_date')}}",
                        "{{Lang::get('mowork.end_date')}}",
                    ],
                    colModel: [
                        {
                            name: 'part_number',
                            index: "{{Lang::get('mowork.part_number')}}",
                            align:'center',
                            width:100,
                            edittype:"text",
                        },
                        {
                            name: 'plan_number',
                            index: "{{Lang::get('mowork.plan_number')}}",
                            align:'center',
                            width:100,
                            edittype: "text",
                            sortable:false
                        },
                        {
                            name: 'plan_name',
                            index: "{{Lang::get('mowork.plan_name')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"text",
                            sortable:false
                        },
                        {
                            name: 'plan_type',
                            index: "{{Lang::get('mowork.plan_type')}}",
                            align:'center',
                            width:100,
                            editable: false,
                            edittype:"text",
                            sortable:false
                        },
                        {
                            name: 'plan_leader',
                            index: "{{Lang::get('mowork.plan_leader')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:plan_leader},
                            sortable:false
                        },
                        {
                            name: 'plan_member',
                            index: "{{Lang::get('mowork.plan_member')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:plan_member,multiple:true},
                            sortable:false
                        },
                        {
                            name: 'plan_description',
                            index: "{{Lang::get('mowork.plan_description')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "textarea",
                            sortable:false
                        },
                        {
                            name: 'start_date',
                            index: "{{Lang::get('mowork.start_date')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype: "text",
                            // 对列进行格式化时设置的函数名或者类型
                            // formatter:"date",
                            // 对某些列进行格式化的设置(array)
                            // formatoptions: {newformat:'Y-m-d'},
                            // 编辑的一系列选项。
                            editoptions: {
                                // dataInit: function (element) {
                                //     $(element).datepicker({
                                //         autoclose: true,
                                //         datefmt: 'yy-mm-dd',
                                //         orientation : 'bottom'
                                //     });
                                // }
                            }
                        },
                        {
                            label: 'end_date',
                            name: 'end_date',
                            index: "{{Lang::get('mowork.end_date')}}",
                            width: 100,
                            editable: true,
                            edittype:"text",
                            editoptions: {
                                dataInit: function (element) {
                                    $(element).datepicker({
                                        autoclose: true,
                                        format: 'yyyy-mm-dd',
                                        orientation : 'bottom'
                                    });
                                }
                            }
                        },
                    ]
                }
            );

            $("#creatPlans").navGrid('#plansGridNav',{edit:true,add:false,del:true,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
                // options for the Edit Dialog
                {
                    editCaption: "修改计划",
                    left:10,
                    top:50,
                    height: 450,
                    reloadAfterSubmit:false,
                    jqModal:false,
                    bSubmit: "保存",
                    bCancel: "关闭",
                    closeAfterEdit:true,
                    recreateForm: true
                },
                {
                    delCaption: "删除计划",
                    left:10,
                    top:50,
                    reloadAfterSubmit:false,
                    jqModal:false,
                    bSubmit: "删除",
                    bCancel: "取消",
                    closeAfterAdd:true,
                    recreateForm: true
                }
            );
            // the bindKeys()
            $("#creatPlans").jqGrid('bindKeys');
        });
    </script>
@stop