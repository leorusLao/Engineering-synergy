@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')

@if(Session::has('result'))
   <div class="alert alert-danger">
          {{Session::get('result')}}
     </div>
@endif
<!--<form action="/dashboard/change-password"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'>-->
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span4">
    </div>
    <div class="span4">
      <form class="form-horizontal" id="myForm">
        <div class="control-group">
           <label class="control-label" >{{Lang::get('mowork.measurement_type')}}</label>
          <div class="controls">
            <input id="measurement_type" name="measurement_type"  type="text" />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" for="inputEmail">{{Lang::get('mowork.measurement_name')}}</label>
          <div class="controls">
            <input id="measurement_name" name="measurement_name" type="text" />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" >{{Lang::get('mowork.measurement_unit')}}</label>
          <div class="controls">
            <input id="measurement_unit" name="measurement_unit" type="text" />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" for="inputEmail">{{Lang::get('mowork.measurement_symbol')}}</label>
          <div class="controls">
            <input id="measurement_symbol" name="measurement_symbol" type="text"  />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" >{{Lang::get('mowork.measurement_ratio')}}</label>
          <div class="controls">
            <input id="measurement_ratio" name="measurement_ratio" type="text"  />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" >{{Lang::get('mowork.measurement_companyid')}} </label>
          <div class="controls">
            <input id="measurement_companyid" name="measurement_companyid" type="text" />
          </div>
        </div>
        <div class="control-group">
           <label class="control-label" >{{Lang::get('mowork.measurement_precise')}} </label>
          <div class="controls">
            <input id="measurement_precise" name="measurement_precise" type="text"  />
          </div>
        </div>
        <input type="text" name="_token" value="{{csrf_token()}}" style="visibility:hidden;" />
        <div class="control-group">
          <div class="controls">
            <button type="button" class="btn_submit">{{Lang::get('mowork.edit')}}</button>
          </div>
        </div>
      </form>
    </div>
    <div class="span4">
    </div>
  </div>
</div>
@stop 

@section('footer.append')
<script type="text/javascript">
$(document).ready(function(){
    $('.btn_submit').click(submit_measure);
    function submit_measure(){ 
      var form_data = $("#myForm").serialize();
      $.ajax({
        url:'/dashboard/ajax-measurement',
        type:'post',
        dataType:'json',
        method:'post',
        data:form_data,
        success:function(msg){
          if(msg.code=1){ 
            alert('成功');
            header('/dashboard/edit-measurement');
          }else if(msg.code=2){ 
            alert('失败');
          } 
        }
      })
    }
})

</script>
@stop

