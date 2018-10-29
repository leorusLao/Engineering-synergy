@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
	$(document).on("click","button[data-target='#delete_confirm']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","button[data-target='#company_user']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","button[data-target='#company_department']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","button[data-target='#company_tcr']",function(){setTimeout('$("body").css("padding-right","0px")',1);});
	$(document).on("click","a.tododelete",function(){setTimeout('$("body").css("padding-right","0px")',1);});
</script>

<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="/asset/css/todolist.css" rel="stylesheet" media="screen">
<link href="/asset/css/fileUpload.css" rel="stylesheet"  media="screen">
<link href="/asset/css/iconfont_upload.css" rel="stylesheet"  media="screen">
@stop
 
@section('content')
<div class="col-xs-12">

<form id='form_project' method='POST'>
<input type="hidden" class="issue_id" name="issue_id" value="{{$issueid}}">
<input type="hidden" class="source_id" name="source_id" value="{{$sourceid}}">
<input type="text" name="_token" value="{{csrf_token()}}" style="visibility:hidden;" />

<div class="ol-md-12">
	
	<?php if($result['issuesource']['code']=="Project"){ ?> 
	<!--来源-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.openissue_resource')}}
		</div>
		<input class="form-control" type="text" value="{{$result['issuesource']['code']}}" readonly="readonly"/>
	</div>
	<!--项目编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_number')}}
		</div>
		<input class="form-control" type="text" value="{{$result['project']['proj_code']}}" readonly="readonly"/>
	</div>
	<!--项目名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['project']['proj_name']}}" readonly="readonly"/>
	</div>
	<!--项目经理-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.manager')}}
		</div>
		<input class="form-control" type="text" value="{{$result['project']['proj_manager']}}" readonly="readonly"/>
	</div>

	<?php } ?>

	<?php if($result['issuesource']['code']=="Plan"){  ?>
	<!--零件名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.part_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['plan']['part_name']}}" readonly="readonly"/>
	</div>
	<!--计划编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_number')}}
		</div>
		<input class="form-control" type="text" value="{{$result['plan']['plan_code']}}" readonly="readonly"/>
	</div>
	<!--计划名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['plan']['plan_name']}}" readonly="readonly"/>
	</div>
	<!--计划类型-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_type')}}
		</div>
		<input class="form-control" type="text" value="{{$result['plan']['plan_type']}}" readonly="readonly"/>
	</div>
	
	<?php } ?>
	

	<!--零件列表-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{Lang::get('mowork.openissue_information')}}</h3>
                <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
            </div>
            <div class="panel-body">
			
			<!--零件信息-->
			<form class="row list_of_items" id='form_linjian'>
				<div class="col-xs-12 col-sm-12">
				<div class="table-responsive table-scrollable">
				<table class="table table-striped table-bordered" id="inline_edit" style="margin-bottom: 0px;">
					<thead>
						<tr>
						<th>{{Lang::get('mowork.title')}}</th>
						<th>{{Lang::get('mowork.category')}}</th>
						<th>{{Lang::get('mowork.description')}}</th>
						<th>{{Lang::get('mowork.solution')}}</th>
						<th>{{Lang::get('mowork.responsible_department')}}</th>
						<th>{{Lang::get('mowork.responsible_peoper')}}</th>
						<th>{{Lang::get('mowork.planned_completion_time')}}</th>
						<th>{{Lang::get('mowork.put_forward_people')}}</th>
						<th>{{Lang::get('mowork.put_forward_time')}}</th>
						<th>{{Lang::get('mowork.comment')}}</th>
						<th>{{Lang::get('mowork.measurement_operation')}}</th>
						</tr>
					</thead>
					<tbody class="area_todolist">
					<?php $todonum=0; foreach ($result['detail'] as $key => $value) { ?>
						<tr class='todolist_list showactions linjian{{$value["id"]}}' num_rw='{{$value["id"]}}' fromdb='1' style='display:table-row; float: none;' role='row'>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['title']}}</td>
						<td opvalue="{{$value['issue_class']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['class_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['description']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['solution']}}</td>
						<td opvalue="{{$value['department']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['department_list_name']}}</td>
						<td opvalue="{{$value['leader']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['leader_list_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['plan_complete_date']}}</td>
						<td opvalue="{{$value['issuer']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issuer_list_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issue_date']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['comment']}}</td>
						<td>
						<div class="pull-right todoitembtns" style="float:left!important; padding-top:0px;">
						<a href="#" class="todoedit"><span class="glyphicon glyphicon-pencil linjian-pencil"></span></a>
						<span class="striks"> | </span><a class="tododelete redcolor" onclick="delete_linjian({{$value['id']}})">
						<span class="glyphicon glyphicon-trash"></span></a></div>
						</td>
						<div style="display:none;" class='todotext_<?php echo $key;?>_<?php $todonum++; echo $todonum; $todonum=0;?>'>{{$value['id']}}</div>
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				</div>
				<div class="partid_delete" style="display:none;"></div>
				</div>	
			</form>


			<!-- 添加零件信息 -->
	        <div class="todolist_list adds" style="border-bottom:0px;">
	            <form role="form" id="main_input_box" class="form-inline">
	                <!--标题-->
	                <div class="form-group col-md-3">
	                    <input class="form-control cust_text1 title_op edit_text1" placeholder="* {{Lang::get('mowork.title')}}"  name="title_op" required='required' type="text">
	                </div>

					<!--分类-->
	                <div class="form-group col-md-3">
	                	<select class="form-control select2 category" style="width:100%;">
	                	<?php foreach ($result['issueclass'] as $key => $value): ?>
	                		<option value="{{$value['id']}}">{{$value['name']}}</option>
	                	<?php endforeach ?>
	                	</select>
	                </div>

	                <!--责任部门-->
	                <div class="form-group col-md-3">
						<input class="form-control responsible_department edit_text5" name="project_department" type="text" value="" style="width:75%;"  placeholder="{{Lang::get('mowork.responsible_department')}}"  id="responsible_department"  onfocus=this.blur() />
						<input class="form-control project_department_value edit_text_value5" name="project_department_value" type="text" value="" style="display:none;" id="responsible_peoper_value" />
					    &nbsp;&nbsp;<button type="button" class="btn btn-raised btn-default btn-large" data-toggle="modal" data-target="#company_department">{{Lang::get('mowork.select')}}</button>
					</div>

	                <!--责任人-->
	                <div class="form-group col-md-3">	               
						<input class="form-control responsible_peoper edit_text6" name="project_member" type="text" value="" style="width:75%;"  placeholder="{{Lang::get('mowork.responsible_peoper')}}"  id="responsible_peoper"  onfocus=this.blur() />
						<input class="form-control project_member_value edit_text_value6" name="project_member_value" type="text" value="" style="display:none;" id="responsible_peoper_value" />
					    &nbsp;&nbsp;<button type="button" class="btn btn-raised btn-default btn-large" data-toggle="modal" data-target="#company_user">{{Lang::get('mowork.select')}}</button>
	                </div>

	                <!--提出人-->
	                <div class="form-group col-md-3">	               
						<input class="form-control put_forward_people edit_text8" name="project_tcr" type="text" value="" style="width:75%;"  placeholder="{{Lang::get('mowork.put_forward_people')}}"  id="put_forward_people"  onfocus=this.blur() />
						<input class="form-control project_tcr_value edit_text_value8" type="text" name="project_tcr_value" style="display:none;" id="responsible_peoper_value" />
					    &nbsp;&nbsp;<button type="button" class="btn btn-raised btn-default btn-large" data-toggle="modal" data-target="#company_tcr">{{Lang::get('mowork.select')}}</button>
	                </div>

	                <!--计划完成时间-->
				    <div class="input-group date form_date form-group col-md-3 input_op" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="date_acceptance" data-link-format="yyyy-mm-dd hh:ii">
					    <input class="form-control cust_text1 planned_completion_time edit_text7"  name="start_date" size="16" type="text" placeholder="{{Lang::get('mowork.planned_completion_time')}}"  value=""  onfocus=this.blur() />
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>

	                <!--提出时间-->
				    <div class="input-group date form_date form-group col-md-3 input_op" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="date_acceptance" data-link-format="yyyy-mm-dd hh:ii">
					    <input class="form-control cust_text1 put_forward_time edit_text9"  name="start_date" size="16" type="text" placeholder="{{Lang::get('mowork.put_forward_time')}}"  value=""  onfocus=this.blur() />
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>

	                <!--描述-->
	                <div class="form-group col-md-12">
	                    <textarea class="form-control cust_text1 description edit_text3"  placeholder="{{Lang::get('mowork.description')}}"  name="description"></textarea> 
	                </div>

	                <!--解决方案-->
	                <div class="form-group col-md-12">
	                    <textarea class="form-control cust_text1 solution edit_text4"  placeholder="{{Lang::get('mowork.solution')}}"  name="solution"></textarea> 
	                </div>

	                <!--备注-->
	                <div class="form-group col-md-12">
	                    <textarea class="form-control cust_text1 comment edit_text10"  placeholder="{{Lang::get('mowork.comment')}}"  name="comment" ></textarea> 
	                </div>

	                <input type="hidden" value='' class="num_rw_ing">
	                
	                <div class="col-md-12">
	                <input type="submit" style="display:block;" class="btn btn-default hidden-xs add_button btn_addissue" value="{{Lang::get('mowork.add')}}" class="btn btn-primary add_button">
					<input type="submit" style="display:none;" class="btn btn-default hidden-xs add_button btn_editissue" value="{{Lang::get('mowork.text_edit')}}" class="btn btn-primary add_button">
					<input type="button" style="display:none;" class="btn btn-default hidden-xs add_button btn_exitedit" value="{{Lang::get('mowork.exit_edit')}}" class="btn btn-primary add_button">
					<input type="button" class="btn btn-default hidden-xs add_button btn_qingkong" value="{{LANG::get('mowork.reset')}}">
	                </div>
	            </form>
	        </div>

            </div>
        </div>
    </div>

	
	<!--文档列表-->
    <div class="col-md-12"> 
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h3 class="panel-title">{{Lang::get('mowork.file_information')}}</h3>
		        <span class="pull-right clickable">
		            <i class="glyphicon glyphicon-chevron-up"></i>
		        </span>
		    </div>
		    <div class="panel-body">

			<div id="fileUploadContent" class="fileUploadContent"></div>
			</div>
			<br/>
		</div>
    </div>



	<!-- 保存全页面 -->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="form-group text-center fl">
			<input type="submit" class="btn btn-default btn_save" value="{{LANG::get('mowork.submit')}}" />
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
                                <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <li class="list-group-item list_compuser">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ary_user" username="<?php echo $value['fullname']?>" class="custom-checkbox marginleft-15" value="<?php echo $value['uid']?>"><?php echo $value['fullname']?>
                                            </label>
                                        </div>
                                    </li>
                                <?php }  ?>
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

<!--- 公司部门 -->
<div class="extended_modals">
    <div class="modal fade in" id="company_department" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{Lang::get('mowork.select_department')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <ul class="list-group">
                                <?php foreach ($result['department'] as $key => $value) { ?>
                                    <li class="list-group-item list_compuser">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ary_department" username="<?php echo $value['name']?>" class="custom-checkbox marginleft-15" value="<?php echo $value['dep_id']?>"><?php echo $value['name']?>
                                            </label>
                                        </div>
                                    </li>
                                <?php }  ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" data-dismiss="modal">{{LANG::get('mowork.cancel')}}</button>
                    <button type="button" class="btn btn-primary btn_savedepartment">{{LANG::get('mowork.save')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!--- 提出人 -->
<div class="extended_modals">
    <div class="modal fade in" id="company_tcr" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{Lang::get('mowork.select_forward_people')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body">
                                <ul class="list-group">
                                <?php foreach ($result['company_user'] as $key => $value) { ?>
                                    <li class="list-group-item list_compuser">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="ary_tcr" username="<?php echo $value['fullname']?>" class="custom-checkbox marginleft-15" value="<?php echo $value['uid']?>"><?php echo $value['fullname']?>
                                            </label>
                                        </div>
                                    </li>
                                <?php }  ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-default" data-dismiss="modal">{{LANG::get('mowork.cancel')}}</button>
                    <button type="button" class="btn btn-primary btn_savetcr">{{LANG::get('mowork.save')}}</button>
                </div>
            </div>
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
<script type="text/javascript" src="/asset/js/upload/fileUpload.js"></script>

<script type="text/javascript">

$(document).ready(function(){

	//新增零件
	var cont_text1 = '';
	$(".btn_addissue").click(function(event){
		$('#main_input_box').submit(function(){
			if(cont_text1 != $('.edit_text1').val()){
				//alert($('.edit_text1').val());
				timestamp = Date.parse(new Date());
				var deleteButton = "<a href='' class='tododelete redcolor'><span class='glyphicon glyphicon-trash'></span></a>";
				var striks ="<span class='striks'> |  </span>";
				var checkBox = "";
				var cont_hm = "<tr class='todolist_list showactions linjian"+ timestamp +"' num_rw='"+ timestamp +"' fromdb='0' style='display:table-row; float: none;' role='row'>" + 
							    "<td class='todotext_1'>"+ $('.title_op').val() +"</td>" + 
							    "<td class='todotext_2' opvalue='"+ $('.category').find('option:selected').val() +"'>"+ $('.category').find('option:selected').text() +"</td>" + 	
							    "<td class='todotext_3'>"+ $('.description').val() +"</td>" + 
							    "<td class='todotext_4'>"+ $('.solution').val() +"</td>" + 
							    "<td class='todotext_5' opvalue='"+ $('.project_department_value').val() +"'>"+ $('.responsible_department').val() +"</td>" + 
							    "<td class='todotext_6' opvalue='"+ $('.project_member_value').val() +"'>"+ $('.responsible_peoper').val() +"</td>" + 
							    "<td class='todotext_7'>"+ $('.planned_completion_time').val() +"</td>" + 	
							    "<td class='todotext_8' opvalue='"+ $('.project_tcr_value').val() +"'>"+ $('.put_forward_people').val() +"</td>" + 
							    "<td class='todotext_9'>"+ $('.put_forward_time').val() +"</td>" + 
							    "<td class='todotext_10'>"+ $('.comment').val() +"</td>" + 
								"<td>" + 
				                "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" + 
				                "<a href='#' class='todoedit'>" + 
				                "<span class='glyphicon glyphicon-pencil linjian-pencil'></span>" + 
				                "</a>" + 
				                "<span class='striks'> | </span>" + 
				                "<a class='tododelete redcolor' onclick='delete_linjian("+timestamp+")'>" + 
				                "<span class='glyphicon glyphicon-trash'></span>" + 
				                "</a>" + 
				                "</div>" + 
								"</td></tr>";

				$(".area_todolist").append(cont_hm);
				$("#custom_textbox").val('');
				$("#task_deadline").val('');
				cont_text1 = $('.edit_text1').val();
			}
			return false; 
		})
	});
	

});


//编辑零件
$(document).on('click', '.todoedit .linjian-pencil', function (e) {
	e.preventDefault();
	var num_rw = $(this).closest('.todolist_list').attr('num_rw');
	$('.todolist_list').css('background','#fff').css('color','#000');
	$(this).closest('.todolist_list').css('background','#418BCA').css('color','#ffffff');
	$('.num_rw_ing').val(num_rw);
	for (var i = 1; i < 11; i++) {
		text = $(this).closest('.todolist_list').find('.todotext_'+i).text();
		attrvalue = $(this).closest('.todolist_list').find('.todotext_'+i).attr('opvalue')
		if(i==5){ 
			$('.edit_text'+i).val(text);
			$('.edit_text_value'+i).val(attrvalue);
			$('input[name="ary_department"]').each(function(k){ 
				var ary_value = attrvalue.split(',');
				for (var l = 0; l < ary_value.length; l++) {
					if (ary_value[l] == $(this).attr('value')) {
						$(this).attr('checked',true);
					}
				};
			})
		}
		if(i==6){ 
			$('.edit_text'+i).val(text);
			$('.edit_text_value'+i).val(attrvalue);
			$('input[name="ary_user"]').each(function(k){ 
				var ary_value = attrvalue.split(',');
				for (var l = 0; l < ary_value.length; l++) {
					if (ary_value[l] == $(this).attr('value')) {
						$(this).attr('checked',true);
					}
				};
			})
		}
		if(i==8){ 
			$('.edit_text'+i).val(text);
			$('.edit_text_value'+i).val(attrvalue);
			$('input[name="ary_tcr"]').each(function(k){
				var ary_value = attrvalue.split(',');
				for (var l = 0; l < ary_value.length; l++) {
					if (ary_value[l] == $(this).attr('value')) {
						$(this).attr('checked',true);
					}
				};
			})
		}

		if(i==1 || i==7 || i==9){ 
			$('.edit_text'+i).val(text);
		}else if(i==2){ 
			for(var j = 1; j < 10; j++){ 
				var cont_option = $('.select2').find('option:eq('+ j +')').text();
				if(cont_option == text){ 
					$('.select2').find('option:eq('+ j +')').attr('selected','selected');
				}
			}
		}
		$('.edit_text'+i).html(text);
	}	
	//时间控件
	$('.form_date').datetimepicker({
		language:  'zh',
	    weekStart: 1,
	    todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
	    showMeridian: 1,
		startDate :new Date().Format("yyyy-MM-dd")
	});
	$('.btn_editissue').css('display','block');
	$('.btn_exitedit').css('display','block');
	$('.btn_addissue').css('display','none');
});

//修改零件保存
$('.btn_editissue').click(function(event){ 
	$('#main_input_box').submit(function(){
		var num_rw_ing = $('.num_rw_ing').val();
		for (var i=1; i<11; i++) {
			j = i - 1;
			var cont_text = $('.edit_text'+i).val();
			if(i==2){ 
				var cont_text = $('.select2').find('option:selected').text();
			}
			if(i==5 || i==6 || i==8){ 
				var cont_value = $('.edit_text_value'+i).val();
				$('.linjian'+ num_rw_ing +' td').eq(j).attr('opvalue',cont_value);
			}
			$('.linjian'+ num_rw_ing +' td').eq(j).text(cont_text);
			$('.edit_text'+i).val('');
		};	
		$('.btn_editissue').css('display','none');
		$('.btn_exitedit').css('display','none');
		$('.btn_addissue').css('display','block');	
		var num_rw_ing = $('.num_rw_ing').val();
		$('.linjian'+ num_rw_ing).css('background','#fff').css('color','#000');
		$('.edit_text_value5').val('');
		$('.edit_text_value6').val('');
		$('.edit_text_value8').val('');
		$('input[name="ary_department"]').attr('checked',false);
		$('input[name="ary_user"]').attr('checked',false);
		$('input[name="ary_tcr"]').attr('checked',false);
		$('.num_rw_ing').val(0);
		return false;	
	})
})

//退出编辑
$('.btn_exitedit').click(function(){
	for (var i = 1; i < 11; i++) {
		$('.edit_text'+i).val('');
	}
	$('.btn_editissue').css('display','none');
	$('.btn_exitedit').css('display','none');
	$('.btn_addissue').css('display','block');
	var num_rw_ing = $('.num_rw_ing').val();
	$('.linjian'+ num_rw_ing).css('background','#fff').css('color','#000');
	$('.edit_text_value5').val('');
	$('.edit_text_value6').val('');
	$('.edit_text_value8').val('');
	$('input[name="ary_department"]').attr('checked',false);
	$('input[name="ary_user"]').attr('checked',false);
	$('input[name="ary_tcr"]').attr('checked',false);

	$('.num_rw_ing').val(0);	
})

//清空
$('.btn_qingkong').click(function(){ 
	for (var i = 1; i < 11; i++) {
		$('.edit_text'+i).val('');
	}
})


//modal弹出
var MyModal_1 = (function() {
	function modal(fn) {
		this.fn = fn; //点击确定后的回调函数
		this._addClickListen();
	}
	modal.prototype = {
		show: function(id) {
			$('#delete_confirm').modal('show');
			this.id = id;
		},
		_addClickListen: function() {
			var that = this;
			$("#delete_confirm").find('*').on("click", function(event) {
				event.stopPropagation(); //阻止事件冒泡
			});
			$("#delete_confirm,.btn_cancel").on("click", function(event) {
				that.hide();
			});
			$(".btn_delete").on("click", function(event) {
				that.fn(that.id);
				that.hide();
			});
		},
		hide: function() {
			$('#delete_confirm').modal('hide');
		}

	};
	return {
		modal: modal
	}
})();


function delete_linjian(id){
	var m1 = new MyModal_1.modal(//确定按钮执行
		function func_delete(id){
		    $('.linjian'+id).remove();
		    $('.plan'+id).remove();
		    $('.partid_delete').text($('.partid_delete').text() + id + ',');
		});
	m1.show(id);
}



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

    	//OPENISSUE信息
		$('.area_todolist tr').each(function(i){
			$('.area_todolist tr:eq(' + i + ') td').each(function(j){
				if(j==1 || j==4 || j==5 || j==7){
					key_id = 'linjian_' + i + '_' + j;
					str_lj = str_lj + key_id + '=' + $(this).attr('opvalue') + '&';
				}else{ 
					key = 'linjian_' + i + '_' + j;
					cont = $(this).text();
					str_lj = str_lj + key + '=' + cont + '&';
				}
				if(j==10){
					key_id = 'linjian_' + i + '_11';
					str_lj = str_lj + key_id + '=' + $('.todotext_'+i+'_11').text()+'&';
				}
			});
			num_lj++;
		});
		str_lj = str_lj + '&num_lj=' + num_lj;
		//alert(str_lj);

		//被删除的零件和计划
		str_delete = '&part_delete=' + $('.partid_delete').text();

		//已上传的图片
		$('.fileItem').each(function(i){
			if($('.fileItem:eq('+ i +')').attr('systemfile') == 1){ 
				key = 'pic_' + i;
				cont = $(this).attr('filecodeid');
				str_pic = str_pic + key + '=' + cont + '&';
				num_pic++;
			}
		})
		str_pic = str_pic + '&num_pic=' + num_pic;
		op_type = '&op_type=' + "<?php echo $result['issuesource']['code']; ?>";

		num ++;
		var project = $('#form_project').serialize();
		if(num_lj==0){ 
			alert("{{Lang::get('mowork.openissue_isnull')}}");
			return false;
		}
		var data = project + str_lj + str_pic + str_delete + op_type;
		if(num == 1){
	        $.ajax({
	            type: 'post',
	            data: data,
				url:'/dashboard/openissue-update',
				success:function(msg){
				  if(msg.code == 1){ 
				    alert(msg.msg);
				    num = 0;
				    location.href = '/dashboard/openissue-list';
				  }else{ 
				    alert(msg.msg);
				    num = 0;
				    location.href = '/dashboard/openissue-list';
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


$('.btn_savedepartment').on('click',function(){
	get_department();
});

//选择公司部门
function get_department(){
	username = '';
	userid = '';
	$('input[name="ary_department"]:checked').each(function(){ 
   		username = username + $(this).attr('username') + ' '; 
   		userid = userid + $(this).val() + ',';
  	}); 
   	userid = userid.substr(0,userid.length-1);
  	$('input[name="project_department"]').val(username);
  	$('input[name="project_department_value"]').val(userid);
  	$('#company_department').modal('toggle');
}


$('.btn_savetcr').on('click',function(){
	get_tcr();
});

//选择提出人
function get_tcr(){
	username = '';
	userid = '';
	$('input[name="ary_tcr"]:checked').each(function(){ 
   		username = username + $(this).attr('username') + ' '; 
   		userid = userid + $(this).val() + ',';
  	}); 
   	userid = userid.substr(0,userid.length-1);
  	$('input[name="project_tcr"]').val(username);
  	$('input[name="project_tcr_value"]').val(userid);
  	$('#company_tcr').modal('toggle');
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
	forceParse: 0,
    showMeridian: 1,
	startDate :new Date().Format("yyyy-MM-dd")
});


//上传文件
$("#fileUploadContent").initUpload({
    "uploadUrl":"http://localhost/dashboard/save-files",//上传文件信息地址
    "deleteUrl":"http://localhost/dashboard/delete-files",//删除文件信息地址 
    "progressUrl":"#",//获取进度信息地址，可选，注意需要返回的data格式如下（{bytesRead: 102516060, contentLength: 102516060, items: 1, percent: 100, startTime: 1489223136317, useTime: 2767}）
    "selfUploadBtId":"selfUploadBt",//自定义文件上传按钮id
    "isHiddenUploadBt":false,//是否隐藏上传按钮
    "isHiddenCleanBt":true,//是否隐藏清除按钮
    "isAutoClean":false,//是否上传完成后自动清除
    "velocity":10,//模拟进度上传数据
    "showFileItemProgress":false,
    'id':123456,
    //"showSummerProgress":false,//总进度条，默认限制
    //"scheduleStandard":true,//模拟进度的方式，设置为true是按总进度，用于控制上传时间，如果设置为false,按照文件数据的总量,默认为false
    //"size":350,//文件大小限制，单位kb,默认不限制
    //"maxFileNumber":3,//文件个数限制，为整数
    //"filelSavePath":"",//文件上传地址，后台设置的根目录
    //"beforeUpload":beforeUploadFun,//在上传前执行的函数
    //"onUpload":onUploadFun，//在上传后执行的函数
    // autoCommit:true,//文件是否自动上传
    "fileType":['png','jpg','gif','docx','doc','txt']//文件类型限制，默认不限制，注意写的是文件后缀

});


function beforeUploadFun(opt){
    opt.otherData =[{"name":"你要上传的参数","value":"你要上传的值"}];
}
function onUploadFun(opt,data){
    uploadTools.uploadError(opt);//显示上传错误
}
function testUpload(){
    var opt = uploadTools.getOpt("fileUploadContent_11111");
    uploadEvent.uploadFileEvent(opt);
}
function tt() {
    var opt = uploadTools.getOpt("fileUploadContent_22222");
    uploadTools.uploadError(opt);//显示上传错误
}



//初始化文件
<?php 
if(!empty($result['open_issue']['attached_file'])) {
	foreach ($result['open_issue']['new_pic'] as $key => $value) { ?>

var files = uploadTools.getShowFileType(
	'<?php echo $value["bool"]; ?>',
	'<?php echo $value["suffix"]; ?>',
	'<?php echo $value["name"];?>',
	'<?php echo $result["open_issue"]["public_path"].$value["name"];?>',
	'<?php echo $value["name"];?>',
	1
	);
$('.box').append(files);

<?php  } }  ?>


uploadFileList.initFileList(qjbl);
uploadTools.startMyFile(qjbl);

})
</script>

@stop
