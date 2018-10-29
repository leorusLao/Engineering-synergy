@extends('backend-base')

@section('content')
 <div class="col-xs-12 col-sm-10 col-sm-offset-1">

<div class="margin-b20">
	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-original-title=""><span
		class="glyphicon glyphicon-plus"></span>{{Lang::get('mowork.add')}}</a>
 </div>
   @if(Session::has('result'))
   <div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
   @endif

   <table class="table data-table table-striped table-bordered  sort table-responsive">

          <thead>

            <tr>
              <th>{{Lang::get('mowork.number')}}</th>
              <th>{{Lang::get('mowork.name')}}</th>
              <th>{{Lang::get('mowork.chinese_name')}}</th>
              <th>{{Lang::get('mowork.route')}}</th>
              <th width="11%">{{Lang::get('mowork.is_menu')}}</th>
              <th>{{Lang::get('mowork.sort')}}</th>
              <th>{{Lang::get('mowork.measurement_createtime')}}</th>
              <th width="20%">{{Lang::get('mowork.maintenance')}}</th>
            </tr>

          </thead>

          <tbody>

    @foreach($rows as $row)

      <tr>
      <td>{{{ $row->id }}}</td>
      <td>{{{ $row->route_name }}}</td>
      <td><a href="/dashboard/permission-management/{{{ $row->id }}}">{{{ $row->display_name }}}</a></td>
      <td>{{{ $row->name }}}</td>
      <td>@if($row->is_menu == 1)
          是
        @else
          否
        @endif
      </td>
      <td>{{{ $row->sort }}}</td>
      <td>{{{ $row->created_at }}}</td>
      <td><a href='#updateform' data-toggle="modal" data-book-id="{{$row->id}}" data-route-name="{{$row->route_name}}" data-display-name="{{$row->display_name}}" data-name="{{$row->name}}" data-is-menu="{{$row->is_menu}}" data-sort="{{$row->sort}}" data-icon="{{$row->icon}}"><span class="glyphicon glyphicon-edit">{{Lang::get('mowork.text_edit')}}</span></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='' data-toggle="modal" data-book-id="{{$row->id}}""><span class="glyphicon glyphicon-trash">{{Lang::get('mowork.text_delete')}}</span></a></td>
      </tr>

    @endforeach


     </tbody>

     </table>

 </div>

<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.add').Lang::get('mowork.user_permission_management')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/permission-management' method='post'
					autocomplete='off' role=form>
  				<div class="form-group">
            <select class="form-control" name="pid">
                <option value="0" @if($pid == 0) selected @endif>顶级权限</option>
                @foreach($selectData as $vo)
                  <option value="{{{$vo['id']}}}" @if($pid == $vo['id']) selected @endif>{{{$vo['_flg'].$vo['display_name']}}}</option>
                @endforeach
            </select>
          </div>
					<div class="form-group">
						<input type="text" class="form-control required" name="route_name"
              title="{{Lang::get('mowork.name')}}"
							placeholder="{{Lang::get('mowork.name')}}" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="display_name"
              title="{{Lang::get('mowork.chinese_name')}}"
							placeholder="{{Lang::get('mowork.chinese_name')}}" />
					</div>
          <div class="form-group">
            <input type="text" class="form-control required" name="name"
              title="{{Lang::get('mowork.route')}}"
              placeholder="{{Lang::get('mowork.route')}}" value="" />
          </div>
          <div class="form-group">
            <select class="form-control" name="is_menu">
                  <option value="1">{{Lang::get('mowork.is').Lang::get('mowork.menu')}}</option>
                  <option value="2" selected>{{Lang::get('mowork.not').Lang::get('mowork.menu')}}</option></option>
              </select>
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" name="sort"
              value=""
              title="{{Lang::get('mowork.sort')}}"
              placeholder="{{Lang::get('mowork.sort')}}" />
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" name="icon"
              value=""
              title="icon"
              placeholder="icon" />
          </div>

					<div class="form-group">
						<input type="button" class="form-control btn-info"
							value="{{Lang::get('mowork.add')}}">
					</div>
					<input name="add" type="hidden" value="add">

				</form>
			</div>
			<div class="modal-footer"></div>
			<!-- <div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div> -->
		</div>
	</div>
</div>



<div class="modal fade" id="updateform" style="z-index: 9999">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.update').Lang::get('mowork.user_permission_management')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
				<form action='/dashboard/permission-management' method='post'
					autocomplete='off' role=form>
					<div class="form-group">
						  <select class="form-control" name="pid">
                 <option value="0" @if($pid == 0) selected @endif>顶级权限</option>
                  @foreach($selectData as $vo)
                    <option value="{{{$vo['id']}}}"  @if($pid == $vo['id']) selected @endif>{{{$vo['_flg'].$vo['display_name']}}}</option>
                  @endforeach
              </select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="route_name"
							value=""
              title="{{Lang::get('mowork.name')}}"
							placeholder="{{Lang::get('mowork.name')}}" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" name="display_name"
							value=""
              title="{{Lang::get('mowork.chinese_name')}}"
							placeholder="{{Lang::get('mowork.chinese_name')}}" />
					</div>
          <div class="form-group">
            <input type="text" class="form-control required" name="name"
              title="{{Lang::get('mowork.route')}}"
              placeholder="{{Lang::get('mowork.route')}}" value=""/>
          </div>
          <div class="form-group">
              <select class="form-control" name="is_menu">
                  <option value="1">{{Lang::get('mowork.is').Lang::get('mowork.menu')}}</option>
                  <option value="2">{{Lang::get('mowork.not').Lang::get('mowork.menu')}}</option></option>
              </select>
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" name="sort"
              value=""
              title="{{Lang::get('mowork.sort')}}"
              placeholder="{{Lang::get('mowork.sort')}}" />
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" name="icon"
              value=""
              title="icon"
              placeholder="icon" />
          </div>

					<div class="form-group">
						<input type="button" class="form-control btn-info"
							value="{{Lang::get('mowork.update')}}">
					</div>
					<input name="id" type="hidden" value="">
          <input name="update" type="hidden" value="update">
				</form>
			</div>
			<div class="modal-footer"></div>
			<!-- <div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div> -->
		</div>
	</div>
</div>

@stop

@section('footer.append')

<script type="text/javascript">

    $(function(){

        // 新增
        $('#formholder').find('input[type="button"]').click(function(){
            var pid = $('#formholder').find('select[name="pid"]').val();
            var route_name = $('#formholder').find('input[name="route_name"]').val().replace(/^\s+|\s+$/gm,'');
            var display_name = $('#formholder').find('input[name="display_name"]').val().replace(/^\s+|\s+$/gm,'');
            var name = $('#formholder').find('input[name="name"]').val().replace(/^\s+|\s+$/gm,'');
            var is_menu = $('#formholder').find('select[name="is_menu"]').val();
            var sort = $('#formholder').find('input[name="sort"]').val().replace(/^\s+|\s+$/gm,'');
            var icon = $('#formholder').find('input[name="icon"]').val().replace(/^\s+|\s+$/gm,'');

            var patt1 = new RegExp("[0-9]+")

            if(!patt1.test(sort)) {
              sort = 0;
            }

            if(route_name && display_name)
            {
                $('#formholder .close').click();
                $.ajax({
                    url : '/dashboard/permission-management',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        pid : pid,
                        route_name : route_name,
                        display_name : display_name,
                        name : name,
                        is_menu : is_menu,
                        sort : sort,
                        icon : icon,
                        _token : '{{csrf_token()}}',
                        add : 'add',
                    },
                    success:function(data){
                        if(data[0]){
                            alert('{{Lang::get("mowork.operation_success")}}');
                            location.reload();
                        }else {
                            alert('{{Lang::get("mowork.operation_failure")}}');
                        }

                    },
                    error:function(){
                        alert('{{Lang::get("mowork.operation_failure")}}');
                    }

                });

            }else {
                alert('{{Lang::get("mowork.name")." ".Lang::get("mowork.chinese_name").Lang::get("mowork.content_required")}}');
            }

        });


        $('.glyphicon-edit').click(function(){
            var id = $(this).parent('a').data('book-id');
            var route_name = $(this).parent('a').data('route-name');
            var display_name = $(this).parent('a').data('display-name');
            var name = $(this).parent('a').data('name');
            var is_menu = $(this).parent('a').data('is-menu');
            var sort = $(this).parent('a').data('sort');
            var icon = $(this).parent('a').data('icon');
            $('#updateform').find('input').eq(0).val(route_name);
            $('#updateform').find('input').eq(1).val(display_name);
            $('#updateform').find('select[name="is_menu"]').find('option').prop("selected",false);
            $('#updateform').find('select[name="is_menu"]').find('option').eq(is_menu - 1).prop("selected",true);
            $('#updateform').find('input').eq(2).val(name);
            $('#updateform').find('input').eq(3).val(sort);
            $('#updateform').find('input').eq(4).val(icon);
            $('#updateform').find('input').eq(6).val(id);
        });

        // 编辑
        $('#updateform').find('input[type="button"]').click(function(){
            var id = $('#updateform').find('input[name="id"]').val().replace(/^\s+|\s+$/gm,'');
            var pid = $('#updateform').find('select').val();
            var route_name = $('#updateform').find('input[name="route_name"]').val().replace(/^\s+|\s+$/gm,'');
            var display_name = $('#updateform').find('input[name="display_name"]').val().replace(/^\s+|\s+$/gm,'');
            var name = $('#updateform').find('input[name="name"]').val().replace(/^\s+|\s+$/gm,'');
            var is_menu = $('#updateform').find('select[name="is_menu"]').val();
            var sort = $('#updateform').find('input[name="sort"]').val().replace(/^\s+|\s+$/gm,'');
            var icon = $('#updateform').find('input[name="icon"]').val().replace(/^\s+|\s+$/gm,'');

            var patt1 = new RegExp("[0-9]+");

            if(!patt1.test(sort)) {
              sort = 0;
            }

            if(route_name && display_name)
            {
                $('#updateform .close').click();
                $.ajax({
                    url : '/dashboard/permission-management',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        id : id,
                        pid : pid,
                        route_name : route_name,
                        display_name : display_name,
                        name : name,
                        is_menu : is_menu,
                        sort : sort,
                        icon : icon,
                        _token : '{{csrf_token()}}',
                        update : 'update',
                    },
                    success:function(data){
                        if(data[0]){
                            alert('{{Lang::get("mowork.update_success")}}');
                            location.reload();
                        }else {
                            alert('{{Lang::get("mowork.update_failed")}}');
                        }

                    },
                    error:function(){
                        alert('{{Lang::get("mowork.update_failed")}}');
                    }

                });
            }else {
                alert('{{Lang::get("mowork.name")." ".Lang::get("mowork.chinese_name").Lang::get("mowork.content_required")}}');
            }

        });

        // 删除
        $('.glyphicon-trash').click(function(){
            var b = confirm('{{Lang::get('mowork.want_delete')}}');
            if(b) {
                var id = $(this).parent('a').data('book-id');
                $.ajax({
                    url : '/dashboard/permission-management',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        id : id,
                        _token : '{{csrf_token()}}',
                        delete : 'delete',
                    },
                    success:function(data){
                        if(data[0]){
                            alert('{{Lang::get("mowork.delete_success")}}');
                            location.reload();
                        }else {
                            alert('{{Lang::get("mowork.delete_fail")}}');
                        }
                    },
                    error:function(){
                        alert('{{Lang::get("mowork.delete_fail")}}');
                    }

                });
            }
        });

    });

</script>


@stop
