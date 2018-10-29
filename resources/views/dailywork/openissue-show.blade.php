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
<link href="/asset/css/fileUpload.css" rel="stylesheet"  media="screen">
<link href="/asset/css/iconfont_upload.css" rel="stylesheet"  media="screen">
@stop
 
@section('content')
<div class="col-xs-12">

<form id='form_project' method='POST'>
<input type="hidden" class="issue_id" value="{{$result['open_issue']['issue_id']}}">

<div class="ol-md-12">
	
	<?php if($result['open_issue']['code']=="Plan"){  ?> 
	<!--来源-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.openissue_resource')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['code']}}" readonly="readonly"/>
	</div>
	<!--项目编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_number')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['proj_code']}}" readonly="readonly"/>
	</div>
	<!--项目名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.project_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['proj_name']}}" readonly="readonly"/>
	</div>
	<!--项目经理-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.manager')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['proj_manager']}}" readonly="readonly"/>
	</div>

	<?php } ?>

	<?php if($result['open_issue']['code']=="Plan" || $result['open_issue']['code']=="Project"){  ?>
	<!--零件名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.part_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['part_name']}}" readonly="readonly"/>
	</div>
	<!--计划编号-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_number')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['plan_code']}}" readonly="readonly"/>
	</div>
	<!--计划名称-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_name')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['plan_name']}}" readonly="readonly"/>
	</div>
	<!--计划类型-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.plan_type')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['plan_type']}}" readonly="readonly"/>
	</div>
	<!--负责人-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.manager')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['proj_manager']}}" readonly="readonly"/>
	</div>
	
	<?php } ?>
	

	<!--零件列表-->
    <div class="col-md-12">
        <div class="panel panel-success">
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
						</tr>
					</thead>
					<tbody class="area_todolist">
					<?php $todonum=0; foreach ($result['detail'] as $key => $value) { ?>
						<tr class='todolist_list showactions linjian{{$value["id"]}}' num_rw='{{$value["id"]}}' fromdb='1' style='display:table-row; float: none;' role='row'>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['title']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issue_class']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['description']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['solution']}}</td>
						<td opvalue="{{$value['department']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['department_list_name']}}</td>
						<td opvalue="{{$value['leader']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['leader_list_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['plan_complete_date']}}</td>
						<td opvalue="{{$value['issuer']}}" class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issuer_list_name']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issue_date']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['comment']}}</td>
						
						</tr>
					<?php }  ?>
					</tbody>
				</table>
				<div class="partid_delete" style="display:block;"></div>
				</div>	
			</form>

            </div>
        </div>
    </div>

	
	<!--文档列表-->
    <div class="col-md-12"> 
		<div class="panel panel-primary">
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

	<?php if($result['open_issue']['is_approved'] == 0){  ?>
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
				<input type="button" class="btn btn-primary btn_save" intention='1' value="{{LANG::get('mowork.aggree')}}" />
				<input type="button" class="btn btn-primary btn_save" intention='2' value="{{LANG::get('mowork.reject')}}" />
			</div>
		</div>

	<?php  }   ?>


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
<script type="text/javascript" src="/asset/js/todolist_hm.js"></script>
<script type="text/javascript" src="/asset/js/upload/fileUpload.js"></script>

<script type="text/javascript">

$(document).ready(function(){ 
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }})

//发布
$('.btn_save').on('click', function(){
	intention = $(this).attr('intention');
	cont_approval = $('.cont_approval').val();
	issue_id = $('.issue_id').val();
    $.ajax({
        type: 'post',
        data: {intention:intention,cont_approval:cont_approval,issue_id:issue_id},
		url:'/dashboard/openissue-approvalaction',
		success:function(msg){
		  if(msg.code == 1){ 
		    alert(msg.msg);
		    location.href = '/dashboard/openissue-list';
		  }else{ 
		    alert(msg.msg);
		    location.href = '/dashboard/openissue-approval';
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
