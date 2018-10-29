@extends('backend-base')
@section('css.append')
    <link rel="StyleSheet" href="/asset/css/jquery.treetable.css" type="text/css" />
    <link rel="StyleSheet" href="/asset/css/jquery.treetable.theme.default.css" type="text/css" />
    <script type="text/javascript" src="/asset/js/jquery.treetable.js"></script>
     
@stop
@section('content')

<div class="col-xs-12 col-sm-12">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20" id="result">{{Session::get('result')}}</div>
	<?php $role_id = Session::get('role_id'); 
	      $actway = Session::get('actway');
	?>
	@endif
 
<div class="col-xs-12 col-sm-12">

   @if(count($roles))
			   <select name="role_id">
			   {{--<option value="0">{{Lang::get('mowork.select_role')}}</option>--}}
			   @foreach($roles as $row)
				<option value="{{$row->role_id}}"  @if($row->role_id == $role_id) selected @endif>
				 {{ $row->role_name }}
				 {{ $row->english }}
			  	</option>
			   @endforeach

              </select>


  @endif

	   <button class="btn btn-default btn_save selected_all" style="margin: 20px;">{{Lang::get('mowork.selected_all')}}</button>
 </div>
<div class="col-xs-12 col-sm-12" style="margin-top:20px;border-left:1px solid #777;">
	@foreach($data as $item)
		<li style="list-style-type:none;">{{$item['flg']}}<span style="@if($item['is_menu'] == 1)background-color:#777;@endif"><input type="checkbox" name="permission[]" class="permission" value="{{$item['id']}}" pid="{{$item['pid']}}" />{{$item['display_name']}}</span></li>
	@endforeach
</div>
	<button class="btn btn-default btn_save save" style="margin: 20px;">{{Lang::get('mowork.save')}}</button>
</div>
@stop 

@section('footer.append')
 

<script type="text/javascript">

	// 父级取消勾选
	function cancleDone(obj)
	{
		var b = true;
		$('.permission').each(function(i) {
			if($(this).attr('pid') == obj.val()) {
				b = b && !($(this).prop('checked'));
			}
		});
		if(b) {
			obj.removeAttr('checked');
		}
	}

	// 勾选  父级也勾选
	function prevDone(obj)
	{
		$('.permission').each(function() {
			if($(this).val() == obj.attr('pid'))
			{
				if(obj.prop('checked')) {
					$(this).prop('checked', true);
				} else {
					cancleDone($(this));
				}
				prevDone($(this));
			}
		});
	}

	// 勾选  子级也勾选
	function nextDone(obj)
	{
		$('.permission').each(function() {
			if($(this).attr('pid') == obj.val())
			{
				if(obj.prop('checked')) {
					$(this).prop('checked', true);
				} else {
					$(this).removeAttr('checked');
				}
				nextDone($(this));
			}
		});
	}

	// 复选框  勾选取消
	$('.permission').change(function() {
		prevDone($(this));
		nextDone($(this));
	});

	// 全选
	$('.selected_all').click(function() {
		$('.permission').prop('checked', true);
	});

	// 下拉框选择
	$('select').change(function() {
		getData($(this).val());
	});

	// 点击保存按钮
	$('.save').click(function() {
		var permissions = '';
		$('.permission').each(function() {
			if($(this).prop('checked')) {
				permissions += ',' + $(this).val();
			}
		});
		permissions = permissions.substr(1);
		$.ajax({
			url: '/dashboard/role-control',
			dataType: 'json',
			data: {
				'_token' : '{{ csrf_token() }}',
				'role_id' : $('select').val(),
				'permissons' : permissions
			},
			type: 'post',
			success:function(data) {
				if(data[0])
				{
					alert('{{Lang::get('mowork.save_success')}}');
				} else {
					alert('{{Lang::get('mowork.save_fail')}}');
				}
			},
			error: function() {
				alert('{{Lang::get('mowork.save_fail')}}');
			}
		});
	});

	function getData(id)
	{
		$.ajax({
			url: '/dashboard/role-control',
			dataType: 'json',
			data: {
				'_token' : '{{ csrf_token() }}',
				'role_id' : id
			},
			type: 'post',
			success:function(data) {
				if(data.length)
				{
					$('.permission').each(function() {
						for(var i in data) {
							if($(this).val() == data[i]) {
								$(this).prop('checked', true);
							}
						}
					});
				} else {
					$('.permission').removeAttr('checked');
				}
			},
			error: function() {
				$('.permission').removeAttr('checked');
			}
		});
	}

	getData($('select').first().val());

</script>


@stop