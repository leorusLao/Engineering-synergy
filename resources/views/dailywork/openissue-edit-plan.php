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
<div class="col-xs-12 col-sm-10 col-sm-offset-1">

<form id='form_project' method='POST'>
<input type="hidden" class="issue_id" value="{{$result['open_issue']['issue_id']}}">

<div class="ol-md-12">
	
	<?php  if($result['open_issue']['issue_source']="plan"){  ?> 
	<!--来源-->
	<div class="form-group input-group col-sm-6 col-fl-add">
		<div class="input-group-addon">
		* {{Lang::get('mowork.openissue_resource')}}
		</div>
		<input class="form-control" type="text" value="{{$result['open_issue']['issue_source']}}" readonly="readonly"/>
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

	<?php if($result['open_issue']['issue_source']=="plan" || $result['issue_source']['open_issue']=="detail"){  ?>
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
						<th>{{Lang::get('mowork.measurement_operation')}}</th>
						</tr>
					</thead>
					<tbody class="area_todolist">
					<?php $todonum=0; foreach ($result['detail'] as $key => $value) { ?>
						<tr class='todolist_list showactions linjian{{$value["id"]}}' style='display:table-row; float: none;' role='row'>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['title']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issue_class']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['description']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['solution']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['department']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['leader']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['plan_complete_date']}}</td>
						<td class='todotext_<?php $todonum++; echo $todonum;?>'>{{$value['issuer']}}</td>
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
	                    <input class="form-control cust_text1 tool_check"  placeholder="{{Lang::get('mowork.tool_check')}}"  name="tool_check" type="text">
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
	                
	                <div class="col-md-12">
	                <input type="submit" value="{{Lang::get('mowork.add')}}" class="btn btn-primary add_button">&nbsp;&nbsp;
					<input type="reset" class="btn btn-default hidden-xs add_button" value="{{LANG::get('mowork.reset')}}">
	                </div>
	            </form>
	        </div>

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



	<!-- 保存全页面 -->
	<div class="form-group input-group col-sm-12 col-fl-add">
		<div class="form-group text-center fl">
			<input type="submit" class="btn btn-primary btn_save" value="{{LANG::get('mowork.submit')}}" />
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
				key = 'linjian_' + i + '_' + j;
				cont = $(this).text();
				str_lj = str_lj + key + '=' + cont + '&';
				if(j==16){
					key_id = 'linjian_' + i + '_17';
					str_lj = str_lj + key_id + '=' + $('.todotext_'+i+'_17').text() + '&';
				}
			});
			num_lj++;
		});
		str_lj = str_lj + '&num_lj=' + num_lj;

		//计划
		$('.area_todolist_plan tr').each(function(i){	
			$('.area_todolist_plan tr:eq(' + i + ') td').each(function(j){
				key = 'plan_' + i + '_' + j;
				cont = $(this).text();
				str_plan = str_plan + key + '=' + cont + '&';
				if(j==7){
					key_id = 'plan_' + i + '_8';
					str_plan = str_plan + key_id + '=' + $('.todotext_'+i+'_plan_8').text() + '&';
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
				str_pic = str_pic + key + '=' + cont + '&';
				num_pic++;
			}
		})
		str_pic = str_pic + '&num_pic=' + num_pic;

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
<?php if(!empty($result['result_project']['new_pic'])) {foreach ($result['result_project']['new_pic'] as $key => $value) { ?>

var files = uploadTools.getShowFileType(
	'<?php echo $value["bool"]; ?>',
	'<?php echo $value["suffix"]; ?>',
	'<?php echo $value["name"];?>',
	'<?php echo $result["result_project"]["public_path"].$value["name"];?>',
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
