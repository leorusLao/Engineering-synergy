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
        <div class="modal fade" id="agree" style="z-index: 9999;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">X</button>
                        <h4 class="modal-title text-center">同意审批意见</h4>
                    </div>
                    <div class="modal-body">
                        <textarea name="approval_comment" id="approval_comment" style="width: 100%;margin-bottom: 20px;margin-top:5px;" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnAgree">确认</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end of modal-->
        <!-- Modal for showing refuse confirmation -->
        <div class="modal fade" id="refuse" style="z-index: 9999;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">X</button>
                        <h4 class="modal-title text-center">拒绝审批意见</h4>
                    </div>
                    <div class="modal-body">
                        <textarea name="refuse_comment" id="refuse_comment" style="width: 100%;margin-bottom: 20px;margin-top:5px;" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnRefuse">确认</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end of modal-->

		<!--end of modal-->
	</div>
@stop

@section('footer.append')
	<!-- page specific plugin scripts -->
	<!-- jqgrid表格/弹窗 -->
	{{--Include jqgrid JavaScript sourc--}}
	<script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/i18n/grid.locale-en.js"></script>
	<script src="/asset/Guriddo_jqGrid_JS_5.3.0/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript">
    //同意立项
    function agreeProject(row){
        var part_number = $(row).find("td").eq(2).text();
        $('#agree').on('show.bs.modal',
            function() {
                $("#agree form").append('<input name="part_number" value="'+part_number+'" type="hidden">');
            }
        );
        $('#agree').modal('show');
    }

    //拒绝立项
    function refuseProject(row){
        var part_number = $(row).find("td").eq(2).text();
        $('#refuse').on('show.bs.modal',
            function() {
                $("#refuse form").append('<input name="part_number" value="'+part_number+'" type="hidden">');
            }
        );
        $('#refuse').modal('show');
    }
	$(function(){
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
                sortname: 'part_number',
                sortorder: "asc",
                gridview: true,
                hiddengrid: false,
                // caption:"零件表",
                hidegrid:true,
                loadonce:true,
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
                    "同意",
                    "拒绝"
                ],
                colModel: [
                    {
                        name: 'proj_code',
                        index: "{{Lang::get('mowork.part_number')}}",
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
                        index: "{{Lang::get('mowork.part_name')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'customer_name',
                        index: "{{Lang::get('mowork.part_type')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'proj_name',
                        index: "{{Lang::get('mowork.source')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'proj_manager',
                        index: "{{Lang::get('mowork.quantity')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'end_date',
                        index: "{{Lang::get('mowork.fixture')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'approval_status',
                        index: "{{Lang::get('mowork.gauge')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'cal_name',
                        index: "{{Lang::get('mowork.mould')}}",
                        align:'center',
                        width:100,
                        formatter: function (cellvalue, options, rowObject) {
                            return "<a href='/dashboard/calendar/makeNew/{{hash("sha256"," + $salt.rowObject.cal_id + ")}}/" + rowObject.cal_id + "' style='ui-icon color:#1d97b9' >"+ rowObject.cal_name +"</a>";
                        }
                    },
                    {
                        name: 'updated_at',
                        index: "{{Lang::get('mowork.part_size')}}",
                        align:'center',
                        width:100
                    },
                    {
                        name: 'agree',
                        index: 'agree',
                        width:100,
                        align:'center',
                        formatter: function (cellvalue, options, rowObject) {
                            return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
                                'onclick="agreeProject('+options.rowId+')">同意</a>';
                        }
                    },
                    {
                        name: 'refuse',
                        index: 'refuse',
                        width:100,
                        align:'center',
                        sortable: false,
                        formatter: function (cellvalue, options, rowObject) {
                            return '<a href="javascript:void(0);" style="color:#1d97b9" ' +
                                'onclick="refuseProject('+options.rowId+')">拒绝</a>';
                        }
                    }
                ]
            }
        );



		// 同意
		$('.agree-comment').click(function(){
			$('#approval_comment').val('');
			$('input[name="proj_id"]').val($(this).data('proj_id'));
			$('input[name="approval_status"]').val(1);
			$('.modal-title').text("{{Lang::get('mowork.agree').Lang::get('mowork.approval_comment')}}");
		});

		// 拒绝
		$('.disagree-comment').click(function(){ 
			$('#approval_comment').val('');
			$('input[name="proj_id"]').val($(this).data('proj_id'));
			$('input[name="approval_status"]').val(2);
			$('.modal-title').text("{{Lang::get('mowork.reject').Lang::get('mowork.approval_comment')}}");
		});
	});

</script>

@stop