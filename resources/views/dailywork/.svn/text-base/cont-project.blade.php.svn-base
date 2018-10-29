@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link  href="/asset/css/todolist.css" rel="stylesheet" media="screen">
<link href="/asset/css/fileUpload.css" rel="stylesheet"  media="screen">
<link href="/asset/css/iconfont_upload.css" rel="stylesheet"  media="screen">
<style>
.uploadBts,.span_shanch,.iconfont,.progress { display:none;}
.fileUploadContent .fileItem { height: 200px;}
.fileUploadContent .box { border:0px;}
</style>
@stop
 
@section('content')
<div class="col-md-12">

<form id='form_project' method='POST'>
<input type="hidden" class="projectid" value="{{$result_project['proj_id']}}">

<div class="ol-md-12">

	<!--项目编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_number')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['proj_code']}}" readonly="readonly"/>
	</div>
	<!--项目名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['proj_name']}}" readonly="readonly"/>
	</div>
	<!--项目类别-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_category')}}{{$result_project['proj_type']}}
		</div>
		<input class="form-control" type="text" value="<?php if($result_project['proj_type']==1){echo 'Auto';}else if($result_project['proj_type']==2){echo '3C';}else if($result_project['proj_type']==3){echo 'Media';} ?>" readonly="readonly"/>
	</div>
	<!--客户名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.customer_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['customer_name']}}" readonly="readonly"/>
	</div>
	<!--项目经理-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_manager')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['proj_manager']}}" readonly="readonly"/>
	</div>
	<!--项目成员-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_member')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['member_list_name']}}" readonly="readonly"/>
	</div>
	<!--项目日历-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.project_calendar')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['cal_name']}}" readonly="readonly"/>
	</div>
	<!--项目性质-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_nature')}}
		</div>
		<input class="form-control" type="text" value="<?php if($result_project['property']==0){ echo Lang::get('mowork.private_plan');}else { echo Lang::get('mowork.public_plan');} ?>" readonly="readonly"/>
	</div>
	<!--接受日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.date_acceptance')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['start_date']}}" readonly="readonly"/>
	</div>
	<!--结束日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.date_end')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['end_date']}}" readonly="readonly"/>
	</div>
	<!--工艺验证日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_validation')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['process_trail']}}" readonly="readonly"/>
	</div>
	<!--出模样件日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_sample')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['mold_sample']}}" readonly="readonly"/>
	</div>
	<!--试产验证日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_verification')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['trail_production']}}" readonly="readonly"/>
	</div>
	<!--批量放产日期-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.date_delivery')}}
		</div>
		<input class="form-control" type="text" value="{{$result_project['batch_production']}}" readonly="readonly"/>
	</div>
	<!--项目描述-->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.project_desction')}}
		</div>
		<textarea class="form-control" readonly="readonly">{{$result_project['description']}}</textarea>
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
						</tr>
					</thead>
					<tbody class="area_todolist">
					<?php foreach ($result_detail as $key => $value) { ?>
						<tr>
						<td>{{$value['part_code']}}</td>
						<td>{{$value['part_name']}}</td>
						<td>{{$value['part_type']}}</td>
						<td>{{$value['quantity']}}</td>
						<td>{{$value['note']}}</td>
						<td>{{$value['jig']}}</td>
						<td>{{$value['gauge']}}</td>
						<td>{{$value['mold']}}</td>
						<td>{{$value['processing']}}</td>
						<td>{{$value['part_from']}}</td>
						<td>{{$value['material']}}</td>
						<td>{{$value['mat_size']}}</td>
						<td>{{$value['shrink']}}</td>
						<td>{{$value['surface']}}</td>
						<td>{{$value['part_size']}}</td>
						<td>{{$value['weight']}}</td>
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				</div>	
			</form>



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
						</tr>
					</thead>
					<tbody class="area_todolist_plan">					
					<?php foreach ($result_plan as $key => $value) { ?>
						<tr>
						<td>{{$value['part_code']}}</td>
						<td>{{$value['plan_code']}}</td>
						<td>{{$value['plan_name']}}</td>
						<td>{{$value['plan_type']}}</td>
						<td>{{$value['description']}}</td>
						<td>{{$value['start_date']}}</td>
						<td>{{$value['end_date']}}</td>
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				</div>	
			</form>

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


<?php if($result_project['approval_status'] == 0){  ?>
	<!--审批意见-->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="input-group-addon">
		{{Lang::get('mowork.approval_comment')}}
		</div>
		<textarea class="form-control cont_approval"></textarea>
	</div>

	<!-- 保存全页面 -->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="form-group text-center fl">
			<input type="button" class="btn btn-default btn_save" intention='1' value="{{LANG::get('mowork.agree')}}" />
			<input type="button" class="btn btn-default btn_save" intention='2' value="{{LANG::get('mowork.reject')}}" />
		</div>
	</div>

<?php  }   ?>


</div>


	</form>
</div>
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/jquery.dataTables_hm.js"></script>
<script type="text/javascript" src="/asset/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="/asset/js/dataTables.colReorder_hm.js"></script>
<script type="text/javascript" src="/asset/js/todolist_hm.js"></script>
<script type="text/javascript" src="/asset/js/upload/fileUpload.js"></script>

<script type="text/javascript">

$(document).ready(function(){ 
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }})

//发布
$('.btn_save').on('click', function(){
	intention = $(this).attr('intention');
	cont_approval = $('.cont_approval').val();
	projectid = $('.projectid').val();
    $.ajax({
        type: 'post',
        data: {intention:intention,cont_approval:cont_approval,projectid:projectid},
		url:'/dashboard/approval-project',
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
    return false; // 阻止表单自动提交事件
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
    "fileType":['png','jpg','gif','docx','doc','txt']//文件类型限制，默认不限制，注意写的是文件后缀

});


//初始化文件
<?php if(!empty($result_project['new_pic'])) {foreach ($result_project['new_pic'] as $key => $value) { ?>

var files = uploadTools.getShowFileType(
	'<?php echo $value["bool"]; ?>',
	'<?php echo $value["suffix"]; ?>',
	'<?php echo $value["name"];?>',
	'<?php echo $result_project["public_path"].$value["name"];?>',
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
