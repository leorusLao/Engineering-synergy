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
				<li><a href="#step-1">1:编辑项目头信息</a></li>
				<li><a href="#step-2">2:编辑零件信息</a></li>
				<li><a href="#step-3">3:编辑计划信息</a></li>
			</ul>
			<div>
				<div id="step-1" class="">
					<div class="form-horizontal clearfix" id="sample-form">
						{{--项目编号--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_number')}} </label>
						<div class="col-md-4 col-lg-3">
							<input id="project_number" readonly="readonly" value="{{ $result_project->proj_code }}" class="form-control" name="project_number" placeholder="{{Lang::get('mowork.project_number')}}">
						</div>
						{{--项目名称--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_name')}} </label>
						<div class="col-md-4 col-lg-3">
							<input id="project_name" class="form-control" value="{{ $result_project->proj_name }}" placeholder="项目名称">
						</div>
						{{--项目类别--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_category')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="project_category" class="form-control" name="project_category" placeholder="项目类别" >
								@foreach($project_type as $tmpType)
								<option value="{{ $tmpType['type_id'] }}" @if($tmpType['type_id'] == $result_project->proj_type)selected="selected"@endif>{{ $tmpType['name'] }}</option>
								@endforeach
							</select>
						</div>
						{{--</div>--}}
						{{--客户名称--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.customer_name')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="customer_number" class="form-control" name="customer_number">
								@foreach($customer as $tmpCustomer)
								<option value="{{$tmpCustomer['cust_company_id']}}" @if($tmpCustomer['cust_company_id'] == $result_project->customer_id)selected="selected"@endif>{{$tmpCustomer['company_name']}}</option>
								@endforeach
							</select>
						</div>
						{{--项目经理--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_manager')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="proj_manager_uid" class="form-control" name="proj_manager_uid">
								@foreach($company_user as $tmpMember)
								<option value="{{$tmpMember['uid']}}" @if($tmpMember['uid'] == $result_project->proj_manager_uid)selected="selected"@endif>{{$tmpMember['fullname']}}</option>
								@endforeach
							</select>
						</div>
						{{--项目成员--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_member')}} </label>
						<div class="col-md-4 col-lg-3" style="line-height: 2.5">
							<select multiple id="project_member_value" class="chosen-select form-control" name="project_member" data-placeholder="选择成员">
								@foreach($company_user as $tmpMember)
								<option value="{{$tmpMember['uid']}}" @if(in_array($tmpMember['uid'], explode(',', $result_project->member_list)))selected="selected"@endif>{{$tmpMember['fullname']}}</option>
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
									@foreach($calendar as $tmpCalendar)
									<option value="{{ $tmpCalendar['cal_id'] }}" @if($tmpCalendar['cal_id'] == $result_project->calendar_id)selected="selected"@endif>{{ $tmpCalendar['cal_name'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						{{--项目性质--}}
						<label class="col-md-2 col-lg-1 text-info" for="">
							* {{Lang::get('mowork.project_nature')}} </label>
						<div class="col-md-4 col-lg-3">
							<select id="project_plan" class="form-control" name="project_plan" placeholder="{{Lang::get('mowork.project_nature')}}">
								<option value="1" @if($result_project->property == 1)selected="selected"@endif>{{Lang::get('mowork.public_plan')}}</option>
								<option value="0" @if($result_project->property == 0)selected="selected"@endif>{{Lang::get('mowork.private_plan')}}</option>
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
										   type="text" value="{{ $result_project->start_date }}" required="required">
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
										   type="text" value="{{ $result_project->end_date }}" required="required">
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
										   type="text" value="{{ $result_project->process_trail }}" required="required">
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
										   type="text" value="{{ $result_project->mold_sample }}" required="required">
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
										   type="text" value="{{ $result_project->trail_production }}" required="required">
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
										   type="text" value="{{ $result_project->batch_production }}" required="required">
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
							<textarea id="project_desction" class="autosize-transition form-control">{{ $result_project->description }}</textarea>
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
											<form action="{{ url('/upload/instruction') }}" class="dropzone" id="mydropzone" style="min-height: 20px;">
												<input name="_token" value="{{ csrf_token() }}" type="hidden">
												

                                                @foreach ($result_project->new_pic as $value)
                                                @if ($value['bool'])



                                                <!-- 图片 -->
											    <div class="dz-preview dz-processing dz-success dz-complete dz-image-preview">  
											    	<div class="dz-image">
											    		<img data-dz-thumbnail="" alt="{{$value['name']}}" src="{{$result_project->public_path}}{{$value['path']}}" style="width:120px;height: 120px;">
											    	</div>  
											    	<div class="dz-details">    
											    		<div class="dz-size">
											    			<span data-dz-size=""><strong>{{$value['size']}}</strong></span>
											    		</div>    
											    		<div class="dz-filename">
											    			<span data-dz-name="">{{$value['name']}}</span>
											    		</div>  
											    	</div>  
											    	<div class="dz-progress">
											    		<span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span>
											    	</div>  
											    	<div class="dz-error-message">
											    		<span data-dz-errormessage=""></span>
											    	</div>  
											    	<div class="dz-success-mark">    
											    		
											    	</div>  
											    	<div class="dz-error-mark">    
											    		  
											        </div>
											        <a class="dz-remove" href="javascript:undefined;" data-dz-remove="">{{Lang::get('mowork.cancel_image')}}</a>
											    </div>


                                                @else

          										<!-- 普通文件 -->
												<div class="dz-preview dz-file-preview dz-processing dz-success dz-complete">  
													<div class="dz-image">
														<img data-dz-thumbnail="">
													</div>  
													<div class="dz-details">    
														<div class="dz-size"
														 <span data-dz-size=""><strong>{{$value['size']}}</strong></span>
														</div>    
														<div class="dz-filename">
															<span data-dz-name="">{{$value['name']}}</span>
														</div>  
													</div>  
													<div class="dz-progress">
														<span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span>
													</div>  
													<div class="dz-error-message">
														<span data-dz-errormessage=""></span>
													</div>  
													<div class="dz-success-mark">    
														 
													</div>  
													<div class="dz-error-mark">    
														 
													</div>
													<a class="dz-remove" file-data="{{json_encode($value)}}" onclick="removeUploadedFile(this);" href="javascript:;">{{Lang::get('mowork.cancel_file')}}</a>
											    </div>
											    @endif


                                                @endforeach



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
											<select multiple id="plan_category" class="form-control chosen-select" name="project_category" placeholder="项目类别" >
												@foreach($plan_type as $key => $row)
                                                    <option value="{{$row['type_id']}}">{{$row['type_name']}}</option>
                                                @endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button id="btnSave" type="button" class="btn btn-primary">{{Lang::get('mowork.save')}}</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">{{Lang::get('mowork.close_issue')}}</button>
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
        function addPlan(obj){
            part_number = $(obj).parents('tr').find("td").eq(2).text();
            //同步零件编号（打开modal窗口的时候传入）
            $('#addPlan').on('show.bs.modal',
                function() {
                    temp_part_number = part_number;
                }
            );
            // 计划类别显示
            $('#addPlan').modal('show');
        }

        //删除文件
        function removeUploadedFile(evt){
            var file_data = $(evt).attr('file-data');
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/removefile')}}",
                        data: {
                            fdata: file_data,//fullpath for this uploaded file to be deleted
                            basecata: 'company',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function( data ) {
                        	/*console.log(data);*/
                        	$(evt).parent().remove();
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        },
                        dataType: 'html'  //use type html rather than json in order to post token
                    });
        }


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
            @foreach($part_type as $key => $row)
                part_type += "{{$row['name']}}:{{$row['name']}};"
            @endforeach
            part_type = part_type.substring(0, part_type.length-1);

            // 零件编号
            var part_number = "";

            //计划类型
            var plan_type = "";
            @foreach($plan_type as $key => $row)
                plan_type += "{{$row['type_id']}}:{{$row['type_name']}};"
            @endforeach
            plan_type = plan_type.substring(0, plan_type.length-1);

            //计划负责人
            var plan_leader = "";
            @foreach($company_user as $key => $row)
                plan_leader += "{{$row['fullname']}}:{{$row['fullname']}};"
            @endforeach
            plan_leader = plan_leader.substring(0, plan_leader.length-1);

            //计划成员
            var plan_member = "";
            @foreach($company_user as $key => $row)
                plan_member += "{{$row['fullname']}}:{{$row['fullname']}};"
            @endforeach
            plan_member = plan_member.substring(0, plan_member.length-1);

            var sourceArr = new Array();
            sourceArr[1] = '自制';
            sourceArr[2] = '外购';
            sourceArr[3] = '客供';

            //各种计划类型
            var all_plan_type = '';
            @foreach($all_coding as $key => $row)
                all_plan_type += '"{{$row['type_id']}}":"{{$row['plan_code']}}",'
            @endforeach
            all_plan_type = all_plan_type.substring(0, all_plan_type.length-1);
            all_plan_type = "{" +all_plan_type+ "}";
            var obj_all_plan_type = $.parseJSON( all_plan_type );
            // {74:akjda180416000001;75:123a180416000001;76:afd180416000001}

            // console.log(all_plan_type);

            // 初始化零件的信息
            var parts_info = new Array();
            @foreach($result_detail as $v)
        		parts_info[parts_info.length] = '{"part_code": "{{$v['part_code']}}","part_name": "{{$v['part_name']}}","part_type":"{{$v['part_type']}}","part_from": "' + sourceArr["{{$v['part_from']}}"] + '", "quantity": "{{$v['quantity']}}","jig": "{{$v['jig']}}", "gauge": "{{$v['gauge']}}","mold": "{{$v['mold']}}","part_size": "{{$v['part_size']}}","weight": "{{$v['weight']}}","material": "{{$v['material']}}","mat_size": "{{$v['mat_size']}}","shrink": "{{$v['shrink']}}","processing": "{{$v['processing']}}","surface": "{{$v['surface']}}","note": "{{$v['note']}}"}';
        	@endforeach
        	// console.log(parts_info)
			
			// 初始化计划的信息
			var plans_info = new Array();
			// 所有的零件对应的计划
        	var all_part_plan_num = new Array();  
        	@foreach($result_plan as $v)
	        	<?php
	        		$tmpArr = explode(',', $v['member']);
	        		foreach ($tmpArr as $kk => $vv) {
	        			$tmpArr[$kk] = $user_arr[$vv];
	        			if(empty($tmpArr[$kk])){unset($tmpArr[$kk]);}
	        		}
	        	?>
        		plans_info[plans_info.length] = '{"part_code":"{{$v['part_code']}}","plan_code":"{{$v['plan_code']}}","plan_name":"{{$v['plan_name']}}","plan_type":"{{$v['plan_type']}}","leader":"{{$user_arr[$v['leader']]}}","member":"{{implode(',', $tmpArr)}}","description":"{{$v['description']}}","start_date":"{{$v['start_date']}}","end_date":"{{$v['end_date']}}"}';

        		if(!all_part_plan_num["{{$v['part_code']}}"]){all_part_plan_num["{{$v['part_code']}}"] = [];}
        		all_part_plan_num["{{$v['part_code']}}"].push("{{$v['plan_type']}}");
        	@endforeach
        	// console.log(plans_info);

        	// console.log(all_part_plan_num);

			//共用的是同一个modal因此清除下拉框的选项，同时清除为零件新建的所有计划id,上次的part_number。
            $('#addPlan').on('hidden.bs.modal', function () {
                // all_plan_num.splice(0,all_plan_num.length);
                $("#plan_category").val("");
                $("#plan_category").trigger("chosen:updated");
            });

			$("#btnSave").on("click",function(){
				var plan_category = $("#plan_category").val();
				if(plan_category == null){
				    alert('请选择计划类别');
				    return;
				}

				all_part_plan_num[temp_part_number] = plan_category;
				// console.log(all_part_plan_num);
				alert("新建计划成功");
				$(this).next().click();
			});


			// 零件
        	tmp_parts_grid_data = [];
        	parts_grid_data = [];
        	for(var i in parts_info)
        	{
        		tmp_parts_grid_data[i] = eval('(' + parts_info[i] + ')');
        	}

        	// 计划
        	tmp_plans_grid_data = [];
        	plans_grid_data = [];
        	for(var i in plans_info)
        	{
        		tmp_plans_grid_data[i] = eval('(' + plans_info[i] + ')');	
        	}

            //项目立项完成提交项目、零件、计划(针对零件)逻辑
            function btnSubmit(){
            	var project_id = '{{ $result_project->proj_id }}';
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
                // console.log(data);

                // ajax提交项目立项需要提交的数据
                $.ajax({
                    type: 'post',
                    data: {
                    	pData: data,
                    	_token: '{{ csrf_token() }}' 
                    },
                    url:'/dashboard/update-project',
                    success:function(msg){
                        if(msg.code == 1){
                            alert(msg.msg);
                            var project_id = $("#project_number").val();
                            project_id =  project_id.substring(project_id.length-3,project_id.length);
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
                        next: '{{Lang::get("mowork.next_Step")}}',
                        previous: '{{Lang::get("mowork.previous_Step")}}'
                    },
                    toolbarSettings: {
                        toolbarPosition: 'bottom', // none, top, bottom, both
                        toolbarButtonPosition: 'right', // left, right
                        showNextButton: true, // show/hide a Next button
                        showPreviousButton: true, // show/hide a Previous button
                        toolbarExtraButtons: [
                            $('<button></button>').text('{{Lang::get("mowork.save")}}')
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
            $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection)
            {    	
            	parts_grid_data = [];
            	plans_grid_data = [];
            	// 零件
            	if(stepNumber == 1) {
            		parts_grid_data = tmp_parts_grid_data;
	            }else{
	            	var part_number_str = ',';
	                // 添加的零件可能有删除
	                $('#creatParts').find('tr').each(function(){
	                    part_number_str += $(this).find('td').eq(2).text().replace(/(^\s*)|(\s*$)/g, "") + ',';
	                });
	                
	                for(var i in tmp_parts_grid_data){
	                	if(part_number_str.indexOf(',' + tmp_parts_grid_data[i]['part_code'] + ',') != -1){
	                		parts_grid_data.push(tmp_parts_grid_data[i]);
	                	}else{
	                		delete all_part_plan_num[tmp_parts_grid_data[i]['part_code']];
	                	}
	                }

	                tmp_parts_grid_data = parts_grid_data;
	                
	            }

	            $("#creatParts").jqGrid('clearGridData');  //清空表格
	                $("#creatParts").jqGrid('setGridParam',{  // 重新加载数据
	                    datatype:'local',
	                    data : parts_grid_data,   // 需要重新加载的数据
	                    page:1
	                }).trigger("reloadGrid"); 
				// console.log(all_part_plan_num);
				// C1804000305:(2) ["205", "206"]
				// C1804000664:(2) ["204", "205"]
				// console.log(tmp_plans_grid_data);
				// 0:description:"关联"end_date:"2018-04-27"leader:"大雄"member:"大雄"part_code:"C1804000306"plan_code:"P18040307"plan_name:"测试啊啊"plan_type:"204"start_date:"2018-04-26"
				// 1:description:"",end_date:"",leader:"",member:"大雄",part_code:"C1804000306",plan_code:"M18040002",plan_name:"",plan_type:"205",start_date:""
	            // 计划
	            if(stepNumber == 2){
	                var part_plan_type_arr = [];
	                var tmp_plans_grid_data_arr = [];
	                for(var ii in all_part_plan_num){
	                	for(var i in all_part_plan_num[ii])
	                	{
	                		part_plan_type_arr.push(ii + '-' + all_part_plan_num[ii][i]);
	                	}
	                }
	                // console.log(part_plan_type_arr)
	                // [C1804000305-205,C1804000305-206,C1804000664-204,C1804000664-205]
	                for(var i in tmp_plans_grid_data){
	                	tmp_plans_grid_data_arr[tmp_plans_grid_data[i]['part_code'] + '-' + tmp_plans_grid_data[i]['plan_type']] = tmp_plans_grid_data[i];
	                }
	                // console.log(tmp_plans_grid_data_arr)
	                // C1804000306-204:description:"关联"end_date:"2018-04-27"leader:"大雄"member:"大雄"part_code:"C1804000306"plan_code:"P18040307"plan_name:"测试啊啊"plan_type:"204"start_date:"2018-04-26"
					// C1804000306-205:description:"",end_date:"",leader:"",member:"大雄",part_code:"C1804000306",plan_code:"M18040002",plan_name:"",plan_type:"205",start_date:"" 

					// 新增的次数 (生成计划编号) 默认为0
					var tmp_part_plan_arr = []

					for(var i in part_plan_type_arr){
						var tmpArr = part_plan_type_arr[i].split('-');
						// console.log(tmpArr);
						if(tmp_plans_grid_data_arr[part_plan_type_arr[i]]){
							plans_grid_data.push(tmp_plans_grid_data_arr[part_plan_type_arr[i]])
						}else{
							if(tmp_part_plan_arr[tmpArr[1]])
							{
								tmp_part_plan_arr[tmpArr[1]]++;
							}else{
								tmp_part_plan_arr[tmpArr[1]] = 0;
							}
							var tempData = {"part_code": tmpArr[0],"plan_code": numberPlus(obj_all_plan_type[tmpArr[1]], tmp_part_plan_arr[tmpArr[1]]),"plan_type":tmpArr[1]};
							plans_grid_data.push(tempData);
						}
					}

					// console.log(plans_grid_data);

	            	$("#creatPlans").jqGrid('clearGridData');  //清空表格
	                $("#creatPlans").jqGrid('setGridParam',{  // 重新加载数据
	                    datatype:'local',
	                    data : plans_grid_data,   // 需要重新加载的数据
	                    page:1
	                }).trigger("reloadGrid");
	            }

            });

            //多选下拉框
            $(".chosen-select").chosen({width: "100%"});

            // jqgrid(parts_grid、_grid)
            $("#creatParts").jqGrid(
                {
                    editurl: 'clientArray', // 修改单条数据
                    // url:'', // 列表页数据
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
                        "{{Lang::get('mowork.upload_part_file')}}",
                        "新建计划",
                    ],
                    colModel: [
                        {
                            name: 'part_code',
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
                            // formatter:function(){
                            //     //获取显示配置记录数量
                            //     var part_rowNum =  $("#creatParts").jqGrid('getGridParam', 'records'); 
                               	
                               
                            //     // return numberPlus('{{$part_number}}', part_rowNum - parseInt("{{count($result_detail)}}"))
                            // }
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
                            name: 'part_from',
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
                            name: 'jig',
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
                            name: 'mold',
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
                            name: 'weight',
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
                            name: 'material',
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
                            name: 'mat_size',
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
                            name: 'processing',
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
                            name: 'surface',
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
                            name: 'note',
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
                                    'onclick="part_upload('+options.rowId+')">{{Lang::get("mowork.upload_part_file")}}}</a>';
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
                                    'onclick="addPlan(this)">新建计划</a>';
                            }
                        }
                    ],
                    afterInsertRow:function(){
                    	var part_rowNum =  $("#creatParts").jqGrid('getGridParam', 'records');
                    	var tmpPartCode = numberPlus('{{$part_number}}', part_rowNum - 1 - parseInt("{{count($result_detail)}}"));
                    	var obj = $('#creatParts tr:eq(1) td');
                    	obj.eq(2).attr('title', tmpPartCode).text(tmpPartCode);
                    	parts_info[parts_info.length] = '{'
                    		+ '"part_code": "' + tmpPartCode
                    		+ '",'
                    		+ '"part_name": "' + obj.eq(3).text().replace(/(^\s*)|(\s*$)/g, '') 
                    		+ '",'
                    		+ '"part_type":"' + obj.eq(4).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"part_from": "' + obj.eq(5).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"quantity": "' + obj.eq(6).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"jig": "' + obj.eq(7).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"gauge": "' + obj.eq(8).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"mold": "' + obj.eq(9).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"part_size": "' + obj.eq(10).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"weight": "' + obj.eq(11).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"material": "' + obj.eq(12).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"mat_size": "' + obj.eq(13).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"shrink": "' + obj.eq(14).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"processing": "' + obj.eq(15).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"surface": "' + obj.eq(16).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '",'
                    		+ '"note": "' + obj.eq(17).text().replace(/(^\s*)|(\s*$)/g, '')
                    		+ '"'
                    		+'}';
                    	tmp_parts_grid_data.push(eval('(' + parts_info[parts_info.length - 1] + ')'));
                    }
                    


                }

                


            );

            $("#creatParts").navGrid('#partsGridNav',{edit:true,add:true,del:true,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
                // options for the Edit Dialog
                // {
                //     editCaption: "修改零件",
                //     left:10,
                //     top:50,
                //     width:jqParts_dialog_width,
                //     height: 280,
                //     reloadAfterSubmit:true,
                //     jqModal:false,
                //     bSubmit: "保存",
                //     bCancel: "关闭",
                //     closeAfterEdit:true,
                //     recreateForm: true
                // }
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
            $("#creatParts").jqGrid('bindKeys');
            $(window).bind('resize', function () {
            });



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
                            name: 'part_code',
                            index: "{{Lang::get('mowork.part_number')}}",
                            align:'center',
                            width:100,
                            edittype:"text",
                        },
                        {
                            name: 'plan_code',
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
                            name: 'leader',
                            index: "{{Lang::get('mowork.plan_leader')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:plan_leader},
                            sortable:false
                        },
                        {
                            name: 'member',
                            index: "{{Lang::get('mowork.plan_member')}}",
                            align:'center',
                            width:100,
                            editable: true,
                            edittype:"select",
                            editoptions:{value:plan_member,multiple:true},
                            sortable:false
                        },
                        {
                            name: 'description',
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





            $('.dropzone').click(function(event){
                event.preventDefault();
            });

            // 上传文件控件配置
            Dropzone.options.mydropzone={
                maxFiles: 5,
                maxFilesize: 4,// MB
                acceptedFiles: ".pdf,.docx,xlsx.jpg,.gif,.png",
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