@extends('backend-base')

@section('css.append')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- page specific plugin styles -->
    {{--datetimepicker plugin--}}
    <link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    {{--ace backend template--}}
    <link rel="stylesheet" href="/ace-master/assets/css/ace-skins.min.css"/>
    <link rel="stylesheet" href="/ace-master/assets/css/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/ace-master/assets/css/ui.jqgrid.min.css"/>
    {{--multiple select--}}
    <link rel="stylesheet" href="/ace-master/assets/css/chosen.min.css"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="/ace-master/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <!-- file upload -->
    <link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
    <style type="text/css">
        .form-group {
            padding-left: 8%;
            padding-right: 8%;
        }
    </style>
@stop

@section('content')
    <div class="widget-box">
        <div class="widget-body">
            <div class="widget-main">
                <div id="fuelux-wizard-container" class="no-steps-container">
                    <div>
                        <ul class="steps" style="margin-left: 0">
                            <li data-step="1" class="active">
                                <span class="step">1</span>
                                <span class="title">新建项目</span>
                            </li>
                            <li data-step="2">
                                <span class="step">2</span>
                                <span class="title">新建零件</span>
                            </li>
                            <li data-step="3">
                                <span class="step">3</span>
                                <span class="title">新建计划</span>
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <div class="step-content pos-rel">
                        <div class="step-pane active" data-step="1">
                            <div class="center">
                                <h5 class="blue lighter">新建项目</h5>
                            </div>
                            <form class="form-horizontal" id="sample-form">
                                <div class="form-group">
                                    {{--项目编号--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_number')}} </label>
                                    <div class="col-sm-3">
                                        <select class="col-xs-12 col-sm-9" placeholder="" id="form-field-select-1">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    {{--项目名称--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_name')}} </label>
                                    <div class="col-sm-3">
                                        <input type="text" id="form-field-1" placeholder=""
                                               class="col-xs-12 col-sm-9">
                                    </div>
                                    {{--项目类别--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_category')}} </label>
                                    <div class="col-sm-3">
                                        <select class="col-xs-12 col-sm-9" placeholder="项目类别" id="form-field-select-1">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{--客户名称--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.customer_name')}} </label>
                                    <div class="col-sm-3">
                                        <select class="col-xs-12 col-sm-9" placeholder="客户名称" id="form-field-select-1">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    {{--项目经理--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_manager')}} </label>
                                    <div class="col-sm-3">
                                        <select class="col-xs-12 col-sm-9" placeholder="项目经理" id="form-field-select-1">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    {{--项目成员--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_member')}} </label>
                                    <div class="col-sm-3">
                                        {{--<input type="text" id="form-field-1" placeholder="" class="col-xs-12 col-sm-9">--}}
                                        <div style="width: 75%">
                                            <select multiple="" class="chosen-select col-xs-12 col-sm-9 tag-input-style" id="form-field-select-4" data-placeholder="">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{--项目日历--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_calendar')}} </label>
                                    <div class="col-sm-3">
                                        {{--<input type="text" id="form-field-1" placeholder="项目日历"--}}
                                               {{--class="col-xs-12 col-sm-9">--}}
                                        <div>
                                            <select class="col-xs-12 col-sm-9" placeholder="项目日历" id="form-field-select-1">
                                                <option value=""></option>
                                            </select>
                                        </div>

                                    </div>
                                    {{--项目性质--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.project_nature')}} </label>
                                    <div class="col-sm-3">
                                        {{--<input type="text" id="form-field-1" placeholder="项目性质"--}}
                                               {{--class="col-xs-12 col-sm-9">--}}
                                        <select class="col-xs-12 col-sm-9" placeholder="项目性质" id="form-field-select-1">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    {{--接受日期--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.date_acceptance')}} </label>
                                    <div class="col-sm-3">
                                        {{--<input type="text" id="form-field-1" placeholder="" class="col-xs-12 col-sm-9">--}}
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_acceptance" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar"  name="start_date" size="16" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{--结束日期--}}
                                    <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                        * {{Lang::get('mowork.date_end')}} </label>
                                    <div class="col-sm-3">
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar" size="16" name="end_date" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 工艺验证日期 --}}
                                    <label class="col-sm-1 control-label no-padding-right"
                                           for="form-field-1"> {{Lang::get('mowork.date_validation')}} </label>
                                    <div class="col-sm-3">
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar" size="16" name="end_date" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 出模样件日期 --}}
                                    <label class="col-sm-1 control-label no-padding-right"
                                           for="form-field-1"> {{Lang::get('mowork.date_sample')}} </label>
                                    <div class="col-sm-3">
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar" size="16" name="end_date" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{-- 试产验证日期 --}}
                                    <label class="col-sm-1 control-label no-padding-right"
                                           for="form-field-1"> {{Lang::get('mowork.date_verification')}} </label>
                                    <div class="col-sm-3">
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar" size="16" name="end_date" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 批量放产日期 --}}
                                    <label class="col-sm-1 control-label no-padding-right"
                                           for="form-field-1"> {{Lang::get('mowork.date_delivery')}} </label>
                                    <div class="col-sm-3">
                                        <div style="width: 75%">
                                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="date_end" data-link-format="yyyy-mm-dd">
                                                <input class="form-control border-left-squar" size="16" name="end_date" type="text" value=""  required="required">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {{-- 项目描述 --}}
                                    <label class="col-sm-1 control-label no-padding-right"
                                           for="form-field-1"> {{Lang::get('mowork.project_desction')}} </label>
                                    <div class="col-sm-3">
                                        <textarea id="form-field-11" class="autosize-transition form-control"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{--data-step="2"--}}
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="step-pane" data-step="2">
                                    <div class="center">
                                        <h5 class="blue lighter">新建零件</h5>
                                    </div>
                                    <table id="grid-table"></table>
                                    <div id="grid-pager"></div>
                                </div>
                            </div>
                        </div>
                        <div class="step-pane" data-step="3">
                            <div class="center">
                                <h5 class="blue lighter">新建计划</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="wizard-actions">
                    <button class="btn btn-prev" disabled="disabled">
                        <i class="ace-icon fa fa-arrow-left"></i>
                        Prev
                    </button>
                    <button class="btn btn-success btn-next" data-last="Finish">
                        Next
                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer.append')
    <!-- page specific plugin styles -->
    <script src="/ace-master/assets/js/jquery.maskedinput.min.js"></script>
    <script src="/ace-master/assets/js/select2.min.js"></script>
    {{--日历--}}
    <script src="/asset/js/bootstrap-datetimepicker.js"></script>
    {{--导航向导--}}
    <script src="/ace-master/assets/js/wizard.min.js"></script>
    {{--验证--}}
    <script src="/ace-master/assets/js/jquery.validate.min.js"></script>
    {{--表格--}}
    <script src="/ace-master/assets/js/jquery.jqGrid.min.js"></script>
    <script src="/ace-master/assets/js/grid.locale-en.js"></script>
    {{--弹窗--}}
    <script src="/ace-master/assets/js/bootbox.js"></script>
    {{--选择输入框--}}
    <script src="/ace-master/assets/js/chosen.jquery.min.js"></script>
    <!-- ace scripts -->
    <script src="/ace-master/assets/js/ace-elements.min.js"></script>
    <script src="/ace-master/assets/js/ace.min.js"></script>
    {{--文件上传--}}
    <script src="/asset/dropzone4/dropzone.js"></script>
    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function ($) {
            var $validation = false;
            $('#fuelux-wizard-container')
                .ace_wizard({

                })
                .on('actionclicked.fu.wizard', function (e, info) {
                    if (info.step == 1 && $validation) {
                        if (!$('#validation-form').valid()) e.preventDefault();
                    }
                    if (info.step == 2) {
                    }
                })
                .on('finished.fu.wizard', function (e) {
                    bootbox.dialog({
                        message: "项目已经成功创建",
                        buttons: {
                            "success": {
                                "label": "OK",
                                "className": "btn-sm btn-primary"
                            }
                        }
                    });
                })
                .on('stepclick.fu.wizard', function (e) {
                    // e.preventDefault();
                });


            //documentation : http://docs.jquery.com/Plugins/Validation/validate
            //表单验证
            $('#validation-form').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                ignore: "",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password2: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
                    name: {
                        required: true
                    },
                    phone: {
                        required: true,
                        phone: 'required'
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    comment: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    platform: {
                        required: true
                    },
                    subscription: {
                        required: true
                    },
                    gender: {
                        required: true,
                    },
                    agree: {
                        required: true,
                    }
                },
                messages: {
                    email: {
                        required: "Please provide a valid email.",
                        email: "Please provide a valid email."
                    },
                    password: {
                        required: "Please specify a password.",
                        minlength: "Please specify a secure password."
                    },
                    state: "Please choose state",
                    subscription: "Please choose at least one option",
                    gender: "Please choose gender",
                    agree: "Please accept our policy"
                },
                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                    $(e).remove();
                },
                errorPlacement: function (error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    }
                    else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    }
                    else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    }
                    else error.insertAfter(element.parent());
                },
                submitHandler: function (form) {
                },
                invalidHandler: function (form) {
                }
            });
            //多选框
            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect: true});
                //resize the chosen on window resize
                $(window).off('resize.chosen').on('resize.chosen', function () {
                        $('.chosen-select').each(function () {
                            var $this = $(this);
                            $this.next().css({'width': $this.parent().width()});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function (e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function () {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                });
            }
        })
    </script>
    {{--jqgrid--}}
    <script type="text/javascript">
        var grid_data =
            [
                {id: "23", name: "Speakers", note: "note", stock: "No", ship: "ARAMEX", sdate: "2007-12-03"}
            ];
        jQuery(function ($) {
            var grid_selector = "#grid-table";
            var pager_selector = "#grid-pager";

            // 设置表格的宽度等于上级元素及表格根据窗口调整大小/closest() 方法获得匹配选择器的第一个祖先元素
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                $(grid_selector).jqGrid('setGridWidth', parent_column.width());
            })
            //resize on sidebar collapse/expand
            $(document).on('settings.ace.jqGrid', function (ev, event_name, collapsed) {
                if (event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed') {
                    //setTimeout is for webkit only to give time for DOM changes and then redraw!!!
                    setTimeout(function () {
                        $(grid_selector).jqGrid('setGridWidth', parent_column.width());
                    }, 20);
                }
            })
            //if your grid is inside another element, for example a tab pane, you should use its parent's width:
            /**
             $(window).on('resize.jqGrid', function () {
					var parent_width = $(grid_selector).closest('.tab-pane').width();
					$(grid_selector).jqGrid( 'setGridWidth', parent_width );
				})
             //and also set width when tab pane becomes visible
             $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				  if($(e.target).attr('href') == '#mygrid') {
					var parent_width = $(grid_selector).closest('.tab-pane').width();
					$(grid_selector).jqGrid( 'setGridWidth', parent_width );
				  }
				})
             */

            jQuery(grid_selector).jqGrid({
                subGrid: false,
                data: grid_data,
                datatype: "local",
                height: 350,
                colNames: [' ',
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
                    "{{Lang::get('mowork.comment')}}"
                ],
                colModel: [
                    {
                        name: 'myac', index: '', width: 80, fixed: true, sortable: false, resize: false,
                        formatter: 'actions',
                        formatoptions: {
                            keys: true,
                            delbutton: true,
                            delOptions: {
                                recreateForm: true,
                                beforeShowForm: beforeDeleteCallback
                            },
                            editformbutton:true,
                            editOptions:{
                                recreateForm: true,
                                beforeShowForm:beforeEditCallback
                            }
                        }
                    },
                    {name: 'part_number', index: "{{Lang::get('mowork.part_number')}}", width:100,editable: false,edittype:"select",editoptions:{value:"FE:FedEx"}},
                    {name: 'part_name', index: "{{Lang::get('mowork.part_name')}}", width: 100,editable: true,edittype: "text"},
                    {name: 'part_type', index: "{{Lang::get('mowork.part_type')}}", width: 100,editable: true,edittype:"select",editoptions:{value:"FE:FedEx"}},
                    {name: 'source', index: "{{Lang::get('mowork.source')}}", width: 100,editable: true, edittype:"select",editoptions:{value:"FE:FedEx"}},
                    {name: 'quantity', index: "{{Lang::get('mowork.quantity')}}", width: 100,editable: true, edittype: "text"},
                    {name: 'fixture', index: "{{Lang::get('mowork.fixture')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'gauge', index: "{{Lang::get('mowork.gauge')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'mould', index: "{{Lang::get('mowork.mould')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'part_size', index: "{{Lang::get('mowork.part_size')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'part_weight', index: "{{Lang::get('mowork.part_weight')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'part_material', index: "{{Lang::get('mowork.part_material')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'material_specification', index: "{{Lang::get('mowork.material_specification')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'shrink', index: "{{Lang::get('mowork.shrink')}}", width: 100, sortable: false, editable: true, edittype: "text"},
                    {name: 'processing_technology', index: "{{Lang::get('mowork.processing_technology')}}", width: 100, sortable: false,editable: true, edittype: "text"},
                    {name: 'surface_process', index: "{{Lang::get('mowork.surface_process')}}", width: 100, sortable: false, editable: true,edittype: "text"},
                    {name: 'comment', index: "{{Lang::get('mowork.comment')}}", width: 100, sortable: false, editable: true, edittype: "text"}
                ],
                viewrecords: true,
                rowNum: 10,
                rowList: [10, 20, 30],

                pager: pager_selector,
                altRows: true,
                //toppager: true,
                multiselect: true,
                //multikey: "ctrlKey",
                multiboxonly: true,
                loadComplete: function () {
                    var table = this;
                    setTimeout(function () {
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },
                editurl: "./dummy.php",//nothing is saved
                caption: "新建零件"
                //,autowidth: true,
                /**
                 ,
                 grouping:true,
                 groupingView : {
						 groupField : ['name'],
						 groupDataSorted : true,
						 plusicon : 'fa fa-chevron-down bigger-110',
						 minusicon : 'fa fa-chevron-up bigger-110'
					},
                 caption: "Grouping"
                 */
            });
            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size
            //enable search/filter toolbar
            //jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true})
            //jQuery(grid_selector).filterToolbar({});
            //switch element when editing inline
            function aceSwitch(cellvalue, options, cell) {
                setTimeout(function () {
                    $(cell).find('input[type=checkbox]')
                        .addClass('ace ace-switch ace-switch-5')
                        .after('<span class="lbl"></span>');
                }, 0);
            }
            //enable datepicker
            function pickDate(cellvalue, options, cell) {
                setTimeout(function () {
                    $(cell).find('input[type=text]')
                        .datepicker({format: 'yyyy-mm-dd', autoclose: true});
                }, 0);
            }
            //navButtons
            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
                { 	//navbar options
                    edit: true,
                    editicon: 'ace-icon fa fa-pencil blue',
                    add: true,
                    addicon: 'ace-icon fa fa-plus-circle purple',
                    del: true,
                    delicon: 'ace-icon fa fa-trash-o red',
                    search: true,
                    searchicon: 'ace-icon fa fa-search orange',
                    refresh: true,
                    refreshicon: 'ace-icon fa fa-refresh green',
                    view: true,
                    viewicon: 'ace-icon fa fa-search-plus grey',
                },
                {
                    //edit record form
                    //closeAfterEdit: true,
                    //width: 700,
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);
                    }
                },
                {
                    //new record form
                    //width: 700,
                    closeAfterAdd: true,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                            .wrapInner('<div class="widget-header" />')
                        style_edit_form(form);
                    }
                },
                {
                    //delete record form
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        if (form.data('styled')) return false;

                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_delete_form(form);

                        form.data('styled', true);
                    },
                    onClick: function (e) {
                    }
                },
                {
                    //search form
                    recreateForm: true,
                    afterShowSearch: function (e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                        style_search_form(form);
                    },
                    afterRedraw: function () {
                        style_search_filters($(this));
                    }
                    ,
                    multipleSearch: true,
                    /**
                     multipleGroup:true,
                     showQuery: true
                     */
                },
                {
                    //view record form
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                    }
                }
            )
            function style_edit_form(form) {
                //enable datepicker on "sdate" field and switches for "stock" field
                form.find('input[name=sdate]').datepicker({format: 'yyyy-mm-dd', autoclose: true})
                form.find('input[name=stock]').addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
                //don't wrap inside a label element, the checkbox value won't be submitted (POST'ed)
                //.addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');
                //update buttons classes
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
                buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')
                buttons = form.next().find('.navButton a');
                buttons.find('.ui-icon').hide();
                buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
                buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');
            }
            function style_delete_form(form) {
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
                buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
            }
            function style_search_filters(form) {
                form.find('.delete-rule').val('X');
                form.find('.add-rule').addClass('btn btn-xs btn-primary');
                form.find('.add-group').addClass('btn btn-xs btn-success');
                form.find('.delete-group').addClass('btn btn-xs btn-danger');
            }
            function style_search_form(form) {
                var dialog = form.closest('.ui-jqdialog');
                var buttons = dialog.find('.EditTable')
                buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
                buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
                buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');
            }
            function beforeDeleteCallback(e) {
                var form = $(e[0]);
                if (form.data('styled')) return false;
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                style_delete_form(form);
                form.data('styled', true);
            }
            function beforeEditCallback(e) {
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                style_edit_form(form);
            }
            //it causes some flicker when reloading or navigating grid
            //it may be possible to have some custom formatter to do this as the grid is being created to prevent this
            //or go back to default browser checkbox styles for the grid
            function styleCheckbox(table) {
                /**
                 $(table).find('input:checkbox').addClass('ace')
                 .wrap('<label />')
                 .after('<span class="lbl align-top" />')


                 $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
                 .find('input.cbox[type=checkbox]').addClass('ace')
                 .wrap('<label />').after('<span class="lbl align-top" />');
                 */
            }


            //unlike navButtons icons, action icons in rows seem to be hard-coded
            //you can change them like this in here if you want
            function updateActionIcons(table) {
                /**
                 var replacement =
                 {
                     'ui-ace-icon fa fa-pencil' : 'ace-icon fa fa-pencil blue',
                     'ui-ace-icon fa fa-trash-o' : 'ace-icon fa fa-trash-o red',
                     'ui-icon-disk' : 'ace-icon fa fa-check green',
                     'ui-icon-cancel' : 'ace-icon fa fa-times red'
                 };
                 $(table).find('.ui-pg-div span.ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
                 */
            }

            //replace icons with FontAwesome icons like above
            function updatePagerIcons(table) {
                var replacement =
                    {
                        'ui-icon-seek-first': 'ace-icon fa fa-angle-double-left bigger-140',
                        'ui-icon-seek-prev': 'ace-icon fa fa-angle-left bigger-140',
                        'ui-icon-seek-next': 'ace-icon fa fa-angle-right bigger-140',
                        'ui-icon-seek-end': 'ace-icon fa fa-angle-double-right bigger-140'
                    };
                $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function () {
                    var icon = $(this);
                    var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

                    if ($class in replacement) icon.attr('class', 'ui-icon ' + replacement[$class]);
                })
            }

            function enableTooltips(table) {
                $('.navtable .ui-pg-button').tooltip({container: 'body'});
                $(table).find('.ui-pg-div').tooltip({container: 'body'});
            }

            //var selr = jQuery(grid_selector).jqGrid('getGridParam','selrow');

            $(document).one('ajaxloadstart.page', function (e) {
                $.jgrid.gridDestroy(grid_selector);
                $('.ui-jqdialog').remove();
            });
        });
    </script>
    {{--datetimepicker--}}
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})
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
                language: 'zh',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                startDate: new Date().Format("yyyy-MM-dd"),
            });
            //文档上传
            Dropzone.options.mydropzone = {
                maxFiles: 1,
                maxFilesize: 4,
                acceptedFiles: ".jpg,.gif,.png,.docx,.doc,.txt,",
                addRemoveLinks: true,
                dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.upload_project_file')}}</span>",
                dictFileTooBig: "{{Lang::get('mowork.image_too_big')}}",
                dictRemoveFile: "{{Lang::get('mowork.cancel_image')}}",
                dictInvalidFileType: "{{Lang::get('mowork.image_type_error')}}",
                dictMaxFilesExceeded: "{{Lang::get('mowork.exceed_max_files')}}",
                init: function () {
                    this.on("maxfilesexceeded", function (file) {
                        this.removeFile(file);
                    });
                    this.on("error", function (file, responseText) {
                        alert(responseText);
                        console.log(file);
                    });
                    this.on("success", function (file, responseText) {
                        console.log(file);
                    });
                },
                removedfile: function (file) {
                    var name = file.name;
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/relink')}}",
                        data: {
                            fname: name,//fullpath for this uploaded file to be deleted
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                        },
                        error: function (xhr, status, error) {
                            alert(error);
                        },
                        dataType: 'html'  //use type html rather than json in order to post token
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            }
        })
    </script>
@stop