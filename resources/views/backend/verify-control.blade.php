@extends('backend-base')

@section('content')
 <div class="col-xs-12 col-sm-10 col-sm-offset-1">

<div class="margin-b20">
	<a href='#formholder' rel="tooltip" class="add"
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
              <th>{{Lang::get('mowork.module')}}</th>
              <th>{{Lang::get('mowork.verify_mode')}}</th>
              <th>{{Lang::get('mowork.founder')}}</th>
              <th width="11%">{{Lang::get('mowork.verifyer')}}</th>
              <th>{{Lang::get('mowork.ratifyer')}}</th>
              <th>{{Lang::get('mowork.measurement_createtime')}}</th>
              <th width="20%">{{Lang::get('mowork.maintenance')}}</th>
            </tr>

          </thead>

          <tbody>

    @foreach($rows as $row)

      <tr>
      <td>{{ $row->id }}</td>
      <td>{{ $module[$row->module] }}</td>
      <td>{{ $mode[$row->mode] }}</a></td>
      <td>{{ $row->founder }}</td>
      <td>{{$row->verifyer}}</td>
      <td>{{{ $row->ratifyer }}}</td>
      <td>{{{ $row->created_at }}}</td>
      <td><a href='#updateform' data-toggle="modal" data-id="{{$row->id}}" data-module="{{$row->module}}" data-mode="{{$row->mode}}" data-founder="{{$row->founder}}" data-verifyer="{{$row->verifyer}}" data-ratifyer="{{$row->ratifyer}}"><span class="glyphicon glyphicon-edit">{{Lang::get('mowork.text_edit')}}</span></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);'  data-id="{{$row->id}}""><span class="glyphicon glyphicon-trash">{{Lang::get('mowork.text_delete')}}</span></a></td>
      </tr>

    @endforeach


     </tbody>

     </table>
    <div class='text-center'>{{$rows->links()}}</div>
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
				<form action='' method='post'
					autocomplete='off' role=form>
  				<div class="form-group">
            <select class="form-control" name="module">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.module')}}</option>
                @foreach($module as $k => $v)
                <option value="{{$k}}" @if(in_array($k, $moduleArr))disabled="disabled"@endif>{{$v}}</option>
                @endforeach
            </select>
          </div>
					<div class="form-group">
            <select class="form-control" name="mode">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.verify_mode')}}</option>
                @foreach($mode as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>
          </div>
					<div class="form-group">
              <select class="form-control" name="founder" multiple="multiple">
                <option value="0" disabled="disabled">{{Lang::get('mowork.select').Lang::get('mowork.founder')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
					</div>
          <div class="form-group">
              <select class="form-control" name="verifyer" multiple="multiple">
                <option value="0" disabled="disabled">{{Lang::get('mowork.select').Lang::get('mowork.verifyer')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
              <select class="form-control" name="ratifyer" multiple="multiple">
                <option value="0" disabled="disabled">{{Lang::get('mowork.select').Lang::get('mowork.ratifyer')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
          </div>

					<div class="form-group">
						<input type="button" class="form-control btn-info"
							value="{{Lang::get('mowork.add')}}">
					</div>

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
        <form action='' method='post'
          autocomplete='off' role=form>
          <div class="form-group">
            <select class="form-control" name="module">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.module')}}</option>
                @foreach($module as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
            <select class="form-control" name="mode">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.verify_mode')}}</option>
                @foreach($mode as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
              <select class="form-control" name="founder" multiple="multiple">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.founder')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
              <select class="form-control" name="verifyer" multiple="multiple">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.verifyer')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
              <select class="form-control" name="ratifyer" multiple="multiple">
                <option value="0">{{Lang::get('mowork.select').Lang::get('mowork.ratifyer')}}</option>
                @foreach($uidArr as $k => $v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
              </select>
          </div>

          <div class="form-group">
            <input type="button" class="form-control btn-info"
              value="{{Lang::get('mowork.save')}}">
          </div>
          <input type="hidden" name="id" value="">
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
        // 初始化
        $('.add').click(function(){
            $('#formholder').find('select[name="module"]').find('option').first().prop('selected', true);
            $('#formholder').find('select[name="mode"]').find('option').first().prop('selected', true);
            $('#formholder').find('select[name="founder"]').find('option').prop('selected', false);
            $('#formholder').find('select[name="verifyer"]').find('option').prop('selected', false);
            $('#formholder').find('select[name="ratifyer"]').find('option').prop('selected', false);
            $('#formholder').find('select[name="founder"]').find('option').prop('disabled', false);
            $('#formholder').find('select[name="verifyer"]').find('option').prop('disabled', false);
            $('#formholder').find('select[name="ratifyer"]').find('option').prop('disabled', false);
            $('#formholder').find('select[name="founder"]').find('option').first().prop('disabled', true);
            $('#formholder').find('select[name="verifyer"]').find('option').first().prop('disabled', true);
            $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('disabled', true);
        });

        // 关联
        $('#formholder').find('select[name="mode"]').change(function(){
            if($(this).val() == 1){
                $('#formholder').find('select[name="verifyer"]').find('option').prop('disabled', true);
                $('#formholder').find('select[name="ratifyer"]').find('option').prop('disabled', true);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('disabled', false);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('disabled', false);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('selected', true);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('selected', true);
            }else if($(this).val() == 2){
                $('#formholder').find('select[name="verifyer"]').find('option').prop('disabled', false);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('disabled', true);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('selected', false);
                $('#formholder').find('select[name="ratifyer"]').find('option').prop('disabled', true);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('disabled', false);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('selected', true);
            }else{
                $('#formholder').find('select[name="verifyer"]').find('option').prop('disabled', false);
                $('#formholder').find('select[name="ratifyer"]').find('option').prop('disabled', false);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('selected', false);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('selected', false);
                $('#formholder').find('select[name="verifyer"]').find('option').first().prop('disabled', true);
                $('#formholder').find('select[name="ratifyer"]').find('option').first().prop('disabled', true);
            }
        });

        // 新增
        $('#formholder').find('input[type="button"]').click(function(){
            var module = $('#formholder').find('select[name="module"]').val();
            var mode = $('#formholder').find('select[name="mode"]').val();
            var founder = $('#formholder').find('select[name="founder"]').val();
            var verifyer = $('#formholder').find('select[name="verifyer"]').val();
            var ratifyer = $('#formholder').find('select[name="ratifyer"]').val();

            if(module && mode && founder && verifyer && ratifyer){
                $('#formholder .close').click();
                $.ajax({
                    url : '/dashboard/verify-control',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        module : module,
                        mode : mode,
                        founder : founder,
                        verifyer : verifyer,
                        ratifyer : ratifyer,
                        _token : '{{csrf_token()}}',
                        submit : 'add',
                    },
                    success:function(data){
                        if(data){
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
                alert('{{Lang::get("mowork.all").Lang::get("mowork.content_required")}}');
            }

        });

        // 编辑显示 
        $('.glyphicon-edit').click(function(){
            var id = $(this).parent('a').data('id');
            var module = $(this).parent('a').data('module');
            var mode = $(this).parent('a').data('mode');
            var founder = $(this).parent('a').data('founder');
            var verifyer = $(this).parent('a').data('verifyer');
            var ratifyer = $(this).parent('a').data('ratifyer');
            $('#updateform').find('input[name="id"]').val(id);
            console.log(module);
            console.log(mode);
            $('#updateform').find('select[name="module"]').find('option').each(function(){
                if($(this).val() == module){
                    $(this).show();
                    $(this).prop('selected', true);
                }else{
                    $(this).hide();
                }
            });
            $('#updateform').find('select[name="mode"]').find('option').each(function(){
                if($(this).val() == mode){
                    $(this).prop('selected', true);
                }
            });
        });

        // 编辑操作
        $('#updateform').find('input[type="button"]').click(function(){
            var id = $('#updateform').find('input[name="id"]').val();
            var module = $('#updateform').find('select[name="module"]').val();
            var mode = $('#updateform').find('select[name="mode"]').val();
            var founder = $('#updateform').find('select[name="founder"]').val();
            var verifyer = $('#updateform').find('select[name="verifyer"]').val();
            var ratifyer = $('#updateform').find('select[name="ratifyer"]').val();

            if(module && mode && founder && verifyer && ratifyer){
                $('#updateform .close').click();
                $.ajax({
                    url : '/dashboard/verify-control',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        id : id,
                        module : module,
                        mode : mode,
                        founder : founder,
                        verifyer : verifyer,
                        ratifyer : ratifyer,
                        _token : '{{csrf_token()}}',
                        submit : 'update',
                    },
                    success:function(data){
                        if(data){
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
                alert('{{Lang::get("mowork.all").Lang::get("mowork.content_required")}}');
            }

        });

        // 删除
        $('.glyphicon-trash').click(function(){
            var b = confirm('{{Lang::get('mowork.want_delete')}}');
            if(b) {
                var id = $(this).parent('a').data('id');
                $.ajax({
                    url : '/dashboard/verify-control',
                    type : 'post',
                    dataType: 'json',
                    data : {
                        id : id,
                        _token : '{{csrf_token()}}',
                        submit : 'delete',
                    },
                    success:function(data){
                        if(data){
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
