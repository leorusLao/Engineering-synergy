@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
	$(document).on("click","button.am-text-danger",function(){setTimeout('$("body").css("padding-right","0px")',0);});
</script>
@stop
 
@section('content')
<div class="col-xs-12">

<div class="col-xs-12 col-sm-12">


<div class="container-fluid">
	<div class="row-fluid">
		<div class="table-responsive table-scrollable">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th nowrap='nowrap'>{{Lang::get('mowork.openissue_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.openissue_resource')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.plan_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.category')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.title')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.solution')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_department')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.responsible_peoper')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.approval_status')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.progress')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_people')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.put_forward_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.planned_completion_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.actual_completion_time')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.measurement_operation')}}</th>
					</tr>
				</thead>
				<tbody>

				<?php 
					foreach ($result as $key => $value) {
				?>
					<tr>
						 
						<td style="display:none;">{{$value['detail_id']}}</td>
						<td><a href="/dashboard/openissue-edit/{{hash('sha256',$salt.$value['source_id'].$value['issue_id'])}}/{{$value['source_id']}}/{{$value['issue_id']}}">{{$value['detail_id']}}</a></td>						
						<td>{{$value['name']}}</td>
						<td>{{$value['proj_code']}}</td>
						<td>{{$value['proj_name']}}</td>
						<td>{{$value['plan_code']}}</td>
						<td>{{$value['plan_name']}}</td>
						<td>{{$value['class_name']}}</td>
						<td>{{$value['title']}}</td>
						<td>{{$value['solution']}}</td>
						<td>{{$value['dep_name']}}</td>
						<td>{{$value['str_leader']}}</td>
						<td><?php if($value['is_approved']==0){ echo '未审批';}else if($value['is_approved']==1){echo '通过';} else if($value['is_approved']==2){echo '未通过';} ?></td>
						<td><?php if($value['is_completed']==0){ echo '开放';}else if($value['is_completed']==1){ echo '关闭';} ?></td>
						<td>{{$value['str_issuer']}}</td>
						<td>{{$value['issue_date']}}</td>
						<td>{{substr($value['plan_complete_date'],0,10)}}</td>
						<td>{{$value['real_complete_date']}}</td>
						<td><nobr>
						<a href="/dashboard/openissue-edit/{{hash('sha256',$salt.$value['source_id'].$value['issue_id'])}}/{{$value['source_id']}}/{{$value['issue_id']}}" class="am-btn am-text-secondary"><i class="livicon" data-name="edit" data-size="12" data-c="#3bb4f2" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.text_edit')}}</a>
						<a href="/dashboard/openissue-progress/{{hash('sha256',$salt.$value['source_id'].$value['issue_id'])}}/{{$value['source_id']}}/{{$value['issue_id']}}" class="am-btn am-text-secondary"><i class="livicon" data-name="edit" data-size="12" data-c="#3bb4f2" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.progress')}}</a>
						</nobr>
						</td>
					</tr>
				<?php }  ?>

				</tbody>
			</table>
		</div>
			<div class='text-center'><?php echo $result->links(); ?></div>
	</div>
</div>

</div>


</div>
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/jquery.doubleScroll.js"></script>
<script>	

$(document).ready(function(){ 

	//$('.table-responsive').doubleScroll();
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }})
	
	str_id = '';
	$('.check_all').bind('click',cheak_all);
	$('.btn_delete_all').bind('click',btn_dele_all);

	function cheak_all(){ 
		if($('.check_all').is(':checked') == true){ 
			$('.input_check').prop('checked',true);
		}else{ 
			$('.input_check').prop('checked',false);
		}
	}

	function btn_dele_all(){
		$('.input_check').each(function(i){
			if($('.input_check:eq(' + i + ')').is(':checked') == true){ 
				str_id = str_id + $('.input_check:eq(' + i + ')').attr('projectid') + ',';
			}
		})
		delete_project(str_id);
	}


})


//modal弹出
var MyModal = (function() {
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

var m1 = new MyModal.modal(func_delete);
//确定按钮执行
function func_delete(id){
    $.ajax({
        type: 'post',
        data: {'id':id},
		url:'/dashboard/delete-project',
		success:function(msg){
		  if(msg.code == 1){ 
		    alert(msg.msg);
		    location.href = '/dashboard/list-project';
		  }else{ 
		    alert(msg.msg);
		    location.href = '/dashboard/list-project';
		  }        
		}
    });
}

function delete_project(id){
	m1.show(id);
}

</script>
@stop