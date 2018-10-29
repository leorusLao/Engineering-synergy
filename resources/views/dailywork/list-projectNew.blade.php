@extends('backend-base') 

@section('css.append')
<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<!-- page specific plugin styles -->
	<!-- third-party plugins (jqgrid) -->
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
		div.content {
			padding: 15px;
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
		<div class="jqGrid_wrapper">
			<table id="projectList"></table>
			<div id="projectListGridNav"></div>
		</div>
		<!-- Modal for showing delete confirmation -->
		<div class="modal fade" id="deleteProject" style="z-index: 9999;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"
								aria-hidden="true">X</button>
						<h4 class="modal-title text-center">删除项目</h4>
					</div>
					<div class="modal-body">
						<div class="text-left clearfix">
							您真的要删除该记录吗?
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="btnDelete">确认</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					</div>
				</div>
			</div>
		</div>
		<!--end of modal-->
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

	//项目列表中删除项目
	function deleteProject(proj_id){
		$('#deleteProject').on('show.bs.modal',
            function() {
                $("#deleteProject .modal-body").append('<input id="proj_id" name="proj_id" value="'+proj_id+'" type="hidden">');
            }
		);
		$('#deleteProject').modal('show');
	}


	//确认删除项目列表中的项目
	$("#btnDelete").on("click",function(){
	    var proj_id = $("#proj_id").val();
        console.log(proj_id);
        $.ajax({
            type: 'post',
            data: {'id':proj_id},
            url:'/dashboard/delete-project',
            success:function(msg){
                if(msg.code == 1){
                    alert(msg.msg);
                    location.href = '/dashboard/list-projectNew';
                }else{
                    alert(msg.msg);
                    location.href = '/dashboard/list-projectNew';
                }
            }
        });
	});


	$(document).ready(function(){
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }})

		});

		// 动态设置jqgrid宽度
		var jqParts_width = $('.jqGrid_wrapper').width() - 30;
		var jqParts_dialog_width = '';
		if(jqParts_width < 1600){
			jqParts_dialog_width = 1000;
		}else{
			jqParts_dialog_width = 1400;
		}

		//jqgrid(projectList_grid)
		$("#projectList").jqGrid(
			{
				editurl: 'clientArray',
                url: '/dashboard/list-projectNew',
				datatype: "json",
				styleUI: 'Bootstrap',
				pager: '#projectListGridNav',
				width: jqParts_width,
				height: 650,
				multiselect: true,
				rownumbers: true,
				hoverrows: true,
				rowNum: 20,
				rowList: [15, 30, 50],
				viewrecords: true,
				recordtext: "第{0}页-{1}条，共{2}条",
				cellEdit: false,
				sortable:true,
				sortname: 'project_number',
				sortorder: "asc",
				gridview: true,
				hiddengrid: false,
				// caption:"零件表",
				hidegrid:true,
				loadonce:true,
                // loadComplete: function(data) { //完成服务器请求后，回调函数
                //     if (data.records == 0) { //如果没有记录返回，追加提示信息，删除按钮不可用
                //         $("p").appendTo($("#list")).addClass("nodata").html('找不到相关数据！');
                //         $("#del_btn").attr("disabled", true);
                //     } else { //否则，删除提示，删除按钮可用
                //         $("p.nodata").remove();
                //         $("#del_btn").removeAttr("disabled");
                //     }
                // },
				colNames: [
					"{{Lang::get('mowork.project_number')}}",
					"{{Lang::get('mowork.customer_number')}}",
					"{{Lang::get('mowork.customer_name')}}",
					"{{Lang::get('mowork.project_name')}}",
					"{{Lang::get('mowork.project_manager')}}",
					"{{Lang::get('mowork.date_acceptance')}}",
					"{{Lang::get('mowork.project_approval')}}",
					"{{Lang::get('mowork.project_calendar')}}",
					"{{Lang::get('mowork.measurement_updatetime')}}",
					//{{--"{{Lang::get('mowork.entered')}}",--}}
					"编辑",
					"删除"
					// "Excel导出"
				],
				colModel: [
					{
						name: 'proj_code',
						index: "{{Lang::get('mowork.project_number')}}",
						align:'center',
						width:100,
						sortable: true,
						sortorder:"asc",
                        formatter: function (cellvalue, options, rowObject) {
						return "<a href='/dashboard/show-projectNew/{{hash("sha256"," + $salt.rowObject.proj_id + ")}}/" + rowObject.proj_id + "' style='ui-icon color:#1d97b9' >"+ rowObject.proj_code +"</a>";
                        }
					},
					{
						name: 'customer_id',
						index: "{{Lang::get('mowork.customer_number')}}",
						align:'center',
						width:100
					},
					{
						name: 'customer_name',
						index: "{{Lang::get('mowork.customer_name')}}",
						align:'center',
						width:100,
					},
					{
						name: 'proj_name',
						index: "{{Lang::get('mowork.project_name')}}",
						align:'center',
						width:100
					},
					{
						name: 'proj_manager',
						index: "{{Lang::get('mowork.project_manager')}}",
						align:'center',
						width:100
					},
					{
						name: 'end_date',
						index: "{{Lang::get('mowork.date_acceptance')}}",
						align:'center', width:100
					},
					{
						name: 'approval_status',
						index: "{{Lang::get('mowork.project_approval')}}",
						align:'center',
						width:100
					},
					{
						name: 'cal_name',
						index: "{{Lang::get('mowork.project_calendar')}}",
						align:'center',
						width:100
					},
					{
						name: 'updated_at',
						index: "{{Lang::get('mowork.measurement_updatetime')}}",
						align:'center',
						width:100
					},
					{{--{--}}
						{{--name: 'entered',--}}
						//{{--index: "{{Lang::get('mowork.entered')}}",--}}
						{{--align:'center',--}}
						{{--width:100--}}
					{{--},--}}
                    {
                        name: 'editProject',
                        index: 'editProject',
                        width:100,
                        align:'center',
                        formatter: function (cellvalue, options, rowObject) {
                            return "<a href='/dashboard/edit-projectNew/{{hash("sha256"," + $salt.rowObject.proj_id + ")}}/" + rowObject.proj_id + "' style='ui-icon color:#1d97b9' >编辑</a>";
                        }
                    },
					{
						name: 'deleteProject',
						index: 'deleteProject',
						width:100,
						align:'center',
						formatter: function (cellvalue, options, rowObject) {
							return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
								'onclick="deleteProject('+rowObject.proj_id+')">删除</a>';
						}
					}
					// {
					// 	name: 'exportExcel',
					// 	index: 'exportExcel',
					// 	width:100,
					// 	align:'center',
					// 	formatter: function (cellvalue, options, rowObject) {
					// 		return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
					// 			'onclick="addPlan('+options.rowId+')">Excel导出</a>';
					// 	}
					// }
				]
			}
		);

		$("#projectList").navGrid('#projectListGridNav',{edit:false,add:false,del:true,search:false,refresh: false,view:false,edittext:"修改",addtext: "添加",viewtext:"预览",deltext: "删除",position: "left", cloneToTop: false},
			{
				deleteCaption: "删除",
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