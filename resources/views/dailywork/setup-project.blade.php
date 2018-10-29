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
        /*alter basics style*/
        .clearfix:after{
            content:"";
            height:0;
            line-height:0;
            display:block;
            visibility:hidden;
            clear:both
        }
        .clearfix{
            zoom:1;
        }
        label.col-md-2 {
            text-align: center;
            line-height: 2;
            padding-right: 5px;
            margin-bottom: 0px;
        }
        label.col-md-2 {
            margin-top: 20px;
        }
        div.col-md-2{
            margin-top: 10px;
        }
        div.col-md-10{
            margin-top: 20px;
        }
        div.col-lg-3{
            margin-top: 20px;
        }
        .etrMargin{
            margin-right: 30%;
        }

        @media screen and (min-width:1050px){
            .etrMargin {
                margin-right: 0;
            }
        }
        @media screen and (min-width:1200px) and (max-width:1400px){
            .col-lg-1 {
                width: 13.66666666%;
                padding-right: 10px;
            }
            .col-lg-3 {
                width: 33.33333333%;
            }
            .col-lg-11{
                width: 80.666667%;
            }
            .etrMargin {
                margin-right: 0;
            }
        }
        @media screen and (min-width:1400px) and (max-width:1788px){
            .col-lg-1 {
                width: 13.66666666%;
            }
            .col-lg-3 {
                width: 33.33333333%;
            }
            .col-lg-11{
                width: 80.666667%;
            }
            .etrMargin {
                 margin-right: 0;
            }
        }
        @media screen and (min-width: 1788px){
            .col-lg-11 {
                width: 91.66666667%;
            }
            .etrMargin {
                margin-right: 30%;
            }
        }

        div.content {
            padding: 15px;
        }

        .sw-theme-arrows > ul.step-anchor > li > a {
            line-height: 40px;
        }
        .sw-theme-arrows > .sw-container {
            padding-top: 50px;
        }

        div.col-md-4 col-lg-3{
            padding-left: 0;
        }
        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }
        div.form-horizontal {
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
        .ui-jqgrid .ui-jqgrid-resize {
            height: 0px !important;
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
                <li><a href="#step-3">3:生成计划</a></li>
            </ul>
            <div>
                <div id="step-1" class="">
                    <div class="form-horizontal clearfix" id="sample-form">
                            {{--项目编号--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_number')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <input id="project_number" readonly="readonly" value="<?php echo $result['project_number']?>" class="form-control" name="project_number" placeholder="{{Lang::get('mowork.project_number')}}">
                            </div>
                            {{--项目名称--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_name')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <input id="project_name" class="form-control" placeholder="项目名称">
                            </div>
                            {{--项目类别--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_category')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <select id="project_category" class="form-control" name="project_category" placeholder="项目类别" >
                                    @foreach($result['project_type'] as $key => $row)
                                        <option value="{{$row['type_id']}}">{{$row['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        {{--</div>--}}
                            {{--客户名称--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.customer_name')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <select id="customer_number" class="form-control" name="customer_number">
                                    <?php foreach ($result['customer'] as $key => $value) { ?>
                                    <option value="{{$value['cust_company_id']}}">{{$value['company_name']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                            {{--项目经理--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_manager')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <select id="proj_manager_uid" class="form-control" name="proj_manager_uid">
                                    <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <option value="{{$value['uid']}}">{{$value['fullname']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                            {{--项目成员--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_member')}} </label>
                            <div class="col-md-4 col-lg-3" style="line-height: 2.5">
                                <select multiple id="project_member_value" class="chosen-select form-control" name="project_member" data-placeholder="选择成员">
                                    <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <option value="{{$value['uid']}}" username="<?php echo $value['fullname']?>">{{$value['fullname']}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        {{--</div>--}}
                            {{--项目日历--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_calendar')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <div>
                                    <select id="project_calendar" class="form-control" name="project_calendar" placeholder="项目日历">
                                        <?php foreach ($result['calendar'] as $key => $value) { ?>
                                        <option value="{{$value['cal_id']}}">{{$value['cal_name']}}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            {{--项目性质--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.project_nature')}} </label>
                            <div class="col-md-4 col-lg-3">
                                <select id="project_plan" class="form-control" name="project_plan" placeholder="项目性质">
                                    <option value="1">{{Lang::get('mowork.public_plan')}}</option>
                                    <option value="0">{{Lang::get('mowork.private_plan')}}</option>
                                </select>
                            </div>
                            {{--接受日期--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.date_acceptance')}} </label>
                            <div class="col-md-4 col-lg-3">
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
                        {{--</div>--}}
                            {{--结束日期--}}
                            <label class="col-md-2 col-lg-1 text-info" for="">
                                * {{Lang::get('mowork.date_end')}} </label>
                            <div class="col-md-4 col-lg-3">
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
                            <label class="col-md-2 col-lg-1 no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_validation')}} </label>
                            <div class="col-md-4 col-lg-3">
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
                            <label class="col-md-2 col-lg-1 no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_sample')}} </label>
                            <div class="col-md-4 col-lg-3">
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
                        {{--</div>--}}
                            {{-- 试产验证日期 --}}
                            <label class="col-md-2 col-lg-1 no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_verification')}} </label>
                            <div class="col-md-4 col-lg-3">
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
                            <label class="col-md-2 col-lg-1 no-padding-right text-info"
                                   for=""> {{Lang::get('mowork.date_delivery')}} </label>
                            <div class="col-md-4 col-lg-3 etrMargin">
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
                        {{--</div>--}}
                            {{-- 项目描述 --}}
                            <label class="col-md-2 col-lg-1 no-padding-right text-info"
                                   for="" style="line-height: 3.5"> {{Lang::get('mowork.project_desction')}} </label>
                            <div class="col-md-10 col-lg-11">
                                <textarea id="project_desction" class="autosize-transition form-control"></textarea>
                            </div>
                        <a class="col-md-4 col-lg-4 no-padding-right text-info" style="margin-top: 20px;margin-left: 20px;" href='#projectFile' rel="tooltip" data-placement="right" data-toggle="modal" data-placement="right" data-original-title="{{Lang::get('mowork.upload_project_file')}}"><span><b>{{Lang::get('mowork.upload_project_file')}}</b></span>
                        </a>
                        <div class="modal fade" id="projectFile" style="z-index: 9999;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">X</button>
                                        <h4 class="modal-title text-center">{{Lang::get('mowork.upload_file')}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-left">{{Lang::get('mowork.select_area')}}</div>
                                        <div class="margin-b20">
                                            <form action="{{ url('/upload/instruction') }}" class="dropzone"
                                                  id="mydropzone" style="min-height: 20px;">
                                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer"></div>
                                    <div class="text-center"
                                         style="margin-top: -10px; margin-bottom: 10px">
                                        <button type="button" data-dismiss="modal" class="btn-warning">X</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="step-2" class="">
                    <div class="jqGrid_wrapper">
                        <table id="creatParts"></table>
                        <div id="partsGridNav"></div>
                    </div>
                    <div class="modal fade" id="partFile" style="z-index: 9999;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">X</button>
                                    <h4 class="modal-title text-center">{{Lang::get('mowork.upload_file')}}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="text-left">{{Lang::get('mowork.select_area')}}</div>
                                    <div class="margin-b20">
                                        <form action="{{ url('/upload/instruction') }}" class="dropzone"
                                              id="mydropzone" style="min-height: 20px;">
                                            <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer"></div>
                                <div class="text-center"
                                     style="margin-top: -10px; margin-bottom: 10px">
                                    <button type="button" data-dismiss="modal" class="btn-warning">X</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addPlan" style="z-index: 9999;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">X</button>
                                    <h4 class="modal-title text-center">"新建计划"</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="text-left clearfix">


                                        <label class="col-md-2 col-lg-2 text-info text-center text-info" for="" style="margin-top: 0px;">"计划类别"</label>
                                        <div class="col-md-4 col-lg-3" style="margin-top: 0px;">
                                            <select multiple id="plan_category" class="form-control chosen-select" name="plan_category" placeholder="计划类别" >
                                                @foreach($result['plan_type'] as $key => $row)
                                                    <option value="{{$row['type_id']}}">{{$row['type_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btnSave" type="button" class="btn btn-primary">保存</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="step-3" class="">
                    <div class="jqGrid_wrapper">
                        <table id="creatPlans"></table>
                        <div id="plansGridNav"></div>
                    </div>
                    <div class="modal fade" id="planFile" style="z-index: 9999;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">X</button>
                                    <h4 class="modal-title text-center">{{Lang::get('mowork.upload_file')}}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="text-left">{{Lang::get('mowork.select_area')}}</div>
                                    <div class="margin-b20">
                                        <form action="{{ url('/upload/instruction') }}" class="dropzone"
                                              id="mydropzone" style="min-height: 20px;">
                                            <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer"></div>
                                <div class="text-center"
                                     style="margin-top: -10px; margin-bottom: 10px">
                                    <button type="button" data-dismiss="modal" class="btn-warning">X</button>
                                </div>
                            </div>
                        </div>
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
        //零件文档上传
        function part_upload(row){
            var part_number = $(row).find("td").eq(2).text();
            $('#partFile').on('show.bs.modal',
                function() {
                    $("#partFile form").append('<input name="part_number" value="'+part_number+'" type="hidden">');
                }
            );
            $('#partFile').modal('show');
        }


        //新建计划
        function addPlan(row){
            part_number = $(row).find("td").eq(2).text();
            //同步零件编号（打开modal窗口的时候传入）
            $('#addPlan').on('show.bs.modal',
                function() {
                    temp_part_number = part_number;
                }
            );
            // 计划类别显示
            $('#addPlan').modal('show');
        }




        //计划文档上传
        // function plan_upload(obj){
        //     // var strUrl = $("#planFile form").prop("action")+'/';
        //     $('#planFile').on('show.bs.modal',
        //         function() {
        //             // strUrl += $(obj).attr('plan_data');
        //             // $("#planFile form").prop("action",strUrl);
        //             $("#planFile form").append('<input name="plan_number" value="'+$(obj).attr('plan_data')+'" type="hidden">');
        //         }
        //     );
        //     $('#planFile').modal('show');
        // }


        $(document).ready(function () {
            //为所有ajax默认添加请求头
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});

            // Date.prototype.Format = function (fmt) {
            //     var o = {
            //         "M+": this.getMonth() + 1, //月份
            //         "d+": this.getDate(), //日
            //         "h+": this.getHours(), //小时
            //         "m+": this.getMinutes(), //分
            //         "s+": this.getSeconds(), //秒
            //         "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            //         "S": this.getMilliseconds() //毫秒
            //     };
            //     if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            //     for (var k in o)
            //         if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            //     return fmt;
            // };
            //datetimepicker
            $('.form_date').datetimepicker({
                language: 'zh',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
                // startDate: new Date().Format("yyyy-MM-dd"),//去掉此属性，可选择以前日期。
            });

            // 动态设置jqgrid宽度
            var jqParts_width = $('.jqGrid_wrapper').width() - 30;
            var jqParts_dialog_width = '';
            if(jqParts_width < 1600){
                jqParts_dialog_width = 1000;
            }else{
                jqParts_dialog_width = 1400;
            }

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
                plan_type += "{{$row['type_id']}}:{{$row['type_name']}};"
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


            //各种计划类型
            var all_plan_type = '';
            @foreach($all_coding as $key => $row)
                all_plan_type += '"{{$row['type_id']}}":"{{$row['plan_code']}}",'
            @endforeach
            all_plan_type = all_plan_type.substring(0, all_plan_type.length-1);
            all_plan_type = "{" +all_plan_type+ "}";
            var obj_all_plan_type = $.parseJSON( all_plan_type );

            // {74:akjda180416000001;75:123a180416000001;76:afd180416000001}


            //共用的是同一个modal因此清除下拉框的选项，同时清除为零件新建的所有计划id,上次的part_number。
            $('#addPlan').on('hidden.bs.modal', function () {
                // all_plan_num.splice(0,all_plan_num.length);
                $("#plan_category").val("");
                $("#plan_category").trigger("chosen:updated");
            });

            //为每条零件新建计划的计划初始编号（零件和计划的关系：一对多的关系）

            var all_plan_num = [];      //为零件新建的所有计划id
            var part_plan_num = '';     //一条零件编号对应的计划id（计划归哪条零件）
            var all_part_plan_num = [];     //所有的零件对应的计划

            $("#btnSave").on("click",function(){
                var plan_category = $("#plan_category").val();
                if(plan_category == null){
                    alert('请选择计划类别');
                    return;
                }

                // for(var x in plan_category){
                //     all_plan_num.push(plan_category[x]);
                // }
                all_part_plan_num[temp_part_number] = plan_category;

                // part_plan_num = '{"'+temp_part_number+'":['+all_plan_num+']}';
                // part_plan_num = $.parseJSON( part_plan_num );
                // all_part_plan_num.push(part_plan_num);
                alert("新建计划成功");
                $(this).next().click();
                // console.log('bb');console.log(all_part_plan_num);

                // for(var x in plan_category){
                //    all_plan_num.push(plan_category[x]);
                //}
                //part_plan_num = '{"'+temp_part_number+'":['+all_plan_num+']}';
                //part_plan_num = $.parseJSON( part_plan_num );
                //all_part_plan_num.push(part_plan_num);
                //alert("新建计划成功");

            });



            // 新增每行零件数据时,生成计划缺省信息（globle variates）
            var plans_grid_data = [];

            //项目立项完成提交项目、零件、计划(针对零件)逻辑
            function btnSubmit(){
                var project_code = $("#project_number").val();
                // console.log(project_code);
                var project_name = $("#project_name").val();
                var project_category = $("#project_category").val();
                var customer_number = $("#customer_number").val();
                var proj_manager_uid = $("#proj_manager_uid").val();
                var project_member_value = $("#project_member_value").val();
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
                // var part_num = 0;
                // var str_part_num = '&';
                // $('#creatParts tr.jqgrow').each(function(i){
                //     $('#creatParts tr.jqgrow:eq(' + i + ') td').each(function(j){
                //         key = 'part_' + i + '_' + j;
                //         cont = $(this).text().replace(/(^\s*)|(\s*$)/g, "");
                //         str_part_num = str_part_num + key + '=' + cont + '&';
                //     });
                //     part_num++;
                // });
                // str_part_num = str_part_num + 'part_num=' + part_num;

                //计划信息

                // var plan_num = 0;
                // var str_plan_num = '&';
                // $('#creatPlans tr.jqgrow').each(function(i){
                //     $('#creatPlans tr.jqgrow:eq(' + i + ') td').each(function(j){
                //         key = 'plan_' + i + '_' + j;
                //         cont = $(this).text().replace(/(^\s*)|(\s*$)/g, "");
                //         str_plan_num = str_plan_num + key + '=' + cont + '&';
                //     });
                //     plan_num++;
                // });
                // str_plan_num = str_plan_num + 'plan_num=' + plan_num;
                // console.log(str_plan_num);

                // // 项目立项组成需要提交的数据
                // var project_info = '&project_code='+project_code+'&project_name='+project_name+'&project_category='+project_category+'&customer_number='+customer_number+'&proj_manager_uid='+proj_manager_uid+'&project_member_value='+project_member_value+'&project_calendar='+project_calendar+'&project_plan='+project_plan+'&start_date='+start_date+'&end_date='+end_date+'&validation_date='+validation_date+'&sample_date='+sample_date+'&pilot_date='+pilot_date+'&production_date='+production_date+'&project_desction='+project_desction+'&str_part_num='+str_part_num+'&str_plan_num='+str_plan_num;
                // //将项目信息、零件信息、计划信息加入总信息
                // var data = project_info+str_part_num+str_plan_num;
                // console.log(data);

                // 项目立项组成需要提交的数据
                // project_info = '&project_code='+project_code+'&project_name='+project_name+'&project_category='+project_category+'&customer_number='+customer_number+'&proj_manager_uid='+proj_manager_uid+'&project_member_value='+project_member_value+'&project_calendar='+project_calendar+'&project_plan='+project_plan+'&start_date='+start_date+'&end_date='+end_date+'&validation_date='+validation_date+'&sample_date='+sample_date+'&pilot_date='+pilot_date+'&production_date='+production_date+'&project_desction='+project_desction+'&str_part_num='+str_part_num;

                var project_info = '';
                var part_info = '';
                var plan_info = '';

                project_info += '{"project":{' 
                    + '"proj_code":"'        + project_code          + '",'
                    + '"proj_name":"'           + project_name          + '",' 
                    + '"proj_type":"'           + project_category      + '",' 
                    + '"customer_id":"'         + customer_number       + '",' 
                    + '"proj_manager_uid":"'    + proj_manager_uid      + '",' 
                    + '"member_list":"'         + project_member_value  + '",' 
                    + '"calendar_id":"'         + project_calendar      + '",' 
                    + '"property":"'            + project_plan          + '",' 
                    + '"start_date":"'          + start_date            + '",' 
                    + '"end_date":"'            + end_date              + '",' 
                    + '"process_trail":"'       + validation_date       + '",' 
                    + '"mold_sample":"'         + sample_date           + '",' 
                    + '"trail_production":"'    + pilot_date            + '",' 
                    + '"batch_production":"'    + production_date       + '",' 
                    + '"description":"'         + project_desction      + '"'  
                    +  '},';
                
                part_info += '"part":[';

                $('#creatParts tr.jqgrow').each(function(i){
                    if(i != 0){part_info += ','}
                    part_info += '{'
                        + '"part_code":"' // 零件编号
                        + $(this).find('td').eq(2).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"part_name":"' // 零件名称
                        + $(this).find('td').eq(3).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"part_type":"' // 零件类型
                        + $(this).find('td').eq(4).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"part_from":"' // 来源
                        + $(this).find('td').eq(5).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"quantity":"' // 数量
                        + $(this).find('td').eq(6).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"jig":"' // 夹具
                        + $(this).find('td').eq(7).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"gauge":"' // 检具
                        + $(this).find('td').eq(8).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"mold":"' // 模具
                        + $(this).find('td').eq(9).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"part_size":"' // 零件尺寸
                        + $(this).find('td').eq(10).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"weight":"' // 零件重量
                        + $(this).find('td').eq(11).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"material":"' // 零件材料
                        + $(this).find('td').eq(12).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"mat_size":"' // 材料规则
                        + $(this).find('td').eq(13).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"shrink":"' // 缩水率
                        + $(this).find('td').eq(14).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"processing":"' // 加工工艺
                        + $(this).find('td').eq(15).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"surface":"' // 表面处理
                        + $(this).find('td').eq(16).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        +'"note":"' // 备注
                        + $(this).find('td').eq(17).text().replace(/(^\s*)|(\s*$)/g, "")
                        +'"'
                        +'}';
                });

                part_info += '],';

                plan_info += '"plan":[';

                $('#creatPlans tr.jqgrow').each(function(i){
                    if(i != 0){plan_info += ','}
                    plan_info += '{'
                        + '"part_code":"' // 零件编号
                        + $(this).find('td').eq(2).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"plan_code":"' // 计划编号
                        + $(this).find('td').eq(3).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"plan_name":"' // 计划名称
                        + $(this).find('td').eq(4).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"plan_type":"' // 计划类型
                        + $(this).find('td').eq(5).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"leader":"' // 计划负责人
                        + $(this).find('td').eq(6).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"member":"' // 计划成员
                        + $(this).find('td').eq(7).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"description":"' // 计划描述(关联信息，描述)
                        + $(this).find('td').eq(8).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"start_date":"' // 开始日期
                        + $(this).find('td').eq(9).text().replace(/(^\s*)|(\s*$)/g, "")
                        + '",'
                        + '"end_date":"' // 结束日期
                        + $(this).find('td').eq(10).text().replace(/(^\s*)|(\s*$)/g, "")
                        +'"'
                        +'}';
                });

                plan_info += ']}';

              
                //将项目信息、零件信息、计划信息加入总信息
                var data = project_info + part_info + plan_info;
                console.log(data);
                // return;
                //ajax提交项目立项需要提交的数据
                $.ajax({
                    type: 'post',
                    data: {
                        pData : data,
                        _token : "{{csrf_token()}}"
                    },
                    url:'/dashboard/save-project',
                    success:function(msg){
                        console.log(msg);
                        // return;
                        if(msg.code == 1){
                            alert(msg.msg);
                            /*var project_id = $("#project_number").val();*/
                            //获取返回的project_id
                            var project_id = msg.project_id;
                            /*project_id =  project_id.substring(project_id.length-3,project_id.length);*/
                            //创建项目成功之后，上传文件时候，加入验证（文件是否为空）
                            $.ajax({
                                type: 'post',
                                url:'/dashboard/project/file-maintenance/'+project_id,
                                success:function(msg){
                                    if(msg.code == 1){
                                        alert(msg.msg);
                                    }else{
                                        alert(msg.msg);
                                    }
                                },
                                error: function(err){
                                    alert("请求错误，请重试");
                                }
                            });
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
                    keyNavigation:false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
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

            // Smart Wizard plugin 上新增的保存按钮加上提交事件（提交项目头信息或者项目完整信息）
            $('button.btn-submit').bind('click',btnSubmit);
            // Initialize the showStep event


            /*$("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {

                if(stepNumber == 2) {
                    // $('button.btn-submit').bind('click',btnSubmit);

                    console.log(all_part_plan_num);
                    console.log(obj_all_plan_type);

                    //筛选重复为零件新建的计划（倒序筛出）
                  var temp_all_part_plan_num = [];
                    var tempD = [];
                    tempD.splice(0,tempD.length);
                    for(i=all_part_plan_num.length-1; i>=0;i--){
                        for(var x in all_part_plan_num[i]){
                            var tempLength = temp_all_part_plan_num.length;
                            if(tempLength == 0){
                                tempD.push(x);
                                temp_all_part_plan_num.push(all_part_plan_num[i]);
                            }else{
                                for(z=0;z<=tempLength-1;z++){
                                    for(var d in temp_all_part_plan_num[z]){
                                        if(tempD.indexOf(x) != -1){
                                            continue;
                                        }else{
                                            temp_all_part_plan_num.push(all_part_plan_num[i]); 
                                            tempD.push(x);
                                        };
                                    }
                                }
                            }
                        };
                    }
                    console.log(temp_all_part_plan_num);

                    //对筛选出来的各组数据（type_id计划类型，零件编号）进行排序，为了和计划编号对应，以及计划编号增加（前端做的处理）
                    for(i=0; i<= temp_all_part_plan_num.length-1; i++){
                        for(var x in temp_all_part_plan_num[i]){
                            console.log(x);
                        }
                    }

                    var part_rowNum =  $("#creatParts").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                    var tempData = {};
                    plans_grid_data = [];
                    var numParts = $('#creatParts tr.jqgrow').length;

                    for(i=0; i<numParts; i++){
                        //增加对应计划时候的计划编号逻辑
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

                        //增加对应计划时候的零件编号逻辑
                        var part_number = $('#creatParts tr.jqgrow:eq(' + i + ') td')[2].innerText;


                        //增加与零件相对应的项目计划
                        tempData = {"part_number": part_number,"plan_number": plan_number,"plan_type":""};
                        plans_grid_data.push(tempData);
                    }

                }else{
                    $('button.btn-submit').unbind('click',btnSubmit);
                }

                $("#creatPlans").jqGrid('clearGridData');  //清空表格
                $("#creatPlans").jqGrid('setGridParam',{  // 重新加载数据
                    datatype:'local',
                    data : plans_grid_data,   // 需要重新加载的数据
                    page:1
                }).trigger("reloadGrid");
            });*/

            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
                plans_grid_data = [];
                var part_number_str = ',';
                // 添加的零件可能有删除
                $('#creatParts').find('tr').each(function(){
                    part_number_str += $(this).find('td').eq(2).text().replace(/(^\s*)|(\s*$)/g, "") + ',';
                });
                
                // plan_type 出现的次数
                var tmpArr = new Array();
                for(var ii in all_part_plan_num){
                    // 删除的零件
                    if(part_number_str.indexOf(',' + ii + ',') == -1){ 
                        delete all_part_plan_num[ii];
                        continue;
                    }
                    var part_number = ii;
                    var tmp_part_plan_num = all_part_plan_num[ii];
                    
                    for(var iii in tmp_part_plan_num){
                        var plan_type = tmp_part_plan_num[iii];
                        if(tmpArr[plan_type + '']){
                            // 后面的数字加 tmpArr[plan_type]
                            var plan_number = numberPlus(obj_all_plan_type[plan_type], tmpArr[plan_type]);
                            tmpArr[plan_type + ''] += 1;
                        }else{
                            var plan_number = obj_all_plan_type[plan_type];
                            tmpArr[plan_type + ''] = 1;
                        }

                        tempData = {"part_number": part_number,"plan_number": plan_number,"plan_type":plan_type};
                        plans_grid_data[plans_grid_data.length] = tempData;
                    }
                }

                //增加与零件相对应的项目计划

                // if(stepNumber == 2) {
                //     $('button.btn-submit').bind('click',btnSubmit);

                //     console.log(all_part_plan_num);
                //     console.log(obj_all_plan_type);

                //     //筛选重复为零件新建的计划（倒序筛出）
                //     var temp_all_part_plan_num = [];
                //     var tempD = [];
                //     tempD.splice(0,tempD.length);
                //     for(i=all_part_plan_num.length-1; i>=0;i--){
                //         for(var x in all_part_plan_num[i]){
                //             var tempLength = temp_all_part_plan_num.length;
                //             if(tempLength == 0){
                //                 tempD.push(x);
                //                 temp_all_part_plan_num.push(all_part_plan_num[i]);
                //             }else{
                //                 for(z=0;z<=tempLength-1;z++){
                //                     for(var d in temp_all_part_plan_num[z]){
                //                         if(tempD.indexOf(x) != -1){
                //                             continue;
                //                         }else{
                //                             temp_all_part_plan_num.push(all_part_plan_num[i]);
                //                             tempD.push(x);
                //                         };
                //                     }
                //                 }
                //             }
                //         };
                //     }
                //     console.log(temp_all_part_plan_num);

                //     //对筛选出来的各组数据（type_id计划类型，零件编号）进行排序，为了和计划编号对应，以及计划编号增加（前端做的处理）
                //     for(i=0; i<= temp_all_part_plan_num.length-1; i++){
                //         for(var x in temp_all_part_plan_num[i]){
                //             console.log(x);
                //         }
                //     }

                //     var part_rowNum =  $("#creatParts").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                //     var tempData = {};
                //     plans_grid_data = [];
                //     var numParts = $('#creatParts tr.jqgrow').length;

                //     for(i=0; i<numParts; i++){
                //         //增加对应计划时候的计划编号逻辑
                //         var plans_rowNum =  plans_grid_data.length; //获取显示配置记录数量
                //         var head_plan_number = plan_number.substr(0,5);
                //         if(plans_rowNum == 0){
                //             plan_number = parseInt(plan_number.substr(-6));
                //         }else{
                //             plan_number = parseInt(plan_number.substr(-6))+ 1;
                //         }
                //         var plans_tempLength = plan_number.toString().length;
                //         if(plans_tempLength == 1){
                //             plan_number = head_plan_number +"00000" + plan_number;
                //         }else if(plans_tempLength == 2){
                //             plan_number = head_plan_number + "0000" + plan_number;
                //         }
                //         else if(plans_tempLength == 3){
                //             plan_number = head_plan_number + "000" + plan_number;
                //         }else if(plans_tempLength == 4){
                //             plan_number = head_plan_number + "00" + plan_number;
                //         }else if(plans_tempLength == 5){
                //             plan_number = head_plan_number + "0" + plan_number;
                //         }else{
                //             plan_number = head_plan_number + plan_number;
                //         }

                //         //增加对应计划时候的零件编号逻辑
                //         var part_number = $('#creatParts tr.jqgrow:eq(' + i + ') td')[2].innerText;


                //         //增加与零件相对应的项目计划
                //         tempData = {"part_number": part_number,"plan_number": plan_number,"plan_type":""};
                //         plans_grid_data.push(tempData);
                //     }

                // }else{
                //     $('button.btn-submit').unbind('click',btnSubmit);
                // }


                $("#creatPlans").jqGrid('clearGridData');  //清空表格
                $("#creatPlans").jqGrid('setGridParam',{  // 重新加载数据
                    datatype:'local',
                    data : plans_grid_data,   // 需要重新加载的数据
                    page:1
                }).trigger("reloadGrid");
            });

            //多选下拉框
            $(".chosen-select").chosen({width: "100%"});

            //jqgrid(parts_grid、_grid)
            $("#creatParts").jqGrid(
                {
                    editurl: 'clientArray',
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
                        "{{Lang::get('mowork.comment')}}",
                        "上传零件文件",
                        "新建计划",
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
                                var part_rowNum =  $("#creatParts").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                                // var head_part_number = part_number.substr(0,5);
                                // if(part_rowNum == 0){
                                //     part_number = parseInt(part_number.substr(-6));
                                // }else{
                                //     part_number = parseInt(part_number.substr(-6))+ 1;
                                // }
                                // var tempLength = part_number.toString().length;
                                // if(tempLength == 1){
                                //     part_number = head_part_number +"00000" + part_number;
                                // }else if(tempLength == 2){
                                //     part_number = head_part_number + "0000" + part_number;
                                // }
                                // else if(tempLength == 3){
                                //     part_number = head_part_number + "000" + part_number;
                                // }else if(tempLength == 4){
                                //     part_number = head_part_number + "00" + part_number;
                                // }else if(tempLength == 5){
                                //     part_number = head_part_number + "0" + part_number;
                                // }else{
                                //     part_number = head_part_number + part_number;
                                // }
                                // return part_number;
                                return numberPlus(part_number, part_rowNum);
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
                            editoptions:{value:"自制:自制;外购:外购;客供:客供"},
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
                        },
                        {
                            name: 'uploadDoc',
                            index: 'uploadDoc',
                            width:100,
                            align:'center',
                            sortable: false,
                            formatter: function (cellvalue, options, rowObject) {
                                return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
                                    'onclick="part_upload('+options.rowId+')">上传零件文件</a>';
                            }
                        },
                        {
                            name: 'addPlan',
                            index: 'addPlan',
                            width:100,
                            align:'center',
                            sortable: false,
                            formatter: function (cellvalue, options, rowObject) {
                                return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
                                    'onclick="addPlan('+options.rowId+')">新建计划</a>';
                            }
                        }
                    ]
                }
            );

            $("#creatParts").navGrid('#partsGridNav',{edit:true,add:true,del:true,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
                    // options for the Edit Dialog
                    {
                        editCaption: "修改零件",
                        left:10,
                        top:50,
                        width:jqParts_dialog_width,
                        height: 280,
                        reloadAfterSubmit:true,
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
                        width:jqParts_dialog_width,
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
            // $("#creatParts").jqGrid('bindKeys');
            // $(window).bind('resize', function () {
            // });



            // plans_grid_data（计划表）
            $("#creatPlans").jqGrid(
                {
                    data: plans_grid_data,
                    datatype: "local",
                    // url: "../JQGridTest/Index",  //控制器
                    editurl: 'clientArray',
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
                    sortable:true,
                    sortname: 'part_number',
                    sortorder: "asc",
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
                        "计划文档上传"
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
                            sortable:true
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
                            editable: true,
                            edittype:"select",
                            editoptions:{value:plan_type},
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
                            label: 'start_date',
                            name: 'start_date',
                            index: '{{Lang::get('mowork.start_date')}}',
                            width: 100,
                            editable: true,
                            edittype:"text",
                            editoptions: {
                                dataInit: function (element) {
                                    $(element).datetimepicker({
                                        minView: "month",
                                        language:  'zh-CN',
                                        format: 'yyyy-mm-dd',
                                        todayBtn:  1,
                                        autoclose: 1
                                    });
                                }
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
                                    $(element).datetimepicker({
                                        minView: "month",
                                        language:  'zh-CN',
                                        format: 'yyyy-mm-dd',
                                        todayBtn:  1,
                                        autoclose: 1
                                    });
                                }
                            }
                        },
                        {
                            name: 'operate',
                            index: 'operate',
                            width:100,
                            align:'center',
                            sortable: false,
                            formatter: function (cellvalue, options, rowObject) {
                                return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
                                    'onclick="plan_upload(this)" plan_data="'+rowObject.plan_number+'">上传计划文件</a>';
                            }
                        }
                    ]
                }
            );

            $("#creatPlans").navGrid('#plansGridNav',{edit:true,add:false,del:false,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
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
                }
            );
            // 新建子计划
            // $('#creatPlans').navButtonAdd('#plansGridNav',
            //     {
            //         buttonicon: "ui-icon-mail-closed",
            //         title: "新建子计划",
            //         caption: "新建子计划",
            //         position: "last",
            //         onClickButton: addChildPlan
            //     }
            // );
            // function addChildPlan(){
            //     var plans_rowNum =  $("#creatPlans").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
            //     if(plans_rowNum == 0){
            //         alert("请先新建零件生成计划!");
            //     }else{
            //         var slts = $("#creatPlans").jqGrid('getGridParam','selarrrow'); //选中行的id
            //         var length_arrSlts = slts.length;
            //         if(length_arrSlts < 1){
            //             alert("请选择一条计划新建子计划!");
            //         }else if(length_arrSlts > 1){
            //             alert("只能在一条计划新建子计划!");
            //         }else{
            //             alert("新建子计划！");
            //         }
            //     }
            // }
            // the bindKeys()
            // $("#creatPlans").jqGrid('bindKeys');

            function validate() {
                err = '';
                cat = $('#folder_cat').val();
                if(cat.length == 0 ) {
                    err += "{{Lang::get('mowork.folder_required')}}\n";
                }
                if(err.length > 0 ) {
                    alert(err);
                    return false;
                }
                return true;
            }


            $('.dropzone').click(function(event){
                event.preventDefault();
            });


            // 上传文件控件配置
            Dropzone.options.mydropzone={
                maxFiles: 5,
                maxFilesize: 4,// MB
                acceptedFiles: ".pdf,.docx,.xlsx,.jpg,.gif,.png",
                addRemoveLinks: true,
                dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.click_upload')}}</span>",
                dictFileTooBig: "{{Lang::get('mowork.image_too_big')}}",
                dictRemoveFile: "{{Lang::get('mowork.cancel_image')}}",
                dictInvalidFileType: "{{Lang::get('mowork.image_type_error')}}",
                dictMaxFilesExceeded: "{{Lang::get('mowork.exceed_max_files')}}",
                init: function() {
                    this.on("maxfilesexceeded", function(file){
                        this.removeFile(file);
                    });
                    this.on("error", function(file, responseText) {
                        alert("{{Lang::get('mowork.upload_file_error')}}");
                        this.removeFile(file);
                    });
                    this.on("success", function(file, responseText) {
                        console.log(file);
                    });
                },

                removedfile: function(file) {
                    var name = file.name;
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/relink')}}",
                        data: {
                            fname: name,//fullpath for this uploaded file to be deleted
                            _token: "{{ csrf_token() }}"
                        },
                        success: function( data ) {
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        },
                        dataType: 'html'  //use type html rather than json in order to post token
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            }
        });
        $("[rel=tooltip]").tooltip({animation:false});

        // 编号加num
        function numberPlus(str, num)
        {
            var prefix = str.replace(/[0-9]+$/, '');
            var number = str.replace(/^[a-zA-Z]+/, '');
            var sum = str.length
            number = parseInt(number) + num + '';
            var n = sum - prefix.length - number.length
            var tmpStr = '';
            if(n > 0){
                for(var i = 0; i < n; i++){
                    tmpStr += '0';
                }
            }
            
            return prefix + tmpStr + number
        }
    </script>
@stop