/**
 * user:zhoubing
 * date:2018-2-2
 * function:part&plan operates
 * notes:零件&计划新增，计划随零件联动增加，零件计划（增删改查）
 * paras:
 **/


$(document).ready(function(){
    //create sign part & create corresponding plan(项目计划)单个零件信息新增
    $("form#main_input_box").submit(function(event){
        event.preventDefault();
        var deleteButton = " <a href='' class='tododelete redcolor'><span class='glyphicon glyphicon-trash'></span></a>";
        var striks ="<span class='striks'> |  </span>";
        var checkBox = "";
        //剔除表单中默认下拉框选项(零件类型option只引导用户选择)
        var part_type = $('.part_type').find("option:selected").text();
        if(part_type == '*零件类型'){
            part_type = '';

        }
        //剔除表单中默认下拉框选项(零件来源只引导用户选择)
        var part_resource = $('.part_resource').find("option:selected").text();
        if(part_resource == '*来源'){
            part_resource = '';
        }
        //单个零件信息新增对应表格一行记录（拼接字符串）
        var cont_part_col = "<tr class='todolist_list showactions part"+ $('.part_number').val() +"' style='display:table-row; float: none;' role='row'>" +
            "<td class='todotext_1'>"+ $('.part_number').val() +"</td>" +
            "<td class='todotext_2'>"+ $('.part_name').val() +"</td>" +
            "<td class='todotext_3'>"+ part_type +"</td>" +
            "<td class='todotext_4'>"+ part_resource +"</td>" +
            "<td class='todotext_5'>"+ $('.quantity').val() +"</td>" +
            "<td class='todotext_7'>"+ $('.fixture').val() +"</td>" +
            "<td class='todotext_8'>"+ $('.gauge').val() +"</td>" +
            "<td class='todotext_9'>"+ $('.mould').val() +"</td>" +
            "<td class='todotext_10'>"+ $('.part_size').val() +"</td>" +
            "<td class='todotext_11'>"+ $('.part_weight').val() +"</td>" +
            "<td class='todotext_12'>"+ $('.part_material').val() +"</td>" +
            "<td class='todotext_13'>"+ $('.material_specification').val() +"</td>" +
            "<td class='todotext_14'>"+ $('.shrinkage').val() +"</td>" +
            "<td class='todotext_15'>"+ $('.processing_technology').val() +"</td>" +
            "<td class='todotext_16'>"+ $('.surface_treatment').val() +"</td>" +
            "<td class='todotext_6'>"+ $('.comment').val() +"</td>" +
            "<td>" +
            "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" +
            "<a href='#' class='todoedit'>" +
            "<span class='glyphicon glyphicon-pencil part-pencil'></span>" +
            "</a>" +
            "<span class='striks'> | </span>" +
            "<a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,\"" + $('.part_number').val() + "\",\"温馨提示\",\"确定要删除此记录？\")'>" +
            "<span class='glyphicon glyphicon-trash'></span>" +
            "</a>" +
            "</div>" +
            "</td></tr>";
        // (追加到零件todolist)
        $(".area_todolist").append(cont_part_col);

        //计划随零件而创建，单个计划信息新增对应表格一行记录（拼接字符串）
        var plan_type = '产品开发计划';
        //globle variable(项目计划编号，检具计划编号，夹具计划编号，模具计划编号初始编号为0)
        if($(".area_todolist_plan tr.project_coding:last td:nth-child(2)").text()){
            var max_plan_project_num = parseInt($(".area_todolist_plan tr.project_coding:last td:nth-child(2)").text().charAt(($(".area_todolist_plan tr.project_coding:last td:nth-child(2)").text().length)-1));
            pre_plan_project_num =$(".area_todolist_plan tr.project_coding:last td:nth-child(2)").text().substr(0, $(".area_todolist_plan tr.project_coding:last td:nth-child(2)").text().length-1);
            plan_project_num = max_plan_project_num;
                plan_project_num += 1;
        }else{
            var plan_project_num = parseInt($("#plan_number").attr("project_coding").charAt(($("#plan_number").attr("project_coding").length)-1));
            var pre_plan_project_num =$("#plan_number").attr("project_coding").substr(0, $("#plan_number").attr("project_coding").length-1);
        }
        var cont_plan_col = "<tr class='project_coding todolist_list_plan showactions plan"+ $('.part_number').val() +"' style='background-color:#00c0ef; display:table-row; float: none;' role='row'>" +
            "<td class='todotext_1'>"+ $('.part_number').val() +"</td>" +
            "<td class='todotext_2'>"+pre_plan_project_num+ plan_project_num +"</td>" +
            "<td class='todotext_3'>$('.part_number').val()</td>" +
            "<td class='todotext_4'>"+ plan_type + "</td>" +
            "<td class='todotext_5'>"+ $('.quantity').val()+ "</td>" +
            "<td class='todotext_6'></td>" +
            "<td class='todotext_7'></td>" +
            "<td class='todotext_8'></td>" +
            "<td class='todotext_9'></td>" +
            "<td class='todotext_10'></td>" +
            "<td class='todotext_11'></td>" +
            "<td class='todotext_12'></td>" +
            "<td>" +
            "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" +
            "<a href='#' class='todoedit'>" +
            "<span class='glyphicon glyphicon-pencil plan-pencil'></span>" +
            "</a>" +
            "<span class='striks'> | </span>" +
            //"<a class='tododelete redcolor' onclick='delete_plan(\""+ $('.part_number').val() +"\")'>" +
            "<a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,\"" + $("#plan_number").attr("project_coding").substr(0, $("#plan_number").attr("project_coding").length-1) + plan_project_num + "\",\"温馨提示\",\"确定要删除此记录？\")'>" +
            "<span class='glyphicon glyphicon-trash'></span>" +
            "</a>" +
            "</div>" +
            "</td></tr>";
        // 项目计划增加1
        plan_project_num += 1;
        // (追加到计划todolist)
        $(".area_todolist_plan").append(cont_plan_col);

        // 增加相应夹具开发计划
        var fixture = $('.fixture').val();
        //globle variable(项目计划编号，检具计划编号，夹具计划编号，模具计划编号初始编号为0)
        if($(".area_todolist_plan tr.fixture:last td:nth-child(2)").text()){
            var max_plan_fixture_num = parseInt($(".area_todolist_plan tr.fixture:last td:nth-child(2)").text().charAt(($(".area_todolist_plan tr.fixture:last td:nth-child(2)").text().length)-1));
            pre_plan_fixture_num =$(".area_todolist_plan tr.fixture:last td:nth-child(2)").text().substr(0, $(".area_todolist_plan tr.fixture:last td:nth-child(2)").text().length-1);
            plan_fixture_num = max_plan_fixture_num;
            plan_fixture_num += 1;
        }else{
            var plan_fixture_num = parseInt($("#plan_number").attr("jig_coding").charAt(($("#plan_number").attr("jig_coding").length)-1));
            var pre_plan_fixture_num =$("#plan_number").attr("jig_coding").substr(0, $("#plan_number").attr("jig_coding").length-1);
        }
        for(i=0;i<fixture;i++){
            var plan_type = '夹具开发计划';
            var cont_plan_col = "<tr class='fixture todolist_list_plan showactions plan"+ $('.part_number').val() +"' style='display:table-row; float: none;' role='row'>" +
                "<td class='todotext_1'>"+ $('.part_number').val() +"</td>" +
                "<td class='todotext_2'>"+ pre_plan_fixture_num + plan_fixture_num +"</td>" +
                "<td class='todotext_3'></td>" +
                "<td class='todotext_4'>"+ plan_type + "</td>" +
                "<td class='todotext_5'>"+ $('.quantity').val() + "</td>" +
                "<td class='todotext_6'></td>" +
                "<td class='todotext_7'></td>" +
                "<td class='todotext_8'></td>" +
                "<td class='todotext_9'></td>" +
                "<td class='todotext_10'></td>" +
                "<td class='todotext_11'></td>" +
                "<td class='todotext_12'></td>" +
                "<td>" +
                "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" +
                "<a href='#' class='todoedit'>" +
                "<span class='glyphicon glyphicon-pencil plan-pencil'></span>" +
                "</a>" +
                "<span class='striks'> | </span>" +
                //"<a class='tododelete redcolor' onclick='delete_plan(\""+ $('.part_number').val() +"\")'>" +
                "<a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,\"" + $("#plan_number").attr("jig_coding").substr(0, $("#plan_number").attr("jig_coding").length-1) + plan_fixture_num + "\",\"温馨提示\",\"确定要删除此记录？\")'>" +
                "<span class='glyphicon glyphicon-trash'></span>" +
                "</a>" +
                "</div>" +
                "</td></tr>";
            // 检具计划增加1
            plan_fixture_num += 1;
            $(".area_todolist_plan").append(cont_plan_col);
        }
        // 增加相应检具开发计划
        var gauge = $('.gauge').val();
        //globle variable(项目计划编号，检具计划编号，夹具计划编号，模具计划编号初始编号为0)

        if($(".area_todolist_plan tr.gauge:last td:nth-child(2)").text()){
            var max_plan_gauge_num = parseInt($(".area_todolist_plan tr.gauge:last td:nth-child(2)").text().charAt(($(".area_todolist_plan tr.gauge:last td:nth-child(2)").text().length)-1));
            pre_plan_gauge_num = $(".area_todolist_plan tr.gauge:last td:nth-child(2)").text().substr(0, $(".area_todolist_plan tr.gauge:last td:nth-child(2)").text().length-1);
            plan_gauge_num = max_plan_gauge_num;
                plan_gauge_num += 1;
        }else{
            var plan_gauge_num = parseInt($("#plan_number").attr("gauge_coding").charAt(($("#plan_number").attr("gauge_coding").length)-1));
            var pre_plan_gauge_num =$("#plan_number").attr("gauge_coding").substr(0, $("#plan_number").attr("gauge_coding").length-1);
        }
        for(i=0;i<fixture;i++){
            var plan_type = '检具开发计划';
            var cont_plan_col = "<tr class='gauge todolist_list_plan showactions plan"+ $('.part_number').val() +"' style='display:table-row; float: none;' role='row'>" +
                "<td class='todotext_1'>"+ $('.part_number').val() +"</td>" +
                "<td class='todotext_2'>"+ pre_plan_gauge_num +plan_gauge_num+"</td>" +
                "<td class='todotext_3'></td>" +
                "<td class='todotext_4'>"+ plan_type + "</td>" +
                "<td class='todotext_5'>"+ $('.quantity').val() + "</td>" +
                "<td class='todotext_6'></td>" +
                "<td class='todotext_7'></td>" +
                "<td class='todotext_8'></td>" +
                "<td class='todotext_9'></td>" +
                "<td class='todotext_10'></td>" +
                "<td class='todotext_11'></td>" +
                "<td class='todotext_12'></td>" +
                "<td>" +
                "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" +
                "<a href='#' class='todoedit'>" +
                "<span class='glyphicon glyphicon-pencil plan-pencil'></span>" +
                "</a>" +
                "<span class='striks'> | </span>" +
                //"<a class='tododelete redcolor' onclick='delete_plan(\""+ $('.part_number').val() +"\")'>" +
                "<a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,\""+ $("#plan_number").attr("gauge_coding").substr(0, $("#plan_number").attr("gauge_coding").length-1) + plan_gauge_num + "\",\"温馨提示\",\"确定要删除此记录？\")'>" +
                "<span class='glyphicon glyphicon-trash'></span>" +
                "</a>" +
                "</div>" +
                "</td></tr>";
            // 检具计划增加1
            plan_gauge_num += 1;
            $(".area_todolist_plan").append(cont_plan_col);
        }
        // 增加相应模具开发计划
        var mould = $('.mould').val();
        //globle variable(项目计划编号，检具计划编号，夹具计划编号，模具计划编号初始编号为0)
        if($(".area_todolist_plan tr.mould:last td:nth-child(2)").text()){
            var max_plan_mould_num = parseInt($(".area_todolist_plan tr.mould:last td:nth-child(2)").text().charAt(($(".area_todolist_plan tr.mould:last td:nth-child(2)").text().length)-1));
            pre_plan_mould_num =$(".area_todolist_plan tr.mould:last td:nth-child(2)").text().substr(0, $(".area_todolist_plan tr.mould:last td:nth-child(2)").text().length-1);
            plan_mould_num = max_plan_mould_num;
                plan_mould_num += 1;
        }else{
            var plan_mould_num = parseInt($("#plan_number").attr("mold_coding").charAt(($("#plan_number").attr("mold_coding").length)-1));
            var pre_plan_mould_num =$("#plan_number").attr("mold_coding").substr(0, $("#plan_number").attr("mold_coding").length-1);
        }
        for(i=0;i<mould;i++){
            var plan_type = '模具开发计划';
            var cont_plan_col = "<tr class='mould todolist_list_plan showactions plan"+ $('.part_number').val() +"' style='display:table-row; float: none;' role='row'>" +
                "<td class='todotext_1'>"+ $('.part_number').val() +"</td>" +
                "<td class='todotext_2'>"+ pre_plan_mould_num + plan_mould_num +"</td>" +
                "<td class='todotext_3'></td>" +
                "<td class='todotext_4'>"+ plan_type + "</td>" +
                "<td class='todotext_5'>"+ $('.quantity').val() + "</td>" +
                "<td class='todotext_6'></td>" +
                "<td class='todotext_7'></td>" +
                "<td class='todotext_8'></td>" +
                "<td class='todotext_9'></td>" +
                "<td class='todotext_10'></td>" +
                "<td class='todotext_11'></td>" +
                "<td class='todotext_12'></td>" +
                "<td>" +
                "<div class='pull-right todoitembtns' style='float:left!important; padding-top:0px;'>" +
                "<a href='#' class='todoedit'>" +
                "<span class='glyphicon glyphicon-pencil plan-pencil'></span>" +
                "</a>" +
                "<span class='striks'> | </span>" +
                //"<a class='tododelete redcolor' onclick='delete_plan(\""+ $('.part_number').val() +"\")'>" +
                "<a class='tododelete redcolor' onclick='myconfirm(delete_linjin,cancel,\"" + $("#plan_number").attr("mold_coding").substr(0, $("#plan_number").attr("mold_coding").length-1)+ plan_mould_num + "\",\"温馨提示\",\"确定要删除此记录？\")'>" +
                "<span class='glyphicon glyphicon-trash'></span>" +
                "</a>" +
                "</div>" +
                "</td></tr>";
            // 模具计划增加1
            plan_mould_num += 1;
            $(".area_todolist_plan").append(cont_plan_col);
        }
    });
});
/**
 * function: operate confirm
 * notes: 具体零件具体计划操作弹窗
 * paras: func:操作函数/cancel：取消函数/param：零件编号或者计划编号/titlem:弹框头部文本/contentm:弹框提示内容
 **/
function myconfirm(func, cancel, param, titlem, contentm){
    var openhtml = '<div class="m-modal"><div class="m-modal-dialog"><div class="m-top">'
        + '<h4 class="m-modal-title">'+ titlem +'</h4><span class="m-modal-close">&times;</span>'
        +'</div><div class="m-middle"><p>'+ contentm +'</p>'
        +'</div><div class="m-bottom"><button class="m-btn-sure">确定</button><button class="m-btn-cancel">取消</button></div></div></div>';
    $("body").append(openhtml);
    $('.m-modal').fadeIn(100);
    $('.m-modal').children('.m-modal-dialog').animate({
        "margin-top": "90px"
    }, 250);
    $(".m-modal").attr("deleteid",param);
    if (typeof(func) == 'function')
        $('.m-btn-sure').click(func);
    if (typeof(cancel) == 'function')
        $('.m-btn-cancel').click(cancel);
}
/**
 * function: operate cancel
 * notes: 具体零件具体计划操作弹窗取消操作
 * paras: func:
 **/
function cancel(){
    var $modal = $('.m-modal');
    $modal.children('.m-modal-dialog').animate({
        "margin-top": "-100%"
    }, 500);
    $modal.fadeOut(100);
    setTimeout('$(".m-modal").remove()',700);
}



/**
 * function:part operates
 * notes:根据零件新增计划，零件（增删改查）
 **/

/**
 * function:part&corresponding plan delete
 * notes:删除对应零件和计划
 * paras:id:part_number(零件编号)
 **/
function delete_linjin(id){
    id = $(".m-modal").attr("deleteid");
    $('.part'+id).remove();
    $('.plan'+id).remove();
    $('.partid_delete').text($('.partid_delete').text() + id + ',');
    cancel();
}
/**
 * function:single part edit
 * notes:编辑单个对应零件
 * paras:
 **/
$(document).on('click', '.todoedit .part-pencil', function (e) {
    e.preventDefault();
    var text = '';
    for (var i = 1; i < 18; i++) {
        text = $(this).closest('.todolist_list').find('.todotext_'+i).text();
        if(i!=1){
            text = "<input type='text' name='text' class='todoinput_" + i + "' value='"+text+"' onkeypress='return event.keyCode != 13;' />";
        }
        $(this).closest('.todolist_list').find('.todotext_'+i).html(text);
    }
    $(this).removeClass('glyphicon-pencil part-pencil').addClass('glyphicon-saved part-saved hidden-xs');
});
/**
 * function:single part saved
 * notes:保存单个对应零件
 * paras:
 **/
$(document).on('click', '.todoedit .part-saved', function (e) {
    e.preventDefault();
    for (var i = 2; i < 18; i++) {
        var text = $(this).closest('.todolist_list').find('.todoinput_'+i).val();
        $(this).closest('.todolist_list').find('.todotext_'+i).html(text);
    }
    $(this).removeClass('glyphicon-saved part-saved hidden-xs').addClass('glyphicon-pencil part-pencil');
    $(this).closest('.todolist_list').find('.striked').show();
});



/**
 * function:plan operates
 * notes:计划（增删改查）
 **/

/**
 * function:delete single plan
 * notes:单个计划删除
 * paras:
 **/
function delete_plan(id){
    id = $(".m-modal").attr("deleteid");
    $('.plan'+id).remove();
    $('.planid_delete').text($('.planid_delete').text() + id + ',' );
    cancel();
}
/**
 * function:single plan type select
 * notes:单个计划类型筛选
 * paras:
 **/
$("#plan_category_todolist").change(function(){
    var selectValue = $('#plan_category_todolist').find("option:selected").text();
    // var selectValue = $('this').find("option:selected").text();
    $(".area_todolist_plan tr td:nth-child(4)").each(function(){
        if($(this).text() != selectValue){
            $(this).parent().css("display","none");
        }else{
            $(this).parent().css("display","table-row");
        }
    });
});
/**
 * function:single plan edit
 * notes:单个计划编辑
 * paras:
 **/
$(document).on('click', '.todoedit .plan-pencil', function (e) {
    e.preventDefault();
    var text = '';
    for (var i = 1; i < 8; i++) {
        text = $(this).closest('.todolist_list_plan').find('.todotext_'+i).text();
        if(i==6 || i==7){
            text = "<div class='input-group date form_date' data-date='' data-date-format='yyyy-mm-dd' data-link-field='date_end' data-link-format='yyyy-mm-dd'>"
                + "<input class='form-control border-left-squar todoinput_" + i + "'' size='16' name='end_date' type='text' value='" + text + "' onfocus=this.blur()  required='required'>"
                + "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
            + "</div>";
        }else if(i!=1){
            text = "<input type='text' name='text' class='form-control cust_text1 part_size mt0 todoinput_" + i + "' value='"+text+"' onkeypress='return event.keyCode != 13;' />";
        }
        $(this).closest('.todolist_list_plan').find('.todotext_'+i).html(text);
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
        forceParse: 0
    });


    $(this).removeClass('glyphicon-pencil plan-pencil').addClass('glyphicon-saved plan-saved hidden-xs');
});
/**
 * function:single plan saved
 * notes:单个计划保存
 * paras:
 **/
$(document).on('click', '.todoedit .plan-saved', function (e) {
    e.preventDefault();
    for (var i = 2; i < 8; i++) {
        var text = $(this).closest('.todolist_list_plan').find('.todoinput_'+i).val();
        $(this).closest('.todolist_list_plan').find('.todotext_'+i).html(text);
    }
    $(this).removeClass('glyphicon-saved plan-saved hidden-xs').addClass('glyphicon-pencil plan-pencil');
    $(this).closest('.todolist_list_plan').find('.striked').show();
});






