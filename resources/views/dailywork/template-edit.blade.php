@extends('backend-base') @section('css.append') @stop

@section('content')



<div class="col-xs-12 col-sm-4 col-sm-offset-4">
     
	@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
    @if(isset($errors))
            	<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
    @endif
   
<form action="/dashboard/template/edit/{{$token}}/{{$row->id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<fieldset>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_code')}}</i>
</div>
<input class="form-control" name="template_code" type="text" value="{{$row->template_code}}" id="template_code" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_name')}}</i>
</div>
<input class="form-control" name="template_name" type="text" value="{{$row->template_name}}" id="template_name" />
</div>

<div class="form-group input-group">
					    <div class="input-group-addon">
						<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.template_type')}}</i>
						</div>
						<div class="text-left"> &nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" name="template_type" value="1" id="type1"  @if($row->template_type == 1) checked @endif >{{Lang::get('mowork.plan_template')}}
						  &nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="radio" name="template_type" value="2" id="type2"  @if($row->template_type == 2) checked @endif >{{Lang::get('mowork.node_template')}}  
						</div>
</div>

<div class="form-group input-group" id="plan_type">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.plan_type')}}</i>
</div>
 <select name="plan_type_id" id="template_type" class="form-control">
	@foreach($planTypeList as $res)
	<option value="{{$res->type_id}}"  @if($row->plan_type_id == $res->type_id) selected @endif >{{$res->type_name}}</option>
	@endforeach
 </select>
</div>

<div class="form-group input-group" id="node_type">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.node_type')}}</i>
</div>
 <select name="node_type_id" class="form-control">
	@foreach($nodeTypeList as $res)
	<option value="{{$res->type_id}}"  @if($row->node_type_id == $res->type_id) selected @endif >{{$res->type_name}}</option>
	@endforeach
 </select>
</div>		 

<div class="form-group">
<input type="submit" name ="submit" class="btn btn-lg btn-info" value="{{Lang::get('mowork.update')}}">
</div>

 <input name="_token" type="hidden" value="{{ csrf_token() }}">
</fieldset>
</form>
    
</div>
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	 if({{$row->template_type}} == 2) {
    		$("#plan_type").hide();
          	$("#node_type").show();
         } else {
        	$("#plan_type").show();
          	$("#node_type").hide();
         }
         
    	 $("#type1").click(function(){
         	$("#plan_type").show();
         	$("#node_type").hide();
         });
         $("#type2").click(function(){
         	$("#plan_type").hide();
         	$("#node_type").show();
         });
      
     });
 
    function validateForm(){
        var errors = '';
      	 
        var template_code = $.trim($('#template_code').val()); 
        if(template_code.length < 1) {
        	errors += "{{Lang::get('mowork.tmplcode_required')}} \n";	
      	}

        var template_name = $.trim($('#template_name').val());
        if(template_name < 1) {
           errors += "{{Lang::get('mowork.tmplname_required')}} \n";	
        }
            
        if(errors.length > 0) {
      	alert(errors);
      	return false;
        }
        return true;
        
      }
    
</script>


@stop