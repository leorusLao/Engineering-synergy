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
@if(Session::has('result'))
   <div class="text-center text-warning">
          {{Session::get('result')}}
   </div>
@endif

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
		<div class="table-responsive table-scrollable">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th style="text-align: center;"><input type="checkbox" style="zoom:130%;" class="check_all" /></th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.customer_number')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.customer_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_name')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_manager')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.date_acceptance')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_approval')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.project_calendar')}}</th>
						<th nowrap='nowrap'>{{Lang::get('mowork.measurement_updatetime')}}</th>
						<!-- <th nowrap='nowrap'>{{Lang::get('mowork.entered')}}</th> -->
						<th nowrap='nowrap'>{{Lang::get('mowork.measurement_operation')}}</th>
					</tr>
				</thead>
				<tbody>

				<?php
					foreach ($result as $key => $value) {
				?>
					<tr>
						<td style="text-align: center;"><input type="checkbox" class="input_check" @if(isset($hasPartArr[$value['proj_id']])) projectid="0" @else projectid="{{$value['proj_id']}}" @endif /></td>
						<td style="display:none;">{{$value['proj_id']}}</td>
						<td><a href="/dashboard/show-project/{{hash('sha256',$salt.$value['proj_id'])}}/{{$value['proj_id']}}">{{$value['proj_code']}}</a></td>
						<td>{{$value['customer_id']}}</td>
						<td>{{$value['customer_name']}}</td>
						<td>{{$value['proj_name']}}</td>
						<td>{{$value['proj_manager']}}</td>
						<td>{{$value['start_date']}}</td>
						<td><?php if($value['approval_status'] == 1){ echo '待递交'; }elseif($value['approval_status'] == 2){ echo '待审批';}elseif($value['approval_status'] == 3){echo '审批同意';}elseif($value['approval_status'] == 4){ echo '审批拒绝';} ?></td>
						<td>{{$value['cal_name']}}</td>
						<td>{{substr($value['updated_at'],0,10)}}</td>
						<!-- <td>1</td> -->
						<td><nobr>
						<a href="/dashboard/edit-projectNew/{{hash('sha256',$salt.$value['proj_id'])}}/{{$value['proj_id']}}" class="am-btn am-text-secondary"><i class="livicon" data-name="edit" data-size="12" data-c="#3bb4f2" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.text_edit')}}</a>
						<button type="button" class="am-btn am-text-danger" @if(isset($hasPartArr[$value['proj_id']]))onclick="delete_project({{$value['proj_id']}})@else onclick="alert('项目中有零件，不能删除')" @endif"><i class="livicon" data-name="remove" data-size="12" data-c="#dd514c" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.text_delete')}}</button>
						<!-- <button type="button" class="am-btn"><i class="livicon" data-name="file-import" data-size="12" data-c="#000" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.excel_export')}}</button> -->
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

<div class="form-group input-group col-sm-12 col-fl-add">
	<div class="form-group text-center fl">
	<button type="button" data-toggle="modal" class="am-btn am-text-danger btn_delete_all"><i class="livicon" data-name="remove" data-size="12" data-c="#dd514c" data-hc="#000" data-loop="false"></i>{{Lang::get('mowork.text_delete')}}</button>
	</div>
</div>

</div>

<!-- Modal for showing delete confirmation -->
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
                <button type="button" class="btn btn-default btn_delete">{{LANG::get('mowork.delete')}}</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->

</div>
@stop

@section('footer.append')
<script>	

$(document).ready(function(){ 
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
		var b = false;
		$('.input_check').each(function(i){
			if($('.input_check:eq(' + i + ')').is(':checked') == true){ 
				var tmp_id = $('.input_check:eq(' + i + ')').attr('projectid');
				if(tmp_id == 0){
					b = true;
				}else{
					str_id += tmp_id  + ',';
				}
			}
		})

		if(str_id == ''){
			alert("{{Lang::get('mowork.do_not_delete_project')}}");
		}else{ 
			if(b){
				alert("{{Lang::get('mowork.do_not_delete_project')}}");
			}else{
				str_id = str_id.substr(0,str_id.length-1);
				delete_project(str_id);
			}
		}
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