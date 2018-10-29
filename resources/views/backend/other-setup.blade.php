@extends('backend-base')
 
@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
  <div class="margin-b20">
    {{--<a href='#formholder' rel="tooltip" class="add"--}}
      {{--data-placement="right" data-toggle="modal"--}}
      {{--data-placement="right"--}}
      {{--data-original-title="{{Lang::get('mowork.add')}}">--}}
      {{--<span class="glyphicon glyphicon-plus">{{Lang::get('mowork.add')}}{{Lang::get('mowork.basic_info')}}--}}
      {{--</span>--}}
    {{--</a>--}}
  </div>   
  <h4>{{Lang::get('mowork.serial_number')}}</h4>
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif @if(count($rows))
	
<div class="table-responsive table-scrollable">
<table class="table data-table table-condensed table-bordered">

		<thead>
			<tr>
				<th>{{Lang::get('mowork.prefix')}}</th>
				<th>{{Lang::get('mowork.description')}}</th>
				<th>{{Lang::get('mowork.in_english')}}</th>
				<th>{{Lang::get('mowork.cycle')}}</th>
				<th>{{Lang::get('mowork.year_format')}}</th>
				<th>{{Lang::get('mowork.month_format')}}</th>
				<th>{{Lang::get('mowork.day_flag')}}</th>
				<th>{{Lang::get('mowork.serial_length')}}</th>
				<th>计划编码-名称</th>
				<th>{{Lang::get('mowork.maintenance')}}</th>
			</tr>
		</thead>

		<tbody>

			@endif @foreach($rows as $row)
 
			<tr>

				<td>{{ $row->prefix }}</td>
				<td>{{ $row->description }}</td>
				<td>{{ $row->description_en }}</td>
				<td>{{ $row->cycle . '/' . $row->cycle_en }}</td>
        <td>{{ $row->yyyy }}</td>
        <td>@if($row->mm == 0 ) &#10007; @else &#10004; @endif</td>
        <td>@if($row->dd == 0 ) &#10007; @else &#10004; @endif</td>
        <td>{{ $row->serial_length }}</td>
        <td>{{ $row->cc_cfg_name }}</td>
        <td>
          @if($row->company_id != 0)
				    <a href="#formholder" rel="tooltip" data-placement="right" data-toggle="modal" class="edit" data-placement="right" data-original-title="{{Lang::get('mowork.edit')}}" data-id="{{ $row->id }}" data-prefix="{{ $row->prefix }}" data-description="{{ $row->description }}" data-description_en="{{ $row->description_en }}" data-cycle="{{ $row->cycle }}" data-cycle_en="{{ $row->cycle_en }}" data-yyyy="{{ $row->yyyy }}" data-mm="{{ $row->mm }}" data-dd="{{ $row->dd }}" data-serial_length="{{ $row->serial_length }}" data-cc_cfg_name="{{ $row->cc_cfg_name }}" ><span class="glyphicon glyphicon-edit"></span></a> &nbsp; &nbsp;
					   {{--<a rel="tooltip" data-placement="right" data-original-title="{{Lang::get('mowork.delete')}}" class="delete" data-id="{{ $row->id }}"><span class="glyphicon glyphicon-trash"></span></a>--}}
          @endif
        </td>
			</tr>

			@endforeach @if(count($rows))

		</tbody>

	</table>
   </div>
	<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
	@endif
</div>

<div class="modal fade" id="formholder">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
          aria-hidden="true">X</button>
        <h4 class="modal-title text-center">{{Lang::get('mowork.coding_config')}}{{Lang::get('mowork.basic_info')}}</h4>
      </div>
      <form method='post' autocomplete='off' role=form class="add_edit" onsubmit='return validateForm();'>
      <div class="modal-body pull-center text-center">
          
          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.prefix')}} *</i>
            </div>
            <input type="text" class="form-control" name="prefix" id='prefix' maxlength="3">
          </div>
         
          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.description')}} *</i>
            </div>
            <input type="text" class="form-control" name="description"
               id='description' / >
          </div>
        
          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.in_english')}} *</i>
            </div>
            <input type="text" class="form-control" name="description_en"
               id='description_en' / >
          </div>
          
          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.cycle')}}</i>
            </div>
            <select autocomplete="off" class="form-control" id="cycle-en">
              <option value="年/Year">年/Year</option>
              <option value="月/Month">月/Month</option>
              <option value="日/Date">日/Date</option>
            </select>
          </div>

          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.year_format')}}</i>
            </div>
            <select autocomplete="off" class="form-control" name="yyyy" id="yyyy">
              <option value="YYYY">YYYY</option>
              <option value="YY">YY</option>
            </select>
          </div>

          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.month_format')}}</i>
            </div>
            <select autocomplete="off" class="form-control" name="mm" id="mm">
              <option value="1">{{Lang::get('mowork.yes')}}</option>
              <option value="0">{{Lang::get('mowork.no')}}</option>
            </select>
          </div>

          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.day_flag')}}</i>
            </div>
            <select autocomplete="off" class="form-control" name="dd" id="dd">
              <option value="1">{{Lang::get('mowork.yes')}}</option>
              <option value="0">{{Lang::get('mowork.no')}}</option>
            </select>
          </div>
          
          <div class="form-group input-group">
              <div class="input-group-addon">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.serial_length')}} *</i>
            </div>
            <input type="number" class="form-control" name="serial_length"
               id='serial_length' min="1" max="10" required="required" / >
          </div>

          <div class="form-group input-group">
              <div class="input-group-addon">
                  <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">计划编码-名称*</i>
              </div>
              <input type="text" class="form-control" name="cc_cfg_name" id='cc_cfg_name' required="required" / >
          </div>

          <input type="hidden" name="cycle" id="cycle" value="" />
          <input type="hidden" name="cycle_en" id="cycle_en" value="" />
          <input type="submit" name="submit" value="add_edit" style="display: none;">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="clearfix"></div>
      </div>
  
      <div class="text-center" style="margin-bottom: 20px;">
        <button type="button" class="click" >{{Lang::get('mowork.add')}}</button>
      </div>
      </form>
      <form method="post" style="display: none;" class="delete_form">
        <input type="hidden" name="id">
        <input type="submit" name="submit" value="delete">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      </form> 
    </div>
  </div>
</div>

@stop 

@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	 // 新增
       $('.add').click(function(){
          $('.add_edit').find('input[name="id"]').remove();
          $('.add_edit').find('input').val('');
          $('.click').text("{{Lang::get('mowork.add')}}");
          $('.add_edit').find('input').last().val($('.delete_form').find('input').last().val());
       });
       // 编辑
       $('.edit').click(function(){
          obj = $(this);
          $('.add_edit').find('input[name="id"]').remove();
          $('.add_edit').append('<input type="hidden" name="id" value="' + obj.data('id') + '"/ >')
          $('#prefix').val(obj.data('prefix'));
          $('#description').val(obj.data('description'));
          $('#description_en').val(obj.data('description_en'));
          $('#cycle-en').find('option').each(function(){
              if($(this).val() == obj.data('cycle') + '/' + obj.data('cycle_en')) {
                  $(this).prop('selected', true);
              }
          });
          $('#yyyy').find('option').each(function(){
            $(this).prop('selected', false);
            if($(this).val() == obj.data('yyyy')) {
                $(this).prop('selected', true);
            }
          });
          $('#mm').find('option').each(function(){
            $(this).prop('selected', false);
            if($(this).val() == obj.data('mm')) {
                $(this).prop('selected', true);
            }
          });
          $('#dd').find('option').each(function(){
            $(this).prop('selected', false);
            if($(this).val() == obj.data('dd')) {
                $(this).prop('selected', true);
            }
          });
          $('#serial_length').val(obj.data('serial_length'));

          $('#cc_cfg_name').val(obj.data('cc_cfg_name'));

          $('.click').text("{{Lang::get('mowork.edit')}}");
       });

       // 删除
       $('.delete').click(function(){
          if(confirm('{{Lang::get('mowork.want_delete')}}') == true) {
              $('.delete_form').find('input[name="id"]').val($(this).data('id'));
              $('.delete_form').find('input[name="submit"]').click();
          }
       });

       // 月 禁用  日 跟随禁用 计数周期 只能 为年
       $('#mm').change(function(){
          if($(this).val() == 0) {
              // 禁用
              $('#dd').find('option').last().prop('selected', true);
              $('#dd').find('option').first().prop('disabled', true);
              $('#cycle-en').find('option').first().prop('selected', true);
              $('#cycle-en').find('option').last().prop('disabled', true);
              $('#cycle-en').find('option').first().next().prop('disabled', true);
          }else if($(this).val() == 1) {
              // 启用
              $('#dd').find('option').first().prop('disabled', false);
              $('#cycle-en').find('option').first().next().prop('disabled', false);
          }
       });

       // 日  禁用  计数周期不能为 日
       $('#dd').click(function(){
          if($(this).val() == 0) {
              // 禁用
              if($('#cycle-en').val() == '日/Date') {
                  $('#cycle-en').find('option').first().next().prop('selected', true);
              }
              $('#cycle-en').find('option').last().prop('disabled', true);
          }else if($(this).val() == 1) {
              // 启用
              $('#cycle-en').find('option').last().prop('disabled', false);
          }
       });

       //新增确认按钮
       $('.click').click(function(){
          var cycle_en = $('#cycle-en').val();
          var n = cycle_en.indexOf('/');
          $('#cycle').val(cycle_en.substr(0,n));
          $('#cycle_en').val(cycle_en.substr(n + 1));
          $('.add_edit').find('input[type="submit"]').val('add_edit');
          $('.add_edit').find('input[type="submit"]').click();
       });

  
     });

    function validateForm(){
    	  var errors = '';
    		 
    	  var prefix = $.trim($('#prefix').val()); 
    	  if(prefix.length < 1) {
    	  	errors += "{{Lang::get('mowork.prefix_required')}} \n";	
    	  } else if (prefix.length > 6) {
    		  errors += "{{Lang::get('mowork.prefix_max')}} \n";	
          }

    	  var description = $.trim($('#description').val()); 
    	  if(description.length < 1) {
    	     errors += "{{Lang::get('mowork.description_required')}} \n";	
    		}

        var description_en = $.trim($('#description_en').val());
        if(description_en.length < 1) {
           errors += "{{Lang::get('mowork.english')}}{{Lang::get('mowork.description_required')}} \n"; 
        }

    	  if(errors.length > 0) {
        		alert(errors);
        		return false;
    	  }
    	  return true;
     }
</script>


@stop