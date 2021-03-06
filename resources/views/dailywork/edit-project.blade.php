@extends('backend-base')

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
	$(document).on("click","button[data-target='#delete_confirm']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","button[data-target='#company_user']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","a.tododelete",function(){setTimeout('$("body").css("padding-right","0px")',1);});

</script>

<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link  href="/asset/css/todolist.css" rel="stylesheet" media="screen">

@stop

@section('content')
<div class="col-xs-12">

<form id='form_project' method='POST'>
<input type="hidden" class="projectid" value="{{$result['result_project']['proj_id']}}">

<div>


	<!--项目编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_number')}}
		</div>
		<input class="form-control" name="project_number" type="text" value="{{$result['result_project']['proj_code']}}" placeholder="{{Lang::get('mowork.project_number')}}" id="project_number" required="required"/>
	</div>
	<!--项目名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_name')}}
		</div>
		<input class="form-control" name="project_name" type="text" value="{{$result['result_project']['proj_name']}}"  placeholder="{{Lang::get('mowork.project_name')}}" id="project_name"  required="required"/>
	</div>
	<!--项目类别-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_category')}}
		</div>
		<select id="project_category" class="form-control select2" name="project_category">
		    <option value="1" <?php if($result['result_project']['proj_type']==1){ echo "selected='selected'";}?> >{{LANG::get('mowork.Auto')}}</option>
		    <option value="2" <?php if($result['result_project']['proj_type']==2){ echo "selected='selected'";}?> >{{LANG::get('mowork.3C')}}</option>
		    <option value="3" <?php if($result['result_project']['proj_type']==3){ echo "selected='selected'";}?> >{{LANG::get('mowork.Media')}}</option>
		</select>
	</div>
	<!--客户名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.customer_name')}}
		</div>
		<select id="customer_number" class="form-control select2" name="customer_number">
			@foreach ($result['customer'] as $key => $value)
		    <option value="{{$value['cust_company_id']}}" @if($value['cust_company_id'] == $result['result_project']['customer_id']) selected="selected"@endif >{{$value['company_name']}}</option>
		    @endforeach
		</select>
	</div>
	<!--项目经理-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_manager')}}
		</div>
		<select id="proj_manager_uid" class="form-control select2" name="proj_manager_uid">
			<?php foreach ($result['company_user'] as $key => $value) { ?>
		    <option value="{{$value['uid']}}" <?php if($value['fullname']==$result['result_project']['proj_manager']){ echo "selected='selected'";}?> >{{$value['fullname']}}</option>
		    <?php } ?>
		</select>
	</div>
	<!--项目成员-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_member')}}
		</div>
		<input class="form-control" name="project_member" type="text" value="{{$result['result_project']['member_list_name']}}" style="width:80%;"  placeholder="{{Lang::get('mowork.project_member')}}" id="project_member"  onfocus=this.blur() required="required"/>
		<input class="form-control" name="project_member_value" type="text" value="{{$result['result_project']['member_list']}}" style="display:none;" id="project_member_value" />
	    &nbsp;&nbsp;<button type="button" class="btn btn-raised btn-default btn-large" data-toggle="modal" data-target="#company_user">{{Lang::get('mowork.select')}}</button>
	</div>
	<!--项目日历-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.project_calendar')}}
		</div>
		<select id="project_calendar" class="form-control select2" name="project_calendar">
			<?php foreach ($result['calendar'] as $key => $value) { ?>
		    <option value="{{$value['cal_id']}}"  <?php if($value['cal_name']==$result['result_project']['cal_name']){ echo "selected='selected'";}?> >{{$value['cal_name']}}</option>
		    <?php } ?>
		</select>
	</div>
	<!--项目性质-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_nature')}}
		</div>
		<select id="project_plan" class="form-control select2" name="project_plan">
		    <option value="1" <?php if($result['result_project']['property']==1){ echo "selected='selected'";}?> >{{Lang::get('mowork.public_plan')}}</option>
		    <option value="0" <?php if($result['result_project']['property']==0){ echo "selected='selected'";}?> >{{Lang::get('mowork.private_plan')}}</option>
		</select>
	</div>
	<!--接受日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.date_acceptance')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_acceptance" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar"  name="start_date" size="16" type="text"  value="{{$result['result_project']['start_date']}}"  onfocus=this.blur()  required="required">
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_acceptance" value="" />
	</div>
	<!--结束日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.date_end')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar" size="16" name="end_date" type="text" value="{{$result['result_project']['end_date']}}" onfocus=this.blur()  required="required">
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_end" value="" />
	</div>
	<!--工艺验证日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_validation')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_validation" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar" size="16" name="validation_date" type="text" value="{{$result['result_project']['process_trail']}}" onfocus=this.blur() >
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_validation" value="" />
	</div>
	<!--出模样件日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_sample')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_sample" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar" size="16" name="sample_date" type="text" value="{{$result['result_project']['mold_sample']}}" onfocus=this.blur()>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_sample" value="" />
	</div>
	<!--试产验证日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_verification')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_verification" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar" size="16" name="pilot_date" type="text" value="{{$result['result_project']['trail_production']}}" onfocus=this.blur()>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_verification" value="" />
	</div>
	<!--批量放产日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_delivery')}}
		</div>
		<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_delivery" data-link-format="yyyy-mm-dd">
		    <input class="form-control border-left-squar" size="16" type="text" name="production_date" value="{{$result['result_project']['batch_production']}}"  onfocus=this.blur()>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		</div>
		<input type="hidden" id="date_delivery" value="" />
	</div>
	<!--项目描述-->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.project_desction')}}
		</div>
		<textarea class="form-control" name="project_desction"  placeholder="{{Lang::get('mowork.project_desction')}}" id="project_desction" >{{$result['result_project']['description']}}</textarea>
	</div>

	<!--零件列表-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{Lang::get('mowork.part_information')}}</h3>
                <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
            </div>
            <div class="panel-body">

			<!--零件信息-->
			<form class="row list_of_items" id='form_linjian'>
				<div class="col-xs-12 col-sm-12">
				<table class="table table-striped table-bordered" id="inline_edit" style="margin-bottom: 0px;">
					<thead>
						<tr>
						<th>{{Lang::get('mowork.part_number')}}</th>
						<th>{{Lang::get('mowork.part_name')}}</th>
						<th>{{Lang::get('mowork.part_type')}}</th>
						<th>{{Lang::get('mowork.quantity')}}</th>
						<th>{{Lang::get('mowork.comment')}}</th>
						<th>{{Lang::get('mowork.fixture')}}</th>
						<th>{{Lang::get('mowork.gauge')}}</th>
						<th>{{Lang::get('mowork.mould')}}</th>
						<th>{{Lang::get('mowork.processing_technology')}}</th>
						<th>{{Lang::get('mowork.resource')}}</th>
						<th>{{Lang::get('mowork.part_material')}}</th>
						<th>{{Lang::get('mowork.material_specification')}}</th>
						<th>{{Lang::get('mowork.shrinkage')}}</th>
						<th>{{Lang::get('mowork.surface_treatment')}}</th>
						<th>{{Lang::get('mowork.part_size')}}</th>
						<th>{{Lang::get('mowork.part_weight')}}</th>
						<th>{{Lang::get('mowork.measurement_operation')}}</th>
						</tr>
					</thead>
					<tbody class="area_todolist">
					<?php $todonum=0; foreach ($result['result_detail'] as $key => $value) { ?>
						<tr class='todolist_list showactions linjian{{$value["part_code"]}}' style='display:table-row; float: none;' role='row'>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['part_code']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['part_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['part_type']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['quantity']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['note']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['jig']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['gauge']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['mold']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['processing']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['part_from']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['material']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['mat_size']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['shrink']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['surface']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['part_size']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['weight']}}</td>
						<td>
						<div class="pull-right todoitembtns" style="float:left!important; padding-top:0px;">
						<a href="#" class="todoedit"><span class="glyphicon glyphicon-pencil linjian-pencil"></span></a>
						<span class="striks"> | </span>
		                <a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,"{{$value['part_code']}}","温馨提示","确定要删除此记录？")'>
		                <span class='glyphicon glyphicon-trash'></span>
		                </a>
		                <span class='striks'> | </span>
		                <a class='addplan'>
		                <span class='glyphicon glyphicon-plus-sign'></span>
		                </a>
						</div>
						</td>
						<div style="display:none;" class='todotext_<?php echo $key;?>_<?php $todonum++; echo $todonum; $todonum=0;?>'>{{$value['id']}}</div>
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				<div class="partid_delete" style="display:none;"></div>
				</div>
			</form>


			<!-- 添加零件信息 -->
	        <div class="todolist_list adds" style="border-bottom:0px;">
	            <form role="form" id="main_input_box" class="form-inline">
	                <div class="form-group col-md-3">
	                	<!--零件编号-->
	                    <input class="form-control cust_text1 part_number" placeholder="* {{Lang::get('mowork.part_number')}}"  name="part_number" required='required' type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--零件名称-->
	                    <input class="form-control cust_text1 part_name"  placeholder="* {{Lang::get('mowork.part_name')}}"  name="part_name" required='required' type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--零件类型-->
	                    <input class="form-control cust_text1 part_type"  placeholder="{{Lang::get('mowork.part_type')}}"  name="part_type" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--数量-->
	                    <input class="form-control cust_text1 quantity"  placeholder="{{Lang::get('mowork.quantity')}}"  name="quantity" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--备注-->
	                    <input class="form-control cust_text1 comment"  placeholder="{{Lang::get('mowork.comment')}}"  name="comment" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--夹具-->
	                    <input class="form-control cust_text1 fixture"  placeholder="{{Lang::get('mowork.fixture')}}"  name="fixture" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--检具-->
	                    <input class="form-control cust_text1 gauge"  placeholder="{{Lang::get('mowork.gauge')}}"  name="gauge" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--模具-->
	                    <input class="form-control cust_text1 mould"  placeholder="{{Lang::get('mowork.mould')}}"  name="mould" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--加工工艺-->
	                    <input class="form-control cust_text1 processing_technology"  placeholder="{{Lang::get('mowork.processing_technology')}}"  name="processing_technology" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--来源-->
	                    <input class="form-control cust_text1 resource"  placeholder="{{Lang::get('mowork.resource')}}"  name="resource" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--零件材料-->
	                    <input class="form-control cust_text1 part_material"  placeholder="{{Lang::get('mowork.part_material')}}"  name="part_material" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--材料规格-->
	                    <input class="form-control cust_text1 material_specification"  placeholder="{{Lang::get('mowork.material_specification')}}"  name="material_specification" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--缩水率-->
	                    <input class="form-control cust_text1 shrinkage"  placeholder="{{Lang::get('mowork.shrinkage')}}"  name="shrinkage" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--表面处理-->
	                    <input class="form-control cust_text1 surface_treatment"  placeholder="{{Lang::get('mowork.surface_treatment')}}"  name="surface_treatment" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--零件尺寸-->
	                    <input class="form-control cust_text1 part_size"  placeholder="{{Lang::get('mowork.part_size')}}"  name="part_size" type="text">
	                </div>
	                <div class="form-group col-md-3">
	                	<!--零件重量-->
	                    <input class="form-control cust_text1 part_weight"  placeholder="{{Lang::get('mowork.part_weight_g')}}"  name="part_weight" type="text">
	                </div>

	                <div class="col-md-12">
	                <input type="submit" value="{{Lang::get('mowork.add')}}" class="btn btn-default add_button">&nbsp;&nbsp;
					<input type="reset" class="btn btn-default hidden-xs add_button" value="{{LANG::get('mowork.reset')}}">
	                </div>
	            </form>
	        </div>

            </div>
        </div>
    </div>



	<!--计划列表-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{Lang::get('mowork.plan_information')}}</h3>
                <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
            </div>
            <div class="panel-body">

			<!--计划信息-->
			<form class="row list_of_items" id='form_plan'>
				<div class="col-xs-12 col-sm-12">
				<table class="table table-striped table-bordered" id="inline_edit" style="margin-bottom: 0px;">
					<thead>
						<tr>
						<th>{{Lang::get('mowork.part_number')}}</th>
						<th>{{Lang::get('mowork.plan_number')}}</th>
						<th>{{Lang::get('mowork.plan_name')}}</th>
						<th>{{Lang::get('mowork.plan_type')}}</th>
						<th>{{Lang::get('mowork.plan_description')}}</th>
						<th>{{Lang::get('mowork.start_date')}}</th>
						<th>{{Lang::get('mowork.end_date')}}</th>
						<th>{{Lang::get('mowork.measurement_operation')}}</th>
						</tr>
					</thead>
					<tbody class="area_todolist_plan">
					<?php $todonum=0; $partid_yl = ''; $num=0; foreach ($result['result_plan'] as $key => $value) {
							$partid_yl = $partid_yl.$value['plan_id'].',';
							$num++;
						?>
						<tr class='todolist_list_plan showactions plan{{$value["part_code"]}}<?php echo $num;?> pland{{$value['part_code']}}' style='display:table-row; float: none;' role='row'>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['part_code']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['plan_code']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['plan_name']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['plan_type']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['description']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['start_date']}}</td>
						<td class="todotext_<?php $todonum++; echo $todonum;?>">{{$value['end_date']}}</td>
						<td>
						<div class="pull-right todoitembtns" style="float:left!important; padding-top:0px;">
						<a href="#" class="todoedit"><span class="glyphicon glyphicon-pencil plan-pencil"></span></a>
						<span class="striks"> | </span>
						<!-- <a class="tododelete redcolor" onclick="delete_plan({{$value['part_code']}})"><span class="glyphicon glyphicon-trash"></span></a> -->

		                <a class='tododelete redcolor' onclick='myconfirm(delete_plan,cancel,"{{$value['part_code']}}<?php echo $num;?>","温馨提示","确定要删除此记录？")'>
		                <span class='glyphicon glyphicon-trash'></span>
		                </a>

						</div>
						</td>
						<div style="display:none;" class="todotext_<?php echo $key;?>_plan_<?php $todonum++; echo $todonum; $todonum=0;?>">{{$value['plan_id']}}</div>
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				<div class="planid_delete" style="display:none;"></div>
				</div>
			</form>

            </div>
        </div>
    </div>


	<!--文档列表
    <div class="col-md-12">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h3 class="panel-title">{{Lang::get('mowork.file_information')}}</h3>
		        <span class="pull-right clickable">
		            <i class="glyphicon glyphicon-chevron-up"></i>
		        </span>
		    </div>

		</div>
    </div>
    -->

	<!-- 保存全页面 -->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="form-group text-center fl">
			<input type="submit" class="btn btn-default btn_save" value="{{LANG::get('mowork.submit')}}" />
		</div>
	</div>

</div>


<!-- 删除提示 -->
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="user_delete_confirm_title">
                    {{LANG::get('mowork.project_delete')}}
                </h4>
            </div>
            <div class="modal-body">
                {{LANG::get('mowork.want_delete')}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn_cancel" data-dismiss="modal">{{LANG::get('mowork.cancel')}}</button>
                <button type="button" class="btn btn-primary btn_delete">{{LANG::get('mowork.drop')}}</button>
            </div>
        </div>
    </div>
</div>


<!--- 公司成员 -->
<div class="extended_modals">
    <div class="modal fade in" id="company_user" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{Lang::get('mowork.select_company_user')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <ul class="list-group">
                                <?php if(!empty($result['company_user'])){ foreach ($result['company_user'] as $key => $value) { ?>
                                    <li class="list-group-item list_compuser">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" <?php if(!empty($result['result_project']['member_list_ary'])){  if(in_array($value['uid'],$result['result_project']['member_list_ary'])){ echo 'checked=true';}  }  ?> name="ary_user" username="<?php echo $value['fullname']?>" class="custom-checkbox marginleft-15" value="<?php echo $value['uid']?>"><?php echo $value['fullname']?>
                                            </label>
                                        </div>
                                    </li>
                                <?php }}  ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" data-dismiss="modal">{{LANG::get('mowork.cancel')}}</button>
                    <button type="button" class="btn btn-primary btn_saveusers">{{LANG::get('mowork.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END modal-->

	</form>
</div>
@stop

@section('footer.append')
<!-- <script src="/asset/js/moment.min.js" type="text/javascript"></script> -->
<script type="text/javascript" src="/asset/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/asset/js/jquery.dataTables_hm.js"></script>
<script type="text/javascript" src="/asset/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="/asset/js/dataTables.colReorder_hm.js"></script>
<script type="text/javascript" src="/asset/js/table-advanced_hm.js"></script>
<script type="text/javascript" src="/asset/js/todolist_hm.js"></script>
<script type="text/javascript" src="/asset/js/upload/fileUpload.js"></script>

<script type="text/javascript">

$(document).ready(function(){
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }})
var num = 0;
var num_lj = 0;
var str_lj = '&';
var num_plan = 0;
var str_plan = '&';
var num_pic = 0;
var str_pic = '&';


//发布
$('.btn_save').on('click', function(){
    $('#form_project').submit(function(){

    	//零件
		$('.area_todolist tr').each(function(i){
			$('.area_todolist tr:eq(' + i + ') td').each(function(j){
				if(j==16){
					key_id = 'part_' + i + '_17';
					str_lj = str_lj + key_id + '=' + $('.todotext_'+i+'_17').text();
				}else{
					key = 'part_' + i + '_' + j;
					cont = $(this).text();
					str_lj = str_lj + key + '=' + cont + '&';
				}
			});
			num_lj++;
		});
		str_lj = str_lj + '&num_lj=' + num_lj;

		//计划
		$('.area_todolist_plan tr').each(function(i){
			$('.area_todolist_plan tr:eq(' + i + ') td').each(function(j){
				if(j==7){
					key_id = 'plan_' + i + '_8';
					str_plan = str_plan + key_id + '=' + $('.todotext_'+i+'_plan_8').text();
				}else {
					key = 'plan_' + i + '_' + j;
					cont = $(this).text();
					str_plan = str_plan + key + '=' + cont + '&';
				}
			});
			num_plan++;
		});
		str_plan = str_plan + '&num_plan=' + num_plan;

		//被删除的零件和计划
		str_delete = '&part_delete=' + $('.partid_delete').text() + '&plan_delete=' + $('.planid_delete').text();

		//已上传的图片
		$('.fileItem').each(function(i){
			if($('.fileItem:eq('+ i +')').attr('systemfile') == 1){
				key = 'pic_' + i;
				cont = $(this).attr('filecodeid');
				str_pic = str_pic + key + '=' + cont;
				num_pic++;
			}
		})

		str_pic = str_pic == '&' ? str_pic + 'num_pic=' + num_pic : str_pic + 'num_pic=' + num_pic;

		projectid = $('.projectid').val();
		str_projectid = '&project_id=' + projectid;

		num ++;
		var project = $('#form_project').serialize();
		var data = project + str_lj + str_plan + str_pic + str_projectid + str_delete;
		if(num == 1){
	        $.ajax({
	            type: 'post',
	            data: data,
				url:'/dashboard/update-project',
				success:function(msg){
				  if(msg.code == 1){
				    alert(msg.msg);
				    num = 0;
				    location.href = '/dashboard/list-project';
				  }else{
				    alert(msg.msg);
				    num = 0;
				    location.href = '/dashboard/setup-project';
				  }
				}
	        });
    	}
        return false; // 阻止表单自动提交事件
    });

});

$('.btn_saveusers').on('click',function(){
	get_check();
});


//选择公司成员
function get_check(){
	username = '';
	userid = '';
	$('input[name="ary_user"]:checked').each(function(){
   		username = username + $(this).attr('username') + ' ';
   		userid = userid + $(this).val() + ',';
  	});
   	userid = userid.substr(0,userid.length-1);
  	$('input[name="project_member"]').val(username);
  	$('input[name="project_member_value"]').val(userid);
  	$('#company_user').modal('toggle');
}

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
}

//时间控件
$('.form_date').datetimepicker({
    language:  'zh',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
	startDate :new Date().Format("yyyy-MM-dd")
});


//上传文件-removed


})
</script>

@stop
