@extends('backend-base')

@section('css.append')
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<!-- page specific plugin styles -->
	<!-- third-party plugins (smartwizard/datetimepicker/multiple select/dropzone/jqgrid) -->

	{{--Include SmartWizard CSS--}}
	<link href="/asset/SmartWizard-master/dist/css/smart_wizard.min.css" rel="stylesheet" type="text/css"/>
	{{--Optional SmartWizard theme[arrow]--}}
	<link href="/asset/SmartWizard-master/dist/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css"/>
	{{--chosen plugin--}}
	<link rel="stylesheet" href="/asset/chosen_v1.8.3/chosen.min.css">
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
				<li><a href="#step-1">1:项目头信息</a></li>
				<li><a href="#step-2">2:零件信息</a></li>
				<li><a href="#step-3">3:计划信息</a></li>
				<li><a href="#step-4">4:文档信息</a></li>
			</ul>
			<div>
				<div id="step-1" class="">
					<div class="form-horizontal clearfix" id="sample-form">
						{{--项目编号--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_number')}} </label>
						<div class="col-md-4 col-lg-3">
							<input id="project_number" readonly="readonly" value="{{ $result_project->proj_code }}" class="form-control" name="project_number" placeholder="{{Lang::get('mowork.project_number')}}" />
						</div>
						{{--项目名称--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_name')}} </label>
						<div class="col-md-4 col-lg-3">
							<input id="project_name" class="form-control" readonly="readonly" placeholder="项目名称" value="{{ $result_project->proj_name }}"/>
						</div>
						{{--项目类别--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_category')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="project_category" class="form-control" name="project_category" placeholder="项目类别" >
								<option value="{{ $result_project->proj_type }}" selected="selected">{{ $result_project->proj_type_name }}</option>
							</select>
						</div>
						{{--</div>--}}
						{{--客户名称--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.customer_name')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="customer_number" class="form-control" name="customer_number">
								<option value="{{ $result_project->customer_id }}" selected="selected">{{ $result_project->customer_name }}</option>
							</select>
						</div>
						{{--项目经理--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_manager')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="proj_manager_uid" class="form-control" name="proj_manager_uid">
								<option value="{{ $result_project->proj_manager_uid }}" selected="selected">{{ $result_project->proj_manager }}</option>
							</select>
						</div>
						{{--项目成员--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_member')}} </label>
						<div class="col-md-4 col-lg-3" style="line-height: 2.5">
							<select multiple id="project_member_value" class="chosen-select form-control" name="project_member" data-placeholder="选择成员" disabled="disabled">
								@foreach($result_project->member_list_data as $tmpData)
								<option selected="selected" value="{{ $tmpData['uid'] }}">{{ $tmpData['fullname'] }}</option>
								@endforeach
							</select>
						</div>
						{{--</div>--}}
						{{--项目日历--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_calendar')}} </label>
						<div class="col-md-4 col-lg-3">
							<div>
								<select id="project_calendar" class="form-control" name="project_calendar" placeholder="项目日历">
									<option selected="selected">{{ $result_project->cal_name }}</option>
								</select>
							</div>
						</div>
						{{--项目性质--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_nature')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="project_plan" class="form-control" name="project_plan" placeholder="项目性质">
								<option value="1" @if($result_project->property == 1)selected="selected"@else disabled="disabled"@endif>{{Lang::get('mowork.public_plan')}}</option>
								<option value="0"  @if($result_project->property == 0)selected="selected"@else disabled="disabled"@endif>{{Lang::get('mowork.private_plan')}}</option>
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
										   type="text" value="{{ $result_project->start_date }}" required="required" readonly="readonly" />
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
										   type="text" value="{{ $result_project->end_date }}" required="required" readonly="readonly" />
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
										   type="text" value="{{ $result_project->process_trail }}" required="required" readonly="readonly" />
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
										   type="text" value="{{ $result_project->mold_sample }}" required="required" readonly="readonly" />
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
										   type="text" value="{{ $result_project->trail_production }}" required="required" readonly="readonly" />
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
										   type="text" value="{{ $result_project->batch_production }}" required="required" readonly="readonly" />
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
							<textarea id="project_desction" class="autosize-transition form-control" readonly="readonly">{{ $result_project->description }}</textarea>
						</div>
					</div>
				</div>
				<div id="step-2" class="">
					<div class="jqGrid_wrapper">
						<table id="partsInfo"></table>
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
											<select multiple id="project_category" class="form-control chosen-select" name="project_category" placeholder="项目类别" >

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
						<table id="plansInfo"></table>
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
				<div id="step-4" class="">
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
	{{--Include chosen JavaScript sourc--}}
	<script src="/asset/chosen_v1.8.3/chosen.jquery.min.js"></script>
	{{--Include jqgrid JavaScript sourc--}}
	<script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/i18n/grid.locale-en.js"></script>
	<script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/jquery.jqGrid.min.js"></script>

	<script type="text/javascript">
        $(document).ready(function () {

            // 动态设置jqgrid宽度
            var jqParts_width = $('.jqGrid_wrapper').width() - 30;
            var jqParts_dialog_width = '';
            if(jqParts_width < 1600){
                jqParts_dialog_width = 1000;
            }else{
                jqParts_dialog_width = 1400;
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
                        showPreviousButton: true // show/hide a Previous button
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

            // Initialize the showStep event
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            });

            //多选下拉框
            $(".chosen-select").chosen({width: "100%"});

            //jqgrid(parts_grid、_grid)
            $("#partsInfo").jqGrid(
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
                                var part_rowNum =  $("#partsInfo").jqGrid('getGridParam', 'records'); //获取显示配置记录数量
                                var head_part_number = part_number.substr(0,5);
                                if(part_rowNum == 0){
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


            // plans_grid_data（计划表）
            $("#plansInfo").jqGrid(
                {
                    data: plans_grid_data,
                    datatype: "local",
                    url: "../JQGridTest/Index",  //控制器
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
                            edittype:"text"
                            // editoptions: {
                            //     dataInit: function (element) {
                            //         $(element).datetimepicker({
                            //             minView: "month",
                            //             language:  'zh-CN',
                            //             format: 'yyyy-mm-dd',
                            //             todayBtn:  1,
                            //             autoclose: 1
                            //         });
                            //     }
                            // }
                        },
                        {
                            label: 'end_date',
                            name: 'end_date',
                            index: "{{Lang::get('mowork.end_date')}}",
                            width: 100,
                            editable: true,
                            edittype:"text"
                            // editoptions: {
                            //     dataInit: function (element) {
                            //         $(element).datetimepicker({
                            //             minView: "month",
                            //             language:  'zh-CN',
                            //             format: 'yyyy-mm-dd',
                            //             todayBtn:  1,
                            //             autoclose: 1
                            //         });
                            //     }
                            // }
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
        });
	</script>
@stop